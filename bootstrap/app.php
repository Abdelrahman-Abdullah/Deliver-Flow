<?php

use Illuminate\Container\Attributes\Auth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
            'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'setLocale' => \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->api([
            'setLocale',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
            $exceptions->render(function (Throwable $e, Request $request) {

                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'statsus' => false,
                        'message' => 'Unauthenticated. Please login.',
                        'error'   => null
                    ], 401);
                }

                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException ||
                     $e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {

                    return response()->json([
                        'statsus' => false,
                        'message' => 'You are not authorized to perform this action.',
                        'error'   => null
                    ], 403);
                }

                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $model = class_basename($e->getModel());
                    return response()->json([
                        'statsus' => false,
                        'message' => "{$model} not found.",
                        'error'   => null
                    ], 404);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->json([
                        'statsus' => false,
                        'message' => 'Route not found.',
                        'error'   => null
                    ], 404);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    return response()->json([
                        'statsus' => false,
                        'message' => 'Method not allowed.',
                        'error'   => null
                    ], 405);
                }

                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'statsus' => false,
                        'message' => 'Validation error.',
                        'error'   => $e->errors()
                    ], 422);
                }

                if ($e instanceof Illuminate\Http\Exceptions\ThrottleRequestsException) {
                    return response()->json([
                        'statsus' => false,
                        'message' => 'Too many requests. Please try again later.',
                        'error'   => null
                    ], 429);
                }

                // For other exceptions, return a generic error message in production, or the actual error message in development.
                return response()->json([
                    'status'  => false,
                    'message' => app()->isProduction()
                        ? 'Server error. Please try again later.'
                        : $e->getMessage(),
                    'errors'  => app()->isProduction()
                        ? null
                        : ['file' => $e->getFile(), 'line' => $e->getLine()],
                ], 500);
            });
        //
    })->create();
