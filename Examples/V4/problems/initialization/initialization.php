<?php
/**
 * Example presents usage of the successful initialization of 
 * Sphere Engine Problems API client
*/

use SphereEngine\Api\ProblemsClientV4;

// require library
require_once('../../../../autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = '<endpoint>';

// initialization
$client = new ProblemsClientV4($accessToken, $endpoint);