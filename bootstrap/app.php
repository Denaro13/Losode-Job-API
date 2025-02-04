<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
// use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->respond(function (Response $response) {
        //     if ($response->getStatusCode() === 404) {
        //         return back()->with([
        //             'message' => 'The page expired, please try again.',
        //         ]);
        //     }

        //     return $response;
        // });
        // $exceptions->render(function (NotFoundHttpException $e, Request $request) {
        //     // if ($request->is('api/*')) {
        //     //     return response()->json([
        //     //         'message' => 'Record not found.'
        //     //     ], 404);
        //     // }

        // });

        // Handle Missing Model Data
        // $exceptions->render(function (ModelNotFoundException $e, Request $request) {
        //     // if ($request->is('api/*')) {
        //     return response()->json([
        //         'message' => 'Requested resource not found.'
        //     ], 404);
        //     // }
        // });

        // Handle Authentication Errors
        // $exceptions->render(function (AuthenticationException $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'message' => 'Unauthorized access. Please log in.'
        //         ], 401);
        //     }
        // });

        // Handle Validation Errors
        // $exceptions->render(function (ValidationException $e, Request $request) {
        //     if ($request->is('api/*')) {
        //     return response()->json([
        //         'message' => 'Validation failed',
        //         'errors' => $e->errors()
        //     ], 422);
        //     }
        // });

        // Handle Generic Server Errors (Unexpected Errors)
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'An unexpected error occurred. Please try again later.'
                ], 500);
            }
        });
    })->create();
