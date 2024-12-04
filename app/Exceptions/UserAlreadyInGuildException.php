<?php

namespace App\Exceptions;

use Exception;

class UserAlreadyInGuildException extends Exception
{
    protected $code = 400;
    protected $message = 'Este usuário já está vinculado a esta guilda.';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $code, $previous);
    }
}
