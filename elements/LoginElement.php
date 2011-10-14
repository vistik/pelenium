<?php

require_once 'core/CommonElementFunctions.php';
require_once 'core/Exception.php';

class LoginElement extends CommonElementFunctions {

    function __construct($selenium, $path = '') {
        parent::constructor($selenium, $path);
    }

    function login($email, $password) {
        $this->selenium->type("id=Email", $email);
        $this->selenium->type("id=Passwd", $password);
        $this->selenium->click("id=PersistentCookie");
        $this->selenium->click("id=signIn");
        $this->selenium->waitForPageToLoad("30000");
        
    }

}

?>