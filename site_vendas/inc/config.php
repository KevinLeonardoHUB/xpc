<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'XPC Informática');
define('DB_PATH', __DIR__ . '/../storage/database.sqlite');

function detect_base_url(): string
{
    $projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..') ?: dirname(__DIR__));
    $projectName = basename($projectRoot);
    $documentRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '');

    if ($documentRoot !== '' && str_starts_with($projectRoot, $documentRoot)) {
        $relative = trim(substr($projectRoot, strlen($documentRoot)), '/');
        return $relative === '' ? '' : '/' . $relative;
    }

    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $parts = array_values(array_filter(explode('/', trim($scriptName, '/'))));

    $projectIndex = array_search($projectName, $parts, true);
    if ($projectIndex !== false) {
        return '/' . implode('/', array_slice($parts, 0, $projectIndex + 1));
    }

    if (!empty($parts)) {
        array_pop($parts); // remove file name
    }
    if (!empty($parts) && end($parts) === 'admin') {
        array_pop($parts);
    }

    return $parts ? '/' . implode('/', $parts) : '';
}

define('BASE_URL', detect_base_url());

define('ADMIN_EMAIL', 'kevinleonardomail@hotmail.com');
define('ADMIN_DEFAULT_PASSWORD', 'Admin@123456');

define('UPLOADS_DIR', __DIR__ . '/../storage/uploads');
define('UPLOADS_URL', BASE_URL . '/storage/uploads');

function env_value(string $key, ?string $default = null): ?string
{
    static $env = null;
    if ($env === null) {
        $env = [];
        $envFile = __DIR__ . '/../.env';
        if (file_exists($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                    continue;
                }
                [$k, $v] = explode('=', $line, 2);
                $env[trim($k)] = trim($v, " \t\n\r\0\x0B\"");
            }
        }
    }
    return $env[$key] ?? $default;
}
