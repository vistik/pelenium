<?php

require_once 'core/CommonElementFunctions.php';
require_once 'core/Exception.php';

class TopbarElement extends CommonElementFunctions {

    function __construct($selenium, $path = '') {
        parent::constructor($selenium, $path);
    }

    function gotoInternetSearch() {
        $this->selenium->click("css=#gb_1 > span.gbts");
        $this->selenium->waitForPageToLoad("30000");
    }

    function gotoImageSearch() {
        $this->selenium->click("css=#gb_2 > span.gbts");
        $this->selenium->waitForPageToLoad("30000");
    }

    function gotoMapSearch() {
        $this->selenium->click("css=#gb_8 > span.gbts");
        $this->selenium->waitForPageToLoad("30000");
    }

    function gotoGmail() {
        $this->selenium->click("css=#gb_23 > span.gbts");
        $this->selenium->waitForPageToLoad("30000");
    }

    function gotoLogin() {
        $this->selenium->click("id=gbi4s1");
        $this->selenium->waitForPageToLoad("30000");
    }

    function verifyUserLoggedIn($email) {
        $this->selenium->click("id=gbi4m1");
        $this->waitForTextPresent($email);
    }

    function verifyFailedLogin(){
        $this->selenium->assertTrue($this->selenium->isElementPresent("id=errormsg_0_Passwd"));
    }

}

?>