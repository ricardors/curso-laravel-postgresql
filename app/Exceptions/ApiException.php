<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public function __construct(private array $data, private int $status)
    {}

    public function render()
    {
        return response($this->data, $this->status);
    }
}