import React, { useEffect, useState } from 'react';
import { Route, Routes } from 'react-router-dom';
import Register from './Register';
import Login from './Login';
import ForgotPassword from './ForgotPassword';
import Home from './Home';
import Dashboard from './layout/Dashboard';
import Client from './layout/Client';
import Auth from './components/dashboard/Auth';
import { instance } from './api';

function App() {
  // const [users, setUsers] = useState([]);

  // useEffect(() => {
  //   const fetchData = async () => {
  //     try {
  //       const {data} = await instance.get('admin/users');
  //       setUsers(data.data);
  //     } catch (error) {
  //       console.error('Error fetching data: ', error);
  //     }
  //   };

  //   fetchData();
  // }, []);

  return (
    <Routes>
      {/* Client routes */}
      <Route path="/" element={<Client />}>
        <Route index element={<Home />} />
        <Route path="forgot-password" element={<ForgotPassword />} />
        <Route path="login" element={<Login />} />
        <Route path="register" element={<Register />} />
      </Route>

      {/* Admin routes */}
      <Route path="/admin" element={<Dashboard />}>
        <Route path="auth" element={<Auth  />} />
      </Route>
    </Routes>
  );
}

export default App;