<?php

class Network {
    public $name;
    public $vlan;
    public $subnet;

    public function __construct($name, $vlan, $subnet) {
        $this->name = $name;
        $this->vlan = (int)$vlan;
        if ($this->vlan < 1) {
            $this->vlan = 1;
        }
        $this->subnet = $subnet;
    }
}
