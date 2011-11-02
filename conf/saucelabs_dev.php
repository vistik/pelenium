<?php
// The saucelab combinations to run in (see: https://saucelabs.com/docs/sauce-ondemand/browsers)
$combinations = array(
    array(
        'Windows 2003' =>
        array(
//            'iexplore' => array('8.'),
            'firefox' => array('3.6.')
        )
    )
);
// url to run test on
$url = 'http://google.com';

// where to find the tests, relative to runner.php's location
$testPath = 'tests/';

// testcase filter
$testFilter = 'SauceLab*';

// where to find the test results
$resultPath = 'results/';

// delete old testresults before test run
$deleteReportsBeforeTestRun = true;

// type of test run
$type = 'saucelabs';

// saucelabs username
$username = '<saucelabs_username>';

// saucelabs apikey
$apikey = '<saucelabs_key>';

// debugmode
$debug = 1;
?>
