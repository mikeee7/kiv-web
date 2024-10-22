<?php

/**
 * Interface IHasArea
 * Rozhrani pro "obsahy".
 */
interface IHasArea {

    /**
     * Vypocte obsah daneho tvaru.
     *
     * @return float    Vypocitany obsah.
     */
    public function getArea():float;

}

?>
