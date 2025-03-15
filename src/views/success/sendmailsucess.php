<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <title>Success</title>
    <script>
        // autoback after 5 seconds
        setTimeout(function() {
            window.location.href = '/contact';
        }, 5000);

        // countdown
        window.onload = function() {
            let timeLeft = 5;
            const countdown = document.getElementById('countdown');
            setInterval(function() {
                timeLeft--;
                if (timeLeft >= 0) {
                    countdown.textContent = timeLeft;
                }
            }, 1000);
        };
    </script>
</head>

<body class="bg-gray-100 dark:bg-darkmode2">
    <?php
    // User authentication setup
    use controllers\ModuleController;
    use controllers\UserController;
    use controllers\PostController;

    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../layouts/footer.php';
    require_once __DIR__ . '/../../controllers/UserController.php';

    $userController = new UserController();
    $postController = new PostController();
    $moduleController = new ModuleController();

    $user = null;

    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
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

    $userObj = $user;

    echo render_quora_header($user_logged_in, $user_name, $user_avatar, $user_email, $userObj);
    ?>
    <?php
    if (!is_null($userObj)) {
        echo '<div class="container mx-auto py-6 w-1/3 rounded-lg">';
        echo '<h1 class="text-2xl text-green-500 font-bold text-center mt-20 justify-center">✔️ Email has been sent!</h1>';
        echo '<p class="text-gray-600 text-left mt-4 text-2xl dark:text-white">You will be redirected to the contact page in <span id="countdown" class="text-green-500 text-medium">5</span> seconds.</p>';
        echo '</div>';
        echo '<div class="mt-[100px]"></div>';
    } else if (is_null($userObj)) {
        echo '<div class="flex w-full h-auto flex-col items-center justify-center">
                <h1 class="text-2xl text-red-500 font-bold text-center mt-20">Error cannot send email without login</h1>
                <a href="/login" class="block smt-4 bg-red-500 hover:bg-red-600 transition text-white font-bold py-2 px-4 rounded-lg">Login</a>
            </div>';
    }
    ?>
</body>

</html>