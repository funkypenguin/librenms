<?php

$name = 'cape';
$app_id = $app['app_id'];
$unit_text = 'Files Written Per Run';
$colours = 'psychedelic';
$dostack = 0;
$printtotal = 1;
$addarea = 0;
$transparency = 15;
$float_precision = 3;

$vars['stddev'] = ($vars['stddev'] ?? 'off') === 'on' ? 'on' : 'off';

if (isset($vars['package'])) {
    $rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app['app_id'], 'pkg-files_written___-___', $vars['package']]);
} else {
    $rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app['app_id'], 'files_written']);
}

$descr = 'Average';
$ds = 's3files_written';

require 'includes/html/graphs/generic_stats.inc.php';
