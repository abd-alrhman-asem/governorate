<?php

namespace App\Exceptions\Else;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TooManyRequestsException extends Exception
{
    protected $message;
    protected $code;

    /**
     * Creates an exception indicating that too many requests have been made.
     *
     * @param string $message The error message (default: "Too many requests have been made.")
     * @param int $code The response code (default: 429 - Too Many Requests)
     */
    public function __construct(string $message = "Too many requests have been made.", int $code = Response::HTTP_TOO_MANY_REQUESTS)
    {
        // Set the custom message and code properties
        $this->message = $message;
        $this->code = $code;

        // Pass the custom message and code to the parent Exception constructor
        parent::__construct($this->message, $this->code);
    }
}
