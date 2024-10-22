<?php
// pripojim pozadovane rozhrani
require_once "IHasArea.interface.php";

abstract class ATetragon extends AGeometricShape implements IHasArea {

    /** @var string $color  Barva tvaru. */
    protected $color;

    /**
     * Konstruktor pro ctyruhelnik.
     *
     * @param string $name  Nazev.
     * @param float $x      Souradnice X.
     * @param float $y      Souradnice Y.
     * @param string $color Barva tvaru.
     */
    public function __construct(string $name, float $x, float $y, string $color)
    {
        parent::__construct($name, $x, $y);
        $this->color = $color;
    }


}

?>
