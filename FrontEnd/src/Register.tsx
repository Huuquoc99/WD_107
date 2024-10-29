import { zodResolver } from "@hookform/resolvers/zod";
import React from "react";
import { z } from "zod";
import { instance } from "./api";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { User } from "./interfaces/user";


const Schema = z.object({
    email: z.string().email(),
    password: z.string().min(8).max(20),
    password_confirmation: z.string().min(8).max(20),
    name: z.string(),
}).refine(data => data.password === data.password_confirmation, {
    message: "Passwords don't match",
    path: ["password_confirmation"], // Chỉ định trường để hiển thị lỗi
  });
const Register = () => {
  const nav = useNavigate();
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<User>({
    resolver: zodResolver(Schema),
  });
  const onSubmit = async (user: User) => {
    const { data } = await instance.post(`register`, user);
    nav("/login");
  };
  return (
    <div className="my-[110px] mx-auto max-w-[600px] p-8 sign-box backgound-two">
       <h1 className="mb-4 text-xl text-center font-bold text-[#4E7C32]">
          ĐĂNG KÝ TÀI KHOẢN
        </h1>
      <form onSubmit={handleSubmit(onSubmit)}>
        <div className="form-group">
          <label htmlFor="name" className="form-label">
            Name
          </label>
          <input
            type="text"
            className="form-control"
            {...register("name", { required: true })}
          />
          {errors.name && (
            <span className="text-danger">{errors.name.message}</span>
          )}
        </div>
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
        <div className="form-group">
          <label htmlFor="password" className="form-label">
            Confirm Password
          </label>
          <input
            type="password"
            className="form-control"
            {...register("password_confirmation", { required: true })}
          />
          {errors.password_confirmation && (
            <span className="text-danger">{errors.password_confirmation.message}</span>
          )}
        </div>

        <button className="btn btn-outline-secondary">ADD</button>
      </form>
      <div className="text-center mt-4">
          Bạn đã có tài khoản?{" "}
          <a href="/login" className="text-[#427c1d] underline">
            Đăng nhập
          </a>
        </div>
    </div>
  );
};

export default Register;
