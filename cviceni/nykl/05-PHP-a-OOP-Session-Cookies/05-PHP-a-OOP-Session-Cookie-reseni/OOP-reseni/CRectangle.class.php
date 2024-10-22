<?php
// pripojim pozadovanou tridu
require_once "ATetragon.abstract.php";

/**
 * Class CRectangle
 * Trida pro obdelnik, ktery je geometrickym tvarem a soucasne ma obsah a delku stran.
 */
class CRectangle extends ATetragon {

    /** @var float $sideA  Delka strany A. */
    public $sideA;
    /** @var float $sideB  Delka strany B. */
    public $sideB;

    /**
     * Konstruktor pro obdelnik.
     *
     * @param string $name  Nazev.
     * @param float $x      Souradnice X.
     * @param float $y      Souradnice Y.
     * @param string $color Barva tvaru.
     * @param float $sideA  Strana A.
     * @param float $sideB  Strana B.
     */
    public function __construct(string $name, float $x, float $y, string $color, float $sideA, float $sideB)
    {
        parent::__construct($name, $x, $y, $color);
        $this->sideA = $sideA;
        $this->sideB = $sideB;
    }

    /**
     * Vykresleni obdelniku.
     * Implementace funkce z rozhrani IDrawable.
     */
    public function draw()
    {
        // primo vypisu nazev a souradnice
        echo "["
            .$this->getBasicInfo()
            .", color: ".$this->color
            .", sideA: ".$this->sideA
            .", sideB: ".$this->sideB
            ."]";
    }

    /**
     * Vypocitam a vypisu obsah.
     * Implementace funkce z rozhrani IHasArea.
     * @return float  Vypocitany obsah.
     */
    public function getArea():float
    {
        return ($this->sideA * $this->sideB);
    }

}

?>
