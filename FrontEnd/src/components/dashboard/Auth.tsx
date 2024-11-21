import React, { useEffect, useState } from "react";
import { instance } from "../../api";
import { User } from "../../interfaces/user";

const Auth = () => {
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [isAdmin, setIsAdmin] = useState<boolean>(false);

  useEffect(() => {
    const type = localStorage.getItem("userType"); // Lấy vai trò từ localStorage
    setIsAdmin(type === "1"); // Kiểm tra nếu vai trò là admin

    const fetchUsers = async () => {
      try {
        const token = localStorage.getItem("token");
        const response = await instance.get("admin/users", {
          headers: { Authorization: `Bearer ${token}` },
        });
        setUsers(response.data);
      } catch (err) {
        setError("Không thể tải danh sách người dùng");
      } finally {
        setLoading(false);
      }
    };
    fetchUsers();
  }, []);

  if (loading) return <div>Đang tải...</div>;
  if (error) return <div>{error}</div>;

  return (
    <div>
      <h1>Quản Lý Tài Khoản</h1>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Vai Trò</th>
            {isAdmin && <th>Hành Động</th>} {/* Hiển thị cột hành động nếu là admin */}
          </tr>
        </thead>
        <tbody>
          {users.map((user) => (
            <tr key={user.id}>
              <td>{user.id}</td>
              <td>{user.name}</td>
              <td>{user.email}</td>
              <td>{user.type === 1 ? "Admin" : "Người Dùng"}</td>
              {isAdmin && (
                <td>
                  <button>Chỉnh sửa</button>
                </td>
              )}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default Auth;
