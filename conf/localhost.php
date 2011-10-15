<?php

$combinations = array(
    '*firefox'
);

// url to run test on
$url = 'http://google.com';

// where to find the tests, relative to runner.php's location
$testPath = 'tests/';

// testcase filter
$testFilter = '*';

// where to find the test results
$resultPath = 'results/';

// delete old testresults before test run
$deleteReportsBeforeTestRun = true;

// type of test run
$type = 'localhost';

//debug mode
$debug = 1;

//selenium host
$selenium_host = 'localhost';

//selenium port
$selenium_port = 4444;

// delay (in sec) between starting tests (useful when running locally so test don't timeout)
$delay = 0;
?>
