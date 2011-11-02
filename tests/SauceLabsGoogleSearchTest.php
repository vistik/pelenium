<?php

require_once 'core/Pelenium_Framework.php';

class GoogleSearchTest extends Pelenium_Framework_SauceLabs{

    function searchData() {
        return array(
            array('hello world','Hello world program - Wikipedia, the free encyclopedia'),
            array('SauceLabs','Cross browser testing with Selenium - Sauce Labs')
        );
    }

    /**
     *
     * @param <type> $searchfor
     * @param <type> $expectedResult
     * @dataProvider searchData
     */
    public function testSearchFor($searchfor, $expectedResult) {
        $se = new SearchElement($this, '/');
        $se->search($searchfor);
        $se->verifyResult(1, $expectedResult);
//        $this->tagTest();
    }
}

?>

