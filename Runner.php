<?php

$args = $_SERVER['argv'];
if (!isset($args[1])) {
    $confs = '';
    foreach (glob('conf/*') as $key => $filename) {
        $filename = str_replace('.php', '', $filename);
        $filename = str_replace('conf/', '', $filename);
        $confs .= "$filename";
        $confs .= "\n";
    }
    error("an configuration file has not been set. do it by: php runner.php myconf (should be located in /conf/myconf.php) \nPossible conf files: \n$confs");
    exit;
} else {
    $conf = $args[1];
    $conffile = 'conf/' . $conf . '.php';
    debug("included $conffile.");
    require_once $conffile;
}


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

function debug($text, $force = false, $error = false) {
    global $debug;
    if (($debug || $force) && $error) {
        echo "" . date('Y-m-d H:i:s') . ': ' . $text . "\n";
    } elseif ($error) {
        echo date('Y-m-d H:i:s') . ' ERROR: ' . $text . "\n";
        exit;
    }
}

function error($text) {
    debug($text, false, true);
}

debug("Started test run", true);

if (!isset($type)) {
    error("type is not set. should be either 'saucelabs' or 'localhost' set that in conf file");
    exit;
}

if (!in_array($type, array('saucelabs', 'localhost'))) {
    error("type is not set correct. should be either 'saucelabs' or 'localhost' set that in conf file");
    exit;
}

if (!isset($url)) {
    error('url is not set in the conf file. do it by: writing: $url="http://someurl.com"');
    exit;
}

if (!isset($testPath)) {
    error('testPath is not set in the conf-file. do it by: writing: $testPath="tests/" in your conf-file:' . $conffile);
    exit;
}

if (!isset($resultPath)) {
    error('resultPath is not set in the conf-file. do it by: writing: $resultPath="results/" in your conf-file:' . $conffile);
    exit;
}

if ($type == 'saucelabs') {
    if (!isset($username)) {
        error('username is not set in the conf-file. do it by: writing: $usename="my_sauce_name" in your conf-file:' . $conffile);
        exit;
    }

    if (!isset($apikey)) {
        error('apikey is not set in the conf-file. do it by: writing: $apikey="my-secret-and-magic-key-for-sauce-labs" in your conf-file:' . $conffile);
        exit;
    }
}

if (!is_dir($testPath)) {
    error("the testPath set in conf file is not a directory, Fix that!");
    exit;
}

if (!is_dir($resultPath)) {
    error("the resultPath set in conf file is not a directory, Fix that!");
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

$tests = array();

foreach (glob($testPath . $testFilter) as $filename) {
    debug("added $filename to queue");
    $tests[] = str_replace($testPath, '', $filename);
}

if (count($tests) == 0) {
    error("\ntest path and test filter does not match any tests");
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
            $cmd = "phpunit --log-junit $resultPath" . "testresults-$test-$b-$time.xml " . $testPath . $test . " url=$url browser=$b conf=$conffile selenium_host=$selenium_host selenium_port=$selenium_port > /dev/null &";
            exec($cmd); //
            debug("Running: $cmd");
            sleep(20);
        }
    }
    debug("Done queueing tests", true);
}
?>
