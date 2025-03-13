<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <title>Posts</title>
    <style>
        /* Add required line-clamp utility if not provided by Tailwind */
        .line-clamp-6 {
            display: -webkit-box;
            -webkit-line-clamp: 6;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-darkmode2">
    <?php
    // User authentication setup
    use controllers\UserController;
    use controllers\PostController;

    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../layouts/footer.php';
    require_once __DIR__ . '/../../controllers/UserController.php';
    require_once __DIR__ . '/../posts/post-card.php'; // Include our new post-card component

    $userController = new UserController();
    $postController = new PostController();
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
    ?>

    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 px-4">Posts</h1>

        <?php
        // Render create button if user is logged in
        if ($user_logged_in) {
            echo render_create_post_button();
        }

        // Render posts with grid layout if posts exist
        if (!empty($postsData)) {
            echo render_post_cards($postsData, $user_logged_in, $postController);
        } else {
            echo '<div class="p-4 text-gray-600 dark:text-gray-300">No posts available.</div>';
        }
        ?>
    </div>

    <?php
    echo render_quora_footer();
    ?>

    <script>
        // Delete confirmation
        document.addEventListener('click', function(e) {
            if (e.target && e.target.closest('.delete-btn')) {
                e.preventDefault();
                const deleteBtn = e.target.closest('.delete-btn');
                if (confirm('Are you sure you want to delete this post?')) {
                    window.location.href = deleteBtn.href;
                }
            }
        });

        // Upvote functionality
        document.addEventListener('click', function(e) {
            if (e.target && e.target.closest('.upvote-btn')) {
                const card = e.target.closest('.post-card');
                const postId = card.dataset.postId;
                const scoreElement = card.querySelector('.vote-score span');

                // Call your vote API here
                // For demonstration purposes:
                fetch(`/api/vote?postId=${postId}&direction=up`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the score display
                            const newScore = data.newScore;
                            scoreElement.textContent = newScore > 0 ? `+${newScore}` : newScore;

                            // Update color
                            scoreElement.className = 'mx-1 font-bold ' +
                                (newScore > 0 ? 'text-green-600 dark:text-green-400' :
                                    (newScore < 0 ? 'text-red-600 dark:text-red-400' : ''));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });

        // Similar implementation for downvote
        document.addEventListener('click', function(e) {
            if (e.target && e.target.closest('.downvote-btn')) {
                // Similar implementation as upvote
            }
        });
    </script>
</body>

</html>