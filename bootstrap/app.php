<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Traits\ResponseHelperTrait;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {


            // Check if the request is for an API route
            if ($request->is('api/*')) {

                return response()->json([
                    "success" => false,
                    'message' => 'Record not found.',
                    'data' => null,
                    'error' => null,
                ], 404);
            }

            // Handle web requests or other non-API requests (default Laravel 404 page)
            return response()->view('errors.404', [], 404);
        });
    })->create();
