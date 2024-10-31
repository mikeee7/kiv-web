<?php

namespace kivweb\Views\ClassBased;

use kivweb\Views\IView;

/**
 * Sablona pro zobrazeni uvodni stranky.
 * @package kivweb\Views\ClassBased
 */
class IntroductionTemplate implements IView {

    /**
     * Zajisti vypsani HTML sablony prislusne stranky.
     * @param array $tplData Data stranky.
     */
    public function printOutput(array $tplData)
    {
        $tplHeaders = new TemplateBasics();
        // hlavicka
        $tplHeaders->getHTMLHeader($tplData['title']);

        echo '<div class="alert-info">ClassBased</div>';

        // vypis pohadek
        $res = "";
        if(array_key_exists('stories', $tplData)) {
            foreach ($tplData['stories'] as $d) {
                $res .= "<h2>$d[title]</h2>";
                $res .= "<b>Autor:</b> $d[author] (" . date("d. m. Y, H:i.s", strtotime($d['date'])) . ")<br><br>";
                $res .= "<div style='text-align:justify;'><b>Úryvek:</b> $d[text]</div><hr>";
            }
        } else {
            $res .= "Pohádky nenalezeny";
        }
        echo $res;

        // paticka
        $tplHeaders->getHTMLFooter();
    }

}

?>
