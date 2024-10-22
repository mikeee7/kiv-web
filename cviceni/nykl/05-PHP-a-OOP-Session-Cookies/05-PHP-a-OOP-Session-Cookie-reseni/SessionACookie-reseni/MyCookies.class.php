<?php

/**
 *  Trida pro praci s Cookies.
 *  @author Michal Nykl
 */
class MyCookies {

    /** @var int $defExpire  Defaultni doba expirace cookie [s]  */
    private $defExpire;

    /**
     * MyCookies constructor.
     * Pri vytvoreni objektu nastavim defaultni cas expirace cookies.
     * @param int $defaultExpirationSec  Defaultne 10 dni.
     */
    public function __construct(int $defaultExpirationSec = (10 * 60*60*24)){
        $this->defExpire = $defaultExpirationSec;
    }

    /**
     *  Funkce pro ulozeni hodnoty do cookies.
     *  @param string $key      Klic do pole cookeis (jmeno pro cookei).
     *  @param mixed $value     Hodnota.
     *  @param int $expire      Doba platnosti. Muze byt NULL.
     */
    public function setCookie(string $key, $value, $expire=null){
        // pokud neni uvedena doba platnosti, tak se pouzije defaultni
        if(!isset($expire)){
            $expire = $this->defExpire;
        }
        // nastavim cookie
        setcookie($key, $value, time()+$expire);
    }

    /**
     *  Je cookie nastavena?
     *  @param string $key  Klic do pole cookeis.
     *  @return bool
     */
    public function isCookieSet(string $key):bool{
        return isset($_COOKIE[$key]);
    }

    /**
     *  Vrati hodnotu daneho cookies nebo null, pokud cookies neni nastavena.
     *  @param string $key  Klic do pole cookeis.
     *  @return mixed       Hodnota cookie nebo null, pokud neni nastavena.
     */
    public function readCookie(string $key){
        // existuje dany atribut v cookie
        if($this->isCookieSet($key)){
            return $_COOKIE[$key];
        } else {
            return null;
        }
    }

    /**
     *  Odstrani danou cookie.
     *  @param string $key  Klic do pole cookeis.
     */
    public function removeCookie(string $key){
        // nastavi expiraci na 0, cimz cookie okamzite expiruje
        $this->setCookie($key, null, 0);
    }

}
?>
