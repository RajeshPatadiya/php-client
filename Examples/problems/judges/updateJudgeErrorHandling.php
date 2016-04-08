<?php
/**
 * Example presents error handling for updateJudge() API method  
*/

use SphereEngine\Api\ProblemsClientV3;
use SphereEngine\Api\SphereEngineResponseException;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = getenv("SE_ACCESS_TOKEN_PROBLEMS");
$endpoint = getenv("SE_ENDPOINT_PROBLEMS");

// initialization
$client = new ProblemsClientV3($accessToken, $endpoint);

// API usage
$source = 'int main() { return 0; }';
$nonexisting_compiler = 9999;

try {
	$response = $client->updateJudge(1, $source, $nonexisting_compiler);
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 400) {
		echo 'Empty source';
	} elseif ($e->getCode() == 403) {
		echo 'Access to the judge is forbidden';
	} elseif ($e->getCode() == 404) {
		// agregates two possible reasons of 404 error
		// non existing judge or compiler
		echo 'Non existing resource (judge, compiler), details available in the message: ' . $e->getMessage();
	}
}