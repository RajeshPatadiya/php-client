<?php

use SphereEngine\Api\Mock\ProblemsClientV3;
use SphereEngine\Api\SphereEngineResponseException;

class ProblemsClientV3Test extends PHPUnit_Framework_TestCase
{
	protected static $client;
	
	public static function setUpBeforeClass()
	{
		$access_token = 'correctAccessToken';
		$endpoint = 'unittest';
		self::$client = new ProblemsClientV3(
				$access_token,
				$endpoint);
	}

    public function testAutorizationSuccess()
    {
    	self::$client->test();
    	$this->assertTrue(true);
    }

    public function testTestMethodSuccess()
    {
        $this->assertTrue(count(self::$client->test()) > 0);
    }
    
    public function testCompilersMethodSuccess()
    {
    	$this->assertEquals("C++", self::$client->getCompilers()['items'][0]['name']);
    }
    
    public function testGetProblemsMethodSuccess()
    {
		$problems = self::$client->getProblems();
    	$this->assertEquals(10, $problems['paging']['limit']);
		$this->assertEquals(0, $problems['paging']['offset']);
		$this->assertEquals(false, isset($problems['items'][0]['shortBody']));
		$this->assertEquals(true, isset($problems['items'][0]['lastModifiedBody']));
		$this->assertEquals(true, isset($problems['items'][0]['lastModifiedSettings']));
    	$this->assertEquals(11, self::$client->getProblems(11)['paging']['limit']);
		$this->assertEquals(false, isset(self::$client->getProblems(10, 0, false)['items'][0]['shortBody']));
		$this->assertEquals(true, isset(self::$client->getProblems(10, 0, true)['items'][0]['shortBody']));
    }
    
    public function testGetProblemMethodSuccess()
    {
		$problem = self::$client->getProblem('TEST');
    	$this->assertEquals('TEST', $problem['code']);
		$this->assertEquals(false, isset($problem['shortBody']));
		$this->assertEquals(true, isset($problem['lastModifiedBody']));
		$this->assertEquals(true, isset($problem['lastModifiedSettings']));
		$this->assertEquals(false, isset(self::$client->getProblem('TEST', false)['shortBody']));
		$this->assertEquals(true, isset(self::$client->getProblem('TEST', true)['shortBody']));
    }
    
    public function testCreateProblemMethodSuccess()
    {
    	$problem_code = 'CODE';
    	$problem_name = 'Name';
    	$problem_body = 'Body';
    	$problem_type = 'maximize';
    	$problem_interactive = 1;
    	$problem_masterjudgeId = 1002;
    	$this->assertEquals(
    			$problem_code, 
    			self::$client->createProblem(
    					$problem_code, 
    					$problem_name,
    					$problem_body,
    					$problem_type,
    					$problem_interactive,
    					$problem_masterjudgeId
    				)['code'],
    			'Creation method should return new problem code');
    }
    
    public function testUpdateProblemMethodSuccess()
    {
    	$problem_code = 'CODE';	
    	$new_problem_name = 'Updated name';
    	$new_problem_body = 'update';
    	$new_problem_type = 'maximize';
    	$new_problem_interactive = 1;
    	$new_problem_masterjudgeId = 1002;
    	self::$client->updateProblem(
    			$problem_code,
    			$new_problem_name,
    			$new_problem_body,
    			$new_problem_type,
    			$new_problem_interactive,
    			$new_problem_masterjudgeId);
		// there should be no exceptions during these operations
    }
    
    public function testUpdateProblemActiveTestcasesMethodSuccess()
    {
    	self::$client->updateProblemActiveTestcases('TEST', [0,1,2]);
    }
    
    public function testGetProblemTestcasesMethodSuccess()
    {
    	$this->assertEquals(0, self::$client->getProblemTestcases('TEST')['testcases'][0]['number']);
    }
    
    public function testGetProblemTestcaseMethodSuccess()
    {
    	$this->assertEquals(0, self::$client->getProblemTestcase('TEST', 0)['number']);
    }
    
    public function testCreateProblemTestcaseMethodSuccess()
    {
    	$response = self::$client->createProblemTestcase("TEST", "in0", "out0", 10, 2, 0);
    	$this->assertEquals(0, $response['number'], 'Testcase number');
    }

    public function testUpdateProblemTestcaseMethodSuccess()
    {
    	$new_testcase_input = "in0updated";
    	$new_testcase_output = "out0updated";
    	$new_testcase_timelimit = 10;
    	$new_testcase_judge = 2;
    	$new_testcase_active = 0;
    	
    	self::$client->updateProblemTestcase(
    			'TEST',
    			0,
    			$new_testcase_input, 
    			$new_testcase_output, 
    			$new_testcase_timelimit, 
    			$new_testcase_judge, 
    			$new_testcase_active);
    }
    
    public function testDeleteProblemTestcaseMethodSuccess()
    {
    	self::$client->deleteProblemTestcase('TEST', 0);
    }

    public function testGetProblemTestcaseFileMethodSuccess()
    {
		$problem_code = 'TEST';
    	$this->assertEquals("in0", self::$client->getProblemTestcaseFile($problem_code, 0, 'input'));
    	$this->assertEquals("out0", self::$client->getProblemTestcaseFile($problem_code, 0, 'output'));
		$this->assertEquals("error0", self::$client->getProblemTestcaseFile($problem_code, 0, 'error'));
		$this->assertEquals("source0", self::$client->getProblemTestcaseFile($problem_code, 0, 'source'));
		$this->assertEquals("cmpinfo0", self::$client->getProblemTestcaseFile($problem_code, 0, 'cmpinfo'));
    }
    
    public function testGetJudgesMethodSuccess()
    {
    	$this->assertEquals(10, self::$client->getJudges()['paging']['limit']);
    	$this->assertEquals(11, self::$client->getJudges(11)['paging']['limit']);
    }
    
    public function testGetJudgeMethodSuccess()
    {
    	$this->assertEquals(1, self::$client->getJudge(1)['id']);
    }
    
	public function testCreateJudgeMethodSuccess()
	{
		$judge_source = 'source';
		$judge_compiler = 2;
		$judge_type = 'testcase';
		$judge_name = 'UT judge';
		
		$response = self::$client->createJudge(
						$judge_source,
						$judge_compiler,
						$judge_type,
						$judge_name
						);
		$judge_id = $response['id'];
		$this->assertTrue($judge_id > 0, 'Creation method should return new judge ID');
	}
	
	public function testUpdateJudgeMethodSuccess()
	{
		$judge_id = 100;
		 
		$new_judge_source = 'updated source';
		$new_judge_compiler = 11;
		$new_judge_name = 'UT judge updated';
		
		self::$client->updateJudge(
				$judge_id,
				$new_judge_source,
				$new_judge_compiler,
				$new_judge_name);
	}
	
	public function testGetSubmissionMethodSuccess()
	{
		$submission_id = 10;
		$this->assertEquals($submission_id, self::$client->getSubmission($submission_id)['id']);
	}

	public function testGetSubmissionsMethodSuccess()
	{
		$response = self::$client->getSubmissions([4,9]);
		
		$this->assertEquals(True, isset($response['items']));
		$this->assertEquals(2, count($response['items']));
		$this->assertEquals(4, $response['items'][0]['id']);
		$this->assertEquals(9, $response['items'][1]['id']);
	}

	public function testGetSubmissionsMethodNonexistingSubmission()
	{
		$response = self::$client->getSubmissions([9999,10000]);
		$this->assertEquals(True, isset($response['items']));
		$this->assertEquals(0, count($response['items']));
	}

	public function testGetSubmissionsMethodValidParams()
	{
		$response = self::$client->getSubmissions(1);
		$response = self::$client->getSubmissions([1]);
	}
	
	public function testCreateSubmissionMethodSuccess()
	{
		$submission_problem_code = 'TEST';
		$submission_source = 'source';
		$submission_compiler = 2;
		
		$response = self::$client->createSubmission(
				$submission_problem_code,
				$submission_source,
				$submission_compiler);
		$submission_id = $response['id'];
		$this->assertTrue($submission_id > 0, 'Creation method should return new submission ID');
	}
}
