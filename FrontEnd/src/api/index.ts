import axios from "axios";

export const instance = axios.create({
    baseURL: "http://localhost:8000/api",
    headers: {
        "Content-Type": "application/json",
    },
});

// Hàm này có thể được gọi để cấu hình Authorization header
export const setAuthToken = (token : string | null) => {
    if (token) {
        // Nếu có token, thêm vào header Authorization
        instance.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    } else {
        // Nếu không có token, xóa header Authorization
        delete instance.defaults.headers.common["Authorization"];
    }
};
