
import { Route, Routes } from "react-router-dom";
import "./App.css";
import Register from "./Register";
import Login from "./Login";
import ForgotPassword from "./ForgotPassword";
import Home from "./Home";
import Dashboard from "./Dashboard";





function App() {
  return (
    <>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/admin" element={<Dashboard />} />
        <Route path="/register" element={<Register />} />
        <Route path="/login" element={<Login />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        
      </Routes>
    </>
  );
}

export default App;
