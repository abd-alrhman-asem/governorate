<?php


use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Generates a JSON response with the given data, message, and status code.
 *
 * @param bool $success
 * @param object|array|null $data
 * @param array|null $message
 * @param int $statusCode
 * @param array|null $meta
 * @return JsonResponse
 */
if (!function_exists('_response')) {
    function _response(
        bool                     $success,
        object|array|null|string $data,
        array|null|string        $msg,
        int                      $statusCode = Response::HTTP_OK,
        ?array                   $meta = [],
    ): JsonResponse
    {
        $response = collect(['status' => $success]);
        if ($msg) {
            $response['message'] = $msg;
        }
        if (!is_null($data)) {
            $response['data'] = $data;
        }
        if ($meta) {
            $meta = collect($meta);
            $response = $response->merge($meta);
        }
        return response()->json($response, $statusCode);
    }
}
/**
 * Generates a successful JSON response with the given data and optional meta information.
 *
 * @param object|array|null $data
 * @param array|null $meta
 * @param int $statusCode
 * @return JsonResponse
 */
if (!function_exists('success')) {
    function success(
        object|array|null|string $data = null,
        array|string|null        $msg = null,
        int                      $statusCode = Response::HTTP_OK,
        ?array                   $meta = [],

    ): JsonResponse
    {
        return _response(
            true,
            $data,
            $msg,
            $statusCode,
            $meta
        );
    }
}


/**
 * Creates an error JSON response with the given message and optional information.
 *
 * @param string $message
 * @param array $meta
 * @param int $statusCode
 * @return JsonResponse
 */
if (!function_exists('error')) {
    function error(
        string|array $message,
        int          $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?array       $meta = []
    ): JsonResponse
    {
        return _response(
            false,
            null,
            is_array($message) ? $message : [$message], // تحويل الرسالة إلى مصفوفة إذا لم تكن كذلك
            $statusCode,
            $meta
        );
    }


}
/**
 * Returns a response indicating user successfully logged in with the given token.
 *
 * @param string $token
 * @return JsonResponse
 */
if (!function_exists('loggedInSuccessfully')) {
    function loggedInSuccessfully(string $token, $message = null, $expiresIn = null, $user = null): JsonResponse
    {
        return success(
            $user,
            meta: [
                'message' => $message ?? 'user logged in successfully ',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiresIn,
            ],
        );
    }
}
/**
 * Returns a response indicating  with the given token.
 *
 * @param string $token
 * @return JsonResponse
 */
if (!function_exists('successWithToken')) {
    function successWithToken(string $token, $message = null, $expiresIn = null, $user = null): JsonResponse
    {
        return success(
            meta: [
                'message' => $message ?? '  successfully operation ',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiresIn,
            ],
        );
    }
}

/**
 * Returns a generic response for unknown errors with the given message.
 *
 * @param mixed $errorMessage
 * @return JsonResponse
 */
if (!function_exists('generalFailureResponse')) {
    function generalFailureResponse(
        mixed $errorMessage = 'there is some thing wrong please try again later',
        int   $StatusCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse
    {
        return error(
            "general error : " . $errorMessage,
//            $StatusCode

        );
    }
}

/**
 * Returns a successful response with a status code of 201 and the given message.
 *
 * @param string $message
 * @param int $statusCode
 * @return JsonResponse
 */
if (!function_exists('resourceCreatedResponse')) {
    function resourceCreatedResponse(
        string $message = '',
               $data = null

    ): JsonResponse
    {
        return success(
            data: $data,
            msg: $message ?: 'resource created successfully.',
            statusCode: Response::HTTP_CREATED
        );
    }
}

/**
 * Returns an unauthorized response with the given message or a default one.
 *
 * @param string $message
 * @return JsonResponse
 */
if (!function_exists('unauthorizedResponse')) {
    function unauthorizedResponse(string $message = ''): JsonResponse
    {
        return error(
            $message ?: 'Unauthorized access.',
            Response::HTTP_UNAUTHORIZED

        );
    }
}

/**
 * Returns a not found response with the given message or a default one.
 *
 * @param string $message
 * @return JsonResponse
 */
if (!function_exists('notFoundResponse')) {
    function notFoundResponse(string $message = ''): JsonResponse
    {
        return error(
            $message ?: 'Resource not found.',
            Response::HTTP_NOT_FOUND
        );
    }
}

/**
 * Returns an unprocessable entity response with the given message or a default one.
 *
 * @param string $message
 * @return JsonResponse
 */

if (!function_exists('unprocessableResponse')) {
    function unprocessableResponse(
        array|string $message
    ): JsonResponse
    {
        return error(
            $message ?: 'Unprocessable Entity.',
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

/**
 * Returns a no content response with a status code of 204.
 *
 * @return JsonResponse
 */
if (!function_exists('noContentResponse')) {
    function noContentResponse(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

/**
 * Returns a conflict response with the given data and message.
 *
 * @param string $message
 * @return JsonResponse
 */
if (!function_exists('conflictResponse')) {
    function conflictResponse(
        string $message = ''
    ): JsonResponse
    {
        return error(
            $message ?: "there is conflict . please try again.",
            Response::HTTP_CONFLICT,
        );
    }
}

/**
 * Returns a forbidden response with the given data and message.
 *
 * @param string $message
 * @return JsonResponse
 */
if (!function_exists('forbiddenResponse')) {
    function forbiddenResponse(
        string $message = ''
    ): JsonResponse
    {
        return error(
            $message ?: 'Unfortunately . this forbidden . ',
            Response::HTTP_FORBIDDEN,
        );
    }
}

/**
 * Returns a bad request response with the given data and message.
 *
 * @param string $message
 * @return JsonResponse
 */
if (!function_exists('badRequestResponse')) {
    function badRequestResponse(string $message = ''): JsonResponse
    {
        return error($message ?: 'Bad request', Response::HTTP_BAD_REQUEST);
    }


}

