<?php
// autoload.php
function autoload($className)
{
    // Chuyển đổi namespace thành đường dẫn thư mục
    $classPath = str_replace('\\', '/', $className);

    // Xác định file dựa vào đường dẫn
    $file = __DIR__ . '/src/' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class '$className' not found.");
    }
}

// Đăng ký autoload
spl_autoload_register('autoload');
