<?php
/**
 * Example presents usage of the successful deleteProblemTestcase() API method  
 */

use SphereEngine\Api\ProblemsClientV3;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = '<endpoint>';

// initialization
$client = new ProblemsClientV3($accessToken, $endpoint);

// API usage
$problemCode = 'EXAMPLE';
$testcaseNumber = 0;

$response = $client->deleteProblemTestcase($problemCode, $testcaseNumber);
