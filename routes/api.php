<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Resources\JobResource;
use App\Models\Job;


Route::prefix('v1')->group(
    function () {
        //Auth Routes
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::get('/jobs', [JobController::class, 'index']);
        Route::post('/jobs/{job_id}/apply', [ApplicationController::class, 'store']);
        Route::get('/jobs/{job_id}', [JobController::class, 'show']);
        Route::get('/my/jobs', [JobController::class, 'handleJobs']);

        Route::middleware('auth:sanctum')->group(
            function () {
                Route::post('/logout', [AuthController::class, 'logout']);
                Route::get('/user', [UserController::class, 'show']);
                Route::post('/my/jobs', [JobController::class, 'store']);
                // Route::get('/my/jobs', [UserController::class, 'getUserJobs']);
                Route::patch('/my/jobs/{job_id}', [JobController::class, 'update']);
                Route::delete('/my/jobs/{job_id}', [JobController::class, 'destroy']);
            }
        );
    }
);
