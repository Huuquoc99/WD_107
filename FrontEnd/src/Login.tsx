import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { instance } from "./api";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { User } from "./interfaces/user";

const Schema = z.object({
  email: z.string().email("Địa chỉ email không hợp lệ"),
  password: z.string().min(8, "Mật khẩu phải có ít nhất 8 ký tự").max(20, "Mật khẩu tối đa 20 ký tự"),
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

  const onSubmit = async (user: User) => {
    try {
      const { data } = await instance.post(`login`, user);
      localStorage.setItem("token", data.token); // Lưu token vào localStorage
      if (data.user.type === 1) {
        nav("/admin"); // Chuyển hướng admin đến dashboard admin
      } else {
        nav("/"); // Chuyển hướng người dùng đến dashboard người dùng
      }
    } catch (error: any) { // Sử dụng any để lấy thông tin lỗi chi tiết
      console.error("Login error:", error);
      alert(error.response?.data.errors.email?.[0] || "Đăng nhập thất bại. Vui lòng kiểm tra thông tin đăng nhập."); // Hiển thị thông báo lỗi từ server
    }
  };

  const handleForgotPassword = () => {
    nav("/forgot-password");
  };

  return (
    <div className="my-[110px] mx-auto max-w-[600px] p-8 sign-box backgound-two">
      <h1 className="mb-4 text-xl text-center font-bold text-[#4E7C32]">
        ĐĂNG NHẬP TÀI KHOẢN
      </h1>
      <form onSubmit={handleSubmit(onSubmit)}>
        <div className="form-group">
          <label htmlFor="email" className="form-label">Email</label>
          <input type="email" className="form-control" {...register("email", { required: true })} />
          {errors.email && <span className="text-danger">{errors.email.message}</span>}
        </div>
        <div className="form-group">
          <label htmlFor="password" className="form-label">Mật khẩu</label>
          <input type="password" className="form-control" {...register("password", { required: true })} />
          {errors.password && <span className="text-danger">{errors.password.message}</span>}
        </div>
        <button type="submit" className="btn btn-outline-secondary">Đăng nhập</button>
        <button type="button" className="btn btn-link text-black" onClick={handleForgotPassword}>
          Quên mật khẩu?
        </button>
      </form>
    </div>
  );
};

export default Login;
