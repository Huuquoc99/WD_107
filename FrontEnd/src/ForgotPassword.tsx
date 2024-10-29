import { zodResolver } from "@hookform/resolvers/zod";
import React, { useState } from "react";
import { z } from "zod";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { instance } from "./api"; // Thay đổi đường dẫn nếu cần

const EmailSchema = z.object({
  email: z.string().email("Email không hợp lệ"),
});

const CodeSchema = z.object({
  email: z.string().email("Email không hợp lệ"),
  code: z.string().min(6, "Mã xác minh phải có ít nhất 6 ký tự"), // Điều chỉnh độ dài mã xác minh nếu cần
});

const ResetPasswordSchema = z
  .object({
    email: z.string().email("Email không hợp lệ"),
    password: z.string().min(8, "Mật khẩu phải có ít nhất 8 ký tự"),
    confirmPassword: z.string().min(8, "Xác nhận mật khẩu là bắt buộc"),
  })
  .refine((data) => data.password === data.confirmPassword, {
    message: "Mật khẩu và xác nhận mật khẩu không khớp",
    path: ["confirmPassword"],
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
      step === 1
        ? EmailSchema
        : step === 2
        ? CodeSchema
        : ResetPasswordSchema
    ),
  });

  const onSubmit = async (data) => {
    if (step === 1) {
      try {
        const response = await instance.post("/forgot-password", {
          email: data.email,
        });
        alert(response.data.status || "Mã xác minh đã được gửi đến email của bạn.");
        setStep(2); // Chuyển sang bước nhập mã xác minh
        setEmail(data.email); // Lưu email để sử dụng sau này
      } catch (error) {
        console.error("Error sending verification code:", error);
        alert(error.response?.data?.error || "Gửi mã xác minh không thành công. Vui lòng thử lại.");
      }
    } else if (step === 2) {
      try {
        const response = await instance.post("/verify-code", {
          email,
          code: data.code,
        });
        alert(response.data.status || "Mã xác minh hợp lệ! Bạn có thể đặt lại mật khẩu.");
        setStep(3); // Chuyển sang bước đặt lại mật khẩu
      } catch (error) {
        console.error("Error verifying code:", error);
        alert(error.response?.data?.error || "Mã xác minh không hợp lệ. Vui lòng thử lại.");
      }
    } else {
      try {
        const response = await instance.post("/reset-password", {
          email,
          password: data.password,
        });
        alert(response.data.status || "Mật khẩu đã được đặt lại thành công!");
        nav("/login"); // Chuyển hướng người dùng về trang đăng nhập
      } catch (error) {
        console.error("Error resetting password:", error);
        alert(error.response?.data?.error || "Đặt lại mật khẩu không thành công. Vui lòng thử lại.");
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
              Gửi Mã Xác Minh
            </button>
          </>
        )}
        {step === 2 && (
          <>
            <div className="form-group">
              <label htmlFor="code" className="form-label">
                Mã Xác Minh
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
              Xác Minh Mã
            </button>
          </>
        )}
        {step === 3 && (
          <>
            <div className="form-group">
              <label htmlFor="password" className="form-label">
                Mật Khẩu Mới
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
              <label htmlFor="confirmPassword" className="form-label">
                Xác Nhận Mật Khẩu
              </label>
              <input
                type="password"
                className="form-control"
                {...register("confirmPassword")}
              />
              {errors.confirmPassword && (
                <span className="text-danger">{errors.confirmPassword.message}</span>
              )}
            </div>
            <button className="btn btn-outline-secondary" type="submit">
              Đặt Lại Mật Khẩu
            </button>
          </>
        )}
      </form>
    </div>
  );
};

export default ForgotPassword;
