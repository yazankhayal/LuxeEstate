<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:       __DIR__.'/../routes/web.php',
        api:       __DIR__.'/../routes/api.php',
        commands:  __DIR__.'/../routes/console.php',
        health:    '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Inertia middleware
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Named middleware aliases
        $middleware->alias([
            'admin'  => \App\Http\Middleware\AdminMiddleware::class,
            'locale' => \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Inertia\Inertia $inertia, \Symfony\Component\HttpKernel\Exception\HttpException $e) {
            $status = $e->getStatusCode();
            if (in_array($status, [403, 404, 500, 503])) {
                return \Inertia\Inertia::render("Errors/{$status}")
                    ->toResponse(request())
                    ->setStatusCode($status);
            }
        });
    })->create();
