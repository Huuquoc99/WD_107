import { zodResolver } from "@hookform/resolvers/zod";
import React from "react";
import { z } from "zod";
import { instance } from "./api";
import { useForm } from "react-hook-form";
import { useNavigate, useParams } from "react-router-dom";

const Schema = z.object({
    password: z.string().min(8, "Password must be at least 8 characters").max(20, "Password must be at most 20 characters"),
    confirmPassword: z.string().min(1, "Please confirm your password")
}).refine(data => data.password === data.confirmPassword, {
    message: "Passwords don't match",
    path: ["confirmPassword"],
});

const ResetPassword = () => {
    const { token } = useParams(); // Nhận token từ URL
    const nav = useNavigate();
    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm({
        resolver: zodResolver(Schema),
    });

    const onSubmit = async (data) => {
        try {
            const response = await instance.post('/reset-password', {
                ...data,
                token, // Gửi token cùng với dữ liệu
            });
            alert(response.data.status); // Hiển thị thông báo thành công
            nav("/login"); // Chuyển hướng về trang login sau khi đặt lại thành công
        } catch (error) {
            console.error('Error resetting password:', error);
            // Xử lý lỗi (có thể hiển thị thông báo cho người dùng)
        }
    };

    return (
        <div>
            <form onSubmit={handleSubmit(onSubmit)}>
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
            </form>
        </div>
    );
};

export default ResetPassword;
