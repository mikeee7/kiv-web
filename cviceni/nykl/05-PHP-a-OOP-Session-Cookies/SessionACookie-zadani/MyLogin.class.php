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
        $data = [
        self::KEY_NAME => $username,
        self::KEY_DATE => new DateTime()
        ];
        $this->ses->removeSession(self::SESSION_KEY);
    }

    public function isUserLogged()
    {
        return $this->ses->isSession(self::SESSION_KEY);
    }

    public function getUserInfo(){
        if ($this->isUserLogged()){

            $data = $this->ses->getSession(self::SESSION_KEY);
            return "Uživatel: ". $data[self::KEY_NAME] ."<br>Datum: ". $data[self::KEY_DATE]->format('d.m.Y G:M:s');
        }
        return "Není přihlášen!";
    }
}