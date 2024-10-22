<?php
// pripojim pozadovanou tridu
require_once "AGeometricShape.abstract.php";

class CPoint extends AGeometricShape {

    /**
     * Vykresleni bodu.
     * Implementace funkce z rozhrani IDrawable.
     */
    public function draw()
    {
        // primo vypisu zakladni info
        echo "[". $this->getBasicInfo() ."]";
    }

}


?>
