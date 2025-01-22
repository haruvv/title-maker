<?php

// app/Http/Controllers/ScenarioController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScenarioController extends Controller
{
    public function getRandomScenario()
    {
        $scenarios = [
            '学園もの', '時間停止', '人妻', 'ファンタジー', '近親もの'
        ];
        $randomScenario = $scenarios[array_rand($scenarios)];
        return response()->json(['scenario' => $randomScenario]);
    }
}
