<?php

class Link implements JsonSerializable {
    public $first;
    public $firstPort;
    
    public $second;
    public $secondPort;

    public function __construct($first, $firstPort, $second, $secondPort) {
        $this->first = $first;
        $this->firstPort = $firstPort;
        $this->second = $second;
        $this->secondPort = $secondPort;
    }

    public function jsonSerialize() {
        return [
            'first' => $this->first->mac,
            'firstPort' => (string)$this->firstPort,
            'second' => $this->second->mac,
            'secondPort' => (string)$this->secondPort,
        ];
    }
}
