<?php

namespace App\Exceptions;

use Exception;

class NoPlayersConfirmedException extends Exception
{
    protected $code = 200;
    protected $message = 'Nenhum jogador está confirmado.';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $code, $previous);
    }
}
