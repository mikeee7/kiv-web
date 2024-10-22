<?php
    // nacteni souboru s funkcemi loginu (pracuje se session)
    require_once("MyLogin.class.php");
    $login = new MyLogin;
    // nacteni souboru s funkcemi pro nakup auta (pracuje s cookie) 
    require_once("MyNakupAuta.class.php");
    $nakup = new MyNakupAuta;

    // byl odeslan formular s akci?
    if(isset($_POST["action"])){
        // je pozadavek na ulozeni?
        if( $_POST["action"]=="ulozit"){
            $nakup->saveCarData($_POST["kola"], $_POST["barva"]);
        }
        // je pozadavek na mazani?
        else if($_POST["action"]=="smazat"){
            $nakup->deleteCar();
        }
        // znovu nactu web, abych mohl cookie primo cist
        header("Refresh:0");
    }

?>
<!doctype html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <title>Nákup automobilu</title>
    </head>
    <body>
        <h1>Nákup automobilu</h1>
<?php
   ///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
    if(!$login->isUserLogged()){
?>
        Na tuto stránku mají přístup pouze přihlášení uživatelé.<br>
        Přihlašte se prosím: <a href="login.php">Přihlášení</a>.
        
<?php
   ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
    } else {
   ///////////// PRO PRIHLASENE UZIVATELE ///////////////                
?>
        <b>Přihlášený uživatel</b><br>
        <?= $login->getUserInfo() ?>
        <br>

        Menu: <a href="login.php">Úvodní stránka s přihlášením uživatele</a><br><br>

        <form method="POST">
            <fieldset>
                <legend>Nákup automobilu</legend>
                <table>
                    <tr><td>
                            <label for="kola">Počet kol:</label>
                        </td>
                        <td>
                            <select name="kola" id="kola">
                                <?php
                                    // ziskam pocet kol ulozenych v cookie
                                    $wheels = $nakup->getWheels();
                                    // vypisu volby pro kola a zvolenou vyberu (nebo zadnou)
                                    foreach([3,4,6,8,10,14] as $w){
                                        $sel = ($w == $wheels) ? "selected" : "";
                                        echo "<option value='$w' $sel >$w</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr><td>
                            <label for="barva">Barva:</label>
                        </td>
                        <td>
                            <?php
                                // pokud je v cookie ulozena barva, tak ji pouziju
                                $col = $nakup->getColor();
                                echo "<input type='color' name='barva' value='$col' id='barva'>";
                            ?>
                        </td>
                    </tr>
                    <tr><td colspan="2">
                            <button type="submit" name="action" value="ulozit">
                                Uložit data
                            </button>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <br>

        <form method="post">
            <fieldset>
                <legend>Smazat uložené informace</legend>
                <button type="submit" name="action" value="smazat">
                    Smazat data
                </button>
            </fieldset>
        </form>
        
        <br><br>
        Vybraný automobil:<br>
        
<?php
        // je v cookie ulozena informace o vybranem automobilu?
        if($nakup->isCarSaved()){
            // pozn.: pokud data nejsou ulozena v cookie, tak getInfo() vraci prazdny retezec
            echo $nakup->getInfo();
        } else {
            echo "<i>Uživatel nemá uložena data.</i>";
        }
    }
   ///////////// KONEC: PRO PRIHLASENE UZIVATELE ///////////////                
?>
    
    </body>
</html>
