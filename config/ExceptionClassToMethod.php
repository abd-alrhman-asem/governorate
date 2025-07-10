<?php

use App\Exceptions\Else\GeneralFailureException;
use App\Exceptions\Else\MaintenanceModeException;
use App\Exceptions\Else\NoDataFoundException;
use App\Exceptions\Else\TooManyRequestsException;
use Illuminate\Auth\{Access\AuthorizationException, AuthenticationException};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\{UnauthorizedException, ValidationException};
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\{BadRequestHttpException,
    ConflictHttpException,
    NotFoundHttpException,
    UnprocessableEntityHttpException
};
return [

    /*
    |--------------------------------------------------------------------------
    | Exception Handling Mappings
    |--------------------------------------------------------------------------
    |
    | Here you may define how your application handles various exceptions.
    | Each exception type can be mapped to a specific method for processing.
    | This allows you to centralize your exception handling logic, making
    | your code more maintainable and easier to understand.
    |
    */
    /**
     * Handle cases where a model is not found in the database.
     * Returns a response indicating that the requested resource could not be located.
     */
    ModelNotFoundException::class => 'handleModelNotFoundException',
    /**
     * Handle cases where a route or resource is not found.
     * Ensures the client is informed that the requested endpoint or resource does not exist.
     */
    NotFoundHttpException::class => 'handleNotFoundHttpException',
    /**
     * Handle cases where the request data is unprocessable.
     * Provides feedback on validation or semantic issues with the request data.
     */
    UnprocessableEntityHttpException::class => 'handleUnprocessableEntityException',
    /**
     * Handle authentication failures.
     * Returns an error response indicating the user is not authenticated.
     */
    AuthenticationException::class => 'handleAuthenticationException',
    /**
     * Handle authorization failures.
     * Ensures the client is notified of insufficient permissions to perform the action.
     */
    AuthorizationException::class => 'handleAuthorizationException',
    /**
     * Handle bad request exceptions.
     * Provides feedback when the client sends a malformed or invalid request.
     */
    BadRequestHttpException::class => 'handleBadRequestException',
    /**
     * Handle cases where there is a conflict in the request data.
     * Returns a response indicating that a conflict has occurred, such as a duplicate entry.
     */
    ConflictHttpException::class => 'handleConflictHttpException',
    /**
     * Handle validation errors.
     * Ensures detailed feedback is provided for validation failures.
     */
    ValidationException::class => 'handleValidationException',
    /**
     * Handle unauthorized access attempts.
     * Notifies the client of lack of proper credentials or permissions.
     */
    UnauthorizedException::class => 'handleUnauthorizedException',
    /**
     * Handle access denied exceptions.
     * Returns a response indicating that access to the resource is forbidden.
     */
    AccessDeniedException::class => 'handleAccessDeniedException',
    /**
     * Handle cases where too many requests are sent in a short period.
     * Notifies the client to reduce the request rate and try again later.
     */
    TooManyRequestsException::class => 'handleTooManyRequestsException',
    /**
     * Handle general failure exceptions.
     * Returns a response indicating a generic failure with minimal details.
     */
    GeneralFailureException::class => 'handleGeneralFailureException',
    /**
     * Handle cases where no data is found for the request.
     * Ensures the client is informed that no matching data exists.
     */
    NoDataFoundException::class => 'handleNoDataFoundException',

    MaintenanceModeException::class => 'handelMaintenanceModeException',
];
