<?php

$name = 'suricata';
$unit_text = 'errors/s';
$descr = 'MQTT';
$ds = 'data';

if (isset($vars['sinstance'])) {
    $rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___app_layer__error__mqtt__internal']);
} else {
    $rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___app_layer__error__mqtt__internal']);
}

require 'includes/html/graphs/generic_stats.inc.php';
