import React, { useState } from "react";
import axios from "axios";

const TitleForm = () => {
  const [title, setTitle] = useState("");
  const [score, setScore] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post("http://localhost:8000/api/evaluate", {
        title,
      });
      setScore(response.data.score);
    } catch (error) {
      console.error(error);
    }
  };

  return (
    <div className="mb-4">
      <form onSubmit={handleSubmit} className="space-y-4">
        <input
          type="text"
          value={title}
          onChange={(e) => setTitle(e.target.value)}
          placeholder="タイトルを入力"
          className="p-2 border border-gray-300 rounded"
        />
        <button type="submit" className="p-2 bg-blue-500 text-white rounded">
          評価する
        </button>
      </form>
      {score && <p className="mt-4">スコア: {score}</p>}
    </div>
  );
};

export default TitleForm;
