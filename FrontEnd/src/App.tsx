import { Route, Routes } from "react-router-dom";
import "./App.css";
import Register from "./Register";
import Login from "./Login";
import ForgotPassword from "./ForgotPassword";
import Home from "./Home";
import Dashboard from "./layout/Dashboard";
import Client from "./layout/Client";
import Auth from "./components/dashboard/Auth";

function App() {


  return (
    <>
      <Routes>
        <Route>

          {/* client */}
          <Route path="/" element={<Client />}>
            <Route index element={<Home />} />
            {/* <Route path="/product-detail/:id" element={<ProductDetail />} /> */}
            {/* <Route path="/shop" element={<Shop />} /> */}
            {/* <Route path="/cart" element={<Cart />} /> */}
            {/* <Route path="/checkout" element={<CheckOut />} /> */}
            <Route path="/forgot-password" element={<ForgotPassword />} />
            {/* <Route path="/thankyou" element={<ThankYou />} /> */}
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />
          </Route>



          {/* admin */}
          <Route path="/admin" element={<Dashboard />}>
            {/* <Route path="/admin/product" element={<ListProduct />} /> */}
            <Route path="/admin/auth" element={<Auth  />} />
          </Route>
        </Route>
      </Routes>
    </>
  );
}

export default App;
