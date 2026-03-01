<?php

namespace App\Exception;

class ApiRequestException extends \RuntimeException
{
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }
}
