<?php

require_once('config.php');

require_once('vendor/autoload.php');
require_once('device.php');
require_once('network.php');
require_once('wifi.php');
require_once('link.php');

header('Content-Type: text/plain');

$unifi = new UniFi_API\Client($unifi_config['user'], $unifi_config['pass'], $unifi_config['host'], $unifi_config['site']);
$mikrotik = new \RouterOS\Client($mikrotik_config);

$res = $unifi->login();
if ($res !== true) {
    die('Controller login failure: ' . $res);
}

$device_list = $unifi->list_devices();
$client_list = $unifi->list_clients();
$wifi_list = $unifi->list_wlanconf();
$network_list = $unifi->list_networkconf();
$dhcp_lease_list = $mikrotik->query(new \RouterOS\Query('/ip/dhcp-server/lease/print'))->read();

$networks = [];
foreach ($network_list as $network) {
    $networkObject = new Network($network->name, $network->vlan, @$network->ip_subnet);
    $networks[$networkObject->vlan] = $networkObject;
    $networks[$networkObject->name] = $networkObject;
}

$wifis = [];
foreach ($wifi_list as $wifi) {
    if (!$wifi->vlan_enabled) {
        $vlan = '';
    } else {
        $vlan = $wifi->vlan;
    }
    $network = $networks[$vlan];
    $wifiObj = new WiFi($wifi->name, $network);
    $wifis[$wifiObj->name] = $wifiObj;
}

$dhcp_leases = [];
foreach ($dhcp_lease_list as $lease) {
    $dhcp_leases[strtolower($lease['mac-address'])] = $lease;
}

$devices = [];

foreach ($device_list as $device) {
    $mac = strtolower($device->mac);
    $name = $device->name;
    $network = $networks[''];
    $ip = $device->ip;
    $devices[$mac] = new Device($name, $mac, $network, null, $ip, $device);
}

foreach ($client_list as $client) {
    $mac = strtolower($client->mac);
    if (!empty($devices[$mac])) {
        continue;
    }

    $lease = @$dhcp_leases[$mac];
    $ip = NULL;
    $name = NULL;
    $network = NULL;
    $wifi = NULL;

    if (!empty($lease)) {
        if (!empty($lease['comment'])) {
            $name = $lease['comment'];
        } else if (!empty($lease['host-name'])) {
            $name = $lease['host-name'];
        }
        if (!empty($lease['address'])) {
            $ip = $lease['address'];
        }
    }

    if (!empty($client->ip)) {
        $ip = $client->ip;
    }

    if (empty($name)) {
        if (!empty($client->hostname)) {
            $name = $client->hostname;
        } else if (!empty($ip)) {
            $name = $ip;
        } else {
            $name = $mac;
        }
    }

    $name = str_replace('.foxden.network', '', $name);

    if (!empty($client->essid)) {
        $wifi_name = $client->essid;
        $wifi = $wifis[$wifi_name];
        if (!empty($client->vlan) && $client->vlan !== '0') {
            $network = $networks[$client->vlan];
        } else {
            $network = $wifi->network;
        }
    } elseif (!empty($client->network)) {
        $network = $networks[$client->network];
    }

    $devices[$mac] = new Device($name, $mac, $network, $wifi, $ip, $client);
}

foreach ($devices as $key=>$device) {
    $raw = $device->raw;
    unset($device->raw);

    if (!empty($raw->sw_port) && !empty($raw->sw_mac)) {
        $switch = $devices[$raw->sw_mac];

        $link = new Link($switch, $raw->sw_port, $device, '');
        $switch->links[] = $link;
        $device->links[] = $link;
    }

    if (!empty($raw->uplink) && !empty($raw->uplink->uplink_mac)) {
        $switch = $devices[$raw->uplink->uplink_mac];

        $port_idx = '';
        if (!empty($raw->uplink->port_idx)) {
            $port_idx = $raw->uplink->port_idx;
        }

        $link = new Link($switch, $raw->uplink->uplink_remote_port, $device, $port_idx);
        $switch->links[] = $link;
        $device->links[] = $link;
    }
}

var_dump($devices);
