<?php

namespace App\Exceptions;

use Exception;
use Google\Cloud\ErrorReporting\Bootstrap;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

//use Bugsnag\BugsnagLaravel\BugsnagExceptionHandler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
     public function report(Exception $exception)
 {
     if (isset($_SERVER['GAE_SERVICE'])) {
         Bootstrap::init();
         Bootstrap::exceptionHandler($exception);
     } else {
         parent::report($exception);
     }
 }

 public function report(Exception $exception)
{
    if (app()->bound('sentry') && $this->shouldReport($exception)){
        app('sentry')->captureException($exception);
    }

    parent::report($exception);
}



    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return parent::render($request, $e);
    }
}
