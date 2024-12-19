<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    // ->withExceptions(fn(Exceptions $exceptions) => configureExceptionHandling($exceptions))
    ->withExceptions(fn(Exceptions $exceptions) => $exceptions)
    ->create();

/**
 * Configure exception handling for the application.
 * Sets up reportable exceptions and their handling logic.
 *
 * @param Exceptions $exceptions The exceptions configuration instance
 * @return void
 */
function configureExceptionHandling(Exceptions $exceptions): void
{
    $exceptions->reportable(function (Throwable $e) {
        if (shouldReportException($e)) {
            handleExceptionReporting($e);
        }
    });
}

/**
 * Determine if an exception should be reported based on the environment
 * and application configuration.
 *
 * @param Throwable $e The exception to evaluate
 * @return bool True if the exception should be reported
 */
function shouldReportException(Throwable $e): bool
{
    return app()->environment('local')
        || app()->runningUnitTests()
        || app()->make('exceptions')->shouldReport($e);
}

/**
 * Handle the reporting of an exception, either through FilamentExceptions
 * or the default Laravel logging mechanism.
 *
 * @param Throwable $e The exception to report
 * @return void
 */
function handleExceptionReporting(Throwable $e): void
{
    if (class_exists(\BezhanSalleh\FilamentExceptions\FilamentExceptions::class)) {
        \BezhanSalleh\FilamentExceptions\FilamentExceptions::report($e);
    } else {
        // Log the exception object only, as Laravel's logging system will handle the details
        Log::error($e);

        // If this is a POST request, log the request data with sensitive information filtered
        if (request() instanceof Request && request()->isMethod('post')) {
            $filteredData = filterSensitiveData(request()->all());
            Log::error('Request Data: ' . json_encode($filteredData));
        }
    }
}

/**
 * Filter sensitive information from request data before logging.
 *
 * @param array $data The request data to filter
 * @return array The filtered data
 */
function filterSensitiveData(array $data): array
{
    $sensitiveFields = ['password', 'password_confirmation', 'api_key', 'token', 'secret'];

    return collect($data)->map(function ($value, $key) use ($sensitiveFields) {
        if (in_array(strtolower($key), $sensitiveFields)) {
            return '********';
        }
        return $value;
    })->all();
}
