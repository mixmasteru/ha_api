<?php
/**
 *
 * @see http://stackoverflow.com/questions/19821082/collate-several-xdebug-coverage-results-into-one-report
 */

// autoloader
require_once './vendor/autoload.php';

//get stored coverage obj
$coverage = new PHP_CodeCoverage();
$coverage->filter()->addDirectoryToWhitelist('./src/');
$coverage->start("coverage");

require_once 'dispatch.php';

$coverage->stop();
$writer = new PHP_CodeCoverage_Report_HTML(35, 70);
$writer->process($coverage, 'coverage');
$writer = new PHP_CodeCoverage_Report_Clover();
$writer->process($coverage, 'coverage.xml');