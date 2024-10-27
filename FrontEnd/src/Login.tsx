import { zodResolver } from "@hookform/resolvers/zod";
import React from "react";
import { z } from "zod";
import { instance } from "./api";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { User } from "./interfaces/user";

const Schema = z.object({
  email: z.string().email("Invalid email address"),
  password: z
    .string()
    .min(8, "Password must be at least 8 characters")
    .max(20, "Password must be at most 20 characters"),
});

const Login = () => {
  const nav = useNavigate();
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<User>({
    resolver: zodResolver(Schema),
  });

  const onSubmit = async (user) => {
    const { data } = await instance.post(`login`, user);
    // Lưu token vào localStorage (nếu có)
    localStorage.setItem("token", data.token);
    nav("/dashboard"); // Chuyển hướng đến dashboard sau khi đăng nhập thành công
  };

  const handleForgotPassword = () => {
    nav("/forgot-password"); // Chuyển hướng đến trang quên mật khẩu
  };

  return (
    <div>
      <form onSubmit={handleSubmit(onSubmit)}>
        <div className="form-group">
          <label htmlFor="email" className="form-label">
            Email
          </label>
          <input
            type="email"
            className="form-control"
            {...register("email", { required: true })}
          />
          {errors.email && (
            <span className="text-danger">{errors.email.message}</span>
          )}
        </div>
        <div className="form-group">
          <label htmlFor="password" className="form-label">
            Password
          </label>
          <input
            type="password"
            className="form-control"
            {...register("password", { required: true })}
          />
          {errors.password && (
            <span className="text-danger">{errors.password.message}</span>
          )}
        </div>
        <button className="btn btn-outline-secondary">Login</button>
        <button
          type="button"
          className="btn btn-link"
          onClick={handleForgotPassword}
        >
          Forgot Password?
        </button>
      </form>
    </div>
  );
};

export default Login;
