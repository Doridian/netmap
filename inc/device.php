<?php

class Device implements JsonSerializable {
    public $name;
    public $mac;
    public $ip;
    public $wifi;
    public $network;

    public $interfaces;

    public $raw;

    public function __construct($name, $mac, $network, $wifi, $ip, $raw) {
        $this->name = $name;
        $this->mac = $mac;
        $this->network = $network;
        $this->wifi = $wifi;
        $this->ip = $ip;

        $this->raw = $raw;
    }

    public function fetchSNMP() {
        global $snmp_community, $snmp_devices;
        if ($snmp_devices && !in_array($this->name, $snmp_devices)) {
            return;
        }

        $ifName = snmp2_real_walk($this->ip, $snmp_community, 'IF-MIB::ifName');
        $ifPhysAddress = snmp2_real_walk($this->ip, $snmp_community, 'IF-MIB::ifPhysAddress');

        foreach ($ifName as $key => $name) {
            $idx = explode('.', $key)[1];
            $mac = $ifPhysAddress['IF-MIB::ifPhysAddress.' . $idx];
            $interfaces[$mac] = $name;
        }
    }

    public function jsonSerialize() {
        $netName = null;
        $wifiName = null;

        if ($this->network) {
            $netName = $this->network->name;
        }
        if ($this->wifi) {
            $wifiName = $this->wifi->name;
        }

        return array(
            'name' => $this->name,
            'mac' => $this->mac,
            'network' => $netName,
            'wifi' => $wifiName,
            'ip' => $this->ip,
        );
    }
}
