<?php

class WiFi implements JsonSerializable {
    public $name;
    public $network;

    public function __construct($name, $network) {
        $this->name = $name;
        $this->network = $network;
    }

    public function jsonSerialize() {
        return [
            'name' => $this->name,
            'network' => $this->network->name,
        ];
    }
}
