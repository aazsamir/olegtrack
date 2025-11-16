<?php

declare(strict_types=1);

namespace App\Instagrapi;

use Throwable;

class InstagrapiException extends \Exception
{
    public function __construct(
        array $error,
        ?Throwable $previous = null,
    ) {
        $message = \json_encode($error);

        parent::__construct($message, 0, $previous);
    }
}
