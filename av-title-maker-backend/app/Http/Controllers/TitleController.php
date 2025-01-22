<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TitleController extends Controller
{
    public function evaluateTitle(Request $request)
    {
        $title = $request->input('title');

        // DeepSeek APIを呼び出して評価
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY'),
        ])->post('https://api.deepseek.com/v1/evaluate', [
            'title' => $title,
        ]);
        return response()->json(['score' => '300']);
        $score = $response->json()['score'];
        return response()->json(['score' => $score]);
    }
}