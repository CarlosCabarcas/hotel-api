<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Exceptions\RoomLimitExceededException;
use App\Exceptions\InvalidRoomConfigurationException;
use App\Exceptions\DuplicateRoomConfigurationException;
use Illuminate\Http\JsonResponse;

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
        $exceptions->render(function (RoomLimitExceededException $e): JsonResponse {

                return response()->json([
                    'message' => $e->getMessage()
                ], 422);
            }
        );

        $exceptions->render(function (DuplicateRoomConfigurationException $e): JsonResponse {

                return response()->json([
                    'message' => $e->getMessage()
                ], 422);
            }
        );

        $exceptions->render(function (InvalidRoomConfigurationException $e): JsonResponse {

                return response()->json([
                    'message' => $e->getMessage()
                ], 422);
            }
        );
    })->create();
