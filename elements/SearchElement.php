<?php

require_once 'core/CPHSE_CommonElementFunctions.php';
require_once 'core/CPHSE_Exception.php';

class SearchElement extends CPHSE_CommonElementFunctions {

    function __construct($selenium, $path = '') {
        parent::constructor($selenium, $path);
    }

    function somefunction($foo, $bar) {
        $this->selenium->type("name=email", $foo);
        $this->selenium->type("name=password", $bar);
        $this->selenium->click("some locator");
        $this->selenium->waitForPageToLoad("30000");
    }


}

?>