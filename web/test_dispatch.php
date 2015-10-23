<?php
/**
 *
 * @see http://stackoverflow.com/questions/19821082/collate-several-xdebug-coverage-results-into-one-report
 */

// autoloader
require_once './vendor/autoload.php';

//only works for one call
$coverage = new PHP_CodeCoverage();
$coverage->filter()->addDirectoryToWhitelist('./src/');
$coverage->start('Site coverage');

require_once 'dispatch.php';

$coverage->stop();
$writer = new PHP_CodeCoverage_Report_HTML(35, 70);
$writer->process($coverage, 'coverage');
$writer = new PHP_CodeCoverage_Report_Clover();
$writer->process($coverage, 'coverage.xml');