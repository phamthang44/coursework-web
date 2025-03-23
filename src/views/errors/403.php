<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/tailwind.css">
    <title>403 - Forbidden</title>
</head>

<body>
    <?php

    use utils\SessionManager;

    $errorMsg = SessionManager::get('error');
    $messageDefault = "You don't have permission to access this page";
    if (strpos($errorMsg, "banned")) {
        $messageDefault = "<span class='text-red-500'>You are banned from this site</span>";
    }
    ?>
    <div class="flex items-center justify-center h-screen ">
        <div class="text-center space-y-2">
            <h1 class="text-4xl font-bold text-red-500">403</h1>
            <p class="text-lg font-semibold text-2xl">Forbidden</p>
            <p class="text-xl text-gray-500"><?= $messageDefault ?></p>
            <?php if (strpos($errorMsg, "banned")) { ?>
                <a href="/contact" class="block text-blue-500">Contact to admin to get <span class="text-red-600 text-sm">Unban</span></a>
            <?php } ?>
        </div>
        <?php SessionManager::remove('error'); ?>
</body>

</html>