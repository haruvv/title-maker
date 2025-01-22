import React from "react";
import Scenario from "./components/Scenario";
import TitleForm from "./components/TitleForm";

const App = () => {
  return (
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">
        AIが採点するAVタイトルメーカー
      </h1>
      <Scenario />
      <TitleForm />
    </div>
  );
};

export default App;
