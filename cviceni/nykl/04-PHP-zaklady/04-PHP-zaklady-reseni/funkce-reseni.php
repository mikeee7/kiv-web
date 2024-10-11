<?php

// defaultne vynutim vypis chyb na serveru students.kiv.zcu.cz
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/////////////////////////////////////////////////////////////////////////////////

//// informace o spravnem nacteni souboru s funkcemi
echo "Funkce byly načteny.<br>";

//// funkce s pozdravy

function a(){
    echo "Ahoj<br>";
}
function b(){
    echo "Čau<br>";
}
function c(){
    echo "Čau<br>";
}

//// prace s promennymi

$alfa = "c";
// prirazeni hodnoty
$beta = $alfa;
// prirazeni adresy do pameti, tj. reference
$gama = &$alfa;

// zmena hodnoty v alfa
$alfa = "b";

// vypisu
echo "alfa=$alfa, beta=$beta, gama=$gama <br>";

// prime volani funkci z hodnoty promenne
$beta(); // --> vola c()
$gama(); // --> vola b()

// potlaceni lokalni chyby
echo @"Nemam $abcde <br>";

/////////////////////////////////////////////////////////////////////////////////

/**
*     Načte volby pro SELECT se zvířaty.
*     @return string    Volby do SELECT.
*/
function nactiVolby(){
    $soubor = "zvirata.txt";
    $vystup = ""; // vystupni string
    $zvirata = []; // nebo array()
    // existuje a je souborem? staci is_file
    if(/*file_exists($soubor) && */ is_file($soubor)){

        //// nacteni souboru - varianta A
        $fr = fopen($soubor, "r") or die("Soubor nelze otevřít!"); // otevři soubor pro čtení
        while(!feof($fr)) { // je konec souboru
            $zvirata[] = trim(fgets($fr)); // načti řádek a odstraň bílé znaky
        }
        fclose($fr); // zavři soubor

        //// nacteni souboru - varianta B
        /*$obsah = file_get_contents($soubor);
        $zvirata = explode("\n", $obsah);
        */

        //// vytvoreni voleb pro HTML seznam
        foreach($zvirata as $zvire){
            // volba pro seznam
            $vystup .= "<option value='$zvire'>$zvire</option>";
        }
    }

    // vracim  nactene volby
    return $vystup;
}

/////////////////////////////////////////////////////////////////////////////////

/**
*  Vypíše obsah pole do tabulky a uloží ji do souboru. 
*  @param array $pole   Vstupni pole.
*  @return string       HTML pro vytvoreni tabulky.
*/
function vypisPole($pole){
    // není pole prázdné
    if(count($pole)==0){
        // je - jen informuji a koncim
        echo "prázdné pole";
        return ""; 
    }

    //// ukazka ukonceni PHP a provedeni vypisu HTML a ukazka funkci pro debug
    /*
    ?>
        <pre><?php print_r($pole) ?></pre>
        <hr>
        <pre><?php var_dump($pole) ?></pre>
    <?php
    */

    // prevedu pole na tabulku
    $htmlTabulka = vytvorTabulku($pole); // vytvoří z pole tabulku

    // uloží tabulku do souboru
    ulozDoSouboru($htmlTabulka);

    // vypise pozdrav
    vypisPozdrav($pole);

    // prijmy soubory
    prijmySoubory($pole);

    // vracim HTML tabulku
    return $htmlTabulka;
}

/**
  *  Vypíše obsah pole do tabulky. Používá rekurzi na vložená pole a pokud není u prvku vyplněna hodnota, tak vypíše "nezadáno".
  *  @param array $pole    Vstupni pole.
  *  @return string         HTML pro vytvoreni tabulky.
 */  
function vytvorTabulku($pole){
    // hlavicka tabulky
    $textTabulky = "<table border><tr><th>key</th><th>value</th></tr>";
    // radky tabulky
    foreach($pole as $key => $value){ // procházím pole
        // minimalisticke reseni - ternarni operator
        $textTabulky.= "<tr><td>".$key."</td><td>"
                        .((is_array($value))
                            ? vytvorTabulku($value)
                            : (((trim($value)=="") ? "nezadáno" : $value)
                        ."</td></tr>")); // vypise nebo rekurze
        
        // rozepsane reseni
        /*$textTabulky.= "<tr><td>".$key."</td><td>";
        if(is_array($value)){
            $textTabulky.= vytvorTabulku($value); // rekurze
        } else {
            $textTabulky.= (trim($value)=="") ? "nezadáno":$value; // jen hodnota
        } 
        $textTabulky.= '</td></tr>';
        */
    }
    // konec tabulky
    $textTabulky.= "</table>";
    return $textTabulky;
} 


/**
*   Uloží text do souboru.
*   @param string $text     Text pro uložení do souboru.
*/
function ulozDoSouboru($text){
    //// kontrola existence adresáře
    $adr = "vystup";
    // existuje soubor se jménem adresáře?
    if(is_file($adr)){
        echo "Adresář nelze vytvořit, protože existuje soubor totožného jména.<br>";
    }
    // neexistuje adresář ani soubor
    else if(!file_exists($adr)) {
        // vytvořím adresář
        mkdir($adr);
    }

    //// slozeni obsahu souboru
    // hlavicka, obsah a paticka html
    $obsah = "<!doctype html><html><head><meta charset='utf-8'></head><body>$text</body></html>";

    //// kompletni ulozeni souboru
    // slozim nazev souboru vcetne cesty
    $soubor = $adr ."/". date("Y-m-d_H-i-s") .".html";

    // ulozeni souboru - varianta A
    $fw = fopen($soubor, "w") or die("Soubor nelze otevřít"); // otevři soubor pro zápis
    fwrite($fw, $obsah); // zapis
    fclose($fw); // ukonceni prace se souborem

    // ulozeni souboru - varianta B
    file_put_contents($soubor, $obsah, LOCK_EX); // blokuju soubor, aby do nej nemohl soucasne zapsat nekdo jiny

    // informace o vytvoreni souboru
    echo " -- Vytvořen soubor: ".$soubor." --<hr>";

    // odeslu text emailem
    odeslaniEmailu($obsah);
}

/////////////////////////////////////////////////////////////////////////////////

/**
 * Vypise pozdrav dle promenne (alfa, beta, gama), ktera je dana hodnotou v $pole["pozdrav"].
 * @param array $pole     Vstupni pole.
 */
function vypisPozdrav($pole){
    // pripojim promenne, ktere nejsou soucasti funkce
    global $alfa, $beta, $gama;
    // defaultni vystup
    echo "-- Pozdrav --<br>";

    // je zvolen pozdrav?
    if(isset($pole["pozdrav"]) && $pole["pozdrav"]!=""){
        //// zavolá funkci uloženou v proměnné
        // rozepsane reseni
        $promenna = $pole["pozdrav"];
        $funkce = $$promenna;
        $funkce();
    }

    // vypisu pozdrav
    echo "<hr>";
}

/////////////////////////////////////////////////////////////////////////////////

/**
 * Pokud byly na server odeslany soubory, tak je ulozi do adresare data
 * @param $pole
 */
function prijmySoubory($pole){
    // info o max. velikosti souboru
    echo "Atributy PHP:<br>"
        ."upload_max_filesize: ".ini_get("upload_max_filesize")."<br>"
        ."post_max_size: ".ini_get("post_max_size")."<br>";

    //// mam adresar data?
    $adr = "DATA";
    // neni souborem
    if(is_file($adr)){
        echo "Nelze vytvořit adresář DATA.<br>";
    }
    // neni souborem a neexistuje?
    elseif(!file_exists($adr)) {
        mkdir($adr);
    }
    // nemam adresar data?
    if(!is_dir($adr)){
        echo "Adresář DATA nelze použít.<br>";
        return; // konec funkce
    }

    //// byly na server odeslany nejake soubory?
    if(isset($_FILES["soubory"]["name"])
        && count($_FILES["soubory"]["name"]) > 0
        && !empty($_FILES["soubory"]["name"][0])
    ){
        // byly - prijmu je a ulozim na server
        // viz https://www.w3schools.com/php/php_file_upload.asp
        // pocet souboru vezmu z poctu nazvu
        for($i = 0; $i < count($_FILES["soubory"]["name"]); $i++){
            // ziskam nazev souboru, slozim mu celou cestu a ziskam z ni priponu souboru
            $nazev = basename( $_FILES["soubory"]["name"][$i]);
            $celyNazev = $adr."/".$nazev;
            $pripona = strtolower(pathinfo($celyNazev,PATHINFO_EXTENSION));
            // ziskam velikost souboru - varianta ciste pro upload souboru
            $velikost = $_FILES["soubory"]["size"][$i] . " B.";
            // ziskam velikost souboru - varianta pro libovolny soubor
            //$velikost = filesize($_FILES["soubory"]["tmp_name"][$i]) . " B.";
            // vypis informace
            echo "Zpracovávám soubor [$pripona]: '$nazev', velikost: $velikost<br>";

            //// prenesu soubor na server
            // pozor, pokud je server provozovan pod Windows, tak defaultne pracuje s kodovanim windows-1250 namisto utf-8
            // prevod nazvu z UTF-8 do cp-1250
            $celyNazev = iconv("UTF-8", "WINDOWS-1250", $celyNazev);
            // samotny prenos
            if (move_uploaded_file($_FILES["soubory"]["tmp_name"][$i], $celyNazev)) {
                echo "Soubor '$nazev' byl nahrán na server.<br>";
            } else {
                echo "Soubor '$nazev' se nepodařilo nahrát na server.<br>";
            }
        }
    }
    echo "<hr>";
}

/////////////////////////////////////////////////////////////////////////////////

// Pokud je pouzivaten MailHog, tak vyzaduje upravu php.ini:
// smtp_port=1025, SMTP=localhost
// coz lze udelat i z PHP kodu:
// ini_set("smtp_port", 1025);
// ini_set("SMTP", "localhost")


/**
 * Odesle HTML text na email.
 * @param string $htmlText  Text pro odeslani.
 * @return bool
 */
function odeslaniEmailu(string $htmlText, string $prijemce = "prijemce@example.com"){
    // kontrola, zda se jedna o validni email - bud vraci dany email, nebo false
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        echo "$email neni validni emailovou adresou.";
        return false;
    }
    
    //// pripravim si hlavicky emailu
    // typ dokumentu text/html
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // odesilatel a kopie
    $headers .= "From: <odesilatel@example.com>" . "\r\n";
    $headers .= "Cc: kopie@example.com" . "\r\n";

    // predmet
    $subject = "Můj HTML email";
    
    // text emailu - radky nesmi byt desli nez 70 znaku, jinak nemusi byt odeslano (ukonceni s \n)
    // rozdeli radky tak, aby nebylo deleno slovo
    $htmlText = wordwrap($htmlText, 70);

    // odeslu email
    return mail($prijemce,$subject,$htmlText,$headers);
}

?>
