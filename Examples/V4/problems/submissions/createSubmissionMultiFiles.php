<?php
/**
 * Example presents usage of the successful createSubmissionMultiFiles() API method
*/

use SphereEngine\Api\ProblemsClientV4;

// require library
require_once('../../../../autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = '<endpoint>';

// initialization
$client = new ProblemsClientV4($accessToken, $endpoint);

// API usage
$problemCode = 'TEST';
$files = array(
    'prog.c' => '<source_code>',
    'prog.h' => '<source_code>'
);
$compiler = 11; // C language

$response = $client->createSubmissionMultiFiles($problemCode, $files, $compiler);
// response['id'] stores the ID of the created submission