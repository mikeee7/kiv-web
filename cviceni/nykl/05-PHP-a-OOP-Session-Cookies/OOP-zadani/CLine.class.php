<?php

require_once 'AGeometricShape.abstract.php';

class CLine extends AGeometricShape{
    private $length, $slope;

    /**
     * @param $length
     * @param $slope
     */
    public function __construct(string $name, float $x, float $y, float $length, float $slope)
    {
        parent::__construct($name, $x, $y);
        $this->length = $length;
        $this->slope = $slope;
    }

    public function getInfo()
    {
        return parent::getInfo() ." | length=$this->length, slope=$this->slope";
    }


}
