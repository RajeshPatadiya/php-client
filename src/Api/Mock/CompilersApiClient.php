<?php
/**
 * ApiClient
 * 
 * PHP version 5
 *
 * @category Class
 * @package  SphereEngine\Api
 * @author   https://github.com/sphere-engine/sphereengine-api-php-client
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/sphere-engine/sphereengine-api-php-client
 */
/**
 *  Copyright 2015 Sphere Research Sp z o.o.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace SphereEngine\Api\Mock;

use SphereEngine\Api\ApiClient;
use SphereEngine\Api\Model\HttpApiResponse;
use SphereEngine\Api\SphereEngineResponseException;
use SphereEngine\Api\SphereEngineConnectionException;

class CompilersApiClient extends ApiClient
{	
	private function isAccessTokenCorrect()
	{
		return $this->accessToken == "correctAccessToken";
	}

	/**
	 * Mock HTTP call
	 *
	 * @param string $resourcePath path to method endpoint
	 * @param string $method       method to call
	 * @param array  $queryParams  parameters to be place in query URL
	 * @param array  $postData     parameters to be placed in POST body
	 * @param array  $headerParams parameters to be place in request header
	 * @param string $responseType expected response type of the endpoint
	 * @return mixed
	 */
	protected function makeHttpCall($resourcePath, $method, $urlParams, $queryParams, $postData, $headerParams, $responseType=null)
	{
		if ( ! $this->isAccessTokenCorrect() ) {
			return new HttpApiResponse(401, '', '', 0, '');
		}

		$queryParams['access_token'] = $this->accessToken;

		if ($resourcePath == '/test') {
			return $this->mockTestMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/compilers') {
			return $this->mockCompilersMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/submissions') {
			return $this->mockSubmissionsMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/submissions/{id}') {
			return $this->mockSubmissionMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/submissions/{id}/{stream}') {
			return $this->mockSubmissionStreamMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

	    throw new \Exception('Resource url beyond mock functionality');
	}

	public function mockTestMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			$response = [
				'moreHelp' => 'sphere-engine.com',
				'pi' => 3.14,
				'answerToLifeAndEverything' => 42,
				'oOok' => true
			];
			return new HttpApiResponse(200, '', json_encode($response), 0, '');
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockCompilersMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			$response = [
				'items' => [
					['name' => 'C++', 'short' => 'cpp', 'geshi' => 'cpp', 'ace' => 'c_cpp', 'ver' => '5.1.1'],
					['name' => 'Python', 'short' => 'py', 'geshi' => 'py', 'ace' => 'py', 'ver' => '2.7'],
					['name' => 'Haskell', 'short' => 'hs', 'geshi' => 'hs', 'ace' => 'hs', 'ver' => '7.10.3'],
				]
			];
			return new HttpApiResponse(200, '', json_encode($response), 0, '');
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockSubmissionsMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			if (isset($queryParams['ids'])) {
				$ids = explode(',', $queryParams['ids']);
			} else {
				throw new \Exception('Lack of ids parameter');
			}
		
			$submissions = [];
			foreach($ids as $id) {
				if ($id > 9000) {
					// we don't add anything'
				} else {
					$submissions[] = [
						'id' => $id
					];
				}
			}
			$response = [
				'items' => $submissions
			];
			return new HttpApiResponse(200, '', json_encode($response), 0, '');
		} else if ($method == 'POST') {
			$sourceCode = (isset($postData['sourceCode'])) ? $postData['sourceCode'] : '';
			$compiler = (isset($postData['language'])) ? intval($postData['language']) : 0;
			$input = (isset($postData['input'])) ? $postData['input'] : '';
			
			if ($compiler < 1 || $compiler > 128) {
				return new HttpApiResponse(404, '', '', 0, '');
				//throw new SphereEngineResponseException("Compiler doesn't exist", 404);
			}

			$response = [
				'id' => 1
			];

			return new HttpApiResponse(201, '', json_encode($response), 0, '');
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockSubmissionMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {

			$submissionId = isset($urlParams['id']) ? $urlParams['id'] : 0;

			if ($submissionId == 2) {
				$response = [
					'source' => 'abc',
					'compiler' => [
						'id' => 11
					]
				];
			} elseif ($submissionId == 1) {
				return new HttpApiResponse(403, '', '', 0, '');
				//throw new SphereEngineResponseException("Access denied", 403);	
			} elseif ($submissionId == 3) {
				return new HttpApiResponse(404, '', '', 0, '');
				//throw new SphereEngineResponseException("Non existing submission", 404);	
			} else {
				throw new \Exception("Parameters beyond mock functionality");
			}

			return new HttpApiResponse(200, '', json_encode($response), 0, '');
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockSubmissionStreamMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {

			$submissionId = isset($urlParams['id']) ? $urlParams['id'] : 0;
			$stream = isset($urlParams['stream']) ? $urlParams['stream'] : 'input';

			if ($submissionId == 2 && $stream == 'source') {
				return new HttpApiResponse(200, '', 'abc', 0, '');
			} elseif ($submissionId == 2 && ($stream == 'input' || $stream == 'stdin')) {
				return new HttpApiResponse(200, '', 'input', 0, '');
			} elseif ($submissionId == 2 && ($stream == 'output' || $stream == 'stdout')) {
				return new HttpApiResponse(200, '', 'output', 0, '');
			} elseif ($submissionId == 2 && ($stream == 'error' || $stream == 'stderr')) {
				return new HttpApiResponse(200, '', 'error', 0, '');
			} elseif ($submissionId == 2 && $stream == 'cmpinfo') {
				return new HttpApiResponse(200, '', 'cmpinfo', 0, '');
			} elseif ($submissionId == 1) {
				return new HttpApiResponse(403, '', '', 0, '');
				//throw new SphereEngineResponseException("Access denied", 403);	
			} elseif ($submissionId == 3) {
				return new HttpApiResponse(404, '', '', 0, '');
				//throw new SphereEngineResponseException("Non existing submission", 404);	
			} else {
				throw new \Exception("Parameters beyond mock functionality");
			}
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}
}
