<?php
// pripojim pozadovane rozhrani
require_once "IDrawable.interface.php";

/**
 * Class AGeometricShape.
 * Abstraktni trida se zakladnimi vlastnostmi kazdeho tvaru.
 */
abstract class AGeometricShape implements IDrawable {

    /** @var int $id  Staticky atribut pro pocitani tvaru. */
    private static $counter = 0;
    /** @var int $myId  ID konkretniho tvaru. */
    private $myId;

    /** @var string $name  Nazev tvaru. */
    private $name;

    /** @var float $x  Souradnice X. */
    private $x;
    /** @var float $y  Souradnice Y. */
    private $y;


    /**
     * Konstruktor objektu AGeometricShape.
     * Nastavi zakladni parametry tvaru.
     *
     * @param string $name  Nazev.
     * @param float $x      Souradnice X.
     * @param float $y      Souradnice Y.
     */
    public function __construct(string $name, float $x, float $y){
        $this->name = $name;
        $this->x = $x;
        $this->y = $y;
        // priradim tvaru ID
        $this->setNewId();
    }

    /**
     * Nastavi tvaru nove ID na zaklade citace.
     */
    private function setNewId(){
        // navysim citac a priradim jeho novou hodnotu do ID
        self::$counter++;
        $this->myId = self::$counter;
    }

    /**
     * Vrati zakladni textovou informaci o geom. tvaru.
     * Pozn.: final funkce nepujde prekryt v potomkovi.
     * @return string
     */
    public final function getBasicInfo(){
        // pozn.: $this odkazuje na aktualni instaci tridy, tj. getClass($this) nikdy nevrati AGeometricShape
        return $this->getMyId()
            ." | ".get_class($this)
            ." | name: ".$this->getName()
            .", x: ".$this->getX()
            .", y: ".$this->getY();
    }

    //////////  nasleduji automaticky generovane gettery  /////////////

    /**
     * @return int
     */
    public function getMyId(): int
    {
        return $this->myId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getX(): float
    {
        return $this->x;
    }

    /**
     * @return float
     */
    public function getY(): float
    {
        return $this->y;
    }

}

?>
