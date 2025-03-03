/*
-- Tạo database
CREATE DATABASE IF NOT EXISTS student_stack_overflow;
USE student_stack_overflow;

-- Tạo bảng Users
CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL, 
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Lưu mật khẩu đã mã hóa
    profile_image_path VARCHAR(255), -- Đường dẫn ảnh (nullable)
    bio TEXT, -- Thông tin tiểu sử (nullable)
    role ENUM('user', 'admin') DEFAULT 'user', -- Vai trò của người dùng
    account_create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    dob DATE -- Ngày sinh (nullable)
);

-- Tạo bảng Modules
CREATE TABLE Modules (
    module_id INT PRIMARY KEY AUTO_INCREMENT,
    module_name VARCHAR(50) NOT NULL,
    description TEXT -- Mô tả module (nullable)
);

-- Tạo bảng PostAssets (tài sản của bài đăng, ví dụ: hình ảnh)
CREATE TABLE PostAssets (
    post_asset_id INT PRIMARY KEY AUTO_INCREMENT,
    media_key VARCHAR(255) NOT NULL, -- Đường dẫn hoặc khóa của media
    post_id INT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES Posts(post_id) ON DELETE CASCADE
);


-- Tạo bảng Posts
CREATE TABLE Posts (
    post_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    vote_score INT DEFAULT 0, -- Điểm vote (mặc định 0)
    user_id INT NOT NULL,
    module_id INT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_timestamp DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    post_assets_id INT, -- Liên kết với tài sản của bài đăng (nullable)
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);
ALTER TABLE Posts
ADD FOREIGN KEY (module_id) REFERENCES Modules(module_id) ON DELETE CASCADE;
-- 
-- 
ALTER TABLE Posts 
ADD FOREIGN KEY (post_assets_id) REFERENCES PostAssets(post_asset_id) ON DELETE SET NULL;

-- Tạo bảng PostComments (bình luận cho bài đăng)
CREATE TABLE PostComments (
    post_comment_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    vote_score INT DEFAULT 0, -- Điểm vote cho bình luận (mặc định 0)
    user_id INT NOT NULL,
    parent_comment_id INT, -- Bình luận cha (nullable cho bình luận cấp cao nhất)
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_timestamp DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    post_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (parent_comment_id) REFERENCES PostComments(post_comment_id) ON DELETE SET NULL,
    FOREIGN KEY (post_id) REFERENCES Posts(post_id) ON DELETE CASCADE
);

-- Tạo bảng MessageFromUser (tin nhắn từ người dùng, ví dụ: liên hệ admin)
CREATE TABLE MessageFromUser (
    message_from_user_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    user_id INT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);
*/



