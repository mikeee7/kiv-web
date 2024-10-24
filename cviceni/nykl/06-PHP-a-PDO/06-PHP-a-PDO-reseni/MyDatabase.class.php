<?php
//////////////////////////////////////////////////////////////
////////////// Vlastni trida pro praci s databazi ////////////////
//////////////////////////////////////////////////////////////

/**
 * Vlastni trida spravujici databazi.
 */
class MyDatabase {

    /** @var PDO $pdo  PDO objekt pro praci s databazi. */
    private $pdo;

    /** @var MySession $mySession  Vlastni objekt pro spravu session. */
    private $mySession;
    /** @var string KEY_USER  Klic pro data uzivatele, ktera jsou ulozena v session. */
    private const KEY_USER = "current_user_id";

    /**
     * MyDatabase constructor.
     * Inicializace pripojeni k databazi a pokud ma byt spravovano prihlaseni uzivatele,
     * tak i vlastni objekt pro spravu session.
     * Pozn.: v samostatne praci by sprava prihlaseni uzivatele mela byt v samostatne tride.
     * Pozn.2: take je mozne do samostatne tridy vytahnout konkretni funkce pro praci s databazi.
     */
    public function __construct(){
        // inicialilzuju pripojeni k databazi - informace beru ze settings
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8");
        // nastavení PDO error módu na výjimku, tj. každá chyba při práci s PDO bude výjimkou
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // inicializuju objekt pro praci se session - pouzito pro spravu prihlaseni uzivatele
        // pozn.: v samostatne praci vytvorte pro spravu prihlaseni uzivatele samostatnou tridu.
        require_once("MySessions.class.php");
        $this->mySession = new MySession();
    }


    ///////////////////  Obecne funkce  ////////////////////////////////////////////

    /**
     *  Provede dotaz a bud vrati ziskana data, nebo pri chybe ji vypise a vrati null.
     *  Varianta, pokud NENI pouzit PDO::ERRMODE_EXCEPTION
     *
     *  @param string $dotaz        SQL dotaz.
     *  @return PDOStatement|null    Vysledek dotazu.
     */
    private function executeQueryWithoutException(string $dotaz){
        // vykonam dotaz
        $res = $this->pdo->query($dotaz);
        // pokud neni false, tak vratim vysledek, jinak null
        if ($res != false) {
            // neni false
            return $res;
        } else {
            // je false - vypisu prislusnou chybu a vratim null
            $error = $this->pdo->errorInfo();
            echo $error[2];
            return null;
        }
    }

    /**
     *  Provede dotaz a bud vrati ziskana data, nebo pri chybe ji vypise a vrati null.
     *  Varianta, pokud je pouzit PDO::ERRMODE_EXCEPTION
     *
     *  @param string $dotaz        SQL dotaz.
     *  @return PDOStatement|null    Vysledek dotazu.
     */
    private function executeQuery(string $dotaz){
        // vykonam dotaz
        try {
            $res = $this->pdo->query($dotaz);
            return $res;
        } catch (PDOException $ex){
            echo "Nastala výjimka: ". $ex->getCode() ."<br>"
                ."Text: ". $ex->getMessage();
            return null;
        }
    }

    /**
     * Jednoduche cteni z prislusne DB tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Pripadne omezeni na ziskani radek tabulky. Default "".
     * @param string $orderByStatement  Pripadne razeni ziskanych radek tabulky. Default "".
     * @return array                    Vraci pole ziskanych radek tabulky.
     */
    public function selectFromTable(string $tableName, string $whereStatement = "", string $orderByStatement = ""):array {
        // slozim dotaz
        $q = "SELECT * FROM ".$tableName
            .(($whereStatement == "") ? "" : " WHERE $whereStatement")
            .(($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud je null, tak vratim prazdne pole
        if($obj == null){
            return [];
        }
        // projdu jednotlive ziskane radky tabulky
        /*while($row = $vystup->fetch(PDO::FETCH_ASSOC)){
            $pole[] = $row['login'].'<br>';
        }*/
        // prevedu vsechny ziskane radky tabulky na pole
        return $obj->fetchAll();
    }

    /**
     * Jednoduche vlozeni do prislusne tabulky.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $insertStatement   Text s nazvy sloupcu pro insert.
     * @param string $insertValues      Text s hodnotami pro prislusne sloupce.
     * @return bool                     Vlozeno v poradku?
     */
    public function insertIntoTable(string $tableName, string $insertStatement, string $insertValues):bool {
        // slozim dotaz
        $q = "INSERT INTO $tableName($insertStatement) VALUES ($insertValues)";
        // provedu ho a vratim uspesnost vlozeni
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);
    }

    /**
     * Jednoducha uprava radku databazove tabulky.
     *
     * @param string $tableName                     Nazev tabulky.
     * @param string $updateStatementWithValues     Cela cast updatu s hodnotami.
     * @param string $whereStatement                Cela cast pro WHERE.
     * @return bool                                 Upraveno v poradku?
     */
    public function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement):bool {
        // slozim dotaz
        $q = "UPDATE $tableName SET $updateStatementWithValues WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);
    }

    /**
     * Dle zadane podminky maze radky v prislusne tabulce.
     *
     * @param string $tableName         Nazev tabulky.
     * @param string $whereStatement    Podminka mazani.
     * @return bool
     */
    public function deleteFromTable(string $tableName, string $whereStatement):bool {
        // slozim dotaz
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        // provedu ho a vratim vysledek
        $obj = $this->executeQuery($q);
        // pokud ($obj == null), tak vratim false
        return ($obj != null);
    }

    ///////////////////  KONEC: Obecne funkce  ////////////////////////////////////////////

    ///////////////////  Konkretni funkce  ////////////////////////////////////////////

    /**
     * Ziskani zaznamu vsech uzivatelu aplikace.
     *
     * @return array    Pole se vsemi uzivateli.
     */
    public function getAllUsers(){
        // ziskam vsechny uzivatele z DB razene dle ID a vratim je
        $users = $this->selectFromTable(TABLE_UZIVATEL, "", "id_uzivatel");
        return $users;
    }

    /**
     * Ziskani zaznamu vsech prav uzivatelu.
     *
     * @return array    Pole se vsemi pravy.
     */
    public function getAllRights(){
        // ziskam vsechna prava z DB razena dle ID a vratim je
        $rights = $this->selectFromTable(TABLE_PRAVO, "", "vaha ASC, nazev ASC");
        return $rights;
    }

    /**
     * Ziskani konkretniho prava uzivatele dle ID prava.
     *
     * @param int $id       ID prava.
     * @return array        Data nalezeneho prava.
     */
    public function getRightById(int $id){
        // ziskam pravo dle ID
        $rights = $this->selectFromTable(TABLE_PRAVO, "id_pravo=$id");
        if(empty($rights)){
            return null;
        } else {
            // vracim prvni nalezene pravo
            return $rights[0];
        }
    }

    /**
     * Vytvoreni noveho uzivatele v databazi.
     *
     * @param string $login     Login.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      Je cizim klicem do tabulky s pravy.
     * @return bool             Vlozen v poradku?
     */
    public function addNewUser(string $login, string $heslo, string $jmeno, string $email, int $idPravo = 4){
        // hlavicka pro vlozeni do tabulky uzivatelu
        $insertStatement = "login, heslo, jmeno, email, id_pravo";
        // hodnoty pro vlozeni do tabulky uzivatelu
        $insertValues = "'$login', '$heslo', '$jmeno', '$email', $idPravo";
        // provedu dotaz a vratim jeho vysledek
        return $this->insertIntoTable(TABLE_UZIVATEL, $insertStatement, $insertValues);
    }

    /**
     * Uprava konkretniho uzivatele v databazi.
     *
     * @param int $idUzivatel   ID upravovaneho uzivatele.
     * @param string $login     Login.
     * @param string $heslo     Heslo.
     * @param string $jmeno     Jmeno.
     * @param string $email     E-mail.
     * @param int $idPravo      ID prava.
     * @return bool             Bylo upraveno?
     */
    public function updateUser(int $idUzivatel, string $login, string $heslo, string $jmeno, string $email, int $idPravo){
        // slozim cast s hodnotami
        $updateStatementWithValues = "login='$login', heslo='$heslo', jmeno='$jmeno', email='$email', id_pravo='$idPravo'";
        // podminka
        $whereStatement = "id_uzivatel=$idUzivatel";
        // provedu update
        return $this->updateInTable(TABLE_UZIVATEL, $updateStatementWithValues, $whereStatement);
    }

    ///////////////////  KONEC: Konkretni funkce  ////////////////////////////////////////////

    ///////////////////  Sprava prihlaseni uzivatele  ////////////////////////////////////////

    /**
     * Overi, zda muse byt uzivatel prihlasen a pripadne ho prihlasi.
     *
     * @param string $login     Login uzivatele.
     * @param string $heslo     Heslo uzivatele.
     * @return bool             Byl prihlasen?
     */
    public function userLogin(string $login, string $heslo):bool {
        // ziskam uzivatele z DB - primo overuju login i heslo
        $where = "login='$login' AND heslo='$heslo'";
        $user = $this->selectFromTable(TABLE_UZIVATEL, $where);
        // ziskal jsem uzivatele
        if(count($user)){
            // ziskal - ulozim ID prvniho nalezeneho uzivatele do session
            $this->mySession->addSession(self::KEY_USER, $user[0]['id_uzivatel']);
            return true;
        }
        // neziskal jsem uzivatele
        return false;
    }

    /**
     * Odhlasi soucasneho uzivatele.
     */
    public function userLogout(){
        $this->mySession->removeSession(self::KEY_USER);
    }

    /**
     * Test, zda je nyni uzivatel prihlasen.
     *
     * @return bool     Je prihlasen?
     */
    public function isUserLogged():bool {
        return $this->mySession->isSessionSet(self::KEY_USER);
    }

    /**
     * Pokud je uzivatel prihlasen, tak vrati jeho data,
     * ale pokud nebyla v session nalezena, tak vypise chybu.
     *
     * @return mixed|null   Data uzivatele nebo null.
     */
    public function getLoggedUserData(){
        if($this->isUserLogged()){
            // ziskam data uzivatele ze session
            $userId = $this->mySession->readSession(self::KEY_USER);
            // pokud nemam data uzivatele, tak vypisu chybu a vynutim odhlaseni uzivatele
            if($userId == null) {
                // nemam data uzivatele ze session - vypisu jen chybu, uzivatele odhlasim a vratim null
                echo "SEVER ERROR: Data přihlášeného uživatele nebyla nalezena, a proto byl uživatel odhlášen.";
                $this->userLogout();
                // vracim null
                return null;
            }
            // nactu data uzivatele z databaze
            $userData = $this->selectFromTable(TABLE_UZIVATEL, "id_uzivatel=$userId");
            // mam data uzivatele?
            if(empty($userData)){
                // nemam - vypisu jen chybu, uzivatele odhlasim a vratim null
                echo "ERROR: Data přihlášeného uživatele se nenachází v databázi (mohl být smazán), a proto byl uživatel odhlášen.";
                $this->userLogout();
                return null;
            }
            // protoze DB vraci pole uzivatelu, tak vyjmu jeho prvni polozku a vratim ziskana data uzivatele
            return $userData[0];
        }
        // uzivatel neni prihlasen - vracim null
        return null;
    }

    ///////////////////  KONEC: Sprava prihlaseni uzivatele  ////////////////////////////////////////

}
?>
