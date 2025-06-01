<?php

use App\Helpers\ResponseHelper;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\IsAdmin;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Illuminate\Console\Scheduling\Schedule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_admin' => IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e) {
            ResponseHelper::sendError("Data Not Found", code: Response::HTTP_NOT_FOUND);
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            ResponseHelper::sendError("Data Not Found", code: Response::HTTP_NOT_FOUND);
        });
    })->withSchedule(function (Schedule $schedule) {
        $schedule->command('orders:cancel-old')->everyTwoMinutes();
    })->create();
