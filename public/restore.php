<?php

declare(strict_types=1);

$logFile = 'C:\\Users\\vst\\.gemini\\antigravity-ide\\brain\\546e0e3b-fb55-47bf-9ae3-0c378865a563\\.system_generated\\logs\\transcript_full.jsonl';

if (!file_exists($logFile)) {
    echo "Log file not found at: " . $logFile;
    exit;
}

$handle = fopen($logFile, 'r');
if (!$handle) {
    echo "Could not open log file.";
    exit;
}

$dashboardContent = null;

while (($line = fgets($handle)) !== false) {
    // Look specifically for step 431 where dashboard.blade.php was written
    if (strpos($line, '"step_index":431') !== false) {
        $data = json_decode($line, true);
        if (isset($data['tool_calls'])) {
            foreach ($data['tool_calls'] as $tool) {
                if ($tool['name'] === 'write_to_file' && isset($tool['args']['CodeContent'])) {
                    $dashboardContent = $tool['args']['CodeContent'];
                    break 2;
                }
            }
        }
    }
}
fclose($handle);

if ($dashboardContent) {
    $targetFile = 'G:\\wamp64\\www\\Hindustan_system\\resources\\views\\dashboard.blade.php';
    file_put_contents($targetFile, $dashboardContent);
    echo "SUCCESS: Restored dashboard.blade.php successfully!";
} else {
    echo "ERROR: Could not find dashboard.blade.php contents in transcript logs.";
}
