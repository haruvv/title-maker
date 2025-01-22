<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('DEEPSEEK_API_KEY');
        $this->baseUrl = 'https://api.deepseek.com/v1';
    }

    public function evaluateTitle(string $title)
    {
        $systemPrompt = <<<EOT
あなたはAVタイトルの評価を、エロさも加味してアドバイスする専門家です。以下のポイントで、堅苦しくない感じで評価してください：

■評価のポイント
1. 独創性：ありきたりじゃないか？他の作品と被ってない？
2. インパクト：パッと見で目を引く？ドキッとさせる？
3. 販売適性：売れそう？検索されそう？
4. 言葉選び：キャッチー？わかりやすい？
5. シチュエーション：今っぽい？旬なネタ使ってる？
6. エロさ：ドキドキする？興奮を誘う？

■出力形式（JSON厳守）：
{
    "scores": {
        "originality": [1-10],
        "impact": [1-10],
        "marketability": [1-10],
        "wordChoice": [1-10],
        "situation": [1-10],
        "eroticism": [1-10]  // 新たに追加
    },
    "totalScore": [合計],
    "feedback": "全体的な感想を緩い感じでコメント",
    "improvements": [
        "改善点1（緩い感じで）",
        "改善点2（緩い感じで）",
        "改善点3（緩い感じで）"
    ]
}

■コメントの例：
- 「エロさ的にはなかなかイケてるんじゃない？」
- 「もうちょいエッチな言葉を散りばめてみたら？」
- 「シチュエーションをもっと具体的にすると興奮度アップ！」
- 「サブタイトルでエロさをアピールするのもアリかも！」

※注意事項
- 堅苦しい表現は禁止
- 友達にアドバイスするような感じで
- ポジティブな雰囲気を保つ
EOT;

        try {
            $response = $this->client->post($this->baseUrl . '/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt
                        ],
                        [
                            'role' => 'user',
                            'content' => "以下のタイトルを評価してください：「{$title}」"
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 1000,
                    'response_format' => ['type' => 'json_object']  // JSON形式を強制
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            Log::info('DeepSeek Raw Response:', ['response' => $result]);

            // レスポンスからコンテンツを取得し、JSONとしてパース
            if (isset($result['choices'][0]['message']['content'])) {
                $content = $result['choices'][0]['message']['content'];
                $evaluationData = json_decode($content, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $evaluationData;
                } else {
                    Log::error('JSON Parse Error:', ['error' => json_last_error_msg(), 'content' => $content]);
                    throw new \Exception('Invalid JSON response from API');
                }
            }

            throw new \Exception('Unexpected API response format');

        } catch (GuzzleException $e) {
            Log::error('DeepSeek API Error:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}