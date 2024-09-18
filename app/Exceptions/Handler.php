<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use League\OAuth2\Server\Exception\OAuthServerException;




use Illuminate\Http\Request;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }



    public function render($request, Throwable $exception)
    {
       
      
        // Check if the exception is an instance of ValidationException
        if ($exception instanceof ValidationException && ($request->expectsJson() || $request->isJson())) {
            // Return a JSON response with the validation errors
            return response()->json($exception->errors(), 422);
        }

        // Handle other exceptions as usual
        return parent::render($request, $exception);
    }

}
