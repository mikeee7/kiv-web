<?php

const BASE_NAMESPACE_NAME = "kivweb";
const BASE_APP_DIR_NAME = "app";

const EXTENSIONS = ['.class.php', '.interface.php'];

//// automaticka registrace pozadovanych trid
spl_autoload_register(function ($className){

    $fileName = str_replace(BASE_NAMESPACE_NAME, BASE_APP_DIR_NAME, $className);
    $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $fileName);

    $fileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . $fileName;

    foreach (EXTENSIONS as $ext) {
        if (is_file($fileName . $ext)) {
            require_once($fileName . $ext);
            return;
        }
    }


});



?>
