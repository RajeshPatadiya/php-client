<?php
/**
 * Example presents usage of the successful createSubmission() API method
*/

use SphereEngine\Api\CompilersClientV4;

// require library
require_once('../../../../autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = '<endpoint>';

// initialization
$client = new CompilersClientV4($accessToken, $endpoint);

// API usage
$source = '<source code>';
$compiler = 11; // C language
$input = '2017';

$response = $client->createSubmission($source, $compiler, $input);
// response['id'] stores the ID of the created submission
