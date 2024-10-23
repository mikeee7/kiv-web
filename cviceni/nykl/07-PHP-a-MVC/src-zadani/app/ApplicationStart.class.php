<?php

namespace kivweb;

/**
 * Vstupni bod webove aplikace.
 */
class ApplicationStart {

    /**
     * Inicializace webove aplikace.
     */
    public function __construct()
    {
        // nactu rozhrani kontroleru
    }

    /**
     * Spusteni webove aplikace.
     */
    public function appStart(){

        if(!empty($_GET['page']) && array_key_exists($_GET['page'], WEB_PAGES)){
            $page = $_GET['page'];
        } else {
            $page = DEFAULT_WEB_PAGE_KEY;
        }

        $pageSettings = WEB_PAGES[$page];

        $contr = new $pageSettings['controller_class_name'];
        $data = $contr->show($pageSettings['title']);

        $tpl = new $pageSettings['view_class_name'];
        echo $tpl->printOutput($data, $pageSettings['page_type']);


    }
}


?>

