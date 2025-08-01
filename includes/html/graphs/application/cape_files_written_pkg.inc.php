<?php

$name = 'cape';
$app_id = $app['app_id'];
$unit_text = 'Anti Issues Per Run';
$colours = 'psychedelic';
$dostack = 0;
$printtotal = 1;
$addarea = 0;
$transparency = 15;
$float_precision = 3;

$packages = Rrd::getRrdApplicationArrays($device, $app['app_id'], 'cape', 'pkg-files_written___-___');

$rrd_list = [];
$packages_int = 0;
foreach ($packages as $index => $package) {
    $label = preg_filter('/^pkg\-files\_written\_\_\_\-\_\_\_\-/', '', $package);
    $rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app['app_id'], $package]);
    $rrd_list[] = [
        'filename' => $rrd_filename,
        'descr' => $label,
        'ds' => 'files_written',
    ];

    $packages_int++;
}

if (count($rrd_list)) {
    throw new \LibreNMS\Exceptions\RrdGraphException('No relevant package RRDs found');
}

require 'includes/html/graphs/generic_multi_line.inc.php';
