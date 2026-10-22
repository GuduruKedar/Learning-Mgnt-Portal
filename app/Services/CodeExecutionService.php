<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CodeExecutionService
{
    protected ClientInterface $httpClient;
    protected string $wandboxApiUrl;
    protected int $defaultTimeout;
    protected int $connectTimeout;
    protected int $javaTimeout;
    protected int $cTimeout;
    protected int $pythonTimeout;
    protected bool $localPythonEnabled;
    protected float $localPythonTimeout;
    protected string $tempDir;

    public function __construct(?ClientInterface $httpClient = null)
    {
        $this->wandboxApiUrl = config('services.code_execution.wandbox_url', 'https://wandbox.org/api/compile.json');
        $this->defaultTimeout = (int)config('services.code_execution.timeout', 25);
        $this->connectTimeout = (int)config('services.code_execution.connect_timeout', 6);
        $this->javaTimeout = (int)config('services.code_execution.java_timeout', 25);
        $this->cTimeout = (int)config('services.code_execution.c_timeout', 18);
        $this->pythonTimeout = (int)config('services.code_execution.python_timeout', 15);
        $this->localPythonEnabled = (bool)config('services.code_execution.local_python_enabled', true);
        $this->localPythonTimeout = (float)config('services.code_execution.local_python_timeout', 4.0);

        $this->tempDir = storage_path('app/temp_code');
        if (!is_dir($this->tempDir)) {
            @mkdir($this->tempDir, 0777, true);
        }

        $this->httpClient = $httpClient ?? new Client([
            'timeout' => $this->defaultTimeout,
            'connect_timeout' => $this->connectTimeout,
            'headers' => [
                'Connection' => 'keep-alive',
                'Accept-Encoding' => 'gzip, deflate',
            ],
        ]);
    }

    /**
     * Inject custom HTTP client for testing/mocking.
     */
    public function setHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;
        return $this;
    }

    /**
     * Execute code for given language with standard input.
     *
     * @param string $language 'c', 'java', 'python'
     * @param string $code
     * @param string $stdin
     * @return array
     */
    public function execute(string $language, string $code, string $stdin = ''): array
    {
        $language = strtolower(trim($language));
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();

        Log::info("[CodeExec:{$requestId}] Initiating execution request", [
            'language' => $language,
            'stdin_length' => strlen($stdin),
        ]);

        try {
            switch ($language) {
                case 'python':
                case 'py':
                case 'python3':
                    $result = $this->executePython($code, $stdin, $requestId);
                    break;

                case 'c':
                    $result = $this->executeC($code, $stdin, $requestId);
                    break;

                case 'java':
                    $result = $this->executeJava($code, $stdin, $requestId);
                    break;

                default:
                    return [
                        'success' => false,
                        'output' => null,
                        'error' => "Unsupported language: '{$language}'. Supported languages are C, Java, and Python.",
                        'status' => 1,
                        'status_code' => 1,
                        'execution_status' => 'unsupported_language',
                        'execution_time' => 0,
                    ];
            }

            $elapsed = round(microtime(true) - $startTime, 3);
            $result['execution_time'] = $elapsed;
            $result['status'] = $result['status_code'] ?? ($result['success'] ? 0 : 1);

            Log::info("[CodeExec:{$requestId}] Finished execution", [
                'language' => $language,
                'status' => $result['execution_status'] ?? 'unknown',
                'duration_seconds' => $elapsed,
            ]);

            return $result;

        } catch (\Throwable $e) {
            $elapsed = round(microtime(true) - $startTime, 3);
            Log::error("[CodeExec:{$requestId}] Unhandled execution error: " . $e->getMessage(), [
                'language' => $language,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'output' => null,
                'error' => 'Execution error: ' . $e->getMessage(),
                'status' => 1,
                'status_code' => 1,
                'execution_status' => 'infrastructure_error',
                'execution_time' => $elapsed,
            ];
        }
    }

    /**
     * Run all test cases against provided code.
     *
     * @param string $language
     * @param string $code
     * @param array $testCases
     * @param int $totalQuestionMarks
     * @return array
     */
    public function runTestCases(string $language, string $code, array $testCases, int $totalQuestionMarks = 10): array
    {
        $results = [];
        $passedCount = 0;
        $totalTestCases = count($testCases);
        $requestId = (string) Str::uuid();

        Log::info("[CodeExec:{$requestId}] Starting test suite evaluation", [
            'language' => $language,
            'total_test_cases' => $totalTestCases,
        ]);

        if ($totalTestCases === 0) {
            $exec = $this->execute($language, $code, '');
            $status = $this->determineStatus($exec, $exec['output'] ?? '', '');
            $isPassed = ($status === 'passed');

            return [
                'passed' => $isPassed,
                'passed_count' => $isPassed ? 1 : 0,
                'total_test_cases' => 1,
                'score' => $isPassed ? $totalQuestionMarks : 0,
                'results' => [
                    [
                        'test_case_id' => 1,
                        'status' => $status,
                        'input' => '',
                        'expected_output' => '',
                        'expectedOutput' => '',
                        'actual_output' => $exec['output'],
                        'actualOutput' => $exec['output'],
                        'passed' => $isPassed,
                        'is_hidden' => false,
                        'error' => $exec['error'] ?? null,
                        'execution_time' => $exec['execution_time'] ?? 0,
                        'executionTimeMs' => (int)round(($exec['execution_time'] ?? 0) * 1000),
                    ]
                ],
            ];
        }

        $hasCompilationError = false;
        $compilationErrorMessage = null;
        $cleanLang = strtolower(trim($language));

        // High-performance optimization: Prepare single temporary script once for Python suite
        $sharedTempFile = null;
        if ($this->localPythonEnabled && in_array($cleanLang, ['python', 'py', 'python3'])) {
            $sharedTempFile = $this->tempDir . '/' . Str::random(24) . '.py';
            if (file_put_contents($sharedTempFile, $code) === false) {
                $sharedTempFile = null;
            }
        }

        try {
            foreach ($testCases as $index => $tc) {
                $input = (string)($tc['input'] ?? '');
                $expectedOutput = (string)($tc['expected_output'] ?? '');
                $isHidden = !empty($tc['is_hidden']);

                // If a previous test case had compilation error, subsequent tests fail identically
                if ($hasCompilationError) {
                    $results[] = [
                        'test_case_id' => $index + 1,
                        'status' => 'compilation_error',
                        'input' => $input,
                        'expected_output' => $expectedOutput,
                        'expectedOutput' => $expectedOutput,
                        'actual_output' => null,
                        'actualOutput' => null,
                        'passed' => false,
                        'is_hidden' => $isHidden,
                        'error' => $compilationErrorMessage,
                        'execution_time' => 0,
                        'executionTimeMs' => 0,
                    ];
                    continue;
                }

                if ($sharedTempFile !== null) {
                    $tcStart = microtime(true);
                    $exec = $this->runPythonProcessOnTempFile($sharedTempFile, $input, $requestId);
                    if ($exec !== null) {
                        $elapsed = round(microtime(true) - $tcStart, 3);
                        $exec['execution_time'] = $elapsed;
                        $exec['status'] = $exec['status_code'] ?? ($exec['success'] ? 0 : 1);
                    } else {
                        $exec = $this->execute($language, $code, $input);
                    }
                } else {
                    $exec = $this->execute($language, $code, $input);
                }

                $status = $this->determineStatus($exec, $exec['output'] ?? '', $expectedOutput);

                if ($status === 'compilation_error') {
                    $hasCompilationError = true;
                    $compilationErrorMessage = $exec['error'] ?? 'Compilation failed';
                }

                $isPassed = ($status === 'passed');
                if ($isPassed) {
                    $passedCount++;
                }

                // Do not report Actual: (None) when execution failed to compile or hit infrastructure/timeout error
                $actualOutput = (in_array($status, ['compilation_error', 'infrastructure_error']) || ($status === 'time_limit_exceeded' && empty($exec['output'])))
                    ? null
                    : ($exec['output'] ?? '');

                $results[] = [
                    'test_case_id' => $index + 1,
                    'status' => $status,
                    'input' => $input,
                    'expected_output' => $expectedOutput,
                    'expectedOutput' => $expectedOutput,
                    'actual_output' => $actualOutput,
                    'actualOutput' => $actualOutput,
                    'passed' => $isPassed,
                    'is_hidden' => $isHidden,
                    'error' => $exec['error'] ?? null,
                    'execution_time' => $exec['execution_time'] ?? 0,
                    'executionTimeMs' => (int)round(($exec['execution_time'] ?? 0) * 1000),
                ];
            }
        } finally {
            if ($sharedTempFile && file_exists($sharedTempFile)) {
                @unlink($sharedTempFile);
            }
        }

        $score = ($totalTestCases > 0)
            ? (int)round(($passedCount / $totalTestCases) * $totalQuestionMarks)
            : 0;

        return [
            'passed' => ($passedCount === $totalTestCases),
            'passed_count' => $passedCount,
            'total_test_cases' => $totalTestCases,
            'score' => $score,
            'results' => $results,
        ];
    }

    /**
     * Categorize test case outcome cleanly.
     */
    protected function determineStatus(array $exec, ?string $actualOutput, string $expectedOutput): string
    {
        $execStatus = $exec['execution_status'] ?? null;

        if ($execStatus === 'infrastructure_error') {
            return 'infrastructure_error';
        }
        if ($execStatus === 'compilation_error') {
            return 'compilation_error';
        }
        if ($execStatus === 'time_limit_exceeded') {
            return 'time_limit_exceeded';
        }
        if ($execStatus === 'runtime_error' || (!$exec['success'] && !empty($exec['error']))) {
            if (!empty($exec['error']) && (stripos($exec['error'], 'timed out') !== false || stripos($exec['error'], 'Time Limit Exceeded') !== false)) {
                return 'time_limit_exceeded';
            }
            return 'runtime_error';
        }

        if (!$exec['success']) {
            return 'runtime_error';
        }

        $normActual = $this->normalizeOutput((string)$actualOutput);
        $normExpected = $this->normalizeOutput($expectedOutput);

        return ($normActual === $normExpected) ? 'passed' : 'wrong_answer';
    }

    /**
     * Execute Python code locally or fallback to Wandbox.
     */
    protected function executePython(string $code, string $stdin, string $requestId): array
    {
        if ($this->localPythonEnabled) {
            $localResult = $this->executePythonLocally($code, $stdin, $requestId);
            if ($localResult !== null) {
                return $localResult;
            }
        }

        // Fallback to Wandbox
        return $this->executeViaWandbox('cpython-3.14.0', $code, $stdin, $this->pythonTimeout, $requestId);
    }

    /**
     * Local Python execution via isolated process runner with timeout and cleanup.
     */
    protected function executePythonLocally(string $code, string $stdin, string $requestId): ?array
    {
        $tempFile = $this->tempDir . '/' . Str::random(24) . '.py';
        if (file_put_contents($tempFile, $code) === false) {
            return null;
        }

        try {
            return $this->runPythonProcessOnTempFile($tempFile, $stdin, $requestId);
        } finally {
            if (file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }
    }

    /**
     * High-performance process runner on a prepared script file.
     * Uses -B (no .pyc bytecode writing) and -u (unbuffered binary streams) with adaptive polling.
     */
    protected function runPythonProcessOnTempFile(string $tempFile, string $stdin, string $requestId): ?array
    {
        $descriptors = [
            0 => ['pipe', 'r'], // stdin
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w'], // stderr
        ];

        // -B: do not write bytecode to disk; -u: force unbuffered standard I/O streams
        $pythonCmd = 'python -B -u';
        $process = @proc_open("{$pythonCmd} \"{$tempFile}\"", $descriptors, $pipes);

        if (!is_resource($process)) {
            return null;
        }

        try {
            if (!empty($stdin)) {
                fwrite($pipes[0], $stdin);
            }
            fclose($pipes[0]);

            $timeout = $this->localPythonTimeout;
            $start = microtime(true);
            $running = true;
            $isTimeout = false;
            $sleepUs = 2000; // start adaptive polling at 2ms (2ms, 4ms, 8ms, 12ms)

            while ($running) {
                $status = proc_get_status($process);
                $running = $status['running'];

                if ($running && (microtime(true) - $start) > $timeout) {
                    $isTimeout = true;
                    $pPid = $status['pid'] ?? null;
                    if (PHP_OS_FAMILY === 'Windows' && $pPid) {
                        exec("taskkill /F /T /PID {$pPid} 2>NUL");
                    } else {
                        @proc_terminate($process, 9);
                    }
                    break;
                }

                if ($running) {
                    usleep($sleepUs);
                    if ($sleepUs < 12000) {
                        $sleepUs = min(12000, $sleepUs * 2);
                    }
                }
            }

            $stdout = stream_get_contents($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);

            if (is_resource($pipes[1])) fclose($pipes[1]);
            if (is_resource($pipes[2])) fclose($pipes[2]);
            $exitCode = @proc_close($process);

            if ($isTimeout) {
                return [
                    'success' => false,
                    'output' => $stdout,
                    'error' => "Time Limit Exceeded (Execution exceeded {$timeout}s). Check for infinite loops.",
                    'status_code' => 124,
                    'execution_status' => 'time_limit_exceeded',
                ];
            }

            $hasStderr = !empty(trim($stderr));
            $isSuccess = ($exitCode === 0 && !$hasStderr);

            return [
                'success' => $isSuccess,
                'output' => $stdout,
                'error' => $stderr,
                'status_code' => $exitCode,
                'execution_status' => $isSuccess ? 'completed' : 'runtime_error',
            ];
        } catch (\Throwable $e) {
            if (is_resource($pipes[1] ?? null)) @fclose($pipes[1]);
            if (is_resource($pipes[2] ?? null)) @fclose($pipes[2]);
            if (is_resource($process ?? null)) @proc_close($process);
            throw $e;
        }
    }

    /**
     * Execute C code via Wandbox GCC.
     */
    protected function executeC(string $code, string $stdin, string $requestId): array
    {
        return $this->executeViaWandbox('gcc-13.2.0-c', $code, $stdin, $this->cTimeout, $requestId);
    }

    /**
     * Execute Java code via Wandbox OpenJDK.
     */
    protected function executeJava(string $code, string $stdin, string $requestId): array
    {
        // 1. Remove package declarations (e.g. package com.example;) which cause class file location mismatch
        $sanitizedCode = preg_replace('/^\s*package\s+[^;]+;\s*/m', '', $code);

        // 2. Wandbox compiles single file (prog.java). Having 'public class <Name>' causes compile failure.
        // Replace 'public class <Name>' with 'class <Name>' so any class definition compiles seamlessly.
        $sanitizedCode = preg_replace('/\bpublic\s+(final\s+)?class\b/', '$1class', $sanitizedCode);

        return $this->executeViaWandbox('openjdk-jdk-21+35', $sanitizedCode, $stdin, $this->javaTimeout, $requestId);
    }

    /**
     * Generic Wandbox API executor with OCI container error handling and retry mechanism.
     */
    protected function executeViaWandbox(string $compiler, string $code, string $stdin, int $timeout, string $requestId): array
    {
        $maxRetries = 1;
        $attempt = 0;

        while ($attempt <= $maxRetries) {
            $attempt++;
            try {
                Log::info("[CodeExec:{$requestId}] Dispatching to Wandbox (attempt {$attempt})", [
                    'compiler' => $compiler,
                    'timeout' => $timeout,
                ]);

                $response = $this->httpClient->post($this->wandboxApiUrl, [
                    'json' => [
                        'compiler' => $compiler,
                        'code' => $code,
                        'stdin' => $stdin,
                    ],
                    'timeout' => $timeout,
                    'connect_timeout' => $this->connectTimeout,
                ]);

                $contents = $response->getBody()->getContents();
                $data = json_decode($contents, true) ?? [];

                $exitStatus = (int)($data['status'] ?? 0);
                $programOutput = (string)($data['program_output'] ?? '');
                $compilerError = (string)($data['compiler_error'] ?? '');
                $programError = (string)($data['program_error'] ?? '');

                $rawErrors = $compilerError . "\n" . $programError;

                // Check for OCI container runtime limit error
                if ($this->isOciResourceError($rawErrors)) {
                    if ($attempt <= $maxRetries) {
                        Log::warning("[CodeExec:{$requestId}] Wandbox crun limit detected, retrying after 600ms backoff...", [
                            'attempt' => $attempt,
                            'error' => $rawErrors,
                        ]);
                        usleep(600000); // 600ms backoff
                        continue;
                    }

                    return [
                        'success' => false,
                        'output' => null,
                        'error' => "Execution infrastructure resource limit reached: Wandbox container runtime temporarily exhausted process/thread limit (crun: clone: Resource temporarily unavailable). Please retry in a few moments.",
                        'status_code' => 503,
                        'execution_status' => 'infrastructure_error',
                    ];
                }

                $errorMsg = trim($compilerError . "\n" . $programError);
                $isCompilationError = !empty($compilerError) && $exitStatus !== 0;

                if ($isCompilationError) {
                    return [
                        'success' => false,
                        'output' => null,
                        'error' => $compilerError,
                        'status_code' => $exitStatus,
                        'execution_status' => 'compilation_error',
                    ];
                }

                $isSuccess = ($exitStatus === 0 && empty($compilerError));

                return [
                    'success' => $isSuccess,
                    'output' => $programOutput,
                    'error' => $errorMsg,
                    'status_code' => $exitStatus,
                    'execution_status' => $isSuccess ? 'completed' : 'runtime_error',
                ];

            } catch (ConnectException $ce) {
                $isTimeout = str_contains($ce->getMessage(), 'timed out') || str_contains($ce->getMessage(), 'cURL error 28');
                Log::warning("[CodeExec:{$requestId}] Connection error on attempt {$attempt}: " . $ce->getMessage());

                if ($attempt <= $maxRetries) {
                    usleep(500000);
                    continue;
                }

                return [
                    'success' => false,
                    'output' => null,
                    'error' => $isTimeout
                        ? "Execution timed out after {$timeout}s. Compilation or execution exceeded time limit (cURL error 28)."
                        : "Execution service temporarily unreachable: Failed to connect to compiler engine.",
                    'status_code' => 504,
                    'execution_status' => $isTimeout ? 'time_limit_exceeded' : 'infrastructure_error',
                ];

            } catch (RequestException $re) {
                $statusCode = $re->getResponse() ? $re->getResponse()->getStatusCode() : 500;
                Log::warning("[CodeExec:{$requestId}] HTTP error {$statusCode} on attempt {$attempt}: " . $re->getMessage());

                if ($statusCode >= 500 && $attempt <= $maxRetries) {
                    usleep(500000);
                    continue;
                }

                return [
                    'success' => false,
                    'output' => null,
                    'error' => "Execution service error (HTTP {$statusCode}): The compiler sandbox returned an error.",
                    'status_code' => $statusCode,
                    'execution_status' => 'infrastructure_error',
                ];

            } catch (\Throwable $e) {
                Log::error("[CodeExec:{$requestId}] Unexpected error during execution: " . $e->getMessage());
                return [
                    'success' => false,
                    'output' => null,
                    'error' => "Execution infrastructure failure: " . $e->getMessage(),
                    'status_code' => 500,
                    'execution_status' => 'infrastructure_error',
                ];
            }
        }

        return [
            'success' => false,
            'output' => null,
            'error' => 'Execution service retry attempts exhausted.',
            'status_code' => 503,
            'execution_status' => 'infrastructure_error',
        ];
    }

    /**
     * Check if error message indicates an OCI container runtime / crun resource exhaustion.
     */
    protected function isOciResourceError(string $text): bool
    {
        $patterns = [
            'crun: clone: Resource temporarily unavailable',
            'OCI runtime error',
            'clone: Resource temporarily unavailable',
            'Resource temporarily unavailable',
            'failed to create container',
            'cannot allocate memory',
            'fork: Resource temporarily unavailable',
        ];

        foreach ($patterns as $pattern) {
            if (stripos($text, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize string for comparison by stripping carriage returns and trailing whitespace.
     */
    public function normalizeOutput(string $str): string
    {
        // Replace CRLF with LF
        $str = str_replace("\r\n", "\n", $str);
        // Replace remaining CR with LF
        $str = str_replace("\r", "\n", $str);
        // Trim trailing whitespace from each line
        $lines = explode("\n", $str);
        $trimmedLines = array_map('rtrim', $lines);
        $clean = implode("\n", $trimmedLines);
        return trim($clean);
    }

    /**
     * Provide default starter code template for a language.
     */
    public static function getDefaultStarterCode(string $language): string
    {
        switch (strtolower(trim($language))) {
            case 'c':
                return "#include <stdio.h>\n\nint main() {\n    // Write your C code here\n    printf(\"Hello, World!\\n\");\n    return 0;\n}\n";

            case 'java':
                return "import java.util.Scanner;\n\npublic class Solution {\n    public static void main(String[] args) {\n        Scanner scanner = new Scanner(System.in);\n        // Write your Java code here\n        System.out.println(\"Hello, World!\");\n    }\n}\n";

            case 'python':
            default:
                return "import sys\n\ndef main():\n    # Write your Python code here\n    print(\"Hello, World!\")\n\nif __name__ == '__main__':\n    main()\n";
        }
    }
}
