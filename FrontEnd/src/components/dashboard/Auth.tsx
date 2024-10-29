import React, { useEffect, useState } from 'react';
import { instance } from '../../api';
import { User } from '../../interfaces/user';

const Auth = () => {
    const [users, setUsers] = useState<User[]>([]);
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchUsers = async () => {
            try {
                // Lấy token từ localStorage (hoặc nơi bạn lưu trữ)
                const token = localStorage.getItem('token'); 

                // Gửi yêu cầu với token trong header
                const response = await instance.get('admin/user', {
                    headers: {
                        'Authorization': `Bearer ${token}`, // Gửi token vào header
                    },
                });

                setUsers(response.data);
            } catch (err) {
                setError('Không thể tải danh sách người dùng');
            } finally {
                setLoading(false);
            }
        };

        fetchUsers();
    }, []);

    const handleRoleChange = async (userId: number, newType: number) => {
        try {
            const token = localStorage.getItem('token'); // Lấy token để cập nhật quyền
            await instance.put(`/users/${userId}`, { type: newType }, {
                headers: {
                    'Authorization': `Bearer ${token}`, // Gửi token vào header
                },
            });
            setUsers(prevUsers =>
                prevUsers.map(user =>
                    user.id === userId ? { ...user, type: newType } : user
                )
            );
        } catch (err) {
            setError('Không thể cập nhật quyền truy cập');
        }
    };

    if (loading) {
        return <div>Đang tải...</div>;
    }

    if (error) {
        return <div>{error}</div>;
    }

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
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    {users.map(user => (
                        <tr key={user.id}>
                            <td>{user.id}</td>
                            <td>{user.name}</td>
                            <td>{user.email}</td>
                            <td>{user.type === 1 ? 'Admin' : 'Người Dùng'}</td>
                            <td>
                                <button
                                    onClick={() => handleRoleChange(user.id, user.type === 1 ? 0 : 1)}
                                >
                                    Chuyển thành {user.type === 1 ? 'Người Dùng' : 'Admin'}
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default Auth;
