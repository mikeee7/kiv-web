<?php
    // nacteni souboru s funkcemi loginu (prace se session)
    require_once("MyLogin.class.php");
    $mylogin = new MyLogin();

    if(!empty($_POST['action'])){
        if($_POST['action'] == 'login' && !empty($_POST["jmeno"])){
            $mylogin->login($_POST["jmeno"]);
        }
        elseif ($_POST['action'] == 'logout'){
            $mylogin->logout();
        }
    }

    // zpracovani odeslanych formularu


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
    if (!$mylogin->isUserLogged()){
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
        <?= $mylogin->getUserInfo() ?>
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
