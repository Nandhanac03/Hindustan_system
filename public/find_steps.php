<?php

declare(strict_types=1);

$logFile = 'C:\\Users\\vst\\.gemini\\antigravity-ide\\brain\\546e0e3b-fb55-47bf-9ae3-0c378865a563\\.system_generated\\logs\\transcript_full.jsonl';

if (!file_exists($logFile)) {
    echo "Log file not found.";
    exit;
}

$handle = fopen($logFile, 'r');
while (($line = fgets($handle)) !== false) {
    if (strpos($line, 'dashboard.blade.php') !== false && strpos($line, 'write_to_file') !== false) {
        $data = json_decode($line, true);
        $step = $data['step_index'] ?? 'unknown';
        echo "Step: " . $step . "\n";
    }
}
fclose($handle);
