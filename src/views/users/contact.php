<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <title>Contact to admin - QuoraeHub</title>
</head>

<body class="bg-gray-100 dark:bg-darkmode2">
    <?php
    // User authentication setup
    use controllers\ModuleController;
    use controllers\UserController;
    use controllers\PostController;
    use utils\SessionManager;
    use utils\Template;

    Template::header();
    Template::footer();
    Template::postCard();


    $userController = new UserController();
    $postController = new PostController();
    $moduleController = new ModuleController();

    $currentUser = SessionManager::get('user');
    if ($currentUser !== null) {
        $user_logged_in = true;
    } else {
        $user_logged_in = false;
    }
    echo render_quora_header($user_logged_in, $currentUser->getUsername(), $currentUser->getProfileImage(),  $currentUser->getEmail(), $currentUser);
    if ((SessionManager::get('user_id')) !== null) {
        $userId = SessionManager::get('user_id');
        $user = $userController->getUser($userId);
        $user_logged_in = true;
        $user_name = $user->getUsername();
        $user_avatar = $user->getProfileImage() ?? '';
        $user_email = $user->getEmail();
    } else {
        $user_logged_in = false;
        $user_name = '';
        $user_avatar = '';
        $user_email = '';
    }


    ?>
    <?php
    if (!is_null($user)) {
        echo '<div class="container mx-auto py-6 w-1/3 rounded-lg">
        <h2 class="text-2xl text-red-500 font-bold mb-4">Send Email Message to admin</h2>
        <form action="/sendemail" method="POST" enctype="multipart/form-data" id="form-send-email" class="space-y-4">
            <!-- Title Field (Optional) -->
            <div class="form-group py-4 mb-4">
                <label for="title" class="block font-medium text-gray-700 dark:text-white mb-4">Title (Optional):</label>
                <input type="text" id="title" name="title" placeholder="Enter title (optional)"
                    class="w-full h-12 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white">
                <span class="form-message text-red-500 text-sm"></span>
                <input type="hidden" name="user_id" value="<?php echo $user->getUserId(); ?>">
            </div>

            <!-- Content Field (Required) -->
            <div class="form-group">
                <label for="content" class="block font-medium text-gray-700 dark:text-white mb-4">Content (Required):</label>
                <textarea id="content" name="content" rows="5" placeholder="Enter content"
                    class="w-full h-[300px] p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"></textarea>
                <span class="form-message text-red-500 font-medium text-sm"></span>
            </div>
            <input class="w-[200px] h-[40px] rounded-lg bg-red-700 hover:bg-red-600 transition text-white font-bold" type="submit" value="Send">
        </form>
    </div>
    <div class="mt-[100px]"></div>';
        echo render_quora_footer();
    } else if (is_null($user)) {
        echo '<div class="flex w-full h-auto flex-col items-center">
                <h1 class="text-2xl text-red-500 font-bold text-center mt-20 justify-center">Please login to send email to admin</h1>
                <a href="/login" class="mt-4 bg-red-500 hover:bg-red-600 transition text-white font-bold py-2 px-4 rounded-lg">Login</a>
            </div>';
    } else {
        echo '<h1 class="text-2xl text-red-500 font-bold text-center mt-20 justify-center">✔️ Email has been sent!</h1>';
    }
    ?>
    <script src="/js/script.js"></script>
    <script src="/js/validator.js"></script>
    <script>
        Validator({
            form: "#form-send-email",
            formGroupSelector: ".form-group",
            formMessage: ".form-message",
            rules: [
                Validator.isRequired("#content"),
                Validator.isRequired("#title"),
            ],
        });
    </script>
</body>

</html>