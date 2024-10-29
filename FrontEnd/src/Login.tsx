import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { instance } from "./api";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { User } from "./interfaces/user";

const Schema = z.object({
  email: z.string().email("Invalid email address"),
  password: z.string().min(8, "Password must be at least 8 characters").max(20, "Password must be at most 20 characters"),
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
      localStorage.setItem("token", data.token);
      if (data.user.type === 1) {
        nav("/admin"); // Chuyển hướng admin đến dashboard admin
      } else {
        nav("/"); // Chuyển hướng người dùng đến dashboard người dùng
      }
    } catch (error) {
      console.error("Login error:", error);
      alert("Failed to log in. Please check your credentials.");
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
          <label htmlFor="password" className="form-label">Password</label>
          <input type="password" className="form-control" {...register("password", { required: true })} />
          {errors.password && <span className="text-danger">{errors.password.message}</span>}
        </div>
        <button className="btn btn-outline-secondary">Login</button>
        <button type="button" className="btn btn-link text-black" onClick={handleForgotPassword}>
          Forgot Password?
        </button>
      </form>
    </div>
  );
};

export default Login;
