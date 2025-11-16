<?php

declare(strict_types=1);

namespace App\Service;

class AvatarProxy
{
    public function __construct(
        private string $directory = __DIR__ . '/../../var/avatar',
    ) {}

    public function get(string $url): string
    {
        // 1. Transform URL to filename 
        $filename = md5($url) . '.jpg';

        // 2. Check if file exists in cache
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        }

        $filepath = $this->directory . '/' . $filename;

        if (file_exists($filepath)) {
            return $filepath;
        }

        // 3. Download and save the file
        // we need to pass some headers to avoid 403 error from instagram
        $options = [
            'http' => [
                'header' => [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
                    'Accept: image/webp,image/apng,image/*,*/*;q=0.8',
                    'Accept-Language: en-US,en;q=0.9',
                ],
            ],
        ];

        $context = stream_context_create($options);
        $imageData = file_get_contents($url, false, $context);

        if ($imageData === false) {
            throw new \RuntimeException('Failed to download avatar from ' . $url);
        }

        file_put_contents($filepath, $imageData);

        return $filepath;
    }
}
