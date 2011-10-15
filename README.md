#Selenium framework for PHP (called Pelenium)

## Current version

See VERSION.txt

## Structure of Pelenium

This is a simple framework for selenium. It contains following:

conf/

    configuration files - you can add your own or use the defaults

core/

    contains the 'core' of the framework
    Pelenium_CommonElementFunctions.php - shared functions for all the elements
    Pelenium_Framework.php - the framework file that contains both Pelenium_Framework and Pelenium_Framework_SauceLabs class. Used for localhost and saucelabs
    Pelenium_Exception.php - Exception used in the framework

elements/

    should contain your elements

results/

    the test results

tests/

    your tests

## Requirements

* PHP
* PHPUnit 3.5.15
* Running selenium server (on port 4444)
* PHP in your exec path (so you can run commandline php)

## Idea behind the framework

The main idea is to create reuseable selenium code by dividing a website into elements, like the login part is called login element etc. So every time you need to login, navigate to the login page
and use the login element you have created. See my examples if you wanna see some code.

## Cool features

* The structure (yes is a feature :), it makes sure that you do it the same way everytime)
* a lot of waitFor functions - Make the code nicer
* assertPrestate function - this function can help your detect error due to being at the wrong page. See example in LoginElement line 13
* run the same tests with different configurations
* switch from running locally to saucelabs with a simple change in each test (yes i know it should one change in all but i'm working on it :) )

## How to get started

* Clone this project to your computer to a folder eg. /pelenium
* Navigate to the main dir of the framework /pelenium
* run "php Runner.php localhost" - this will run pelenium with the configuration file 'localhost'

## Configuration files

The configuration files contains alot of settings to your test run

* combinations to run the test in
* url to run test on
* where to find the tests, relative to runner.php's location
* where to put test results
* delete old testreports when starting a new run
* type of test run (saucelabs or localhost)
* saucelabs username, only needed if running saucelabs mode
* saucelabs apikey, only needed if running saucelabs mode
* debugmode. output to the console what the runner does, can be used for debugging
* delay (in sec) between starting tests (useful when running locally so test don't timeout)
* selenium host
* selenium port
