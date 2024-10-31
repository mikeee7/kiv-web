<?php

namespace kivweb\Views\ClassBased;

use kivweb\Views\IView;

/**
 * Sablona pro zobrazeni stranky se spravou uzivatelu.
 * @package kivweb\Views\ClassBased
 */
class UserManagementTemplate implements IView {

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

        // mam vypsat hlasku?
        if(isset($tplData['delete'])){
            echo "<div class='alert'>$tplData[delete]</div>";
        }

        $res = "<table border><tr><th>ID</th><th>Jméno</th><th>Příjmení</th><th>Login</th><th>E-mail</th><th>Web</th><th>Akce</th></tr>";
        // projdu data a vytvorim radky tabulky
        foreach($tplData['users'] as $u){
            $res .= "<tr><td>$u[id_user]</td><td>$u[first_name]</td><td>$u[last_name]</td><td>$u[login]</td><td>$u[email]</td><td>$u[web]</td>"
                ."<td><form method='post'>"
                ."<input type='hidden' name='id_user' value='$u[id_user]'>"
                ."<button type='submit' name='action' value='delete'>Smazat</button>"
                ."</form></td></tr>";
        }
        $res .= "</table>";
        // vypisu tabulku
        echo $res;

        // paticka
        $tplHeaders->getHTMLFooter();

    }

}

?>
