<?php

class Device {
    public $name;
    public $mac;
    public $ip;
    public $wifi;
    public $network;
    public $links;

    public $raw;

    function __construct($name, $mac, $network, $wifi, $ip, $raw) {
        $this->name = $name;
        $this->mac = $mac;
        $this->network = $network;
        $this->wifi = $wifi;
        $this->ip = $ip;

        $this->raw = $raw;

        $this->links = [];
    }
}
