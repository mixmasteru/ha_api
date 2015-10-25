<?php
/**
 *
 * @see http://stackoverflow.com/questions/10167775/aggregating-code-coverage-from-several-executions-of-phpunit
 */

// autoloader
require_once './vendor/autoload.php';

//new coverage obj
$ts = microtime(true);
$coverage = new \PHP_CodeCoverage();
$coverage->filter()->addDirectoryToWhitelist('./src/');
$coverage->start("coverage",true);

require_once 'dispatch.php';

$coverage->stop();
file_put_contents("coverage".strval($ts).".obj",serialize($coverage));