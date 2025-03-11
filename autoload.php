<?php
// autoload.php
function autoload($className)
{
    $classPath = str_replace('\\', '/', $className);

    $file = __DIR__ . '/src/' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class '$className' not found.");
    }
}

spl_autoload_register('autoload');
