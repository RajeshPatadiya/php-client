<?php

use SphereEngine\Api\ProblemsClientV3;
use SphereEngine\Api\SphereEngineResponseException;

class ProblemsClientV3ExceptionsOldTest extends PHPUnit_Framework_TestCase
{
	protected static $client;
	
	public static function setUpBeforeClass()
	{
		$access_token = getenv("SE_ACCESS_TOKEN_PROBLEMS");
		$endpoint = getenv("SE_ENDPOINT_PROBLEMS");
		self::$client = new ProblemsClientV3(
				$access_token,
				$endpoint);
	}
	
    public function testAutorizationFail()
    {
        $access_token = "fake access token";
        $endpoint = getenv("SE_ENDPOINT_PROBLEMS");
        $invalidClient = new ProblemsClientV3(
        		$access_token,
        		$endpoint);
        
        try {
        	$invalidClient->test();
        	$this->assertTrue(false);
        } catch (SphereEngineResponseException $e) {
        	$this->assertTrue($e->getCode() == 401);
        }
    }
    
    public function testGetProblemMethodWrongCode()
    {	
    	try {
    		self::$client->getProblem('NON_EXISTING_PROBLEM');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testCreateProblemMethodCodeTaken()
    {
    	try {
    		self::$client->createProblem('TEST', 'Taken problem code');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
    }
    
    public function testCreateProblemMethodCodeEmpty()
    {
    	try {
    		self::$client->createProblem('', 'Empty problem code');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
    }
    
    public function testCreateProblemMethodCodeInvalid()
    {
    	try {
    		self::$client->createProblem('!@#$%^', 'Invalid problem code');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
    }
    
    public function testCreateProblemMethodEmptyName()
    {
    	try {
    		self::$client->createProblem('UNIQUE_CODE', '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}     	
    }
    
    public function testCreateProblemMethodNonexistingMasterjudge()
    {
    	$nonexistingMasterjudgeId = 9999;
    	try {
			self::$client->createProblem(
    			'UNIQUE_CODE',
				'Nonempty name',
				'body',
				'binary',
				0,
				$nonexistingMasterjudgeId);    	
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemMethodNonexistingProblem()
    {
    	try {
    		self::$client->updateProblem('NON_EXISTING_CODE', 'Nonexisting problem code');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemMethodNonexistingMasterjudge()
    {
    	$nonexistingMasterjudgeId = 9999;
    	try {
    		self::$client->updateProblem(
    				'TEST',
    				'Nonempty name',
    				'body',
    				'binary',
    				0,
    				$nonexistingMasterjudgeId);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemMethodEmptyCode()
    {
    	try {
    		self::$client->updateProblem('', 'Nonempty name');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}  	
    }
    
    public function testUpdateProblemMethodEmptyName()
    {
    	try {
    		self::$client->updateProblem('TEST', '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}	
    }
    
    public function testGetProblemTestcasesMethodNonexistingProblem()
    {
    	try {
    		self::$client->getProblemTestcases('NON_EXISTING_CODE');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetProblemTestcaseMethodNonexistingProblem()
    {
    	try {
    		self::$client->getProblemTestcase('NON_EXISTING_CODE', 0);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetProblemTestcaseMethodNonexistingTestcase()
    {
    	try {
    		self::$client->getProblemTestcase('TEST', 1);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testCreateProblemTestcaseMethodNonexistingProblem()
    {
    	try {
    		self::$client->createProblemTestcase("NON_EXISTING_CODE", "in0", "out0", 10, 2, 1);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testCreateProblemTestcaseMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	try {
    		self::$client->createProblemTestcase("TEST", "in0", "out0", 10, $nonexistingJudge, 1);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemTestcaseMethodNonexistingProblem()
    {
    	try {
    		self::$client->updateProblemTestcase("NON_EXISTING_CODE", 0, 'updated input');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemTestcaseMethodNonexistingTestcase()
    {
    	try {
    		self::$client->updateProblemTestcase("TEST", 1, 'updated input');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemTestcaseMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	try {
    		self::$client->updateProblemTestcase("TEST", 0, 'updated input', 'updated output', 1, $nonexistingJudge, 0);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetProblemTestcaseFileMethodNonexistingProblem()
    {
    	try {
    		self::$client->getProblemTestcaseFile("NON_EXISTING_CODE", 0, 'input');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetProblemTestcaseFileMethodNonexistingTestcase()
    {
    	try {
    		self::$client->getProblemTestcaseFile("TEST", 1, 'input');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetProblemTestcaseFileMethodNonexistingFile()
    {
    	try {
    		self::$client->getProblemTestcaseFile("TEST", 0, 'fakefile');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetJudgeMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	try {
    		self::$client->getJudge($nonexistingJudge);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
	
	public function testCreateJudgeMethodEmptySource()
	{
    	try {
    		self::$client->createJudge('', 1, 'testcase', '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
	}
	
	public function testCreateJudgeMethodNonexistingCompiler()
	{
		$nonexistingCompiler = 9999;
    	try {
    		self::$client->createJudge('nonempty source', $nonexistingCompiler, 'testcase', '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testUpdateJudgeMethodEmptySource()
	{
		$response = self::$client->createJudge('source', 1, 'testcase', 'UT judge');
		$judge_id = $response['id'];
    	try {
    		self::$client->updateJudge($judge_id, '', 1, '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
	}
	
	public function testUpdateJudgeMethodNonexistingJudge()
	{
		$nonexistingJudge = 99999999;
    	try {
    		self::$client->updateJudge($nonexistingJudge, 'nonempty source', 1, '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testUpdateJudgeMethodNonexistingCompiler()
	{
		$response = self::$client->createJudge('source', 1, 'testcase', 'UT judge');
		$judge_id = $response['id'];
		$nonexistingCompiler = 9999;
    	try {
    		self::$client->updateJudge($judge_id, 'nonempty source', $nonexistingCompiler, '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testUpdateJudgeMethodForeignJudge()
	{
    	try {
    		self::$client->updateJudge(1, 'nonempty source', 1, '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 403);
    	}
	}
	
	public function testGetSubmissionMethodNonexistingSubmission()
	{
		$nonexistingSubmission = 9999999999;
    	try {
    		self::$client->getSubmission($nonexistingSubmission);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testCreateSubmissionMethodEmptySource()
	{
    	try {
    		self::$client->createSubmission('TEST', '', 1);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
	}
	
	public function testCreateSubmissionMethodNonexistingProblem()
	{
    	try {
    		self::$client->createSubmission('NON_EXISTING_CODE', 'nonempty source', 1);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testCreateSubmissionMethodNonexistingCompiler()
	{
		$nonexistingCompiler = 9999;
    	try {
    		self::$client->createSubmission('TEST', 'nonempty source', $nonexistingCompiler);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testCreateSubmissionMethodNonexistingUser()
	{
		$nonexistingUser = 9999999999;
    	try {
    		self::$client->createSubmission('TEST', 'nonempty source', 1, $nonexistingUser);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
}
