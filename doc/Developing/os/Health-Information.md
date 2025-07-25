## Sensors

This document will guide you through adding health / sensor
information for your new device.

Currently, we have support for the following health metrics along with
the values we expect to see the data in:

| Class                           | Measurement                 |
| ------------------------------- | --------------------------- |
| airflow                         | cfm                         |
| ber                             | ratio                       |
| charge                          | %                           |
| chromatic_dispersion            | ps/nm                       |
| cooling                         | W                           |
| count                           | #                           |
| current                         | A                           |
| dbm                             | dBm                         |
| delay                           | s                           |
| eer                             | eer                         |
| fanspeed                        | rpm                         |
| frequency                       | Hz                          |
| humidity                        | %                           |
| load                            | %                           |
| loss                            | %                           |
| power                           | W                           |
| power_consumed                  | kWh                         |
| power_factor                    | ratio                       |
| pressure                        | kPa                         |
| quality_factor                  | dB                          |
| runtime                         | Min                         |
| signal                          | dBm                         |
| snr                             | SNR                         |
| state                           | #                           |
| temperature                     | C                           |
| tv_signal                       | dBmV                        |
| bitrate                         | bps                         |
| voltage                         | V                           |
| waterflow                       | l/m                         |
| percent                         | %                           |

### Simple health discovery

We have support for defining health / sensor discovery using YAML
files so that you don't need to know how to write PHP.

> Please note that DISPLAY-HINTS are disabled so ensure you use the
> correct divisor / multiplier if applicable.

All yaml files are located in
`resources/definitions/os_discovery/$os.yaml`. Defining the information
here is not always possible and is heavily reliant on vendors being
sensible with the MIBs they generate. Only snmp walks are supported,
and you must provide a sane table that can be traversed and contains
all the data you need. We will use netbotz as an example here.

`resources/definitions/os_discovery/netbotz.yaml`

```yaml
mib: NETBOTZV2-MIB
modules:
    sensors:
        airflow:
            options:
                skip_value_lt: 0
            data:
                -
                    oid: airFlowSensorTable
                    value: airFlowSensorValue
                    divisor: 10
                    num_oid: '.1.3.6.1.4.1.5528.100.4.1.5.1.2.{{ $index }}'
                    descr: '{{ $airFlowSensorLabel }}'
                    index: 'airFlowSensorValue.{{ $index }}'
```

At the top you can define one or more mibs to be used in the lookup of data:

`mib: NETBOTZV2-MIB`
For use of multiple MIB files separate them with a colon: `mib: NETBOTZV2-MIB:SECOND-MIB`

For `data:` you have the following options:

The only sensor we have defined here is airflow. The available options
are as follows:

- `oid` (required): This is the name of the table you want to snmp walk for data.
- `value` (optional): This is the key within the table that contains
  the value. If not provided will use `oid`
- `num_oid` (required for PullRequests): If not provided, this parameter should be computed
  automatically by discovery process. This parameter is still required to
  submit a pull request. This is the numerical OID that contains
  `value`. This should usually include `{{ $index }}`.
  In case the index is a string, `{{ $str_index_as_numeric }}` can be used instead and will convert
the string to the equivalent OID representation.
- `divisor` (optional): This is the divisor to use against the returned `value`.
- `multiplier` (optional): This is the multiplier to use against the returned `value`.
- `low_limit` (optional): This is the critical low threshold that
  `value` should be (used in alerting). If an OID is specified then
  divisor / multiplier are used.
- `low_warn_limit` (optional): This is the warning low threshold that
  `value` should be (used in alerting). If an OID is specified then
  divisor / multiplier are used.
- `warn_limit` (optional): This is the warning high threshold that
  `value` should be (used in alerting). If an OID is specified then
  divisor / multiplier are used.
- `high_limit` (optional): This is the critical high threshold that
  `value` should be (used in alerting). If an OID is specified then
  divisor / multiplier are used.
- `descr` (required): The visible label for this sensor. It can be a
  key with in the table or a static string, optionally using `{{ index }}`.
- `group` (optional): Groups sensors together under in the webui,
  displaying this text. Not specifying this will put the sensors in
  the default group. If group is set to `transceiver` it will be shown with the port
  instead of in with all the generic sensors (You must also set `entPhysicalIndex` to ifIndex)
- `index` (optional): This is the index value we use to uniquely
  identify this sensor. `{{ $index }}` will be replaced by the `index`
  from the snmp walk.
- `skip_values` (optional): This is an array of values we should skip
  over (see note below).
- `skip_value_lt` (optional): If sensor value is less than this, skip the discovery.
- `skip_value_gt` (optional): If sensor value is greater than this, skip the discovery.
- `entPhysicalIndex` and `entPhysicalIndex_measured` (optional) : If the
  sensor belongs to a physical entity then you can link them here. The currently
  supported variants are :
    - `entPhysicalIndex` contains the entPhysicalIndex from entPhysical table, and `entPhysicalIndex_measured` is NULL
    - `entPhysicalIndex` contains "ifIndex" value of the linked port and `entPhysicalIndex_measured` contains "ports"
- `user_func` (optional): You can provide a function name for the
  sensors value to be processed through (i.e. Convert fahrenheit to
  celsius use `fahrenheit_to_celsius`)
- `snmp_flags` (optional): this sets the flags to be sent to snmpwalk, it 
  overrides flags set on the sensor type and os.  The default is `'-OQUb'`.
  A common issue is dealing with string indexes, setting `'-OQUsbe'` will change them to 
  numeric oids. Setting `['-OQUsbe', '-Pu']` will also allow _ in oid names. You can find more
  in the [Man Page](https://linux.die.net/man/1/snmpcmd)
- `rrd_type` (optional): You can change the type of the RRD file that will be created to
  store the data. By default, type GAUGE is used. More details can be found here:
  https://oss.oetiker.ch/rrdtool/doc/rrdcreate.en.html

For `options:` you have the following available:

- `divisor`: This is the divisor to use against the returned `value`.
- `multiplier`: This is the multiplier to use against the returned `value`.
- `skip_values`: This is an array of values we should skip over (see note below).
- `skip_value_lt`: If sensor value is less than this, skip the discovery.
- `skip_value_gt`: If sensor value is greater than this, skip the discovery.

Multiple variables can be used in the sensor's definition. The syntax
is `{{ $variable }}`. Any oid in the current table can be used, as
well as pre_cached data. The index ($index) and the sub_indexes (in
case the oid is indexed multiple times) are also available: if
$index="1.20", then $subindex0="1" and $subindex1="20".

#### Fetching values from other tables/oids

When referencing an oid in another table the full index will be used to match
the other table. If the indexes of the two tables don't match, you will need
to specify which indexes to use by their index position starting with 0. The
data for the other table must be fetched already.

`{{ IF-MIB::ifName:2 }}`

This simple example shows using the 3rd (0 is the first) index value from
the current table to fetch the IF-MIB::ifName value from the data.

Additionally, you may specify multiple index values with either a
range or list of index positions.

Range: `{{ IP-MIB::ipAddressPrefixOrigin:0-3 }}`
List: `{{ IP-MIB::ipAddressPrefixOrigin:2.3.1.4 }}`

#### Skipping rows of the returned data

You can filter rows of the data returned to only discover valid sensors.
This is often useful when devices always return all sensors possible or
mix sensor types in a single table.

> `skip_values` can also compare items within the OID table against
> values. The index of the sensor is used to retrieve the value
> from the OID, unless a target index is appended to the OID.
> Additionally, you may check fields from the device.
> Comparisons behave on a logical OR basis when chained, so only
> one of them needs to be matched for that particular sensor
> to be skipped during discovery. An example of this is below:

```yaml
                    skip_values:
                    -
                      oid: sensUnit
                      op: '!='
                      value: 4
                    -
                      oid: sensConfig.0
                      op: '!='
                      value: 1
                    -
                      device: hardware
                      op: 'contains'
                      value: 'rev2'
```

`op` can be any of the following operators :

> =, !=, ==, !==, <=, >=, <, >,
> starts, ends, contains, regex, in_array, not_starts,
> not_ends, not_contains, not_regex, not_in_array, exists

Example:

```yaml
                    skip_values:
                    -
                      oid: sensorName
                      op: 'not_in_array'
                      value: ['sensor1', 'sensor2']
```

```yaml
                    skip_values:
                    -
                      oid: sensorOptionalOID
                      op: 'exists'
                      value: false
```

```yaml
        temperature:
            data:
                -
                    oid: hwOpticalModuleInfoTable
                    value: hwEntityOpticalTemperature
                    descr: '{{ $entPhysicalName }}'
                    index: '{{ $index }}'
                    skip_values:
                        -
                            oid: hwEntityOpticalMode
                            op: '='
                            value: '1'
```

If you aren't able to use yaml to perform the sensor discovery, you
will most likely need to use Advanced health discovery.

### Advanced health discovery

If you can't use the yaml files as above, then you will need to create
the discovery code in php. If it is possible to create via yaml, php discovery
will likely be rejected due to the much higher chance of later problems,
so it is highly suggested to use yaml.

The directory structure for sensor information is
`includes/discovery/sensors/$class/$os.inc.php`. The format of all the
sensors follows the same code format which is to collect sensor information
via SNMP and then call the `discover_sensor()` function; except state
sensors which requires additional code. Sensor information is commonly found in an ENTITY
mib supplied by device's vendor in the form of a table. Other mib tables may be used as
well. Sensor information is first collected by
`includes/discovery/sensors/pre_cache/$os.inc.php`. This program will pull in data
from mib tables into a `$pre_cache` array that can then be used in
`includes/discovery/sensors/$class/$os.inc.php` to extract specific values which are
then passed to `discover_sensor()`.

`discover_sensor()` Accepts the following arguments:

- &$valid = This is always null. This is unused.
- $class = Required. This is the sensor class from the table above (i.e humidity).
- $device = Required. This is the $device array.
- $oid = Required. This must be the numerical OID for where the data
  can be found, i.e .1.2.3.4.5.6.7.0
- $index = Required. This must be unique for this sensor class, device
  and type. Typically it's the index from the table being walked, or it
  could be the name of the OID if it's a single value.
- $type = Required. This should be the OS name, i.e. pulse.
- $descr = Required. This is a descriptive value for the sensor. Some
  devices will provide names to use.
- $divisor = Defaults to 1. This is used to divide the returned value.
- $multiplier = Defaults to 1. This is used to multiply the returned value.
- $low_limit = Defaults to null. Sets the low threshold limit for the
  sensor, used in alerting to report out range sensors.
- $low_warn_limit = Defaults to null. Sets the low warning limit for
  the sensor, used in alerting to report near out of range sensors.
- $warn_limit = Defaults to null. Sets the high warning limit for the
  sensor, used in alerting to report near out of range sensors.
- $high_limit = Defaults to null. Sets the high limit for the sensor,
  used in alerting to report out range sensors.
- $current = Defaults to null. Can be used to set the current value on
  discovery. Poller will update this on the next poll cycle anyway.
- $poller_type = Defaults to snmp. Things like the unix-agent can set
  different values but for the most part this should be left as snmp.
- $entPhysicalIndex = Defaults to null. Sets the entPhysicalIndex to
  be used to look up further hardware if available.
- $entPhysicalIndex_measured = Defaults to null. Sets the type of
  entPhysicalIndex used, i.e ports.
- $user_func = Defaults to null. You can provide a function name for
  the sensors value to be processed through (i.e. Convert fahrenheit
  to celsius use `fahrenheit_to_celsius`)
- $group = Defaults to null. Groups sensors together under in the
  webui, displaying this text.
- $rrd_type = Default to 'GAUGE'. Allows to change the type of the RRD
  file created for this sensor. More details can be found here in the
  RRD documentation: https://oss.oetiker.ch/rrdtool/doc/rrdcreate.en.html

For the majority of devices, this is all that's required to add
support for a sensor. Polling is done based on the data gathered using
`discover_sensor()`. If custom polling is needed then the file format
is similar to discovery:
`includes/polling/sensors/$class/$os.inc.php`. Whilst it's possible to
perform additional  snmp queries within polling this should be avoided
where possible. The value for the OID is already available as `$sensor_value`.

Graphing is performed automatically for sensors, no custom graphing is
required or supported.

### Adding a new sensor class

You will need to add code for your new sensor class in the following existing files:

- `LibreNMS/Enum/Sensor.php`: add accordingly, find free icon from [Font Awesome](https://fontawesome.com/icons?d=gallery&m=free)
- `doc/Developing/os/Health-Information.md`: documentation for every sensor class is mandatory.
- `includes/discovery/functions.inc.php`: optional - if sensible low_limit and high_limit values
are guessable when a SNMP-retrievable threshold is not available, add a case for the sensor class
to the sensor_limit() and/or sensor_low_limit() functions.
- `LibreNMS/Util/ObjectCache.php`: optional - choose menu grouping for the sensor class.
- `includes/html/pages/device/overview.inc.php`: add `require 'overview/sensors/$class.inc.php'`
in the desired order for the device overview page.
- `lang/en/sensors.php`: add human-readable names and units for the sensor class
in English, feel free to do so for other languages as well.

Create and populate new files for the sensor class in the following places:

- `includes/discovery/sensors/$class/`: create the folder where advanced php-based discovery
files are stored. Not used for yaml discovery.
=======
- `includes/html/pages/device/overview.inc.php`: add `require 'overview/sensors/$class.inc.php'` in the desired
order for the device overview page.
- `lang/en/sensors.php`: add human-readable names and units for the sensor class in English, feel
free to do so for other languages as well.

Create and populate new files for the sensor class in the following places:

- `includes/discovery/sensors/$class/`: create the folder where advanced php-based discovery files
are stored. Not used for yaml discovery.
- `includes/html/graphs/device/$class.inc.php`: define unit names used in RRDtool graphs.
- `includes/html/graphs/sensor/$class.inc.php`: define various [parameters](https://oss.oetiker.ch/rrdtool/doc/rrdgraph_graph.en.html) for RRDtool graphs.
- `includes/html/pages/device/health/$class.inc.php`
- `includes/html/pages/device/overview/sensors/$class.inc.php`
- `includes/html/pages/health/$class.inc.php`

#### Advanced health sensor example

This example shows how to build sensors using the advanced method. In this example we will
be collecting optical power level (dBm) from Adva FSP150CC family MetroE devices. This example
will assume an understanding of SNMP and MIBs.

The first line walks the cmEntityObject table to get information about the chassis and line cards. From
this information we extract the model type which will identify which tables in the CM-Facility-Mib
the ports are populated in. The program then reads the appropriate table into the `$data`
array `adva_fsp150_ports`. This array will have OID indexies for each port, which we will use
later to identify our sensor OIDs.

Next we are going to build our sensor discovery code. These are optical readings, so the file will be
created as the dBm sensor type in `includes/discover/sensors/dbm/adva_fsp150.inc.php`. Below is
a snippet of the code:

```php
$data = SnmpQuery::walk([
    'CM-FACILITY-MIB::cmEthernetTrafficPortTable',
    'CM-PERFORMANCE-MIB::cmEthernetTrafficPortStatsOPT',
    'CM-PERFORMANCE-MIB::cmEthernetTrafficPortStatsOPR',
])->valuesByIndex();

foreach ($data as $index => $entry) {
    if (isset($entry['CM-FACILITY-MIB::cmEthernetTrafficPortMediaType']) && $entry['CM-FACILITY-MIB::cmEthernetTrafficPortMediaType'] == 'fiber') {
        //Discover received power level
        $oidRx = '.1.3.6.1.4.1.2544.1.12.5.1.21.1.34.' . $index . '.3';
        $oidTx = '.1.3.6.1.4.1.2544.1.12.5.1.21.1.33.' . $index . '.3';
        $currentTx = $data[$index . '.3']['CM-PERFORMANCE-MIB::cmEthernetTrafficPortStatsOPT'] ?? null;
        $currentRx = $data[$index . '.3']['CM-PERFORMANCE-MIB::cmEthernetTrafficPortStatsOPR'] ?? null;
        if ($currentRx != 0 || $currentTx != 0) {
            $ifIndex = $entry['CM-FACILITY-MIB::cmEthernetTrafficPortIfIndex'] ?? 0;
            $ifName = PortCache::getByIfIndex($ifIndex)?->ifName;

            app('sensor-discovery')->discover(new \App\Models\Sensor([
                'poller_type' => $poller_type,
                'sensor_class' => 'dbm',
                'device_id' => $device['device_id'],
                'sensor_oid' => $oidRx,
                'sensor_index' => 'cmEthernetTrafficPortStatsOPR.' . $index,
                'sensor_type' => 'adva_fsp150,
                'sensor_descr' => $ifName . ' Rx Power',
                'sensor_divisor' => 1,
                'sensor_multiplier' => 1,
                'sensor_limit' => null,
                'sensor_limit_warn' => null,
                'sensor_limit_low' => null,
                'sensor_limit_low_warn' => null,
                'sensor_current' => $currentRx,
                'entPhysicalIndex' => $ifIndex,
                'entPhysicalIndex_measured' => 'ports',
            ]));

            app('sensor-discovery')->discover(new \App\Models\Sensor([
                'poller_type' => $poller_type,
                'sensor_class' => 'dbm',
                'device_id' => $device['device_id'],
                'sensor_oid' => $oidRx,
                'sensor_index' => 'cmEthernetTrafficPortStatsOPT.' . $index,
                'sensor_type' => 'adva_fsp150,
                'sensor_descr' => $ifName . ' Tx Power',
                'sensor_divisor' => 1,
                'sensor_multiplier' => 1,
                'sensor_limit' => null,
                'sensor_limit_warn' => null,
                'sensor_limit_low' => null,
                'sensor_limit_low_warn' => null,
                'sensor_current' => $currentTx,
                'entPhysicalIndex' => $ifIndex,
                'entPhysicalIndex_measured' => 'ports',
            ]));
        }
    }
}
```

First the program will loop through each port's index value. In the case of Advas, the ports are
names Ethernet 1-1-1-1, 1-1-1-2, etc, and they are indexed as oid.1.1.1.1, oid.1.1.1.2, etc in
the mib.

Next the program checks which table the port exists in and that the connector type is 'fiber'. There
are other port tables in the full code that were ommitted from the example for brevity. Copper
media won't have optical readings, so if the media type isn't fiber we skip discovery for that port.

The next two lines build the OIDs for getting the optical receive and transmit values using the
`$index` for the port. Using the OIDs the program gets the current receive and transmit values
($currentRx and $currentTx repectively) to verify the values are not 0. Not all SFPs collect digital
optical monitoring (DOM) data, in the case of Adva the value of both transmit and receive will be
0 if DOM is not available. While 0 is a valid value for optical power, its extremely unlikely that
both will be 0 if DOM is present. If DOM is not available, then the program stops discovery for
that port. Note that while this is the case with Adva, other vendors may differ in how they handle
optics that do not supply DOM. Please check your vendor's mibs.

Next the program assigns the values of $entPhysicalIndex and $entPhysicalIndex_measured. In this
case $entPhysicalIndex is set to the value of the `CM-FACILITY-MIB::cmEthernetTrafficPortIfIndex`
so that it is associated with port. This will also allow the sensor graphs to show up on the
associated port's page in the GUI in addition to the Health page.

Following that the program uses a database call to get the description of the port which will be
used as the title for the graph in the GUI.

Lastly the program calls `discover_sensor()` and passes the information collected in the previous
steps. The `null` values are for low, low warning, high, and high warning values, which are not
collected in the Adva's MIB.

You can manually run discovery to verify the code works by running `./discovery.php -h $device_id -m sensors`.
You can use `-v` to see what calls are being used during discovery and `-d` to see debug output.
In the output under `#### Load disco module sensors ####` you can see a list of sensors types. If
there is a `+` a sensor is added, if there is a `-` one was deleted, and a `.` means no change. If
there is nothing next to the sensor type then the sensor was not discovered. There is is also
information about changes to the database and RRD files at the bottom.

```
[librenms@nms-test ~]$ ./discovery.php -h 2 -m sensors
LibreNMS Discovery
164.113.194.250 2 adva_fsp150

#### Load disco module core ####

>> Runtime for discovery module 'core': 0.0240 seconds with 66536 bytes
>> SNMP: [2/0.06s] MySQL: [3/0.00s] RRD: [0/0.00s]
#### Unload disco module core ####


#### Load disco module sensors ####
Pre-cache adva_fsp150:
 ENTITY-SENSOR: Caching OIDs: entPhysicalDescr entPhysicalName entPhySensorType entPhySensorScale entPhySensorPrecision entPhySensorValue entPhySensorOperStatus
Airflow:
Current: .
Charge:
Dbm: Adva FSP-150 dBm..
Fanspeed:
Frequency:
Humidity:
Load:
Power:
Power_consumed:
Power_factor:
Runtime:
Signal:
State:
Count:
Temperature: ..
Tv_signal:
Bitrate:
Voltage: .
Snr:
Pressure:
Cooling:
Delay:
Quality_factor:
Chromatic_dispersion:
Ber:
Eer:
Waterflow:
Percent:

>> Runtime for discovery module 'sensors': 3.9340 seconds with 190024 bytes
>> SNMP: [16/3.89s] MySQL: [36/0.03s] RRD: [0/0.00s]
#### Unload disco module sensors ####

Discovered in 5.521 seconds

SNMP [18/3.96s]: Get[8/0.81s] Getnext[0/0.00s] Walk[10/3.15s]
MySQL [41/0.03s]: Cell[10/0.01s] Row[-4/-0.00s] Rows[31/0.02s] Column[0/0.00s] Update[2/0.00s] Insert[2/0.00s] Delete[0/0.00s]
RRD [0/0.00s]: Update[0/0.00s] Create [0/0.00s] Other[0/0.00s]
```
