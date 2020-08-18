<?php

class WiFi {
    public $name;
    public $network;

    function __construct($name, $network) {
        $this->name = $name;
        $this->network = $network;
    }
}
