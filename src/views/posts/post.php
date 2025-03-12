<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <title>Document</title>
</head>

<body>
    <?php
    // session_start();
    // Include the header component
    use controllers\UserController;

    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../../controllers/UserController.php';
    $userController = new UserController();
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $user = $userController->getUser($userId);
        $user_logged_in = true;
        $user_name = $user->getUsername();
        $user_avatar = '';
        $user_email = $user->getEmail();
    } else {
        $user_logged_in = false;
        $user_name = '';
        $user_avatar = '';
        $user_email = '';
    }
    echo render_quora_header($user_logged_in, $user_name, $user_avatar, $user_email);

    // Giả sử bạn đã gửi mảng $posts từ controller

    if (!empty($postsData)):
    ?>

        <table border="1">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Create</th>
                    <th>Update</th>
                    <th>Delete</th>
                    <th>Module ID</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postsData as $postData): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($postData['post']->getTitle()); ?></td>
                        <td><?php echo htmlspecialchars($postData['post']->getContent()); ?></td>
                        <td><a href="/index.php?action=create&postId=<?= $postData['post']->getPostId(); ?>">Create</a></td>
                        <td><a href="/index.php?action=edit&postId=<?= $postData['post']->getPostId(); ?>">Update</a></td>
                        <td><a class="delete-btn" href="/index.php?action=delete&postId=<?= $postData['post']->getPostId(); ?>">Delete</a></td>
                        <td><?php echo htmlspecialchars($postData['post']->getModuleId()); ?></td>
                        <td>
                            <?php if (!empty($postData['assets'])): ?>
                                <?php foreach ($postData['assets'] as $asset): ?>
                                    <img src="<?php echo htmlspecialchars('/' . $asset->getMediaKey()); ?>" alt="Post image" width="100" height="100">
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else:
        echo "No posts available.";
    endif;
    ?>

    <?php
    echo render_quora_footer();
    ?>
    <script>
        // Select all delete buttons
        let deleteLinks = document.querySelectorAll(".delete-btn");

        function askConfirm(event) {
            event.preventDefault(); // Prevent the default link behavior
            let confirmation = confirm('Are you sure?');
            if (confirmation) {
                window.location.href = event.target.href; // Redirect to the link's href if confirmed
            }
        }

        // Attach the event listener to each delete button
        deleteLinks.forEach(function(deleteLink) {
            deleteLink.addEventListener('click', askConfirm);
        });


        // // // Toggle dark mode
        // var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        // var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // // Change the icons inside the button based on previous settings
        // if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        //     themeToggleLightIcon.classList.remove('hidden');
        // } else {
        //     themeToggleDarkIcon.classList.remove('hidden');
        // }

        // var themeToggleBtn = document.getElementById('theme-toggle');

        // themeToggleBtn.addEventListener('click', function() {

        //     // toggle icons inside button
        //     themeToggleDarkIcon.classList.toggle('hidden');
        //     themeToggleLightIcon.classList.toggle('hidden');

        //     // if set via local storage previously
        //     if (localStorage.getItem('color-theme')) {
        //         if (localStorage.getItem('color-theme') === 'light') {
        //             document.documentElement.classList.add('dark');
        //             localStorage.setItem('color-theme', 'dark');
        //         } else {
        //             document.documentElement.classList.remove('dark');
        //             localStorage.setItem('color-theme', 'light');
        //         }

        //         // if NOT set via local storage previously
        //     } else {
        //         if (document.documentElement.classList.contains('dark')) {
        //             document.documentElement.classList.remove('dark');
        //             localStorage.setItem('color-theme', 'light');
        //         } else {
        //             document.documentElement.classList.add('dark');
        //             localStorage.setItem('color-theme', 'dark');
        //         }
        //     }

        // });
        // let checkbox = document.querySelector("input[name=theme_switch]");

        // if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
        //     document.documentElement.setAttribute("data-theme", "dark");
        //     checkbox.checked = true;
        // } else {
        //     document.documentElement.setAttribute("data-theme", "light");
        //     checkbox.checked = false;
        // }

        // // switch theme if checkbox is engaged
        // checkbox.addEventListener("change", (cb) => {
        //     document.documentElement.setAttribute(
        //         "data-theme",
        //         cb.target.checked ? "dark" : "light"
        //     );
        // });
    </script>
</body>

</html>