<?php

$name = 'squid';
$colours = 'mixed';
$unit_text = 'objects';
$unitlen = 10;
$bigdescrlen = 15;
$smalldescrlen = 15;
$dostack = 0;
$printtotal = 0;
$addarea = 1;
$transparency = 15;

$rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id]);

$rrd_list = [
    [
        'filename' => $rrd_filename,
        'descr' => 'stored',
        'ds' => 'numobjcount',
        'colour' => '582a72',
    ],
];

require 'includes/html/graphs/generic_v3_multiline.inc.php';
