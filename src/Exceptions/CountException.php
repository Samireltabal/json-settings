<?php

namespace Samireltabal\JsonSettings\Exceptions;

use Exception;
use Throwable;

class CountException extends Exception
{
    public mixed $key;

    public mixed $count;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        $count = null,
        $key = null
    ) {
        $this->key = $key;
        $this->count = $count;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function context(): array
    {
        return [
            'count' => $this->count,
            'key' => $this->key,
            'message' => $this->getMessage(),
        ];
    }
}
