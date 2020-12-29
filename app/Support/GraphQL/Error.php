<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.04.19
 * Time: 18:55.
 */

namespace App\Support\GraphQL;

use GraphQL\Error\Debug;
use GraphQL\Error\FormattedError;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Rebing\GraphQL\Error\AuthorizationError;
use Rebing\GraphQL\Error\ValidationError;
use GraphQL\Error as GraphQLError;

class Error
{
    /**
     * @param GraphQLError\Error $e
     *
     * @return mixed
     */
    public static function formatError(GraphQLError\Error $e)
    {
        $debug = config('app.debug') ? (Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE) : 0;

        $formatter = FormattedError::prepareFormatter(null, $debug);

        $error = $formatter($e);

        $previous = $e->getPrevious();

        if ($previous && $previous instanceof ValidationError) {
            $error['validation'] = $previous->getValidatorMessages();
        }

        if ($previous && $previous instanceof ValidationException) {
            $error['validation'] = $previous->getErrors();
        }

        return $error;
    }

    /**
     * @param array    $errors
     * @param callable $formatter
     *
     * @return array
     */
    public static function handleErrors(array $errors, callable $formatter)
    {
        $handler = app()->make(ExceptionHandler::class);

        foreach ($errors as $error) {
            // Try to unwrap exception
            $error = $error->getPrevious() ?: $error;

            // Don't report certain GraphQL errors
            if (
                $error instanceof ValidationException
                || $error instanceof ValidationError
                || $error instanceof AuthorizationError
                || !($error instanceof \Exception)
            ) {
                continue;
            }

            $handler->report($error);
        }

        return array_map($formatter, $errors);
    }
}
