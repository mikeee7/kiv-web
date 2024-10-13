<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Soubor s funkcemi nacten!<br>";

//function a() {
//    echo "Ahoj<br>";
//}
//
//function b() {
//    echo "Čau<br>";
//}
//
//function c() {
//    echo "Nazdar<br>";
//}
//
//$alfa = "c";
//$beta = $alfa;
//$gama = &$alfa;
//$alfa = "b";
//
//echo "alfa=$alfa, beta=$beta, gama=$gama<br>";
//
//$beta();
//$gama();


function nactiZvirata() {
    $soubor = "zvirata.txt";
    $vystup = '';
    $zvirata = [];

    if (is_file($soubor)) {
        $fr = fopen($soubor, "r") or die("Unable to open file!");
        while (!feof($fr)) {
            $zvirata[] = trim(fgets($fr));
        }
        fclose($fr);

        foreach ($zvirata as $zvire) {
            $vystup .= "<option value='$zvire'>$zvire</option>";
        }
    }

    return $vystup;
}

function vypisPole($pole) {
    if (count($pole) == 0) {
        echo "prázdné pole";
        return "";
    }

    $htmlTabulka = vytvorTabulku($pole);

    ulozDoSouboru($htmlTabulka);

    vypisPozdrav($pole);

    prijmiSoubory($pole);

    return $htmlTabulka;
}

function vytvorTabulku($pole) {
    $textTabulky = "<table border><tr><th>key</th><th>value</th></tr>";

    foreach($pole as $key => $value){
        $textTabulky .= "<tr>
                            <td>".$key."</td>
                            <td>".((is_array($value)</td>
                         </tr>";
    }
}