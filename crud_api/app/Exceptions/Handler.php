<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    if ($request->is('api/*')) {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Model Not Found'
            ], 404);
        }

        return response()->json([
            'message' => $exception->getMessage(),
            'status' => 500
        ], 500);
    }

    return parent::render($request, $exception);
}

}
