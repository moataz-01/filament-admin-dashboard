<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/**
 * Configure and initialize the Laravel application.
 *
 * @return \Illuminate\Foundation\Application
 */
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(fn(Middleware $middleware) => $middleware)
    ->withExceptions(fn(Exceptions $exceptions) => $exceptions)
    ->create();
