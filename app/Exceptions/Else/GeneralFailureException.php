<?php

namespace App\Exceptions\Else;
use Exception;
use Symfony\Component\HttpFoundation\Response;


class GeneralFailureException extends Exception
{
    protected $message;
    protected $code;

    /**
     * Creates a dynamic exception that accepts a custom error message and code.
     *
     * @param string $message The error message (default: "An error occurred")
     * @param int $code The response code (default: 500 - Internal Server Error)
     */
    public function __construct(string $message = "An error occurred", int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        // Set the custom message and code properties
        $this->message = $message;
        $this->code = $code;

        // Pass the custom message and code to the parent Exception constructor
        parent::__construct($this->message, $this->code);
    }
}

