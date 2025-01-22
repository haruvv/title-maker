<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\TitleController;

Route::get('/scenario', [ScenarioController::class, 'getRandomScenario']);
Route::post('/evaluate', [TitleController::class, 'evaluateTitle']);