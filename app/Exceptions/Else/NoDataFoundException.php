<?php

namespace App\Exceptions\Else;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class NoDataFoundException extends Exception
{
    protected $message;
    protected $code;

    /**
     * Creates an exception indicating that no data was found.
     *
     * @param string $message The error message (default: "No data available")
     * @param int $code The response code (default: 404 - Not Found)
     */
    public function __construct(string $message = 'No data available', int $code = Response::HTTP_NOT_FOUND)
    {
        // Set the custom message and code properties
        $this->message = $message;
        $this->code = $code;

        // Pass the custom message and code to the parent Exception constructor
        parent::__construct($this->message, $this->code);
    }
}
