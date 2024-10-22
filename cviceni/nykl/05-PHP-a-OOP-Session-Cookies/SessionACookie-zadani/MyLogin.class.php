<?php

class MyLogin {
    private const SESSION_KEY = "usr";
    private const KEY_NAME = "nm";
    private const KEY_DATE = "dt";

    private $ses;

    public function __construct()
    {
        require_once('MySession.class.php');
        $this->ses = MySession::getSingleton();
    }

    public function login(string $username){
        date_default_timezone_set('Europe/Prague');
        $data = [
        self::KEY_NAME => $username,
        self::KEY_DATE => date('d.m.Y, H:i:s')
        ];
        $this->ses->setSession(self::SESSION_KEY, $data);
    }

    public function isUserLogged()
    {
        return $this->ses->isSession(self::SESSION_KEY);
    }

    public function getUserInfo(){
        if ($this->isUserLogged()){

            $data = $this->ses->getSession(self::SESSION_KEY);
            return "Uživatel: ". $data[self::KEY_NAME] ."<br>Datum: ". $data[self::KEY_DATE];
        }
        return "Není přihlášen!";
    }

    public function logout()
    {
        $this->ses->removeSession(self::SESSION_KEY);
    }
}