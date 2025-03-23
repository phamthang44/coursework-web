<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<?php

use utils\Template;
use utils\SessionManager;
use controllers\PostController;
use controllers\UserController;
use controllers\ModuleController;

Template::header();

$currentUser = SessionManager::get('user');
if ($currentUser && $currentUser->getRole() !== 'admin') {
    header("Location: /403");
    exit();
} else {
    $postController = new PostController();
    $userController = new UserController();
    $moduleController = new ModuleController();
    $user_logged_in = true;
    $userName = $currentUser->getUsername();
    $email = $currentUser->getEmail();
    $currentUserProfileImage = $currentUser->getProfileImage() ? $currentUser->getProfileImage() : '';
}

?>

<body class="bg-gray-100 dark:bg-darkmode3 min-h-screen">
    <?php echo render_quora_header($user_logged_in, $userName, $currentUserProfileImage, $email, $currentUser) ?>
    <div class="overlay fixed z-[1] top-0 left-0 w-full h-full bg-[#222222] hidden opacity-45 transition-opacity duration-300"></div>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">User Management</h1>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" placeholder="Search users..." class="w-full pl-10 pr-4 py-2 rounded-lg border dark:border-darkmode dark:bg-darkmode dark:text-gray-200 focus:outline-none focus:border-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-600 text-left">
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Posts</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php foreach ($users as $user) : ?>
                        <?php
                        $userProfileImage = $user->getProfileImage() ? $user->getProfileImage() : '';
                        $amoutOfPosts = $postController->getAmountOfPostByUser($user->getUserId());
                        $userFirstName = $user->getFirstName();
                        $userLastName = $user->getLastName();
                        $userEmail = $user->getEmail();
                        $userId = $user->getUserId();
                        ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <?php if ($userProfileImage) { ?>
                                        <img class="h-10 w-10 rounded-full object-cover" src="/<?= $userProfileImage ?>" alt="<?= $userFirstName . " " . $userLastName ?>">
                                    <?php } else { ?>
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 font-medium">
                                            <?php echo strtoupper($user->getUsername()[0]) ?>
                                        </div>
                                    <?php } ?>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white"><?= $userFirstName . " " . $userLastName ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-300"><?= $userEmail ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                <?= $amoutOfPosts ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                <?php if ($user->getStatus() === "active") { ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-500 dark:text-white">
                                        Active
                                    </span>
                                <?php } else { ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-500 dark:text-white">
                                        Banned
                                    </span>
                                <?php } ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                <?= $user->getRole(); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 font-medium action-ban">
                                <?php if ($user->getStatus() === "banned") { ?>
                                    <a href="/admin/unbanuser/<?= $userId ?>" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" data-user-name="<?= $userFirstName . " " . $userLastName ?>">Unban</a>
                                <?php } else { ?>
                                    <a href="/admin/banuser/<?= $userId ?>" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" data-user-name="<?= $userFirstName . " " . $userLastName ?>">Ban</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
            <!-- Pagination -->
            <div class="px-6 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-darkmode text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-darkmode2 hover:bg-gray-50 dark:hover:bg-darkmode">
                        Previous
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-darkmode text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-darkmode2 hover:bg-gray-50 dark:hover:bg-darkmode">
                        Next
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <?php
                            $currentPage = $_GET['page'] ?? 1;
                            $limit = 10;
                            $offset = ($currentPage - 1) * $limit;
                            $start = $offset + 1;
                            $end = min($offset + $limit, count($users));
                            ?>
                            Showing <span class="font-medium"><?= $start ?></span> to <span class="font-medium"><?= $end ?></span> of <span class="font-medium"><?php echo count($users) ?></span> results
                        </p>
                    </div>
                    <div>
                        <?php
                        $currentPage = max(1, intval($currentPage)); // Đảm bảo page >= 1
                        $limit = 10; // Số dòng trên mỗi trang
                        $totalUsers = count($users); // Tổng số bản ghi
                        $totalPages = ceil($totalUsers / $limit); // Tổng số trang

                        $prevPage = max(1, $currentPage - 1);
                        $nextPage = min($totalPages, $currentPage + 1);
                        ?>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <!-- Previous -->
                            <a href="?page=<?= $prevPage ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-darkmode bg-white dark:bg-darkmode2 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-darkmode <?= ($currentPage == 1) ? 'pointer-events-none opacity-50' : '' ?>">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>

                            <!-- Page number -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="?page=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium 
            <?= ($i == $currentPage) ? 'z-10 bg-blue-50 dark:bg-blue-900 border-blue-500 dark:border-blue-600 text-blue-600 dark:text-blue-200' : 'bg-white dark:bg-darkmode2 border-gray-300 dark:border-darkmode text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-darkmode' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <!-- Next buttons -->
                            <a href="?page=<?= $nextPage ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-darkmode bg-white dark:bg-darkmode2 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-darkmode <?= ($currentPage == $totalPages) ? 'pointer-events-none opacity-50' : '' ?>">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/script.js"></script>
    <script>
        const actions = document.querySelectorAll('.action-ban a');
        actions.forEach(action => {
            action.addEventListener('click', (e) => {
                e.preventDefault();
                const url = e.target.href;
                const confirmCard = new ConfirmCard();
                const userName = e.target.getAttribute('data-user-name');
                confirmCard.openConfirmCard(`<h2 class="text-lg font-semibold text-gray-800 dark:text-white confirm-title" data-url="${url}">Are you sure you want to ban <span class="text-red-600 dark:text-red-700 font-bold">${userName}</span> ?</h2>`);
            });
        });
    </script>
</body>

</html>