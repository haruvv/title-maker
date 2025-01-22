import React, { useEffect, useState } from "react";
import axios from "axios";

const Scenario = () => {
  const [scenario, setScenario] = useState("");

  useEffect(() => {
    axios
      .get("http://localhost:8000/api/scenario")
      .then((response) => setScenario(response.data.scenario))
      .catch((error) => console.error(error));
  }, []);

  return (
    <div className="mb-4">
      <h2 className="text-xl font-semibold">シチュエーション: {scenario}</h2>
    </div>
  );
};

export default Scenario;
