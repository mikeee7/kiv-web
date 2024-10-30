<?php 
// hlavni kontroler - rozcestnik

/////// NASTAVENI ///////////
    // vynuceny vypis vsech chyb serveru - vhodne pro students.kiv.zcu.cz
    ini_set('display_startup_errors',1);
    ini_set('display_errors',1);
    error_reporting(-1);
/////////////////////////////

// spusteni aplikace
$app = new MyApplication();
$app->appStart();

/**
 * Trida zajistujici spusteni cele aplikace.
 */
class MyApplication {

    /** @var SpravaPrihlaseni $loginManager  Sprava prihlaseni uzivatele. */
    private $loginManager;

    /** @var array $webPages  Dostupne stranky webu. */
    private $webPages;

    /**
     * Inicializace spravy prihlaseni a dostupnych stranek webu.
     */
    public function __construct()
    {
        // inicializace spravy prihlaseni
        require("SpravaPrihlaseni.class.php");
        $this->loginManager = new SpravaPrihlaseni();

        // inicializace dostupnych stranek webu
        $this->webPages =  array("uvod", "obchod");
    }

    /**
     * Reaguje na pozadavky prihlaseni a odhlaseni uzivatele.
     * @return string   Vypis informace.
     */
    public function manageUserLogin():string {
        $res = "";
        // reaguje na odeslani formularu
        if(isset($_POST["prihlaseni"]) && isset($_POST["login"])){
            $res .= "Přihlášení uživatele "
                   .($this->loginManager->prihlasUzivatele($_POST["login"])
                        ? "proběhlo v pořádku" : "se nezdařilo")
                   .".";
        } elseif (isset($_POST["odhlaseni"])) {
            // odhlaseni nevraci zadnou hodnotu
            $this->loginManager->odhlasUzivatele();
            $res .= "Proběhlo odhlášení uživatele.";
        }
        return $res;
    }

    /**
     * Spusteni cele aplikace.
     * @throws Throwable
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Syntax
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function appStart(){
        //  data pro sablonu
        $tplData = array();

        /////// Sprava prihlaseni uzivatele /////////

        // zpracovani pripadnych pozadavku na prihlaseni ci odhlaseni uzivatele
        $tplData["prihlaseni"] = $this->manageUserLogin();
        // pridam uzivatele k datum sablony
        $tplData["uzivatel"] = $this->loginManager->kontrolaPrihlaseni();

        /////// KONEC: Sprava prihlaseni uzivatele /////////
        
        /////// Nacteni kontroleru pro pozadovany web /////////////

        // mohu zobrazit pozadovany web?
        if(isset($_GET["web"]) && in_array($_GET["web"], $this->webPages)){
            // mohu - ulozim si prislusnou hodnotu
            $webKey = $_GET["web"];
        } else {
            // nemohu - zobrazim uvodni stranku (defaultne prvni)
            $webKey = $this->webPages[0];
        }
        // nactu kontroler zobrazovane stranky a vykonam jeho funkci pro ziskani dat stranky
        // pozn.: rozcestnik uz umime vytvorit i obecny, viz predchozi cviceni.
        if($webKey=="uvod"){
            require("Uvod.class.php");
            $web = new Uvod;
            $tplData = array_merge($tplData, $web->vratData());
        } 
        elseif($webKey=="obchod"){
            require("Obchod.class.php");
            $web = new Obchod;
            $tplData = array_merge($tplData, $web->vratData($tplData["uzivatel"]));
        }
        else {
            // pro pripad, kdy je neco spatne
            $tplData["nadpis"] = "Stránka nenalezena";
            $tplData["text"] = "Stránka se nepodařilo nalézt.";
        }

        /////// KONEC: Nacteni kontroleru pro pozadovany web /////////////

        /////// Vypsani dat do sablony (v1) /////////////
        
        // jen PHP
        //$this->renderInPhpTemplate($tplData);

        // PHP pres Wrapper
        //$this->renderInPhpTemplateWithWrapper($tplData);

        // Twig v.1 s sablonou pro v.1
        //$this->renderInTwigV1($tplData);

        /////// KONEC: Vypsani dat do sablony (v1) /////////////

        /////// Vypsani dat do sablony (v2) /////////////

        // Twig v.2 s sablonou pro v.2
        $this->renderInTwigV2TemplateV2($tplData, $webKey);

        /////// KONEC: Vypsani dat do sablony (v2) /////////////

    }


    ///////////////  Vypis do sablon v PHP  /////////////////////

    /**
     * Vykresleni obsahu v PHP sablone.
     * @param array $data     Data pro sablonu.
     */
    private function renderInPhpTemplate(array $data){
        // pripojim prislusnou sablonu
        include 'sablony-php/Sablona.class.php';
        // volam prime vypsani sablony
        Sablona::zobraz($data);
    }

    /**
     * Vykresleni obsahu v PHP sablone s vyuzitim wrapperu
     * (tj. Output buffer odchytava vypis PHP sablony).
     * @param array $data     Data pro sablonu.
     */
    private function renderInPhpTemplateWithWrapper(array $data){
        // pripojim tridu zajistuji wrapping
        include 'MyWrapper.class.php';
        // odpovidajici sablona
        $template = 'sablony-php/sablona.tpl.php';
        // urcim, globalni tplData, abych je mohl v sablone pouzit
        global $tplData;
        $tplData = $data;
        // vlozim data do sablony a vypisu sablonu
        echo MyWrapper::renderWithWrapper($template);
    }

    ///////////////  KONEC: Vypis do sablon v PHP  /////////////////////

    ///////////////  Vypis do sablon Twigu, ktere odpovidaji sablone v PHP  /////////////////////

    /**
     * Vykresleni obsahu Twigem v.1 v sablone pro v.1.
     * Vyuziva vlastni adresar Twigu ze ZIP souboru.
     * @param array $data   Data pro sablonu.
     *
     * @throws Throwable
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Syntax
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function renderInTwigV1(array $data){
        // nacist twig z vlastniho adresare
        require_once '../twig-master/lib/Twig/Autoloader.php';
        // adresar s sablonami
        $templatesDirectory = 'sablony-twig-v1';
        // nyni system vyuziva jen jednu sablonu pro vypis obou stranek
        // pri pouziti vice sablon musi byt spravna sablona zvolena zde
        $currentTemplateName = 'cela-sablona-v-jednom-souboru.twig';
        // nebo lepsi sablona
        //$currentTemplateName = 'sablona.twig';

        // registrace Twigu
        Twig_Autoloader::register();
        // cesta k adresari se sablonama - od index.php
        $loader = new Twig_Loader_Filesystem($templatesDirectory);
        // vytvoreni prostredi - lze bez cache
        $twig = new Twig_Environment($loader
            /*,[
                'cache' => 'vlastni_cache',
            ]*/
        );

        // nacteni zvolene sablony z adresare
        $template = $twig->loadTemplate($currentTemplateName);

        ///// vypsani vysledku prostrednictvim sablony
        echo $template->render($data);
    }

    /**
     * Vykresleni obsahu Twigem v.2 v sablone pro v.1.
     * Vyuziva adresar Twigu, ktery vytvoril Composer.
     * Slouzi pouze na ukazku, jak se lisi nacitani Twigu v.1 a Twigu v.2.
     * @param array $data   Data pro sablonu.
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function renderInTwigV2TemplateV1(array $data){
        // nacist twig z vendor component ziskanych s vyuzitim Composer
        require_once '../composer/vendor/autoload.php';
        // adresar s sablonami
        $templatesDirectory = 'sablony-twig-v1';
        // nyni system vyuziva jen jednu sablonu pro vypis obou stranek
        // pri pouziti vice sablon musi byt spravna sablona zvolena zde
        $currentTemplateName = 'cela-sablona-v-jednom-souboru.twig';
        // nebo lepsi sablona
        //$currentTemplateName = 'sablona.twig';

        // cesta k adresari se sablonama - od index.php
        $loader = new \Twig\Loader\FilesystemLoader($templatesDirectory);
        // vytvoreni prostredi - lze bez cache
        $twig = new \Twig\Environment($loader
            /*, [
                'cache' => 'vlastni_cache',
            ]*/
        );

        ///// vypsani vysledku prostrednictvim sablony
        echo $twig->render($currentTemplateName, $data);
    }

    ///////////////  KONEC: Vypis do sablon Twigu, ktere odpovidaji sablone v PHP  /////////////////////

    ///////////////  Vypis do sablony Twigu, lepsi varianta  /////////////////////

    /**
     * Vykresleni obsahu Twigem v.2 v sablone pro v.2.
     * Vyuziva adresar Twigu, ktery vytvoril Composer.
     * Tato verze je vice nazorna, co se tyka ukazky prace s Twigem.
     * @param array $data           Data pro sablonu.
     * @param string $templateKey   Klic pro prislusnou stranku.
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function renderInTwigV2TemplateV2(array $data, string $templateKey){
        // definice sablon prislusnych stranek
        // zde doporucuji pouzit variantu z predesleho cviceni, kde stranky webu jsou ulozeny v konstante
        $webTemplates = array(
            "uvod" => "stranka-uvod.twig",
            "obchod" => "stranka-obchod.twig"
        );

        // nacist twig z vendor component ziskanych s vyuzitim Composer
        require_once '../composer/vendor/autoload.php';
        // adresar s sablonami
        $templatesDirectory = 'sablony-twig-v2';
        // nyni system vyuziva jen jednu sablonu pro vypis obou stranek
        // pri pouziti vice sablon musi byt spravna sablona zvolena zde
        $currentTemplateName = $webTemplates[$templateKey];

        // cesta k adresari se sablonama - od index.php
        $loader = new \Twig\Loader\FilesystemLoader($templatesDirectory);
        // vytvoreni prostredi - lze bez cache, ale pridam debug kvuli funkci dump()
        $twig = new \Twig\Environment($loader, [
                'debug' => true,
                /*'cache' => 'vlastni_cache',*/
        ]);
        // pridani funkci pro debug
        $twig->addExtension(new \Twig\Extension\DebugExtension());

        ///// vypsani vysledku prostrednictvim sablony
        echo $twig->render($currentTemplateName, $data);
    }

    ///////////////  KONEC: Vypis do sablony Twigu, lepsi varianta  /////////////////////

}

?>