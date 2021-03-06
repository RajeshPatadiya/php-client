<?php
/**
 * Example presents usage of the successful getSubmissions() API method
*/

use SphereEngine\Api\CompilersClientV3;

// require library
require_once('../../../../autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = '<endpoint>';

// initialization
$client = new CompilersClientV3($accessToken, $endpoint);

// API usage
$response = $client->getSubmissions(array(2017, 2018));
