<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////

//// Pripojeni k databazi ////

/** Adresa serveru. */
define("DB_SERVER","localhost"); // https://students.kiv.zcu.cz lze 147.228.63.10, ale musite byt na VPN
/** Nazev databaze. */
define("DB_NAME","kivweb");
/** Uzivatel databaze. */
define("DB_USER","root");
/** Heslo uzivatele databaze */
define("DB_PASS","");


//// Nazvy tabulek v DB ////

/** Tabulka s pohadkami. */
define("TABLE_INTRODUCTION", "orionlogin_mvc_introduction");
/** Tabulka s uzivateli. */
define("TABLE_USER", "orionlogin_mvc_user");


//// Dostupne stranky webu ////

/** Adresar kontroleru. */
//const DIRECTORY_CONTROLLERS = "Controllers";
/** Adresar modelu. */
//const DIRECTORY_MODELS = "Models";
/** Adresar sablon */
//const DIRECTORY_VIEWS = "Views";

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "uvod";

/** Dostupne webove stranky. */
const WEB_PAGES = array(//// Uvodni stranka ////
    "uvod" => array(
        "title" => "Úvodní stránka",

        //// kontroler
        //"file_name" => "IntroductionController.class.php",
        "controller_class_name" => \kivweb\Controllers\IntroductionController::class, // poskytne nazev tridy vcetne namespace

        // ClassBased sablona
        "view_class_name" => \kivweb\Views\ClassBased\IntroductionTemplate::class,

        // TemplateBased sablona
        //"view_class_name" => \kivweb\Views\TemplateBased\TemplateBasics::class,
        "template_type" => \kivweb\Views\TemplateBased\TemplateBasics::PAGE_INTRODUCTION,
    ),
    //// KONEC: Uvodni stranka ////

    //// Sprava uzivatelu ////
    "sprava" => array(
        "title" => "Správa uživatelů",

        //// kontroler
        //"file_name" => "UserManagementController.class.php",
        "controller_class_name" => \kivweb\Controllers\UserManagementController::class,

        // ClassBased sablona
        //"view_class_name" => \kivweb\Views\ClassBased\UserManagementTemplate::class,

        // TemplateBased sablona
        "view_class_name" => \kivweb\Views\TemplateBased\TemplateBasics::class,
        "template_type" => \kivweb\Views\TemplateBased\TemplateBasics::PAGE_USER_MANAGEMENT,
    ),
    //// KONEC: Sprava uzivatelu ////

);

?>
