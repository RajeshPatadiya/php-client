<?php
/**
 * Example presents usage of the successful createJudge() API method
*/

use SphereEngine\Api\ProblemsClientV3;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = getenv("SE_ACCESS_TOKEN_PROBLEMS");
$endpoint = getenv("SE_ENDPOINT_PROBLEMS");

// initialization
$client = new ProblemsClientV3($accessToken, $endpoint);

// API usage
$source = 'int main() { return 0; }';
$compiler = 11; // C language

$response = $client->createJudge($source, $compiler);
// response['id'] stores the ID of the created judge