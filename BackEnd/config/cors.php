<?php

return [
    'paths' => ['api/*'], // Đường dẫn API nào sẽ được áp dụng CORS, có thể là ['*'] để áp dụng tất cả
    'allowed_methods' => ['*'], // Các phương thức HTTP được phép (GET, POST, v.v.). Dùng ['*'] để cho phép tất cả
    'allowed_origins' => ['http://localhost:5173', 'http://localhost:3000'], // Thêm các origin cần cho phép
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Các headers được phép, dùng ['*'] để cho phép tất cả
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];

