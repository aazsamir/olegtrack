<?php

declare(strict_types=1);

use Tempest\Router\HttpApplication;

require_once __DIR__ . '/../vendor/autoload.php';

// workaround for PHP built-in server to serve static files from the public directory with query parameters in URL
if (php_sapi_name() == 'cli-server') {
    $url  = parse_url($_SERVER["REQUEST_URI"]);
    $path = $url["path"] ?? '';

    if (str_starts_with($path, '/vendor')) {
        $file = __DIR__ . $path;

        if (is_file($file)) {
            // Serve static files directly
            header('Content-Type: ' . mime_content_type($file));
            readfile($file);
            exit;
        }
    }
}

HttpApplication::boot(__DIR__ . '/../')->run();

exit();
