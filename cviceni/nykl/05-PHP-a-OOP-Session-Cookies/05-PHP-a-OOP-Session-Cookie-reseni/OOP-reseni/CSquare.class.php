<?php
// pripojim pozadovanou tridu
require_once "ATetragon.abstract.php";

class CSquare extends ATetragon {

    /** @var float $side  Delka strany. */
    public $side;

    /**
     * Konstruktor pro ctverec.
     *
     * @param string $name  Nazev.
     * @param float $x      Souradnice X.
     * @param float $y      Souradnice Y.
     * @param string $color Barva tvaru.
     * @param float $side   Strana ctverce.
     */
    public function __construct(string $name, float $x, float $y, string $color, float $side)
    {
        parent::__construct($name, $x, $y, $color);
        $this->side = $side;
    }

    /**
     * Vykresleni ctverce.
     * Implementace funkce z rozhrani IDrawable.
     */
    public function draw()
    {
        // primo vypisu nazev a souradnice
        echo "[".$this->getBasicInfo()
            .", color: ".$this->color
            .", side: ".$this->side
            ."]";
    }

    /**
     * Vypocitam a vypisu obsah.
     * Implementace funkce z rozhrani IHasArea.
     * @return float  Vypocitany obsah.
     */
    public function getArea():float
    {
        return $this->side ** 2; // druha mocnina
    }


}


?>
