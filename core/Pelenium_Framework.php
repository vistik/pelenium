<?php

set_include_path('/usr/local/PEAR' . PATH_SEPARATOR . get_include_path());

include_once 'PHPUnit/Extensions/SeleniumTestCase.php';
include_once 'PHPUnit/Extensions/SeleniumTestCase/SauceOnDemandTestCase.php';

class Pelenium_Framework_SauceLabs extends PHPUnit_Extensions_SeleniumTestCase_SauceOnDemandTestCase {

    var $timeout = 30000;
    var $os = null;
    var $browser = null;
    var $browser_version = null;
    var $url = null;
    var $sleep = 2;
    var $build = 0;
    var $tags = 'notag';

    /**
     * Function to setup
     */
    function setUp() {
        $argv = $_SERVER['argv'];
        $i = 0;
        foreach ($argv as $a) {
            $i++;
            // avoid the first 4 arguments Array ([0] => /usr/local/bin/phpunit, [1] => --log-junit, [2] => results/testresults.xml, [3] => tests/GoogleTest_1.php
            if ($i > 4) {
                $pair = split('=', $a);
                $this->$pair[0] = $pair[1];
            }
        }

        $this->type != 'saucelabs' ? trigger_error('in conf file type is set to "'.$this->type.'" but this test requires it to be "saucelabs"') : '';
        
        $this->browser == '' ? trigger_error('$this->browser is not set! use: browser=<your browser>') : '';
        $this->url == '' ? trigger_error('$this->url is not set! use: url=http://www.my-application.com') : '';
        $this->browser_version == '' ? trigger_error('$this->browser_version is not set! use: browser_version=3.0.') : '';
        $this->os == '' ? trigger_error('$this->os is not set! use: os="Linux"') : '';

        // Basic stuff
        $this->setBrowser($this->browser);
        $this->setBrowserUrl($this->url);

        // Sauce stuff
        $this->setBrowserVersion($this->browser_version);
        $this->setOs($this->os);

        // Extra stuff
        $this->setTimeout(30000);
        $this->setSleep($this->sleep);
    }

    function getDebugInfo() {

        $message = '';
        $message .= "************\n";
        $message .= "Url:" . $this->getLocation() . "\n";
        $message .= "OS:" . $this->os . "\n";
        $message .= "Browser:" . $this->browser . "\n";
        $message .= "Browser version:" . $this->browser_version . "\n";
        $message .= "Sleep:" . $this->sleep . "\n";
        $message .= "Debug:" . print_r($this->drivers[0], true) . "\n";
        $message .= "************\n";
        return $message;
    }

    public function tearDown() {
        //echo $this->getDebugInfo();
        //$this->setContext("sauce:job-tags=" . $this->browser . "," . $this->buildid);
        $this->setContext("sauce:job-tags=" . $this->tags);
        $this->setContext("sauce:job-build=" . $this->build);
    }

}

class Pelenium_Framework extends PHPUnit_Extensions_SeleniumTestCase {

    var $selenium_host = 'localhost';
    var $selenium_port = 4444;
    var $timeout = 30000;
    var $browser = null;
    var $url = null;
    var $sleep = 0;

    /**
     * Function to setup
     */
    function setUp() {
        $argv = $_SERVER['argv'];
        $i = 0;
        foreach ($argv as $a) {
            $i++;
            // avoid the first 4 arguments Array ([0] => /usr/local/bin/phpunit, [1] => --log-junit, [2] => results/testresults.xml, [3] => tests/GoogleTest_1.php
            if ($i > 4) {
                $pair = split('=', $a);
                $this->$pair[0] = $pair[1];
            }
        }

        $this->type != 'localhost' ? trigger_error('in conf file type is set to "'.$this->type.'" but this test requires it to be "localhost"') : '';

        $this->browser == '' ? trigger_error('$this->browser is not set! use: browser=<your browser>') : '';
        $this->url == '' ? trigger_error('$this->url is not set! use: url=http://www.my-application.com') : '';

        // Basic stuff
        $this->setBrowser($this->browser);
        $this->setBrowserUrl($this->url);

        // selenium location
        $this->setHost($this->selenium_host);
        $this->setPort((int) $this->selenium_port);

        // Extra stuff
        $this->setTimeout(30000);
        $this->setSleep($this->sleep);
    }

    function getDebugInfo() {

        $message = '';
        $message .= "************\n";
        $message .= "Url:" . $this->getLocation() . "\n";
        $message .= "Browser:" . $this->browser . "\n";
        $message .= "Sleep:" . $this->sleep . "\n";
        $message .= "Debug:" . print_r($this->drivers[0], true) . "\n";
        $message .= "************\n";
        return $message;
    }

}

foreach (glob("elements/*Element.php") as $filename) {
    require_once $filename;
}
?>
