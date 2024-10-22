<?php
    // nacteni souboru s funkcemi loginu (pracuje se session)
    require_once("MyLogin.class.php");
    $login = new MyLogin;

    // zpracovani odeslanych formularu - mam akci?
    if(isset($_POST["action"])){
        // mam pozadavek na login ?
        if($_POST["action"] == "login") {
            // mam co ulozit?
            if (isset($_POST["jmeno"]) && $_POST["jmeno"] != "") {
                // prihlasim uzivatele
                $login->login($_POST["jmeno"]);
            } else {
                echo "Chyba: Nebylo zadáno jméno uživatele.<br>";
            }
        }
        // mam pozadavek na logout?
        else if($_POST["action"] == "logout"){
            // odhlasim uzivatele
            $login->logout();
        }
        // neznamy pozadavek
        else {
            echo "<script>alert('Chyba: Nebyla rozpoznána požadovaná akce.');</script>";
        }
    }

?>
<!doctype html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <title>Úvodní stránka</title>
    </head>
    <body>
        <h1>Úvodní stránka</h1>
<?php

   ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
    if(!$login->isUserLogged()){
?>
        <form method="POST">
            <fieldset>
                <legend>Přihlášení uživatele</legend>
                <input type="text" name="jmeno" placeholder="-- zadejte jméno --">
                <button type="submit" name="action" value="login">
                    Přihlásit uživatele
                </button>
            </fieldset>
        </form>

<?php
   ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
    } else {
   ///////////// PRO PRIHLASENE UZIVATELE ///////////////                
?>
        <b>Přihlášený uživatel</b><br>
        <?= $login->getUserInfo() ?>
        <br>
        
        Menu: <a href="nakup-auta.php">Nákup auta</a><br>
        <br>

        <form method="POST">
            <fieldset>
                <legend>Odhlášení uživatele</legend>
                <button type="submit" name="action" value="logout">
                    Odhlásit uživatele
                </button>
            </fieldset>
        </form>

<?php
    }
   ///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////                
?>
    
    </body>
</html>
