<?php

class Device implements JsonSerializable {
    public $name;
    public $mac;
    public $ip;
    public $wifi;
    public $network;

    public $raw;

    public function __construct($name, $mac, $network, $wifi, $ip, $raw) {
        $this->name = $name;
        $this->mac = $mac;
        $this->network = $network;
        $this->wifi = $wifi;
        $this->ip = $ip;

        $this->raw = $raw;
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

        return [
            'name' => $this->name,
            'mac' => $this->mac,
            'network' => $netName,
            'wifi' => $wifiName,
            'ip' => $this->ip,
        ];
    }
}
