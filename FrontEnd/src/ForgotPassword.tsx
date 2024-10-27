import { zodResolver } from "@hookform/resolvers/zod";
import React, { useState } from "react";
import { z } from "zod";
import { instance } from "./api";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";

const EmailSchema = z.object({
    email: z.string().email("Invalid email address"),
});

const ResetPasswordSchema = z.object({
    token: z.string(),
    email: z.string().email("Invalid email address"),
    password: z.string().min(8, "Password must be at least 8 characters").max(20, "Password must be at most 20 characters"),
    confirmPassword: z.string().min(1, "Please confirm your password")
}).refine(data => data.password === data.confirmPassword, {
    message: "Passwords don't match",
    path: ["confirmPassword"],
});

const ForgotPassword = () => {
    const nav = useNavigate();
    const [step, setStep] = useState(1);
    const [token, setToken] = useState('');
    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm({
        resolver: zodResolver(step === 1 ? EmailSchema : ResetPasswordSchema),
    });

    const onSubmit = async (data) => {
        if (step === 1) {
            // Gửi email
            try {
                const response = await instance.post('/forgot-password', data);
                alert(response.data.status); // Hiển thị thông báo thành công
                setStep(2); // Chuyển sang bước nhập mã xác nhận
                setToken(response.data.token); // Lưu token
            } catch (error) {
                console.error('Error sending reset link:', error);
                // Xử lý lỗi
            }
        } else {
            // Đặt lại mật khẩu
            try {
                const response = await instance.post('/reset-password', { ...data, token });
                alert(response.data.status); // Hiển thị thông báo thành công
                nav("/login"); // Chuyển hướng về trang login sau khi gửi thành công
            } catch (error) {
                console.error('Error resetting password:', error);
                // Xử lý lỗi
            }
        }
    };

    return (
        <div>
            <form onSubmit={handleSubmit(onSubmit)}>
                {step === 1 ? (
                    <>
                        <div className="form-group">
                            <label htmlFor="email" className="form-label">Email</label>
                            <input
                                type="email"
                                className="form-control"
                                {...register("email")}
                            />
                            {errors.email && <span className="text-danger">{errors.email.message}</span>}
                        </div>
                        <button className="btn btn-outline-secondary">Send Reset Link</button>
                    </>
                ) : (
                    <>
                        <div className="form-group">
                            <label htmlFor="token" className="form-label">Verification Token</label>
                            <input
                                type="text"
                                className="form-control"
                                {...register("token")}
                                defaultValue={token} // Đặt token
                            />
                            {errors.token && <span className="text-danger">{errors.token.message}</span>}
                        </div>
                        <div className="form-group">
                            <label htmlFor="password" className="form-label">New Password</label>
                            <input
                                type="password"
                                className="form-control"
                                {...register("password")}
                            />
                            {errors.password && <span className="text-danger">{errors.password.message}</span>}
                        </div>
                        <div className="form-group">
                            <label htmlFor="confirmPassword" className="form-label">Confirm Password</label>
                            <input
                                type="password"
                                className="form-control"
                                {...register("confirmPassword")}
                            />
                            {errors.confirmPassword && <span className="text-danger">{errors.confirmPassword.message}</span>}
                        </div>
                        <button className="btn btn-outline-secondary">Reset Password</button>
                    </>
                )}
            </form>
        </div>
    );
};

export default ForgotPassword;
