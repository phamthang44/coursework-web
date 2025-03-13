<?php
// autoload.php
function autoload($className)
{
    //----------------------------------------------------------------------------------- new
    // Normalize namespace separators to directory separators
    $classPath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $className);

    // Define the base directory for the namespace
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    // Construct the full file path
    $file = $baseDir . $classPath . '.php';

    // Debug statement
    error_log("Autoloading class: $className, looking for file: $file");

    // Check if the file exists and require it
    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class '$className' not found in '$file'.");
    }
}

spl_autoload_register('autoload');
