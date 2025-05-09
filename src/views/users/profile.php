<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - QuoraeHub</title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .line-clamp-6 {
            display: -webkit-box;
            -webkit-line-clamp: 6;
            line-clamp: 6;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-darkmode2 transition-colors duration-200" id="profile-page">
    <div class="overlay fixed z-[1] top-0 left-0 w-full h-full bg-[#222222] hidden opacity-45 transition-opacity duration-300"></div>
    <?php

    use controllers\PostController;
    use controllers\ModuleController;
    use utils\Template;
    use utils\SessionManager;

    Template::header();
    Template::footer();

    if (!SessionManager::get('user')) {
        header("Location: /login");
        exit();
    }

    if ($currentUser->getStatus() === "banned") {
        SessionManager::set('error', 'You are banned from this site');
        header("Location: /403");
        exit();
    }

    Template::header();
    Template::postCard();

    if (isset($user) && !is_null($user)) {

        echo render_quora_header(true, $currentUser->getUserName(), $currentUser->getProfileImage(), $currentUser->getEmail(), $currentUser);

        $userId = $user->getUserId();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $username = $user->getUsername();
        $profileImage = $user->getProfileImage() ?? '';
        $email = $user->getEmail();
        $bio = $user->getBio() ?? 'Write a description about yourself';
        $accountCreated = $user->getCreatedAccountDate();
        $datetime = new DateTime($accountCreated);
        $formattedAccountCreated = $datetime->format('F Y');

        $currentPage = $postController->getCurrentPage();
        $totalPages = $postController->getTotalPages($userId);
        $modules = $moduleController->getAllModules();


        $dob = $user->getDob();
        $formattedDob = $dob ? date("Y-m-d", strtotime($dob)) : "Not set";
        $profileLink = $firstName . "-" . $lastName . "-" . $userId;
    }
    ?>
    <!-- Profile Section -->
    <div class="container mx-auto py-8 px-4">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Column: Profile Info -->
            <div class="md:w-1/3">
                <div class="bg-white dark:bg-darkmode rounded-lg shadow-md p-6">
                    <div class="flex flex-col items-center">
                        <div class="relative group w-34 h-34">
                            <form class="" action="/users/update-avatar/<?php echo $userId ?>" method="POST" enctype="multipart/form-data" id="form-update-avatar">
                                <div id="preview-container-avatar" class="w-32 h-32 rounded-full mb-4">
                                    <div class="relative">
                                        <?php if (is_null($profileImage) || empty($profileImage)) { ?>
                                            <div class="w-32 h-32 rounded-full bg-purple-600 dark:bg-purple-700 text-white text-6xl font-bold flex items-center justify-center">
                                                <?php echo strtoupper(substr($username, 0, 1)); ?>
                                            </div>
                                        <?php } else { ?>
                                            <div class="w-32 h-32 rounded-full">
                                                <img id="avatar-user" src="/<?php echo $profileImage; ?>" alt="Profile Image" class="w-32 h-32 object-cover rounded-full border border-gray-300" />
                                            </div>
                                        <?php } ?>

                                        <!-- only show edit button when owner -->
                                        <?php if ($isOwner) { ?>
                                            <label for="image" class="absolute bottom-0 right-0 cursor-pointer">
                                                <i class="w-8 h-8 bg-gray-700 dark:bg-gray-700 rounded-full fas fa-camera text-white dark:text-gray-300 p-2"></i>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>
                                <input type="file" name="image" id="image" accept="image/*" class="hidden">
                            </form>
                        </div>

                        <h2 class="text-2xl font-bold mt-4 text-gray-900 dark:text-white"><?php echo $firstName . " " . $lastName ?></h2>
                        <p class="text-gray-500 dark:text-gray-400"><?php echo $email ?></p>

                        <?php
                        if ($isOwner) {
                            echo '<button class="edit-profile mt-4 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold py-2 px-4 rounded-md transition-colors duration-200">
                            Edit Profile
                        </button>';
                        }
                        ?>

                        <div class="w-full mt-6 border-t dark:border-gray-700 pt-4">
                            <form class="mt-4 add-bio-container h-fit" action="/users/update-bio/<?php echo $userId ?>" method="POST" id="form-update-bio">
                                <p class="bio-text text-gray-500 dark:text-gray-400 text-sm"><?php echo $bio ?></p>
                                <?php if ($isOwner) { ?>
                                    <button type="button" class="add-bio mt-2 text-primary-light dark:text-primary-dark text-sm hover:underline"><?= $bio ? "Change your description" : "Add description" ?></button>
                                    <div class="flex gap-2 mt-2">
                                        <button type="submit" class="save-bio bg-primary-light dark:bg-primary-dark hover:bg-red-700 dark:hover:bg-red-800 text-white px-4 py-2 rounded-md transition-colors duration-200" style="display: none;">Save</button>
                                        <button type="button" class="cancel-bio bg-primary-light dark:bg-primary-dark hover:bg-red-700 dark:hover:bg-red-800 text-white px-4 py-2 rounded-md transition-colors duration-200" style="display: none;">Cancel</button>
                                    </div>
                                <?php } ?>
                            </form>

                            <div class="mt-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Account Information</h3>
                                <ul class="mt-2 space-y-2">
                                    <li class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <i class="fas fa-calendar-alt text-gray-400 dark:text-gray-500 mr-2"></i>
                                        <span>Joined <?php echo $formattedAccountCreated ?></span>
                                    </li>
                                    <li class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <i class="fa-solid fa-cake-candles text-gray-400 dark:text-gray-500 mr-2"></i>
                                        <span>Date of Birth: <?php echo $formattedDob ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-[49px]"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Content Tabs -->
            <div class="md:w-2/3">
                <div class="bg-white dark:bg-darkmode rounded-lg shadow-md mb-6">
                    <div class="flex border-b dark:border-gray-700 overflow-x-auto">
                        <a href="/profile/<?= removeVietnameseAccents($profileLink) ?>" class="px-6 py-3 font-medium border-b-2 border-primary-light dark:border-primary-dark text-primary-light dark:text-primary-dark">Profile</a>
                    </div>

                    <div class="p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Posts & Comments</h3>
                        </div>

                        <!-- Posts -->

                        <?php if (empty($posts) || $posts === null) {
                            echo '<div class="flex flex-col items-center justify-center py-16">
                                <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-400 dark:text-gray-500 mb-4">
                                <i class="fas fa-inbox text-4xl"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">You haven\'t shared, answered or posted anything yet.</p>
                            <a href="/posts/create" class="bg-primary-light dark:bg-primary-dark text-white px-6 py-3 rounded-full font-medium hover:bg-red-700 dark:hover:bg-red-800 transition-colors duration-200">Create New Post</a></div>';
                        } else if (!empty($posts) || !is_null($posts)) {
                            foreach ($posts as $post) {
                                $postId = $post->getPostId();
                                $title = $post->getTitle();
                                $content = $post->getContent();
                                $timestamp = $post->getCreateDate();
                                $datetime = new DateTime($timestamp);
                                $formattedTimestamp = $datetime->format('F j, Y');
                                $module = $moduleController->getModuleById($post->getModuleId());
                                $moduleId = $post->getModuleId();
                                $moduleName = $module->getModuleName();
                                $postImageObj = $postController->getPostImage($postId);
                                $avatarUserStr = $user->getProfileImage();
                                $postUserId = $postController->getPostUserId($postId);
                                $voteScore = $voteScores[$postId];

                                $voteUserStatus = $postVoteDAO->getUserVoteStatus($currentUser->getUserId(), $post->getPostId());
                                $voteDisplay = $voteScore > 0 ? "+{$voteScore}" : $voteScore;

                                //check if this vote by currentuser ? if not only show isactivedisplay 
                                $voteUserStatus = isset($voteUserStatus) ? $voteUserStatus : 0;
                                $isActiveUpvote = ($voteUserStatus === 1) ? 'active' : '';
                                $isActiveDownvote = ($voteUserStatus === -1) ? 'active' : '';

                                if ($voteScore > 0) {
                                    $isActiveDisplay = 'text-green-600 dark:text-green-400';
                                } else if ($voteScore < 0) {
                                    $isActiveDisplay = 'text-red-600 dark:text-red-400';
                                } else {
                                    $isActiveDisplay = 'text-gray-600 dark:text-gray-400';
                                }
                                if (!is_null($user)) {
                                    if ($user->getUserId() === $postUserId) {
                                        if ($isOwner) {
                                            $buttonMoreOptions = '<button class="post-options">
                                                <span class="post-card-dot w-8 h-8 rounded-full text-gray-800 dark:text-white">•••</span>
                                              </button>';
                                            $postMoreOptionsDropdown = '<div class="post-card-dropdown hidden absolute right-12 top-1 mt-2 py-2 w-30 bg-white border border-gray-200 dark:bg-darkmode dark:text-gray-600 rounded-lg shadow-md z-10">
                                                <a href="/posts/edit/' . $postId . '" 
                                                   data-url="/posts/edit/' . $postId . '" 
                                                   class="edit-action-advanced block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                   Edit
                                                </a>
                                                <a href="/posts/delete/' . $postId . '" 
                                                   data-url="/posts/delete/' . $postId . '" 
                                                   class="delete-action block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                   Delete
                                                </a>
                                              </div>';
                                        } else {
                                            $buttonMoreOptions = '';
                                            $postMoreOptionsDropdown = '';
                                        }
                                    }
                                }

                                if (!empty($avatarUserStr)) {
                                    $avatarUser = '<div class="w-10 h-10 rounded-full">
                                                        <img src="/' . htmlspecialchars($avatarUserStr, ENT_QUOTES, 'UTF-8') . '" 
                                                             class="w-full h-full rounded-full object-cover avatar-user" 
                                                             alt="avatar user">
                                                   </div>';
                                } else {
                                    $avatarUser = '<div class="w-10 h-10 rounded-full bg-purple-600 text-white text-center flex items-center justify-center">
                                                        <span class="text-lg font-bold">' . strtoupper(substr($username, 0, 1)) . '</span>
                                                   </div>';
                                } ?>

                                <div class="card-container post-card" data-post-id="<?php echo $postId; ?>"
                                    data-title="<?php echo $title; ?>"
                                    data-content="<?php echo $content; ?>"
                                    data-module-id="<?php echo $moduleId; ?>"
                                    data-module-name="<?php echo $moduleName; ?>"
                                    data-post-image="<?php echo (isset($postImageObj) && $postImageObj->getMediaKey()) ? $postImageObj->getMediaKey() : '' ?>">
                                    <div class="border dark:border-gray-700 rounded-lg p-4 mb-4">
                                        <div class="flex">
                                            <div class="w-full flex items-center">
                                                <div class="w-10 h-fit rounded-full">
                                                    <?= $avatarUser ?></div>
                                                <div class="ml-4">
                                                    <h4 class="font-medium text-gray-900 dark:text-white"><?= $firstName . " " . $lastName ?></h4>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Posted on <?= $formattedTimestamp ?></p>
                                                </div>
                                                <div class="relative ml-auto">
                                                    <?= $buttonMoreOptions . $postMoreOptionsDropdown ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white"><?= $title ?></h3>
                                            <p class="mt-2 text-gray-700 dark:text-gray-300 line-clamp-6">
                                                <?= $content ?></p>
                                            <div class="mt-3">
                                                <?php (isset($postImageObj) && $postImageObj->getMediaKey() ? '<img src="/' . $postImageObj->getMediaKey() . '" alt="Post image" class="rounded-lg w-full object-cover" >' : '') ?>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between text-xl text-gray-500 dark:text-gray-400">
                                            <!-- Vote score -->
                                            <div class="vote-score flex items-center relative w-[100px]">
                                                <button aria-label="upvote-button" class="vote-btn upvote-btn p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 <?= $voteScore > 0 ? $isActiveUpvote : "" ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                    </svg>
                                                </button>

                                                <span class="absolute right-[40px] block font-bold <?= $isActiveDisplay ?>">
                                                    <?= $voteDisplay ?>
                                                </span>

                                                <button aria-label="downvote-button" class="vote-btn downvote-btn ml-auto p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 <?= $voteScore <= 0 ? $isActiveDownvote : "" ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <a href="/post/view/<?= $postId ?>" class="flex items-center space-x-2 text-[14px] text-gray-500 dark:text-gray-400 flex-1 ml-[50px]">
                                                <i class="far fa-comment"></i>
                                                <span><?= (count($comments[$postId]) > 0) ? count($comments[$postId]) . " Comments" : count($comments[$postId]) . " Comment" ?></span>
                                            </a>
                                            <!-- Read more link need to fix here-->
                                            <a href="/post/view/<?= $postId ?>" class="inline-block text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                                Read more &rarr;
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <div class="flex items-center justify-center space-x-2 mt-8">
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
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="?page=<?= $i ?>"
                                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 <?=
                                                                                                                        ($i == $currentPage)
                                                                                                                            ? 'bg-red-600 text-white'
                                                                                                                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <!-- <div class="fixed inset-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-70 flex items-center justify-center hidden z-50" id="editProfileModal">

    </div> -->
    <script src="/js/script.js"></script>
    <script src="/js/Validator.js"></script>
    <script>
        <?php
        $error = SessionManager::get('error');
        SessionManager::remove('error');
        ?>
        let errorMessage = <?= json_encode($error) ?>;
        if (errorMessage) {
            const errorModal = new Modal();
            errorMessage = "There is already an account with this email address. Please use another email address.";
            errorModal.openModal(`
                <div class="error-modal">
                    <div class="bg-white dark:bg-darkmode rounded-lg w-full max-w-xl mx-4 p-4">
                        <h3 class="text-lg font-semibold text-red-600">Error !</h3>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">${errorMessage}</p>
                    </div>
                </div>`);
        }
        if (document.querySelector('.edit-profile')) {
            document.querySelector('.edit-profile').addEventListener('click', function() {
                const editProfileModal = new Modal();
                editProfileModal.openModal(`<div class="bg-white dark:bg-darkmode rounded-lg w-full max-w-xl mx-4">
            <div class="flex justify-between items-center border-b dark:border-gray-700 p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Profile</h3>
            </div>
            <form class="pr-4 pt-4 pb-4" action="/users/update/<?= $user->getUserId() ?>" method="POST" id="edit-profile-form" enctype="multipart/form-data">
                <input type="hidden" name="userId" value="<?= $user->getUserId() ?>">
                <div class="flex flex-col md:flex-row md:space-x-4">
                    <div class="md:w-1/3 flex flex-col mb-3 md:mb-0">
                        <div id="preview-container-modal" class="w-32 h-32 rounded-full overflow-hidden mb-4">
                        <?php
                        if (is_null($user->getProfileImage()) || empty($user->getProfileImage())) {
                            echo '<div class="w-full h-full rounded-full bg-purple-600 dark:bg-purple-700 text-white text-6xl font-bold flex items-center justify-center">' . strtoupper(substr($username, 0, 1)) . '</div>';
                        } else {
                            echo '<img src="/' . $profileImage . '" alt="Avatar" class="w-full h-full object-cover rounded-full border border-gray-300" />';
                        }
                        ?>
                        </div>
                        <div class="form-group mt-4">
                            <label class="p-2 text-base text-center bg-gray-300 border-none rounded-xl shadow-lg cursor-pointer transition-all duration-300 ease-in-out hover:bg-gray-200 text-gray-700 dark:text-white dark:bg-gray-700 dark:hover:bg-gray-600" for="image">
                                <input type="file" id="image-modal" name="image" accept="image/*" class="hidden">
                                Change Avatar
                            </label>
                            <span class="form-message text-red-500 font-medium text-sm"></span>
                        </div>
                        <div class="form-group relative mt-4 w-fulls">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1 mt-8">Date of birth</label>
                            <div class="relative w-[160px] max-w-xs">
                                <label for="datepicker" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 z-10">
                                    <i class="fa-solid fa-cake-candles text-gray-400 dark:text-gray-500 mr-2"></i>
                                </label>
                                <input type="date" id="datepicker" name="dob" value="<?php echo $formattedDob ?>"
    class="w-full py-2 px-4 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-darkmode dark:border-gray-700 dark:text-white">
                                <span class="absolute text-xs text-red-500 font-medium right-0 top-0 translate-y-[-100%] hidden"></span>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-2/3">
                        <div class="mb-4 form-group relative">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">First Name</label>
                            <input type="text" id="firstName" name="firstName" value="<?= $firstName ?>" class="w-full border dark:border-gray-600 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                            <span class="form-message text-red-500 font-medium text-sm absolute top-0 right-0"></span>
                        </div>
                        <div class="mb-4 form-group relative">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Last Name</label>
                            <input type="text" id="lastName" name="lastName" value="<?= $lastName ?>" class="w-full border dark:border-gray-600 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                            <span class="form-message text-red-500 font-medium text-sm absolute top-0 right-0"></span>
                        </div>
                        <div class="mb-4 form-group relative">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="<?= $email ?>" class="w-full border dark:border-gray-600 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                            <span class="form-message text-red-500 font-medium text-sm absolute top-0 right-0"></span>
                        </div>
                        <div class="mb-4 form-group">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                            <textarea rows="3" id="bio" name="bio" class="w-full border dark:border-gray-600 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark"><?php echo $bio ?></textarea>
                            <span class="form-message text-red-500 font-medium text-sm"></span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" class="cancel-edit-profile bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md transition-colors duration-200">Cancel</button>
                    <button type="submit" class="bg-primary-light dark:bg-primary-dark hover:bg-red-700 dark:hover:bg-red-800 text-white px-4 py-2 rounded-md transition-colors duration-200">Save Changes</button>
                </div>
            </form>
        </div>`);
            });
        }

        function handleImagePreview(e) {
            let file = e.target.files[0];
            let reader = new FileReader();
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                const errorModal = new Modal();
                errorModal.openModal(`
                    <div class="error-modal">
                        <h1 class="text-2xl text-red-600 dark:text-red-500 font-medium mb-4">Error !</h1>
                        <h2 class="text-gray-600 dark:text-white ">File size exceeds 10MB. Please choose a smaller file.</h2>
                    </div>`);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return;
            }
            reader.onload = function(e) {
                let preview = document.getElementById("preview-update");
                if (preview) {
                    preview.src = e.target.result;
                    let container = document.querySelector("#preview-container-create-post");
                    container.style.display = "block";
                } else if (document.querySelector("#preview-container-modal")) {
                    let preview = document.querySelector("#preview-container-modal");
                    let imgElement = document.createElement("img");
                    imgElement.id = "preview-update";
                    imgElement.src = e.target.result;
                    imgElement.className = "w-full h-full object-cover rounded-full border border-gray-300";
                    preview.innerHTML = "";
                    preview.appendChild(imgElement);
                } else if (document.getElementById("preview-container-avatar")) {
                    let preview = document.getElementById("preview-container-avatar");
                    let label = document.createElement("label");
                    label.htmlFor = "image";
                    label.className = "block relative cursor-pointer";

                    let divElement = document.createElement("div");
                    divElement.className = "w-32 h-32 rounded-full";

                    let imgElement = document.createElement("img");
                    imgElement.id = "avatar-user";
                    imgElement.src = e.target.result;
                    imgElement.className = "w-32 h-32 object-cover rounded-full border border-gray-300";

                    let cameraButton = document.createElement("i");
                    cameraButton.className = "w-8 h-8 bg-gray-700 dark:bg-gray-700 rounded-full fas fa-camera text-white dark:text-gray-300 absolute bottom-0 right-0 p-2";

                    divElement.appendChild(imgElement);
                    label.appendChild(divElement);
                    label.appendChild(cameraButton);
                    preview.innerHTML = "";
                    preview.appendChild(label);
                } else {
                    let imgElement = document.createElement("img");
                    imgElement.id = "preview";
                    imgElement.src = e.target.result;
                    imgElement.className = "w-full h-full object-cover rounded-full border border-gray-300";

                    let container = document.getElementById("preview-container");
                    container.innerHTML = "";
                    container.appendChild(imgElement);
                }
                document.getElementById("preview-container").style.display = "block";
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === "childList") {
                    const image = document.querySelector("#image");
                    if (image) {
                        image.addEventListener("change", handleImagePreview);
                    }

                    const imagePostCreateProfile = document.querySelector("#image-post-upload");
                    if (imagePostCreateProfile) {
                        imagePostCreateProfile.addEventListener("change", handleImagePreview);
                    }
                    // Call again Validator when modal appear
                    Validator({
                        form: "#edit-profile-form",
                        formGroupSelector: ".form-group",
                        formMessage: ".form-message",
                        rules: [
                            Validator.isRequired("#firstName"),
                            Validator.isRequired("#lastName"),
                            Validator.isRequired("#email"),
                            Validator.isEmail("#email"),
                        ],
                    });
                    const cancelEditProfile = document.querySelector('.cancel-edit-profile');
                    if (cancelEditProfile) {
                        cancelEditProfile.onclick = function(e) {
                            const modalElement = document.querySelector('.modal-backdrop');
                            if (modalElement) {
                                modalElement.classList.remove("show");
                                modalElement.addEventListener("transitionend", () => {
                                    modalElement.remove();
                                });
                                // If transition does not work, still ensure Modal be removed after 300ms
                                setTimeout(() => {
                                    if (document.body.contains(modalElement)) {
                                        modalElement.remove();
                                    }
                                }, 300);
                            }
                        }
                    }

                    Validator({
                        form: "#form-upload-post",
                        formGroupSelector: ".form-group",
                        formMessage: ".form-message",
                        rules: [
                            Validator.isRequired("#content"),
                            Validator.isRequiredSelection("#module"),
                            Validator.maxLength("#title", 100),
                        ],
                    });

                    Validator({
                        form: "#form-update-post",
                        formGroupSelector: ".form-group",
                        formMessage: ".form-message",
                        rules: [
                            Validator.isRequired("#content"),
                            Validator.isRequiredSelection("#module"),
                            Validator.maxLength("#title", 100),
                        ],
                    });

                }
            });
        });

        // Observe change in body (or container of modal)
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        const addQuestion = document.querySelector('.add-question');

        if (addQuestion) {
            addQuestion.onclick = function() {
                //close old modal
                const addQuestionDropdown = document.querySelector('.add-question-dropdown');
                if (addQuestionDropdown) {
                    addQuestionDropdown.classList.toggle('hidden');

                    addQuestionDropdown.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (e.target.classList.contains('create-new-post-quick')) {
                            addQuestionDropdown.classList.add('hidden');
                            checkExistingModal();
                            const modal = new Modal();
                            modal.openModal(`<h2 class="text-xl text-red-500 font-bold mb-4">Create new post</h2>
        <form action="/posts/store" method="POST" enctype="multipart/form-data" id="form-upload-post-profile" class="space-y-4">
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
                        class="w-full h-40 resize-none p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"></textarea>
                    <span class="form-message text-red-500 font-medium text-sm"></span>
                </div>

                <!-- Module Select -->
                <div class="form-group">
                    <label for="module" class="block font-medium text-gray-700 dark:text-white mb-4">Module Name:</label>
                    <select id="module" name="module"
                        class="w-50 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 text-black dark:text-white">
                        <option value="" class="text-black dark:text-white">-- Select Module --</option>
                        <?php foreach ($modules as $module): ?>
                            <option class="text-black dark:text-white" value="<?php echo $module->getModuleId(); ?>"><?php echo $module->getModuleName(); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-message text-red-500 font-medium text-sm ml-5"></span>
                </div>
                <!-- Image Upload -->
                <div class="form-group flex gap-8 relative">
                    <div>
                        <label for="image-post-upload" class="block font-medium text-gray-700 dark:text-white mb-2">Upload Image:</label>
                        <label class="custom-file-upload text-gray-700 dark:text-white">
                        <input type="file" id="image-post-upload" name="image" accept="image/*"
                            class="w-2 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none title">
                            Choose an image
                        </label>
                        <span class="form-message text-red-500 font-medium text-sm"></span>
                    </div>
                    <!-- Preview Image -->
                    <div id="preview-container-create-post" class="hidden absolute -top-[100px] left-[440px]">
                        <h3 class="font-medium text-gray-700 dark:text-white">Preview Image:</h3>
                        <img id="preview-update" src="" alt="Preview Image" class="w-80 h-40 object-cover mt-2 rounded-lg border border-gray-300" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <input class="btn bg-red-700 hover:bg-red-600 transition text-white font-bold" type="submit" value="Submit">
                </div>
            </form>
`)
                        }
                    });
                }

            }
        }

        async function handleUpdateAvatar() {
            const form = document.querySelector("#form-update-avatar");
            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData,
                });
                const data = await response.json();
                if (data.success) {
                    document.getElementById("avatar-user").src = "/" + data.newAvatarPath;
                    const avatarsUser = document.querySelectorAll(".avatar-user");
                    avatarsUser.forEach(avatar => {
                        avatar.src = "/" + data.newAvatarPath;
                    });
                } else {
                    console.error(data.message);
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }

        async function handleUpdateBio() {
            const form = document.querySelector("#form-update-bio");
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData,
                });
                const data = await response.json();

                if (data.success) {
                    updateBioText(data.newBio);
                } else {
                    console.error("Update failed:", data.message);
                }
            } catch (error) {
                console.error("Error updating bio:", error);
            }
        }

        function updateBioText(newBio) {
            const textBio = document.createElement("p");
            textBio.classList = "bio-text text-gray-500 dark:text-gray-400 text-sm";
            textBio.innerText = newBio;

            const input = document.querySelector(".bio-input");
            input.replaceWith(textBio);

            toggleBioEdit(false);
        }

        function toggleBioEdit(isEditing) {
            const saveButton = document.querySelector('.save-bio');
            const cancelButton = document.querySelector('.cancel-bio');
            const addBioBtn = document.querySelector('.add-bio');

            saveButton.style.display = isEditing ? "inline-block" : "none";
            cancelButton.style.display = isEditing ? "inline-block" : "none";
            addBioBtn.classList.toggle('hidden', isEditing);
        }

        document.addEventListener("DOMContentLoaded", () => {
            const formUpdateBio = document.querySelector(".add-bio-container");
            if (!formUpdateBio) return;

            formUpdateBio.addEventListener("submit", function(e) {
                e.preventDefault();
                handleUpdateBio();
            });

            formUpdateBio.addEventListener("click", function(e) {
                if (e.target.classList.contains('add-bio')) {
                    e.target.classList.add('hidden');
                    const textBio = document.querySelector(".bio-text");

                    const input = document.createElement("input");
                    input.type = "text";
                    input.value = textBio ? textBio.innerText : "";
                    input.name = "bio-update";
                    input.classList = "bio-input w-full border dark:border-gray-600 rounded-md ml-auto p-[10px] bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark";

                    textBio?.replaceWith(input);
                    toggleBioEdit(true);
                }
            });
            if (document.querySelector('.cancel-bio')) {
                document.querySelector('.cancel-bio').addEventListener("click", function() {
                    const input = document.querySelector(".bio-input");
                    if (input) {
                        const textBio = document.createElement("p");
                        textBio.classList = "bio-text text-gray-500 dark:text-gray-400 text-sm";
                        textBio.innerText = input.value || "No bio set";
                        input.replaceWith(textBio);
                    }
                    toggleBioEdit(false);
                });
            }

        });
        const avatar = document.querySelector("#image");
        if (avatar) {
            avatar.addEventListener("change", handleUpdateAvatar);
        }

        const optionsPostCard = document.querySelectorAll(".post-options");
        optionsPostCard.forEach((option) => {
            option.addEventListener("click", function(e) {
                const dropdown = option.nextElementSibling;
                dropdown.classList.toggle("hidden");
            });
        });
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("delete-action")) {
                e.preventDefault();
                // e.stopPropagation();
                checkExistingDropdown(e);
                checkExistingModal();
                const deleteConfirmCard = new ConfirmCard();
                deleteConfirmCard.openConfirmCard(`
        <h2 class="text-red-600 dark:text-white confirm-title" data-url="${e.target.href}">
            Are you sure you want to delete this post?
        </h2>`);
                dropdown.classList.add("hidden");
            }
        });
    </script>
</body>

</html>