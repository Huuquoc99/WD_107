import { zodResolver } from "@hookform/resolvers/zod";
import React, { useState } from "react";
import { z } from "zod";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { instance } from "./api";

const EmailSchema = z.object({
  email: z.string().email("Invalid email format").nonempty("Email is required"),
});

const CodeSchema = z.object({
  email: z.string().email("Invalid email format").nonempty("Email is required"),
  code: z.string().min(1, "Code is required"),
});

const ResetPasswordSchema = z
  .object({
    email: z
      .string()
      .email("Invalid email format")
      .nonempty("Email is required"),
    password: z
      .string()
      .min(8, "Password must be at least 8 characters")
      .max(20, "Password must not exceed 20 characters"),
    password_confirmation: z
      .string()
      .min(8, "Password must be at least 8 characters")
      .max(20, "Password must not exceed 20 characters"),
  })
  .refine((data) => data.password === data.password_confirmation, {
    message: "Passwords don't match",
    path: ["password_confirmation"],
  });

const ForgotPassword = () => {
  const nav = useNavigate();
  const [step, setStep] = useState(1); // Bước hiện tại
  const [email, setEmail] = useState(""); // Lưu email đã nhập
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm({
    resolver: zodResolver(
      step === 1 ? EmailSchema : step === 2 ? CodeSchema : ResetPasswordSchema
    ),
  });

  const onSubmit = async (data) => {
    if (step === 1) {
      try {
        const response = await instance.post("/forgot-password", {
          email: data.email,
        });
        alert(response.data.message || "Verification code sent to your email.");
        setStep(2); // Chuyển sang bước 2 để nhập mã xác minh
        setEmail(data.email); // Lưu email để sử dụng trong bước 3
      } catch (error) {
        console.error("Error sending verification code:", error);
        alert("Failed to send verification code. Please try again.");
      }
    } else if (step === 2) {
      try {
        const response = await instance.post("/verify-code", {
          email,
          code: data.code,
        });
        alert(
          response.data.message ||
            "Code verified! You can now reset your password."
        );
        setStep(3); // Chuyển sang bước 3 để đặt lại mật khẩu
      } catch (error) {
        console.error("Error verifying code:", error);
        alert("Invalid verification code. Please try again.");
      }
    } else {
      try {
        const response = await instance.post("/reset-password", {
          email,
          password: data.password,
        });
        alert(response.data.message || "Password reset successfully!");
        nav("/login"); // Chuyển hướng về trang login sau khi reset thành công
      } catch (error) {
        console.error("Error resetting password:", error);
        alert("Failed to reset password. Please try again.");
      }
    }
  };

  return (
    <div>
      <form onSubmit={handleSubmit(onSubmit)}>
        {step === 1 && (
          <>
            <div className="form-group">
              <label htmlFor="email" className="form-label">
                Email
              </label>
              <input
                type="email"
                className="form-control"
                {...register("email")}
              />
              {errors.email && (
                <span className="text-danger">{errors.email.message}</span>
              )}
            </div>
            <button className="btn btn-outline-secondary" type="submit">
              Send Verification Code
            </button>
          </>
        )}
        {step === 2 && (
          <>
            <div className="form-group">
              <label htmlFor="code" className="form-label">
                Verification Code
              </label>
              <input
                type="text"
                className="form-control"
                {...register("code")}
              />
              {errors.code && (
                <span className="text-danger">{errors.code.message}</span>
              )}
            </div>
            <button className="btn btn-outline-secondary" type="submit">
              Verify Code
            </button>
          </>
        )}
        {step === 3 && (
          <>
            <div className="form-group">
              <label htmlFor="password" className="form-label">
                New Password
              </label>
              <input
                type="password"
                className="form-control"
                {...register("password")}
              />
              {errors.password && (
                <span className="text-danger">{errors.password.message}</span>
              )}
            </div>
            <div className="form-group">
              <label htmlFor="password_confirmation" className="form-label">
                Confirm Password
              </label>
              <input
                type="password"
                className="form-control"
                {...register("password_confirmation")}
              />
              {errors.password_confirmation && (
                <span className="text-danger">
                  {errors.password_confirmation.message}
                </span>
              )}
            </div>
            <button className="btn btn-outline-secondary" type="submit">
              Reset Password
            </button>
          </>
        )}
      </form>
    </div>
  );
};

export default ForgotPassword;
