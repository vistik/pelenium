<?php

// combinations to run tests in
$combinations = array(
    array(
        'Windows 2003' =>
        array(
            'iexplore' => array('8.', '7.', '6.'),
            'firefox' => array('2.0.', '3.0.', '3.5.', '3.6.', '4.0.', '5.0.'),
            'googlechrome' => array(''), // does not need a version number, but must be blank
            'opera' => array('9.', '10.', '11.'),
            'safari' => array('3.', '4.')
        ),
        'Windows 2008' =>
        array(
            'firefox' => array('4.0.'),
            'iexplore' => array('9.')
        )
    ),
    array(
        'Linux' =>
        array(
            'firefox' => array('3.0.', '3.6.')
        )
    )
);

// url to run test on
$url = 'http://google.com';

// where to find the tests, relative to runner.php's location
$testPath = 'tests/';

// where to put test results
$resultPath = 'results/';

// delete old testreports when starting a new run
$deleteReportsBeforeTestRun = true;

// type of test run
$type = 'saucelabs';

// saucelabs username
$username = '<saucelabs_username>';

// saucelabs apikey
$apikey = '<saucelabs_key>';

//debugmode
$debugmode = 1;


?>
