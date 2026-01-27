<?php

namespace App\Exceptions;

use Exception;

class LeaveActionException extends Exception
{
    protected array $errors;
    protected int $status;

    public function __construct(
        string $message,
        array $errors = [],
        int $status = 422
    ) {
        parent::__construct($message);
        $this->errors = $errors;
        $this->status = $status;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function status(): int
    {
        return $this->status;
    }
}
