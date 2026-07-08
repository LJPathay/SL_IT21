<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'guest' => \App\Http\Middleware\Guest::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'throttle.login' => \App\Http\Middleware\RateLimitLogin::class,
            'bot.detection' => \App\Http\Middleware\BotDetection::class,
            'throttle.api' => \App\Http\Middleware\ApiThrottle::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\LogSecurityEvent::class,
            \App\Http\Middleware\PasswordResetRequired::class,
<<<<<<< HEAD
            \App\Http\Middleware\BotDetection::class,
=======
            \App\Http\Middleware\SecurityHeaders::class,
>>>>>>> f992483d7ae2f99291950dc1784331c65a2c0745
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
