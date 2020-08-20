<?php

$mikrotik_config = [
    'host' => '192.168.88.1',
    'user' => 'user',
    'pass' => 'password',
    'port' => 8728,
];

$unifi_config = [
    'user' => 'user',
    'pass' => 'password',
    'host' => 'https://unifi:8443',
    'site' => 'default',
];

$snmp_community = 'public';
$snmp_networks = null; // ['device1','devices',...], if null, all devices
