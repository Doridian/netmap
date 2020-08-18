<?php

class Network {
    public $name;
    public $vlan;
    public $subnet;

    function __construct($name, $vlan, $subnet) {
        $this->name = $name;
        $this->vlan = $vlan;
        $this->subnet = $subnet;
    }
}
