<?php

namespace App\Http\Controllers;

use App\Services\DeepSeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TitleController extends Controller
{
    protected $deepSeekService;

    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

    public function evaluateTitle(Request $request)
    {
        $title = $request->input('title');
        
        if (empty($title)) {
            return response()->json([
                'error' => 'タイトルを入力してください'
            ], 400);
        }

        try {
            $evaluation = $this->deepSeekService->evaluateTitle($title);
            
            // 評価データの構造を確認
            if (!isset($evaluation['scores']) || !isset($evaluation['totalScore'])) {
                Log::error('Invalid evaluation data structure:', ['evaluation' => $evaluation]);
                throw new \Exception('Invalid evaluation data structure');
            }
            
            return response()->json([
                'success' => true,
                'data' => $evaluation
            ]);
            
        } catch (\Exception $e) {
            Log::error('Title Evaluation Error:', [
                'error' => $e->getMessage(),
                'title' => $title
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'タイトルの評価中にエラーが発生しました: ' . $e->getMessage()
            ], 500);
        }
    }
}