<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Return JSON 401 for unauthenticated AJAX/JSON requests (e.g. spoonacular/save)
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success'  => false,
                    'message'  => 'You must be logged in to perform this action.',
                    'redirect' => route('login'),
                ], 401);
            }
        });
    })->create();
