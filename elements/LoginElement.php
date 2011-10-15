<?php

require_once 'core/Pelenium_CommonElementFunctions.php';
require_once 'core/Pelenium_Exception.php';

class LoginElement extends Pelenium_CommonElementFunctions {

    function __construct($selenium, $path = '') {
        parent::constructor($selenium, $path);
    }

    function login($email, $password) {
        $this->assertPrestate('/^(\/ServiceLogin\?)/', true);
        $this->selenium->type("id=Email", $email);
        $this->selenium->type("id=Passwd", $password);
        $this->selenium->click("id=PersistentCookie");
        $this->selenium->click("id=signIn");
        $this->selenium->waitForPageToLoad("30000");
        
    }

}

?>