<?php
    // pripojeni souboru s funkcemi
    require_once("funkce-reseni.php");
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Výstup</title>
    </head>
    <body>
        <h1>Výstup formuláře</h1>
    
        <div>
            <strong>Post:</strong> <br>
            <?php
                // vypíše pole do tabulky
                echo vypisPole($_POST);
            ?>
        </div>
        <br>
        
        <div>
            <strong>Get:</strong> <br>
            <?php
                // vypíše pole do tabulky
                echo vypisPole($_GET);
            ?>
        </div>
        
    </body>
</html>