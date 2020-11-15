<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     */
    public function render($request, Throwable $e)
    {
        if ($request->wantsJson()) {
            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json(['message' => 'Method not allowed', 'errors' => []], 405);
            } else if ($e instanceof NotFoundHttpException) {
                return response()->json(['message' => 'Route not found', 'errors' => []], 404);
            } else if ($e instanceof ModelNotFoundException) {
                return response()->json(['message' => 'Resource not found', 'errors' => []], 404);
            }
        }

        return parent::render($request, $e);
    }
}
