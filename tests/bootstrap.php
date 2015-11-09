<?php
/**
 * phpunit bootstrap for ci
 * starts php webserver for testing rest endpoints
 *
 * @see http://tech.vg.no/2013/07/19/using-phps-built-in-web-server-in-your-test-suites/
 */

include_once('./vendor/autoload.php');

// Command that starts the built-in web server
$command = sprintf(
    'php -S %s:%d -t %s %s > /dev/null 2>&1 & echo $!',
    WEB_SERVER_HOST,
    WEB_SERVER_PORT,
    WEB_SERVER_DOCROOT,
    WEB_SERVER_INDEXFILE
);

// Execute the command and store the process ID
$output = array();
exec($command, $output);
$pid = (int) $output[0];
//time for start server
sleep(1);

echo sprintf(
        '%s - Web server started on %s:%d with PID %d',
        date('r'),
        WEB_SERVER_HOST,
        WEB_SERVER_PORT,
        $pid
    ) . PHP_EOL;

// Kill the web server when the process ends
register_shutdown_function(function() use ($pid) {

    echo sprintf('%s - Killing process with ID %d', date('r'), $pid) . PHP_EOL;
    exec('kill ' . $pid);

    /**
     * @see http://stackoverflow.com/questions/10167775/aggregating-code-coverage-from-several-executions-of-phpunit
     */
    $arr_ser = glob("coverage*.obj");
    foreach ($arr_ser as $filename) {
        // @var PHP_CodeCoverage
        $cov = unserialize(file_get_contents($filename));
        if (isset($codeCoverage)) {
            $codeCoverage->filter()->addFilesToWhitelist($cov->filter()->getWhitelist());
            $codeCoverage->merge($cov);
        } else {
            $codeCoverage = $cov;
        }
        unlink($filename);
    }

    $writer = new PHP_CodeCoverage_Report_HTML(35, 70);
    $writer->process($codeCoverage, 'build/coverage');
    $writer = new PHP_CodeCoverage_Report_Clover();
    $writer->process($codeCoverage, 'build/logs/clover.xml');

});
