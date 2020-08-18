<?php

class Link {
    public $first;
    public $firstPort;
    
    public $second;
    public $secondPort;

    function __construct($first, $firstPort, $second, $secondPort) {
        $this->first = $first;
        $this->firstPort = $firstPort;
        $this->second = $second;
        $this->secondPort = $secondPort;
    }
}
