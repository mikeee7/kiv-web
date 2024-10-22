<?php
// pripojim pozadovanou tridu
require_once "AGeometricShape.abstract.php";

class CLine extends AGeometricShape {

    /** @var float $length  Delka */
    private $length;
    /** @var float $slope  Smernice */
    private $slope;

    /**
     * Pretezuji konstruktor rodice.
     *
     * @param string $name  Nazev.
     * @param float $x      Souradnice X.
     * @param float $y      Souradnice Y.
     * @param float $length Delka.
     * @param float $slope  Smernice.
     */
    public function __construct(string $name, float $x, float $y, float $length, float $slope)
    {
        // zaklad predam predkovi
        parent::__construct($name, $x, $y);
        // doplnim vlastni
        $this->length = $length;
        $this->slope = $slope;
    }

    /**
     * Vykresleni cary.
     * Implementace funkce z rozhrani IDrawable.
     */
    public function draw()
    {
        // primo vypisu nazev a souradnice
        echo "["
            .$this->getBasicInfo()
            .", length: ".$this->length
            .", scope: ".$this->slope
            ."]";
    }


}

?>
