<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
</head>

<body>
    <?php

    use utils\SessionManager;

    $errorMsg = SessionManager::get('error');
    ?>
    <h1>403 Forbidden</h1>
    <p><?= $errorMsg ?></p>
    <a href="/">Back to main page</a>
    <?php SessionManager::remove('error'); ?>
</body>

</html>