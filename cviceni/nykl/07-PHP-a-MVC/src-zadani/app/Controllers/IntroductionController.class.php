<?php
// nactu rozhrani kontroleru
namespace kivweb\Controllers;

use kivweb\Models\DatabaseModel;

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class IntroductionController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        $this->db = new DatabaseModel();
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return array               Vypis v sablone.
     */
    public function show(string $pageTitle):array {
        $data = [
          'title' => $pageTitle,
          'stories' => $this->db->getAllIntroductions()
        ];
        return $data;
    }
    
}

?>