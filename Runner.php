<?php

$args = $_SERVER['argv'];
$conf = $args[1];

$i = 0;
$extra_args = '';
foreach ($argv as $a) {
    if ($i > 1) {
        $pair = split('=', $a);
        $$pair[0] = $pair[1];
        $extra_args .= $pair[0] . "=" . $pair[1] . " ";
    }
    $i++;
}

//print_r($extra_args);

function debug($text, $force = 0) {
    global $debug;
    if ($debug || $force) {
        echo "\n" . date('Y-m-d H:i:s') . ': ' . $text;
    }
}

debug("Started test run", true);
$conffile = 'conf/' . $conf . '.php';
if (!is_file($conffile)) {
    trigger_error("an configuration file has not been set. do it by: php runner.php myconf (should be located in /conf/myconf.php)");
    exit;
}
debug("included $conffile.");
require_once $conffile;

if (!isset($type)) {
    trigger_error("type is not set. should be either 'saucelabs' or 'localhost' set that in conf file");
    exit;
}

if (!in_array($type, array('saucelabs', 'localhost'))) {
    trigger_error("type is not set correct. should be either 'saucelabs' or 'localhost' set that in conf file");
    exit;
}

if (!isset($url)) {
    trigger_error('url is not set in the conf file. do it by: writing: $url="http://someurl.com"');
    exit;
}

if (!isset($testPath)) {
    trigger_error('testPath is not set in the conf-file. do it by: writing: $testPath="tests/" in your conf-file:' . $conffile);
    exit;
}

if (!isset($resultPath)) {
    trigger_error('resultPath is not set in the conf-file. do it by: writing: $resultPath="results/" in your conf-file:' . $conffile);
    exit;
}

if ($type == 'saucelabs') {
    if (!isset($username)) {
        trigger_error('username is not set in the conf-file. do it by: writing: $usename="my_sauce_name" in your conf-file:' . $conffile);
        exit;
    }

    if (!isset($apikey)) {
        trigger_error('apikey is not set in the conf-file. do it by: writing: $apikey="my-secret-and-magic-key-for-sauce-labs" in your conf-file:' . $conffile);
        exit;
    }
}

if (!is_dir($testPath)) {
    trigger_error("the testPath set in conf file is not a directory, Fix that!");
    exit;
}

if (!is_dir($resultPath)) {
    trigger_error("the resultPath set in conf file is not a directory, Fix that!");
    exit;
}

if ($type == 'localhost') {
    if (!isset($selenium_host)) {
        echo 'selenium_host is not set in the conf-file. do it by: writing: $selenium_host="localhost" in your conf-file:' . $conffile . "\n";
        echo 'selenium_host is set to default: localhost' . "\n";
        $selenium_host = 'localhost';
    }

    if (!isset($selenium_port)) {
        echo 'selenium_port is not set in the conf-file. do it by: writing: $selenium_port=4444 in your conf-file:' . $conffile . "\n";
        echo 'selenium_port is set to default: 4444' . "\n";
        $selenium_port = 4444;
    }

    if (!isset($delay)) {
        echo 'delay is not set in the conf-file. do it by: writing: $delay=20 in your conf-file:' . $conffile . "\n";
        echo 'delay is set to default: 0' . "\n";
        $selenium_port = 4444;
    }
}

if ($deleteReportsBeforeTestRun) {
    debug("deleting old testreports");
    $directory = opendir($resultPath);
    while ($item = readdir($directory)) {
        if (($item != ".") && ($item != "..")) {
            debug("deleting:" . $resultPath . $item);
            unlink($resultPath . $item);
        }
    }
}

debug("url: " . $url, true);

foreach (glob($testPath . $testFilter) as $filename) {
    debug("added $filename to queue");
    $tests[] = str_replace($testPath, '', $filename);
}

if (count($tests) == 0) {
    trigger_error("No tests matches the filter, Fix that!");
    exit;
}

// Saucelabs runner
if ($type == 'saucelabs') {
    foreach ($tests as $test) {
        foreach ($combinations as $c) {
            foreach ($c as $os => $browsers) {
                foreach ($browsers as $b => $versions) {
                    foreach ($versions as $version) {
                        $time = microtime(true);
                        $nice_os = str_replace(' ', '-', $os);
                        debug("queued: $test @$os/$b($version)", true);
                        $cmd = "phpunit --log-junit $resultPath" . "testresults-$test-$b-$version-$nice_os-$time.xml " . $testPath . $test . " url=$url browser=$b browser_version=$version os='$os' $extra_args conf=$conffile type=$type> /dev/null & \n";
                        $queue[] = $cmd;
                        exec($cmd); //
                        debug("Running: $cmd");
                    }
                }
            }
        }
    }

// function for getting the number of job on sauce
    function getNumJobsRunning() {
        global $queue, $username, $apikey;
        $jobsRunning = json_decode(file_get_contents("http://$username:$apikey@saucelabs.com/rest/$username/in-progress-jobs"));
        return count($jobsRunning);
    }

    sleep(5); // Wait 5 sec to make sure the test is running on sauce
    print "waiting\n";
    while (getNumJobsRunning() > 0) {
        print ".";
        sleep(5);
    }
    debug("Done running tests");
    
// Running on localhost
} elseif ($type == 'localhost') {
    foreach ($tests as $test) {
        foreach ($combinations as $b) {
            $time = microtime(true);
            debug("started: $test @$b", true);
            $cmd = "phpunit --log-junit $resultPath" . "testresults-$test-$b-$time.xml " . $testPath . $test . " url=$url browser=$b conf=$conffile selenium_host=$selenium_host selenium_port=$selenium_port $extra_args type=$type > /dev/null &";
            exec($cmd); //
            debug("Running: $cmd");
            sleep($delay);
        }
    }
    debug("Done queueing tests\n", true);
}
?>
