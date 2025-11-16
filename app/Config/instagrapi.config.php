<?php

declare(strict_types=1);

use App\Instagrapi\InstagrapiConfig;

use function Tempest\env;

return new InstagrapiConfig(
    host: env('INSTAGRAPI_HOST', 'http://localhost:8543'),
    username: env('INSTAGRAPI_USERNAME', ''),
    password: env('INSTAGRAPI_PASSWORD', ''),
);
