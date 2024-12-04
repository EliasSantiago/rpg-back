<?php

namespace App\Exceptions;

use Exception;

class GuildFullException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(string $message = "A guilda atingiu o número máximo de jogadores.", int $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
