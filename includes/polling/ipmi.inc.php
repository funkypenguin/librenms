<?php

use App\Facades\LibrenmsConfig;
use LibreNMS\RRD\RrdDefinition;

$ipmi_rows = dbFetchRows("SELECT * FROM sensors WHERE device_id = ? AND poller_type='ipmi'", [$device['device_id']]);

if (is_array($ipmi_rows)) {
    d_echo($ipmi_rows);

    if ($ipmi_hostname = DeviceCache::getPrimary()->getAttrib('ipmi_hostname')) {
        $ipmi['host'] = $ipmi_hostname;
        $ipmi_port = DeviceCache::getPrimary()->getAttrib('ipmi_port');
        $ipmi['port'] = filter_var($ipmi_port, FILTER_VALIDATE_INT) ? $ipmi_port : '623';
        $ipmi['user'] = DeviceCache::getPrimary()->getAttrib('ipmi_username');
        $ipmi['password'] = DeviceCache::getPrimary()->getAttrib('ipmi_password');
        $ipmi['kg_key'] = DeviceCache::getPrimary()->getAttrib('ipmi_kg_key');
        $ipmi['type'] = DeviceCache::getPrimary()->getAttrib('ipmi_type');

        echo 'Fetching IPMI sensor data...';

        $cmd = [LibrenmsConfig::get('ipmitool', 'ipmitool')];
        if (LibrenmsConfig::get('own_hostname') != $device['hostname'] || $ipmi['host'] != 'localhost') {
            if (empty($ipmi['kg_key']) || is_null($ipmi['kg_key'])) {
                array_push($cmd, '-H', $ipmi['host'], '-U', $ipmi['user'], '-P', $ipmi['password'], '-L', 'USER', '-p', $ipmi['port']);
            } else {
                array_push($cmd, '-H', $ipmi['host'], '-U', $ipmi['user'], '-P', $ipmi['password'], '-L', 'USER', '-p', $ipmi['port'], '-y', $ipmi['kg_key']);
            }
        }

        // Check to see if we know which IPMI interface to use
        // so we dont use wrong arguments for ipmitool
        if ($ipmi['type'] != '') {
            array_push($cmd, '-I', $ipmi['type'], '-c', 'sdr');
            $results = trim(external_exec($cmd));
            d_echo($results);
            echo " done.\n";
        } else {
            echo " type not yet discovered.\n";
        }

        foreach (explode("\n", $results) as $row) {
            [$desc, $value, $type, $status] = explode(',', $row);
            $desc = trim($desc, ' ');
            $ipmi_unit_type = LibrenmsConfig::get("ipmi_unit.$type");

            // SDR records can include hexadecimal values, identified by an h
            // suffix (like "93h" for 0x93). Convert them to decimal.
            if (preg_match('/^([0-9A-Fa-f]+)h$/', $value, $matches)) {
                $value = hexdec($matches[1]);
            }

            $ipmi_sensor[$desc][$ipmi_unit_type]['value'] = $value;
            $ipmi_sensor[$desc][$ipmi_unit_type]['unit'] = $type;
        }

        foreach ($ipmi_rows as $ipmisensors) {
            echo 'Updating IPMI sensor ' . $ipmisensors['sensor_descr'] . '... ';

            $sensor_value = $ipmi_sensor[$ipmisensors['sensor_descr']][$ipmisensors['sensor_class']]['value'];
            $unit = $ipmi_sensor[$ipmisensors['sensor_descr']][$ipmisensors['sensor_class']]['unit'];

            echo "$sensor_value $unit\n";

            $rrd_name = get_sensor_rrd_name($device, $ipmisensors);
            $rrd_def = RrdDefinition::make()->addDataset('sensor', 'GAUGE', -20000, 20000);

            $fields = [
                'sensor' => $sensor_value,
            ];

            $tags = [
                'sensor_class' => $ipmisensors['sensor_class'],
                'sensor_type' => $ipmisensors['sensor_type'],
                'sensor_descr' => $ipmisensors['sensor_descr'],
                'sensor_index' => $ipmisensors['sensor_index'],
                'rrd_name' => $rrd_name,
                'rrd_def' => $rrd_def,
            ];
            app('Datastore')->put($device, 'ipmi', $tags, $fields);

            // FIXME warnings in event & mail not done here yet!
            dbUpdate(
                ['sensor_current' => $sensor_value,
                    'lastupdate' => ['NOW()'], ],
                'sensors',
                'poller_type = ? AND sensor_class = ? AND sensor_id = ?',
                ['ipmi', $ipmisensors['sensor_class'], $ipmisensors['sensor_id']]
            );
        }

        unset($ipmi_sensor);
    }
}
