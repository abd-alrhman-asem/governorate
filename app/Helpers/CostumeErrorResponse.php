<?php

/**
 * This file serves as a helper for handling various exceptions and errors
 * within a Laravel application, specifically designed to return JSON responses.
 * While functional, it's important to note that Laravel provides a more robust
 * and idiomatic way to handle exceptions via the `App\Exceptions\Handler.php` class.
 * This helper centralizes exception-to-response mapping, but its global function
 * approach deviates from standard Laravel architecture.
 */

// Importing necessary exception classes from various namespaces.
// This ensures that the exception types can be correctly identified and caught.
use App\Exceptions\Else\GeneralFailureException;
use App\Exceptions\Else\MaintenanceModeException;
use App\Exceptions\Else\NoDataFoundException;
use App\Exceptions\Else\TooManyRequestsException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException; // Note: This is typically for validation, not general HTTP unauthorized.
use Illuminate\Validation\ValidationException;
use Symfony\Component\Finder\Exception\AccessDeniedException; // Note: This is from Symfony Finder, not general HTTP access control.
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

// Essential for catching any type of exception or error.


/**
 * Handles custom exceptions based on a configurable mapping.
 * This function iterates through a predefined configuration array
 * (`ExceptionClassToMethod`) to find a custom handler for the given exception.
 * If a matching, callable handler is found, it is invoked.
 *
 * This approach offers flexibility but might be less performant than a direct
 * `instanceof` chain for a large number of handlers, and less idiomatic than
 * Laravel's `Handler` class's `render` method.
 *
 * @param Throwable $e The exception instance to be handled.
 * @return mixed|null The result of the custom handler, or null if no custom handler is found.
 */
function handleCustomException(Throwable $e): mixed
{
    // Retrieve the custom exception handlers mapping from the application configuration.
    // The configuration is expected to be an associative array where keys are
    // fully qualified exception class names and values are callable functions.
    $handlers = config('ExceptionClassToMethod');

    // Iterate over each defined custom handler.
    foreach ($handlers as $exceptionClass => $handlerFunction) {
        // Check if the current exception is an instance of the exception class
        // specified in the configuration.
        if ($e instanceof $exceptionClass) {
            // Verify that the associated handler function is actually callable.
            // This prevents errors if the configuration points to a non-existent
            // or invalid function.
            if (is_callable($handlerFunction)) {
                // Invoke the custom handler function with the exception instance
                // and return its result. This allows custom logic for specific exceptions.
                return $handlerFunction($e);
            }
        }
    }

    // If no custom handler was found for the given exception, return null.
    // This indicates that the exception should fall back to default or subsequent
    // exception handling mechanisms.
    return null;
}

/**
 * Handles `NotFoundHttpException` (HTTP 404).
 * This exception is typically thrown when a requested resource is not found.
 *
 * @param NotFoundHttpException $e The NotFoundHttpException instance.
 * @return JsonResponse A JSON response with a 404 Not Found status.
 * @uses notFoundResponse() Assumes the existence of a global helper function
 * `notFoundResponse` that constructs the JSON response.
 */
function handleNotFoundHttpException(NotFoundHttpException $e): JsonResponse
{
    // Returns a 404 Not Found JSON response.
    // The message includes the exception's original message for debugging.
    return notFoundResponse("Not Found: " . $e->getMessage());
}

/**
 * Handles `ValidationException` (HTTP 422).
 * This exception occurs when input data fails validation rules.
 *
 * @param ValidationException $e The ValidationException instance.
 * @return JsonResponse A JSON response with a 422 Unprocessable Entity status.
 * @uses unprocessableResponse() Assumes the existence of a global helper function
 * `unprocessableResponse` that constructs the JSON response.
 */
function handleValidationException(ValidationException $e): JsonResponse
{
    // Returns a 422 Unprocessable Entity JSON response.
    // It extracts all validation error messages from the validator instance.
    return unprocessableResponse($e->validator->errors()->all());
}

/**
 * Handles `ConflictHttpException` (HTTP 409).
 * This exception indicates a conflict with the current state of the target resource.
 *
 * @param ConflictHttpException $e The ConflictHttpException instance.
 * @return JsonResponse A JSON response with a 409 Conflict status.
 * @uses conflictResponse() Assumes the existence of a global helper function
 * `conflictResponse` that constructs the JSON response.
 */
function handleConflictHttpException(ConflictHttpException $e): JsonResponse
{
    // Returns a 409 Conflict JSON response.
    // The message includes the exception's original message.
    return conflictResponse("Conflict: " . $e->getMessage());
}

/**
 * Handles `ModelNotFoundException` (HTTP 404).
 * This exception is thrown by Eloquent when a model cannot be found by its ID
 * or other query constraints.
 *
 * @param ModelNotFoundException $e The ModelNotFoundException instance.
 * @return JsonResponse A JSON response with a 404 Not Found status.
 * @uses notFoundResponse() Assumes the existence of a global helper function
 * `notFoundResponse` that constructs the JSON response.
 */
function handleModelNotFoundException(ModelNotFoundException $e): JsonResponse
{
    // Returns a 404 Not Found JSON response.
    // Provides a specific message indicating a model was not found.
    return notFoundResponse("Model not found: " . $e->getMessage());
}

/**
 * Handles `UnprocessableEntityHttpException` (HTTP 422).
 * This exception is typically thrown when the request is syntactically correct
 * but semantically invalid (e.g., business logic errors).
 *
 * @param UnprocessableEntityHttpException $e The UnprocessableEntityHttpException instance.
 * @return JsonResponse A JSON response with a 422 Unprocessable Entity status.
 * @uses unprocessableResponse() Assumes the existence of a global helper function
 * `unprocessableResponse` that constructs the JSON response.
 */
function handleUnprocessableEntityException(UnprocessableEntityHttpException $e): JsonResponse
{
    // Returns a 422 Unprocessable Entity JSON response.
    // The message includes the exception's original message.
    return unprocessableResponse("Unprocessable: " . $e->getMessage());
}

/**
 * Handles `AuthenticationException` (HTTP 401).
 * This exception is thrown when a user attempts to access a protected resource
 * without proper authentication credentials.
 *
 * @param AuthenticationException $e The AuthenticationException instance.
 * @return JsonResponse A JSON response with a 401 Unauthorized status.
 * @uses unauthorizedResponse() Assumes the existence of a global helper function
 * `unauthorizedResponse` that constructs the JSON response.
 */
function handleAuthenticationException(AuthenticationException $e): JsonResponse
{
    // Returns a 401 Unauthorized JSON response.
    // Provides a generic authentication failure message.
    return unauthorizedResponse("Authentication failed: " . $e->getMessage());
}

/**
 * Handles `UnauthorizedException` (HTTP 403 or 401, depending on context).
 * Note: This specific `UnauthorizedException` is from `Illuminate\Validation`.
 * For general HTTP authorization failures (user authenticated but no permission),
 * `AuthorizationException` or `HttpException(403)` is more common.
 * This handler returns a 403 Forbidden response.
 *
 * @param UnauthorizedException $e The UnauthorizedException instance.
 * @return JsonResponse A JSON response with a 403 Forbidden status.
 * @uses unauthorizedResponse() Assumes the existence of a global helper function
 * `unauthorizedResponse` that constructs the JSON response.
 */
function handleUnauthorizedException(UnauthorizedException $e): JsonResponse
{
    // Returns a 403 Forbidden JSON response.
    // Provides a message indicating unauthorized access.
    // Consider if this should be 401 (Unauthorized) or 403 (Forbidden) based on exact context.
    return unauthorizedResponse("Unauthorized access: " . $e->getMessage());
}

/**
 * Handles `AuthorizationException` (HTTP 403).
 * This exception is thrown when an authenticated user does not have the
 * necessary permissions to perform an action or access a resource.
 *
 * @param AuthorizationException $e The AuthorizationException instance.
 * @return JsonResponse A JSON response with a 403 Forbidden status.
 * @uses forbiddenResponse() Assumes the existence of a global helper function
 * `forbiddenResponse` that constructs the JSON response.
 */
function handleAuthorizationException(AuthorizationException $e): JsonResponse
{
    // Returns a 403 Forbidden JSON response.
    // The message includes the exception's original message.
    return forbiddenResponse("Forbidden: " . $e->getMessage());
}

/**
 * Handles `AccessDeniedException` (HTTP 403).
 * Note: This exception is from `Symfony\Component\Finder\Exception\AccessDeniedException`.
 * For general HTTP access control, `AuthorizationException` or `HttpException(403)`
 * is typically preferred for consistency within a web application context.
 *
 * @param AccessDeniedException $e The AccessDeniedException instance.
 * @return JsonResponse A JSON response with a 403 Forbidden status.
 * @uses forbiddenResponse() Assumes the existence of a global helper function
 * `forbiddenResponse` that constructs the JSON response.
 */
function handleAccessDeniedException(AccessDeniedException $e): JsonResponse
{
    // Returns a 403 Forbidden JSON response.
    // Provides a message indicating access was denied.
    return forbiddenResponse("Access Denied: " . $e->getMessage());
}

/**
 * Handles `BadRequestHttpException` (HTTP 400).
 * This exception is thrown when the server cannot or will not process the request
 * due to something that is perceived to be a client error (e.g., malformed request syntax).
 *
 * @param BadRequestHttpException $e The BadRequestHttpException instance.
 * @return JsonResponse A JSON response with a 400 Bad Request status.
 * @uses badRequestResponse() Assumes the existence of a global helper function
 * `badRequestResponse` that constructs the JSON response.
 */
function handleBadRequestException(BadRequestHttpException $e): JsonResponse
{
    // Returns a 400 Bad Request JSON response.
    // The message includes the exception's original message.
    return badRequestResponse("Bad Request: " . $e->getMessage());
}

/**
 * Handles `TooManyRequestsException` (HTTP 429).
 * This exception is typically thrown when a rate limit has been exceeded.
 * Note: Laravel's built-in rate limiting uses `Illuminate\Http\Exceptions\ThrottleRequestsException`.
 * This custom exception implies a custom rate limiting implementation.
 *
 * @param TooManyRequestsException $e The TooManyRequestsException instance.
 * @return JsonResponse A JSON response with a 429 Too Many Requests status.
 * @uses conflictResponse() Assumes the existence of a global helper function
 * `conflictResponse` that constructs the JSON response.
 * Note: The use of `conflictResponse` (409) for `TooManyRequestsException` (429)
 * might be a mismatch. A specific `tooManyRequestsResponse` (429) is recommended.
 */
function handleTooManyRequestsException(TooManyRequestsException $e): JsonResponse
{
    // Returns a 409 Conflict JSON response.
    // Provides a message indicating too many requests.
    // It's generally better to return a 429 (Too Many Requests) status code here.
    return conflictResponse("Too Many Requests: " . $e->getMessage());
}

/**
 * Handles `NoDataFoundException` (HTTP 500 or 404).
 * This custom exception is thrown when a query or operation yields no results.
 * Depending on context, this could be a 404 Not Found (resource not found)
 * or a 500 Internal Server Error (unexpected empty result).
 * The current implementation returns a 500 Internal Server Error.
 *
 * @param NoDataFoundException $e The NoDataFoundException instance.
 * @return JsonResponse A JSON response with a 500 Internal Server Error status.
 * @uses generalFailureResponse() Assumes the existence of a global helper function
 * `generalFailureResponse` that constructs the JSON response.
 */
function handleNoDataFoundException(NoDataFoundException $e): JsonResponse
{
    // Returns a 500 Internal Server Error JSON response.
    // Provides a message indicating no data was found.
    // Reconsider if a 404 Not Found would be more semantically appropriate
    // if the exception implies a specific resource was not found.
    return generalFailureResponse("No data found: " . $e->getMessage());
}

/**
 * Handles `GeneralFailureException` (HTTP 500).
 * This is a custom catch-all exception for general application failures.
 * It provides a way to return a consistent error response for unclassified issues.
 *
 * @param GeneralFailureException $e The GeneralFailureException instance.
 * @return JsonResponse A JSON response with a 500 Internal Server Error status.
 * @uses error() Assumes the existence of a global helper function `error`
 * that constructs the JSON response, potentially allowing
 * a custom status code.
 */
function handleGeneralFailureException(GeneralFailureException $e): JsonResponse
{
    // Returns a JSON response for a general failure.
    // The status code can be derived from the exception's code, defaulting to 500.
    return error("General failure: " . $e->getMessage(), $e->getCode());
}

/**
 * Handles `MaintenanceModeException` (HTTP 503).
 * This custom exception is typically thrown when the application is in maintenance mode.
 *
 * @param MaintenanceModeException $e The MaintenanceModeException instance.
 * @return JsonResponse A JSON response with a 503 Service Unavailable status.
 * @uses error() Assumes the existence of a global helper function `error`
 * that constructs the JSON response.
 */
function handelMaintenanceModeException(MaintenanceModeException $e) // Typo: handel -> handle
{
    // Returns a JSON response for maintenance mode.
    // It's recommended to explicitly set the HTTP status code to 503 Service Unavailable.
    return error("Maintenance Mode: " . $e->getMessage());
}

/**
 * Default exception handler for any unhandled `Throwable`.
 * This function acts as a fallback, catching any exception or error that
 * has not been specifically handled by the other functions.
 * It ensures that the application provides a graceful error response
 * rather than crashing.
 *
 * @param Throwable $e The Throwable instance (any exception or error).
 * @return JsonResponse A JSON response with a 500 Internal Server Error status.
 * @uses generalFailureResponse() Assumes the existence of a global helper function
 * `generalFailureResponse` that constructs the JSON response.
 */
function handleDefaultException(Throwable $e): JsonResponse
{
    // Returns a 500 Internal Server Error JSON response for unexpected errors.
    // The HTTP status code is derived from the exception's code if available,
    // otherwise it defaults to 500.
    // For production environments, consider a more generic message like
    // 'Something went wrong. Please try again later.' to avoid exposing
    // sensitive error details.
    return generalFailureResponse('An unexpected error occurred: ' . $e->getMessage(), (int)($e->getCode() ?? 500));
}
