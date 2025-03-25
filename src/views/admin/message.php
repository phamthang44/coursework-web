<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/tailwind.css">
    <link rel="stylesheet" href="/css/style.css">
    <title>Quorae Hub - Message Box</title>
</head>

<body class="bg-gray-100 dark:bg-darkmode3 min-h-screen">
    <?php

    use utils\SessionManager;
    use controllers\MessagesController;
    use utils\Template;
    use controllers\UserController;

    Template::header();
    Template::sidebar();
    echo render_quora_header(true, $currentUser->getUsername(), $currentUser->getProfileImage(), $currentUser->getEmail(), $currentUser);
    $currentUser = SessionManager::get('user');
    if ($currentUser->getRole() !== 'admin') {
        SessionManager::set('error', 'You are not authorized to view this page');
        header('Location: /403');
    }
    $userController = new UserController();
    ?>
    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">
        <?php echo render_sidebar($dashboardLink, $adminProfileLink); ?>
        <?php if (!empty($messagesFromUsers)) { ?>
            <?php foreach ($messagesFromUsers as $message) {
                $messageTitle = $message->getTitle();
                $messageContent = $message->getMessage();
                $userId = $message->getUserId();
                $timestamp = $message->getCreatedAt();
                $formattedTimestamp = date('Y-m-d', strtotime($timestamp));
                $user = $userController->getUser($userId);
                $username = $user->getUsername();
            ?>
                <div class="bg-white dark:bg-gray-700 shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-2xl font-medium leading-6 text-gray-900 dark:text-white">Title: <?php echo $messageTitle; ?></h3>
                        <p class="mt-[10px] mb-[10px] max-w -2xl text-xl text-gray-500 dark:text-white">Message: <?php echo $messageContent; ?></p>
                        <p class="mt-1 max-w -2xl text-sm text-gray-500 dark:text-white">By : <?php echo $username; ?> ID: <?php echo $userId ?></p>
                        <p class="mt-1 max-w -2xl text-sm text-gray-500 dark:text-yellow-500"><?php echo $formattedTimestamp; ?></p>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">No messages</h3>
                </div>
            <?php } ?>

            </div>
    </div>

</body>

</html>