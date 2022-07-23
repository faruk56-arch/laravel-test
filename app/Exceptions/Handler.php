<?php

namespace App\Exceptions;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     * @codeCoverageIgnore
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        if (app()->environment('production')) {
            Bugsnag::notifyException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     * @codeCoverageIgnore
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if (get_class($exception) == "Illuminate\Database\Eloquent\ModelNotFoundException") {
            return response()->json(['error' => 'Model not found'], 400);
        }
        if (get_class($exception) == "Doctrine\DBAL\Driver\PDOException") {
            return response()->json(['error' => 'Problem with database connection'], 500);
        }

        return parent::render($request, $exception);
    }
}
