<?php

require_once 'core/CommonElementFunctions.php';
require_once 'core/Exception.php';

class SearchElement extends CommonElementFunctions {

    function __construct($selenium, $path = '') {
        parent::constructor($selenium, $path);
    }

    function search($string) {
        $this->selenium->type("id=lst-ib", $string);
        $this->selenium->click("name=btnG");
        if(!$this->waitForTitleContains($string)){
            throw new PeleniumException("Timeout waiting for title to contain $string");
        }
    }

    function verifyResult($index, $expected){
        $result = $this->selenium->getText("//ol[@id='rso']/li[$index]/div/h3/a");
        $this->selenium->assertEquals($expected, $result);
    }


}

?>