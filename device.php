<?php

class Device {
    public $name;
    public $mac;
    public $ip;
    public $wifi;
    public $network;

    function __construct($name, $mac, $network, $wifi, $ip) {
        $this->name = $name;
        $this->mac = $mac;
        $this->network = $network;
        $this->wifi = $wifi;
        $this->ip = $ip;
    }
}
