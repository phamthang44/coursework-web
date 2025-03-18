<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Management System - Admin Dashboard</title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Any custom styles that can't be handled with Tailwind */
    </style>
</head>

<body class="bg-background-light dark:bg-darkmode2 text-gray-800 dark:text-gray-200 transition-colors duration-200">
    <?php

    use controllers\UserController;
    use controllers\PostController;
    use controllers\ModuleController;
    use utils\Template;
    use utils\SessionManager;

    Template::header();
    Template::footer();
    Template::sidebar();

    $userController = new UserController();
    $postController = new PostController();
    $moduleController = new ModuleController();

    if ((SessionManager::get('user_id')) !== null) {
        $currentUser = $userController->getUser(SessionManager::get('user_id'));
        $currentUser = SessionManager::get('user');
        $user_logged_in = true;
        $userName = $currentUser->getUsername();
        $userAvatar = $currentUser->getProfileImage() ?? '';
        $userEmail = $currentUser->getEmail();
        // $profileLink = '/profile/' . $user->getFirstName() . '-' . $user->getLastName() . '-' . $user->getUserId();
        $postsPerPage = 10;
        $currentPage = $postController->getCurrentPage();
        $offset = ($currentPage - 1) * $postsPerPage;
        $data = $postController->getPostsByPage($postsPerPage, $offset);
        $posts = $data['posts'];
        $totalPosts = $data['totalPosts'];
        $totalUsers = $userController->getTotalUserNums();
        $totalModules = $moduleController->getTotalModuleNums();
        $totalPages = ceil($totalPosts / $postsPerPage);
        $dashboardLink = '/admin/dashboard';
        $adminProfileLink = '/admin/profile/' . $currentUser->getFirstName() . '-' . $currentUser->getLastName() . '-' . $currentUser->getUserId();
    } else {
        $user_logged_in = false;
        $userName = '';
        $userAvatar = '';
        $userEmail = '';
    }
    echo render_quora_header($user_logged_in, $userName, $userAvatar, $userEmail, $currentUser);
    ?>
    <div class="flex min-h-screen">
        <?php echo render_sidebar($dashboardLink, $adminProfileLink); ?>
        <!-- Main Content Area -->
        <div class="ml-64 flex-1 p-5">
            <!-- Dashboard Overview -->
            <div id="dashboard-overview">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-border-light dark:border-border-dark">
                    <h2 class="text-2xl font-medium">Dashboard Overview</h2>
                    <div class="flex gap-3">
                        <button class="reload-page flex items-center px-4 py-2 bg-primary-light dark:bg-primary-dark text-black dark:text-white rounded hover:opacity-90 transition-opacity">
                            <i class="fas fa-sync-alt mr-2"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div class="bg-card-light dark:bg-card-dark rounded-lg shadow p-5 flex items-center transition-colors duration-200">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900 text-primary-light dark:text-blue-400 flex items-center justify-center mr-4 text-2xl">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-medium"><?= ($totalPosts) ?></h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Posts</p>
                        </div>
                    </div>

                    <div class="bg-card-light dark:bg-card-dark rounded-lg shadow p-5 flex items-center transition-colors duration-200">
                        <div class="w-12 h-12 rounded-lg bg-pink-100 dark:bg-pink-900 text-secondary-light dark:text-pink-400 flex items-center justify-center mr-4 text-2xl">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-medium"><?= $totalUsers ?></h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Registered Users</p>
                        </div>
                    </div>

                    <div class="bg-card-light dark:bg-card-dark rounded-lg shadow p-5 flex items-center transition-colors duration-200">
                        <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900 text-success-light dark:text-green-400 flex items-center justify-center mr-4 text-2xl">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-medium"><?= $totalModules ?></h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Active Modules</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Posts Section -->
                <div class="bg-card-light dark:bg-card-dark rounded-lg shadow mb-8 overflow-hidden transition-colors duration-200">
                    <div class="flex justify-between items-center p-4 border-b border-border-light dark:border-border-dark">
                        <h3 class="text-lg font-medium">Recent Posts</h3>
                        <div class="flex items-center gap-3">
                            <input
                                type="text"
                                class="px-3 py-2 border border-border-light dark:border-border-dark rounded bg-white dark:bg-gray-800 text-sm w-48 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark"
                                placeholder="Search posts..." />
                            <a href="/posts/create" class="flex items-center px-4 py-2 bg-blue-400 dark:bg-primary-dark text-black dark:text-white rounded hover:opacity-90 transition-opacity">
                                <i class="fas fa-plus mr-2"></i> Add New
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800 transition-colors duration-200">
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Title</th>
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Module</th>
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Author</th>
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Status</th>
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Date</th>
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post) { ?>
                                    <?php
                                    $postId = $post->getPostId();
                                    $postTitle = $post->getTitle();
                                    $postModuleName = $postController->getModuleName($post->getModuleId());
                                    $postAuthor = $postController->getUser($post->getUserId());
                                    $firstName = $postAuthor->getFirstName();
                                    $lastName = $postAuthor->getLastName();
                                    $isPublished = ($post !== null) ? 'Published' : '';
                                    $postDate = $post->getTimestamp();
                                    $postDateFormatted = date('M d, Y', strtotime($postDate));
                                    ?>
                                    <tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                        <td class="px-5 py-4"><?= $postTitle ?></td>
                                        <td class="px-5 py-4"><?= $postModuleName ?></td>
                                        <td class="px-5 py-4"><?= $firstName . " " . $lastName ?></td>
                                        <td class="px-5 py-4">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400"><?= $isPublished ?></span>
                                        </td>
                                        <td class="px-5 py-4"><?= $postDateFormatted ?></td>
                                        <td class="px-5 py-4 flex gap-2">
                                            <a href="/posts/edit/<?php echo $postId ?>" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-primary-light dark:text-blue-400 transition-colors">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/posts/delete/<?php echo $postId ?>" class="delete-action w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-danger-light dark:text-danger-dark transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-center p-4">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- <button class="w-9 h-9 flex items-center justify-center rounded mx-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="w-9 h-9 flex items-center justify-center rounded mx-1 bg-primary-light dark:bg-primary-dark text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800">1</button>
                            <button class="w-9 h-9 flex items-center justify-center rounded mx-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">2</button>
                            <button class="w-9 h-9 flex items-center justify-center rounded mx-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">3</button>
                            <button class="w-9 h-9 flex items-center justify-center rounded mx-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </button> -->
                            <!-- First Button -->
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=1"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200">
                                    First
                                </a>
                            <?php endif; ?>

                            <!-- Previous Button -->
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?= $currentPage - 1 ?>"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200">
                                    Previous
                                </a>
                            <?php else: ?>
                                <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                    Previous
                                </span>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <div class="flex space-x-1">
                                <?php
                                $maxPagesToShow = 5;
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);

                                if ($startPage > 1) {
                                    echo '<span class="px-4 py-2 text-sm font-medium text-gray-400">...</span>';
                                }

                                for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <a href="?page=<?= $i ?>"
                                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 <?= ($i == $currentPage) ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor;

                                if ($endPage < $totalPages) {
                                    echo '<span class="px-4 py-2 text-sm font-medium text-gray-400">...</span>';
                                }
                                ?>
                            </div>

                            <!-- Next Button -->
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?= $currentPage + 1 ?>"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200">
                                    Next
                                </a>
                            <?php else: ?>
                                <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                                    Next
                                </span>
                            <?php endif; ?>

                            <!-- Last Button -->
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?= $totalPages ?>"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200">
                                    Last
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional views (hidden by default) -->
            <!-- Post Management View -->
            <div id="post-management" class="hidden">
                <!-- Content similar to dashboard but for post management -->
            </div>

            <!-- User Management View -->
            <div id="user-management" class="hidden">
                <!-- Content similar to dashboard but for user management -->
            </div>

            <!-- Module Management View -->
            <div id="module-management" class="hidden">
                <!-- Content similar to dashboard but for module management -->
            </div>
        </div>
    </div>
    <script src="/js/script.js"></script>
    <script>
        // Navigation functionality (simplified)
        document.querySelectorAll('.nav-item').forEach((item, index) => {
            item.addEventListener('click', () => {
                // Remove active class from all items
                document.querySelectorAll('.nav-item').forEach(el => {
                    el.classList.remove('bg-blue-900', 'dark:bg-gray-800');
                });

                // Add active class to clicked item
                item.classList.add('bg-blue-900', 'dark:bg-gray-800');

                // Show corresponding view and hide others
                const views = ['dashboard-overview', 'post-management', 'user-management', 'module-management'];
                views.forEach((view, i) => {
                    const el = document.getElementById(view);
                    if (el) {
                        if (i === index) {
                            el.classList.remove('hidden');
                        } else {
                            el.classList.add('hidden');
                        }
                    }
                });
            });
        });

        const deleteActions = document.querySelectorAll('.delete-action');
        deleteActions.forEach(action => {
            action.addEventListener('click', function(e) {
                openDeleteConfirmCard(e);
            });
        });

        function openDeleteConfirmCard(e) {
            e.preventDefault();
            const deleteConfirmCard = new ConfirmCard();
            deleteConfirmCard.openConfirmCard(`
            <h2 class="text-red-600 dark:text-white confirm-title" data-url="${e.target.href}">
                Are you sure you want to delete this post?
            </h2>`);
        }

        const addQuestionBtnOnHeader = document.querySelector('.add-question');
        addQuestionBtnOnHeader.classList.add("hidden");

        const SyncBtn = document.querySelector('.reload-page');
        SyncBtn.addEventListener('click', function() {
            location.reload();
        });
    </script>
</body>

</html>