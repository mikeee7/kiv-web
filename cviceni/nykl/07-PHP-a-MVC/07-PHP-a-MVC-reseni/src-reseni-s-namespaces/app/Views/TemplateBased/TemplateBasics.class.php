<?php

namespace kivweb\Views\TemplateBased;

use kivweb\Views\IView;

/**
 * Trida vypisujici HTML hlavicku a paticku stranky.
 * @package kivweb\Views\TemplateBased
 */
class TemplateBasics implements IView {

    /** @var string PAGE_INTRODUCTION  Sablona s uvodni strankou. */
    const PAGE_INTRODUCTION = "IntroductionTemplate.tpl.php";
    /** @var string PAGE_USER_MANAGEMENT  Sablona se spravou uzivatelu. */
    const PAGE_USER_MANAGEMENT = "UserManagementTemplate.tpl.php";

    /**
     * Zajisti vypsani HTML sablony prislusne stranky.
     * @param array $templateData       Data stranky.
     * @param string $pageType          Typ vypisovane stranky.
     */
    public function printOutput(array $templateData, string $pageType = self::PAGE_INTRODUCTION)
    {
        //// vypis hlavicky
        $this->getHTMLHeader($templateData['title']);

        //// vypis sablony obsahu
        // data pro sablonu nastavim globalni
        global $tplData;
        $tplData = $templateData;
        // nactu sablonu
        require_once($pageType);

        //// vypis pacicky
        $this->getHTMLFooter();
    }


    /**
     *  Vrati vrsek stranky az po oblast, ve ktere se vypisuje obsah stranky.
     *  @param string $pageTitle    Nazev stranky.
     */
    public function getHTMLHeader(string $pageTitle) {
        ?>

        <!doctype html>
        <html>
            <head>
                <meta charset='utf-8'>
                <title><?php echo $pageTitle; ?></title>
                <style>
                    nav { background-color:orange; padding:10px; }
                    nav a { margin: 0px 10px; }
                    footer { padding: 10px; background-color: lightgrey; text-align: center; }
                    .alert { padding: 10px; background-color: lightblue; font-weight: bold; margin-bottom: 20px; border-radius: 10px; }
                </style>
            </head>
            <body>
                <h1>Template: <?php echo $pageTitle; ?></h1>

                <nav>
                    <?php
                        // vypis menu
                        foreach(WEB_PAGES as $key => $pInfo){
                            echo "<a href='index.php?page=$key'>$pInfo[title]</a>";
                        }
                    ?>
                </nav>
                <br>
        <?php
    }
    
    /**
     *  Vrati paticku stranky.
     */
    public function getHTMLFooter(){
        ?>
                <br>
                <footer>Cvičení z KIV/WEB</footer>
            <body>
        </html>

        <?php
    }
        
}

?>
