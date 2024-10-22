<?php

/**
 *  Trida pro praci s vyberem automobilu.
 *  @author Michal Nykl
 */
class MyNakupAuta {

    /** @var MyCookies $coo  Objekt pro praci s cookie. */
    private $coo;

    /** @var string COOKIE_KEY  Klic pro ulozeni dat auta do session */
    private const COOKIE_KEY = "auto";

    /** @var string KEY_WHEELS  Klic pro ulozeni poctu kol do cookie. */
    private const KEY_WHEELS = "kola";
    /** @var string KEY_COLOR  Klic pro ulozeni barvy do cookie. */
    private const KEY_COLOR = "barva";

    /**
     *  Pri vytvoreni objektu zahaji session.
     */
    public function __construct(){
        require_once("MyCookies.class.php");
        // vytvorim instanci tridy pro praci s cookie (objekt)
        $this->coo = new MyCookies;
    }

    /**
     *  Otestuje, zda už existují informace o vybraném automobilu.
     *  @return bool
     */
    public function isCarSaved():bool {
        return $this->coo->isCookieSet(self::COOKIE_KEY);
    }

    /**
     *  Nastavi do cookies pocet kol a barvu automobilu v JSON formatu.
     *  @param int $wheels Pocet kol.
     *  @param string $color Barva.
     */
    public function saveCarData(int $wheels, string $color){
        $data = [ self::KEY_WHEELS => $wheels,
                self::KEY_COLOR => $color];
        $this->coo->setCookie(self::COOKIE_KEY, json_encode($data));
    }

    /**
     * Nacte data automobilu z cookie.
     * @return array|null
     */
    public function loadCarData(){
        if($this->isCarSaved()){
            return json_decode($this->coo->readCookie(self::COOKIE_KEY), true);
        }
        return null;
    }

    /**
     *  Smaze informace o automobilu.
     */
    public function deleteCar(){
        $this->coo->removeCookie(self::COOKIE_KEY);
    }

    /**
     *  Vrati informace o poctu kol.
     *  @return int|null
     */
    public function getWheels(){
        $data = $this->loadCarData();
        return (isset($data[self::KEY_WHEELS]))
            ? intval($data[self::KEY_WHEELS])
            : null;
    }

    /**
     *  Vrati informace o barve.
     *  @return string|null
     */
    public function getColor(){
        $data = $this->loadCarData();
        return ($data != null && array_key_exists(self::KEY_COLOR, $data))
            ? $data[self::KEY_COLOR]
            : null;
    }

    /**
     *  Posklada informace o danem automobilu.
     *  @return string Informace.
     */
    public function getInfo():string {
        $wheels = $this->getWheels();
        $color = $this->getColor();
        if(empty($wheels)){
            return "";
        }
        $str = "<i>Kola: $wheels, barva: $color.</i><br>";
        for ($i = 0; $i < $wheels; $i++) {
            $str .= "<div style='background-color: $color ;width:50px;height:50px;margin:5px;display:inline-block;border-radius:50%;'></div>";
        }
        return $str;
    }

}

?>
