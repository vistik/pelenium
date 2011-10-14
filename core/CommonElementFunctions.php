<?php

/**
 * Common class for shared functions
 * @author Visti Kløft
 */
class CommonElementFunctions {

    /**
     * @author Visti Kløft
     * Simple constructor to be shared among all elements
     */
    function constructor($selenium, $path = '') {
        $this->selenium = $selenium;
        if ($path != '') {
            $this->selenium->open($path);
            $this->selenium->waitForPageToLoad("30000");
        }
//        $this->selenium->windowMaximize();
    }

    /**
     * @author Visti Kløft
     * @param function $function The expression to wait for. eg. "$this->selenium->getTitle() = 'CPHSE: Skybox'"
     * @param int $timeout seconds to wait for the expression to be true (otherwise will return false)
     * @return boolean
     */
    function waitFor($function, $timeout = 30) {
        for ($second = 0;; $second++) {
            if ($second >= $timeout) {
                return false;
            }
            try {
                $eval = eval('return ' . $function . ';');
                if ($eval) {
                    return true;
                }
            } catch (Exception $e) {

            }
            sleep(1);
        }
    }

    function waitForElementPresent($element) {
        return $this->waitFor('$this->selenium->isElementPresent("' . $element . '") == true');
    }

    function waitForTitle($title){
        $title = strtolower($title);
        return $this->waitFor('strtolower($this->selenium->getTitle()) == "'.$title.'"');
    }

    function waitForTitleContains($title){
        $title = strtolower($title);
        return $this->waitFor('strpos(strtolower($this->selenium->getTitle()),"'.$title.'") !== false');
    }

    function waitForTextPresent($text){
        return $this->waitFor('$this->selenium->isTextPresent("' . $text .'")');
    }

    /**
     * Assert the state. Should be called in the beginning of each function to assert
     * @param string $expectedstate the expected state to assert (Normally the url, like: '/admin/', '/users/login/' etc)
     * @param boolean $regex (Optionally, you can use regex in the expected state, eg. if the url contains variables, like '/products/view/123/'. Check http://rubular.com/ for regex)
     */
    function assertPrestate($expectedstate, $regex = false) {
        //TODO: Make it take an array as param for multiple allowedstates
        //$this->assertServerErrors();
        $trace = debug_backtrace(false);
        $expectedstate = strtolower($expectedstate);
        $this->addToTrace("Pre :" . $trace[1]['class'] . "->" . $trace[1]['function'] . " asserted as '" . $expectedstate . "'");
        //file_put_contents('out.txt', print_r(debug_backtrace(false),true));

//        $errmsg1 = $this->generateErrorMessage("expectedstate != GLOBAL['state']", "Expected prestate was '$expectedstate', but actual state was '" . $GLOBALS['state'] . "'");
        $errmsg2 = $this->generateErrorMessage("expectedstate != this->getCurrentPage()", "Expected prestate was '$expectedstate', but actual state was '" . $this->getCurrentPage() . "'");

//        $this->selenium->assertEquals(strtolower($GLOBALS['state']), $expectedstate, $errmsg1);
        if ($regex) {

            $this->selenium->assertTrue(preg_match($expectedstate, strtolower($this->getCurrentPage())) == true, $errmsg2);
        } else {
            $this->selenium->assertEquals($expectedstate, strtolower($this->getCurrentPage()), $errmsg2);
        }
    }

    /**
     * Set the post state after a function
     * @param string $state
     */
    function setPoststate($state) {
        $state = strtolower($state);
        $GLOBALS['state'] = $state;
        $trace = debug_backtrace(false);
        $GLOBALS['setter'] = $trace[1]['class'] . "->" . $trace[1]['function'];
        $this->addToTrace("Post:" . $trace[1]['class'] . "->" . $trace[1]['function'] . " to '" . $state . "'");
    }

    /**
     * Add text to the stacktrace of pre/post states
     * @param string $text
     */
    private function addToTrace($text) {
        $GLOBALS['trace'][] = $text;
    }

    /**
     *
     * @return string the url without the domain, eg. http://google.com/users/login/ will return '/users/login/'. Will only return the last / if the url contains it<br>
     * eg. http://google.com/users/login will return '/users/login'. NB no / in the end
     */
    function getCurrentPage() {
        $s = explode('/', $this->selenium->getLocation());
        $o = '';
        for ($index = 3; $index < count($s); $index++) {
            $o .= "/" . $s[$index];
        }
        return $o;
    }

    /**
     * Used for asserting different server error. We should keep adding new check to this function
     */
    function assertServerErrors() {
        // TODO: Add more server checks!
        $source = strtolower($this->selenium->getHtmlSource());
        $err = strpos($source, 'page not found');
        $this->selenium->assertTrue($err === false, 'There was an 404 here:' . $this->selenium->getLocation());
    }

    /**
     * Function used by assertPrestate to create pretty error messages
     * @param string $title Title of the error
     * @param string $text body of the error
     * @return string The hole error message incl. extra info
     *         State set by: " . $GLOBALS['setter'] . "\n
     */
    function generateErrorMessage($title, $text) {
        $errmsg = "ERROR: $title:\n
        INFO:\n
        Location: " . $this->selenium->getLocation() . "\n
        Current page: " . $this->getCurrentPage() . "\n

        Trace: " . print_r($GLOBALS['trace'], true) . "\n
        ERRORTXT:\n
        $text\n
END OF ERROR\n";
        return $errmsg;
    }

}

?>
