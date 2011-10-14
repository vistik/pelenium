<?php

$combinations = array(
    '*firefox'
);

// url to run test on
$url = 'http://google.com';

// where to find the tests, relative to runner.php's location
$testPath = 'tests/';

// testcase filter
$testFilter = 'localhost*';

// where to find the test results
$resultPath = 'results/';

// delete old testresults before test run
$deleteReportsBeforeTestRun = true;

// type of test run
$type = 'localhost';

//debug mode
$debug = 0;

//selenium host
$selenium_host = 'localhost';

//selenium host
$selenium_port = 4444;
?>
