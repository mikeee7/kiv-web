<?php

require_once 'IDrawable.interface.php';

abstract class AGeometricShape implements IDrawable {
    /** @var string $name  ..... */
    private $name = "";
    private $x, $y;
    private $id;
    private static $counter = 0;

    /**
     * @param string $name
     * @param float $x
     * @param float $y
     */
    public function __construct(string $name, float $x, float $y)
    {
        $this->name = $name;
        $this->x = $x;
        $this->y = $y;
        $this->setID();
    }

    private function setID()
    {
        self::$counter++;
        $this->id = self::$counter;
    }

    public function getInfo()
    {
        return "$this->id | ". get_class($this) ." | $this->name | x=$this->x, y=$this->y";
    }


        public function draw()
    {
        echo $this->getInfo();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setX(float $x): void
    {
        $this->x = $x;
    }

    public function setY(float $y): void
    {
        $this->y = $y;
    }
}