<?php

namespace Tests\Feature;

use App\Services\CodeExecutionService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class CodeExecutionTest extends TestCase
{
    protected CodeExecutionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CodeExecutionService();
    }

    /**
     * Helper to create a service with mocked Guzzle responses.
     */
    protected function createMockedService(array $queue): CodeExecutionService
    {
        $mock = new MockHandler($queue);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new CodeExecutionService($client);
        return $service;
    }

    /**
     * Test 1: Python Hello World.
     */
    public function test_python_hello_world()
    {
        $code = "print('Hello, World!')";
        $result = $this->service->execute('python', $code);

        $this->assertTrue($result['success']);
        $this->assertEquals("Hello, World!", trim($result['output']));
    }

    /**
     * Test 2: C Hello World.
     */
    public function test_c_hello_world()
    {
        $service = $this->createMockedService([
            new Response(200, [], json_encode([
                'status' => '0',
                'program_output' => "Hello, World!\n",
                'compiler_error' => '',
                'program_error' => '',
            ]))
        ]);

        $code = "#include <stdio.h>\nint main() { printf(\"Hello, World!\\n\"); return 0; }";
        $result = $service->execute('c', $code);

        $this->assertTrue($result['success']);
        $this->assertEquals("Hello, World!", trim($result['output']));
    }

    /**
     * Test 3: Java 21 Hello World.
     */
    public function test_java_21_hello_world()
    {
        $service = $this->createMockedService([
            new Response(200, [], json_encode([
                'status' => '0',
                'program_output' => "Hello from Java 21!\n",
                'compiler_error' => '',
                'program_error' => '',
            ]))
        ]);

        $code = "public class Solution { public static void main(String[] args) { System.out.println(\"Hello from Java 21!\"); } }";
        $result = $service->execute('java', $code);

        $this->assertTrue($result['success']);
        $this->assertEquals("Hello from Java 21!", trim($result['output']));
    }

    /**
     * Test 4: Sum of digits: 1234 -> 10.
     */
    public function test_sum_of_digits_1234()
    {
        $code = <<<'PY'
import sys
n = int(sys.stdin.read().strip())
print(sum(int(d) for d in str(n)))
PY;
        $testCases = [
            ['input' => '1234', 'expected_output' => '10']
        ];

        $result = $this->service->runTestCases('python', $code, $testCases);

        $this->assertTrue($result['passed']);
        $this->assertEquals(1, $result['passed_count']);
        $this->assertEquals('10', trim($result['results'][0]['actual_output']));
        $this->assertEquals('passed', $result['results'][0]['status']);
    }

    /**
     * Test 5: Sum of digits: 908 -> 17.
     */
    public function test_sum_of_digits_908()
    {
        $code = <<<'PY'
import sys
n = int(sys.stdin.read().strip())
print(sum(int(d) for d in str(n)))
PY;
        $testCases = [
            ['input' => '908', 'expected_output' => '17']
        ];

        $result = $this->service->runTestCases('python', $code, $testCases);

        $this->assertTrue($result['passed']);
        $this->assertEquals(1, $result['passed_count']);
        $this->assertEquals('17', trim($result['results'][0]['actual_output']));
        $this->assertEquals('passed', $result['results'][0]['status']);
    }

    /**
     * Test 6: Incorrect student output (Wrong Answer).
     */
    public function test_incorrect_student_output()
    {
        $code = "print(999)";
        $testCases = [
            ['input' => '1234', 'expected_output' => '10']
        ];

        $result = $this->service->runTestCases('python', $code, $testCases);

        $this->assertFalse($result['passed']);
        $this->assertEquals(0, $result['passed_count']);
        $this->assertEquals('wrong_answer', $result['results'][0]['status']);
        $this->assertEquals('999', trim($result['results'][0]['actual_output']));
        $this->assertEquals('10', $result['results'][0]['expected_output']);
    }

    /**
     * Test 7: Compilation failure.
     */
    public function test_compilation_failure()
    {
        $service = $this->createMockedService([
            new Response(200, [], json_encode([
                'status' => '1',
                'program_output' => '',
                'compiler_error' => "prog.c:2:1: error: unknown type name 'invalid_code'",
                'program_error' => '',
            ]))
        ]);

        $testCases = [
            ['input' => '1', 'expected_output' => '1']
        ];

        $result = $service->runTestCases('c', "invalid_code", $testCases);

        $this->assertFalse($result['passed']);
        $this->assertEquals('compilation_error', $result['results'][0]['status']);
        $this->assertNull($result['results'][0]['actual_output']);
        $this->assertStringContainsString("unknown type name", $result['results'][0]['error']);
    }

    /**
     * Test 8: Runtime exception.
     */
    public function test_runtime_exception()
    {
        $code = "print(1 / 0)"; // ZeroDivisionError
        $testCases = [
            ['input' => '', 'expected_output' => '0']
        ];

        $result = $this->service->runTestCases('python', $code, $testCases);

        $this->assertFalse($result['passed']);
        $this->assertEquals('runtime_error', $result['results'][0]['status']);
        $this->assertStringContainsString('ZeroDivisionError', $result['results'][0]['error']);
    }

    /**
     * Test 9: Infinite-loop timeout.
     */
    public function test_infinite_loop_timeout()
    {
        $code = "while True: pass";
        $testCases = [
            ['input' => '', 'expected_output' => 'done']
        ];

        $result = $this->service->runTestCases('python', $code, $testCases);

        $this->assertFalse($result['passed']);
        $this->assertEquals('time_limit_exceeded', $result['results'][0]['status']);
        $this->assertStringContainsString('Time Limit Exceeded', $result['results'][0]['error']);
    }

    /**
     * Test 10: Execution-service unavailable (mocked network / cURL error 28 timeout).
     */
    public function test_execution_service_unavailable()
    {
        $service = $this->createMockedService([
            new ConnectException('cURL error 28: Operation timed out after 12011 milliseconds with 0 bytes received', new Request('POST', 'compile.json')),
            new ConnectException('cURL error 28: Operation timed out after 12011 milliseconds with 0 bytes received', new Request('POST', 'compile.json')),
        ]);

        $testCases = [
            ['input' => '10', 'expected_output' => '10']
        ];

        $result = $service->runTestCases('c', "#include <stdio.h>\nint main(){ return 0; }", $testCases);

        $this->assertFalse($result['passed']);
        // Crucial test contract: Infrastructure failure must NEVER be reported as a normal (None) wrong answer!
        $this->assertEquals('time_limit_exceeded', $result['results'][0]['status']);
        $this->assertNull($result['results'][0]['actual_output']);
        $this->assertStringContainsString('cURL error 28', $result['results'][0]['error']);
    }

    /**
     * Test 11: Container resource exhaustion (crun: clone: Resource temporarily unavailable).
     */
    public function test_container_resource_exhaustion()
    {
        $service = $this->createMockedService([
            // Initial failure with crun clone EAGAIN
            new Response(200, [], json_encode([
                'status' => '1',
                'program_output' => '',
                'compiler_error' => 'OCI runtime error: crun: clone: Resource temporarily unavailable',
                'program_error' => '',
            ])),
            // Retry also encounters crun error
            new Response(200, [], json_encode([
                'status' => '1',
                'program_output' => '',
                'compiler_error' => 'OCI runtime error: crun: clone: Resource temporarily unavailable',
                'program_error' => '',
            ])),
        ]);

        $testCases = [
            ['input' => '1234', 'expected_output' => '10']
        ];

        $result = $service->runTestCases('c', "#include <stdio.h>\nint main(){ return 0; }", $testCases);

        $this->assertFalse($result['passed']);
        // Must be identified as infrastructure_error, NOT wrong answer!
        $this->assertEquals('infrastructure_error', $result['results'][0]['status']);
        $this->assertNull($result['results'][0]['actual_output']);
        $this->assertStringContainsString('crun: clone: Resource temporarily unavailable', $result['results'][0]['error']);
    }

    /**
     * Test 12: Multiple test cases with independent inputs.
     */
    public function test_multiple_test_cases_with_independent_inputs()
    {
        $code = <<<'PY'
import sys
line = sys.stdin.read().strip()
if line:
    print(sum(int(d) for d in line))
PY;
        $testCases = [
            ['input' => '1234', 'expected_output' => '10'],
            ['input' => '908', 'expected_output' => '17'],
            ['input' => '55', 'expected_output' => '10'],
        ];

        $result = $this->service->runTestCases('python', $code, $testCases, 15);

        $this->assertTrue($result['passed']);
        $this->assertEquals(3, $result['passed_count']);
        $this->assertEquals(3, $result['total_test_cases']);
        $this->assertEquals(15, $result['score']);

        $this->assertEquals('10', trim($result['results'][0]['actual_output']));
        $this->assertEquals('17', trim($result['results'][1]['actual_output']));
        $this->assertEquals('10', trim($result['results'][2]['actual_output']));
    }
}
