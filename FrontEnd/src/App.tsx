import { useState } from "react";

import "./App.css";

import { Route, Routes } from "react-router-dom";
import RegisterForm from "./component/RegisterForm";
import LoginForm from "./component/LoginForm";
import Dashboard from "./component/Dashboard";



function App() {
  return (
    <>
        <Routes>
        <Route path="/register" element={<RegisterForm />} />
        <Route path="/dashboard" element={<Dashboard />} />
      </Routes>
    </>
  );
}

export default App;
