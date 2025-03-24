<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/tailwind.css">
    <title>404 - Not found</title>
</head>

<body>
    <?php

    use utils\SessionManager;

    $messageDefault = "This page is not found";

    ?>
    <div class="flex items-center justify-center h-screen ">
        <div class="text-center space-y-2">
            <h1 class="text-4xl font-bold text-red-500">404</h1>
            <p class="text-lg font-semibold text-2xl">Not found</p>
            <p class="text-xl text-gray-500"><?= $messageDefault ?></p>
            <a href="/quorae" class="block text-blue-500">Click here to back to home page <span class="text-red-600 text-sm">Quorae</span></a>
        </div>
        <?php SessionManager::remove('error'); ?>
</body>

</html>