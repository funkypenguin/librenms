<?php

$name = 'suricata';
$unit_text = 'flow ends/s';
$colours = 'psychedelic';
$dostack = 0;
$printtotal = 0;
$addarea = 0;
$transparency = 15;

if (isset($vars['sinstance'])) {
    $rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__state__closed']);
    $flow__end__state__established_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__state__established']);
    $flow__end__state__local_bypassed_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__state__local_bypassed']);
    $flow__end__state__new_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__state__new']);
    $flow__end__tcp_liberal_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_liberal']);
    $flow__end__tcp_state__close_wait_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__close_wait']);
    $flow__end__tcp_state__closed_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__closed']);
    $flow__end__tcp_state__closing_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__closing']);
    $flow__end__tcp_state__established_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__established']);
    $flow__end__tcp_state__fin_wait1_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__fin_wait1']);
    $flow__end__tcp_state__fin_wait2_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__fin_wait2']);
    $flow__end__tcp_state__last_ack_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__last_ack']);
    $flow__end__tcp_state__none_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__none']);
    $flow__end__tcp_state__syn_recv_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__syn_recv']);
    $flow__end__tcp_state__syn_sent_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__syn_sent']);
    $flow__end__tcp_state__time_wait_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'instance_' . $vars['sinstance'] . '___flow__end__tcp_state__time_wait']);
} else {
    $rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__state__closed']);
    $flow__end__state__established_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__state__established']);
    $flow__end__state__local_bypassed_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__state__local_bypassed']);
    $flow__end__state__new_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__state__new']);
    $flow__end__tcp_liberal_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_liberal']);
    $flow__end__tcp_state__close_wait_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__close_wait']);
    $flow__end__tcp_state__closed_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__closed']);
    $flow__end__tcp_state__closing_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__closing']);
    $flow__end__tcp_state__established_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__established']);
    $flow__end__tcp_state__fin_wait1_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__fin_wait1']);
    $flow__end__tcp_state__fin_wait2_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__fin_wait2']);
    $flow__end__tcp_state__last_ack_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__last_ack']);
    $flow__end__tcp_state__none_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__none']);
    $flow__end__tcp_state__syn_recv_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__syn_recv']);
    $flow__end__tcp_state__syn_sent_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__syn_sent']);
    $flow__end__tcp_state__time_wait_rrd_filename = Rrd::name($device['hostname'], ['app', $name, $app->app_id, 'totals___flow__end__tcp_state__time_wait']);
}

$rrd_list = [
    [
        'filename' => $rrd_filename,
        'descr' => 'State Closed',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__state__established_rrd_filename,
        'descr' => 'State Established',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__state__local_bypassed_rrd_filename,
        'descr' => 'Local Bypass',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__state__new_rrd_filename,
        'descr' => 'New',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_liberal_rrd_filename,
        'descr' => 'TCP Liberal',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__close_wait_rrd_filename,
        'descr' => 'TCP Close Wait',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__closed_rrd_filename,
        'descr' => 'TCP Closed',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__closing_rrd_filename,
        'descr' => 'TCP Closing',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__established_rrd_filename,
        'descr' => 'TCP Established',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__fin_wait1_rrd_filename,
        'descr' => 'TCP Fin Wait1',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__fin_wait2_rrd_filename,
        'descr' => 'TCP Fin Wait2',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__last_ack_rrd_filename,
        'descr' => 'TCP Last Act',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__none_rrd_filename,
        'descr' => 'TCP None',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__syn_recv_rrd_filename,
        'descr' => 'TCP Syn Recv',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__syn_sent_rrd_filename,
        'descr' => 'TCP Syn Sent',
        'ds' => 'data',
    ],
    [
        'filename' => $flow__end__tcp_state__time_wait_rrd_filename,
        'descr' => 'TCP Time Wait',
        'ds' => 'data',
    ],
];

require 'includes/html/graphs/generic_multi_line.inc.php';
