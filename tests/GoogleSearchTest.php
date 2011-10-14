<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'core/Framework.php';

class GoogleSearchTest extends Framework{

    public function setUp() {
        parent::setup();
    }

    public function testSearchForHelloWorld() {
        $se = new SearchElement($this, '/');
        $se->search('hello world');
        $se->verifyResult(1, 'Hello world program - Wikipedia, the free encyclopedia');
    }

    public function testSearchForSauceLabs() {
        $se = new SearchElement($this, '/');
        $se->search('SauceLabs');
        $se->verifyResult(1, 'Cross browser testing with Selenium - Sauce Labs');
        $se->verifyResult(2, 'Sauce Labs (saucelabs) on Twitter');
    }
}

?>

