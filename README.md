#Selenium framework for PHP (called Pelenium)

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

* PHPUnit 3.5.15
* Running selenium server (on port 4444)

## Idea behind the framework

The main idea is to create reuseable selenium code by dividing a website into elements, like the login part is called login element etc. So every time you need to login, navigate to the login page
and use the login element you have created. See my examples if you wanna see some code.

## Cool features

* The structure (yes is a feature :), it makes sure that you do it the same way everytime)
* a lot of waitFor functions
* assertPrestate function - this function can help your detect error due to being at the wrong page. See example in LoginElement line 13

