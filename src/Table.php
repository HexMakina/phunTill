<?php

/*
 * phunTill Table
 *
 */

namespace HexMakina\phunTill;

class Table {

    private $number;
    private $part;

    public function __construct($number, $part) {
        $this->number = $number;
        $this->part = $part;
    }

    public function order() : Order
    {
        return new Order($this);
    }



    public function getNumber() {
        return $this->number;
    }

    public function setNumber($number) {
        $this->number = $number;
    }

    public function getPart() {
        return $this->part;
    }

    public function setPart($part) {
        $this->part = $part;
    }

}
