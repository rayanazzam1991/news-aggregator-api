<?php

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\Result;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {})
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            if ($request->expectsJson()) {
                return ApiResponseHelper::sendResponse(new Result(null,
                    null, 'Unauthenticated.', false, Response::HTTP_UNAUTHORIZED));
            }

            return back(Response::HTTP_UNPROCESSABLE_ENTITY);
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {

            if ($request->is('api/*')) {
                return ApiResponseHelper::sendResponse(new Result(null,
                    null, 'Record not found.', false,
                    Response::HTTP_NOT_FOUND));
            }

            return back(Response::HTTP_UNPROCESSABLE_ENTITY);
        });
    })->create();
