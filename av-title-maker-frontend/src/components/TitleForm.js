import React, { useState } from "react";
import axios from "axios";

const TitleForm = () => {
  const [title, setTitle] = useState("");
  const [evaluation, setEvaluation] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const response = await axios.post("http://localhost:8000/api/evaluate", {
        title,
      });

      console.log("API Response:", response.data);

      if (response.data.success) {
        setEvaluation(response.data.data);
      } else {
        setError(response.data.error || "エラーが発生しました");
      }
    } catch (error) {
      console.error("API Error:", error);
      setError(error.response?.data?.error || "APIリクエストに失敗しました");
    } finally {
      setLoading(false);
    }
  };

  const getScoreColor = (score) => {
    if (score >= 8) return "text-green-600";
    if (score >= 6) return "text-blue-600";
    if (score >= 4) return "text-yellow-600";
    return "text-red-600";
  };

  return (
    <div className="max-w-2xl mx-auto p-4">
      <form onSubmit={handleSubmit} className="space-y-4">
        <input
          type="text"
          value={title}
          onChange={(e) => setTitle(e.target.value)}
          placeholder="タイトルを入力してください"
          className="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
        />
        <button
          type="submit"
          className="w-full p-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition-colors disabled:bg-blue-300"
          disabled={loading}
        >
          {loading ? "評価中..." : "タイトルを評価する"}
        </button>
      </form>

      {error && (
        <div className="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
          {error}
        </div>
      )}

      {/* {evaluation && (
        <div className="mt-4 p-4 bg-gray-100 rounded-lg">
          <pre className="text-sm">{JSON.stringify(evaluation, null, 2)}</pre>
        </div>
      )} */}

      {evaluation && evaluation.scores && (
        <div className="mt-8 bg-white rounded-lg shadow-lg p-6">
          <h2 className="text-2xl font-bold mb-6 text-gray-800">評価結果</h2>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            {Object.entries(evaluation.scores).map(([key, value]) => (
              <div key={key} className="bg-gray-50 p-4 rounded-lg">
                <div className="text-gray-600 text-sm mb-1">
                  {key === "originality" && "独創性"}
                  {key === "impact" && "インパクト"}
                  {key === "marketability" && "市場性"}
                  {key === "wordChoice" && "ワードチョイス"}
                  {key === "situation" && "シチュエーション"}
                  {key === "eroticism" && "エロさ"}
                </div>
                <div className={`text-2xl font-bold ${getScoreColor(value)}`}>
                  {value}/10
                </div>
              </div>
            ))}
          </div>

          <div className="bg-gray-800 text-white p-4 rounded-lg mb-6">
            <div className="text-sm mb-1">総合評価</div>
            <div className="text-3xl font-bold">{evaluation.totalScore}/60</div>
          </div>

          <div className="mb-6">
            <h3 className="text-lg font-semibold mb-2 text-gray-800">
              詳細フィードバック
            </h3>
            <p className="text-gray-600 bg-gray-50 p-4 rounded-lg">
              {evaluation.feedback}
            </p>
          </div>

          {evaluation.improvements && (
            <div>
              <h3 className="text-lg font-semibold mb-2 text-gray-800">
                改善のポイント
              </h3>
              <ul className="space-y-2">
                {evaluation.improvements.map((improvement, index) => (
                  <li
                    key={index}
                    className="flex items-start gap-2 text-gray-600"
                  >
                    <span className="text-blue-500 mt-1">•</span>
                    {improvement}
                  </li>
                ))}
              </ul>
            </div>
          )}
        </div>
      )}
    </div>
  );
};

export default TitleForm;
