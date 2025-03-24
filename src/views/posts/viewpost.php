<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $post->getTitle() . " - QuoraeHub" ?></title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body class="bg-gray-100 dark:bg-darkmode2 text-gray-900 dark:text-gray-200" id="view-post-page">
    <?php

    use utils\Template;
    use controllers\PostCommentController;
    use controllers\UserController;
    use controllers\PostController;
    use utils\SessionManager;

    Template::header();
    Template::footer();
    Template::postCommentLayout();
    $currentUser = SessionManager::get('user');
    if ($currentUser->getStatus() === 'banned') {
        SessionManager::set('error', 'You are banned from the site');
        header('Location: /403');
    }
    ?>
    <?php echo render_quora_header(true, $currentUser->getUsername(), $currentUser->getProfileImage(), $currentUser->getEmail(), $currentUser) ?>
    <?php
    $authorImage = $user->getProfileImage();
    $authorUserName = $user->getUsername();
    $authorFirstName = $user->getFirstName();
    $authorLastName = $user->getLastName();
    $currentUserFirstName = $currentUser->getFirstName();
    $currentUserLastName = $currentUser->getLastName();
    $profileLinkAuthor = "/profile/$authorFirstName-$authorLastName-" . $user->getUserId();
    $profileLink = "/profile/$currentUserFirstName-$currentUserLastName-" . $currentUser->getUserId();
    // Extract post data

    $postId = $post->getPostId();
    $title = $post->getTitle();
    $content = $post->getContent();
    $moduleName = $postController->getModuleName($post->getModuleId());
    $createdAt = $post->getTimestamp() ?? 'Unknown';
    $updatedAt = $post->getUpdatedTimestamp() ?? 'Unknown';

    // Format dates
    $createdAtFormatted = date('M d, Y', strtotime($createdAt));
    $updatedAtFormatted = date('M d, Y', strtotime($updatedAt));

    //isAuthor
    $isAuthor = $currentUser->getUserId() === $user->getUserId();

    $voteDisplay = $voteScore > 0 ? "+{$voteScore}" : $voteScore;
    //check if this vote by currentuser ? if not only show isactivedisplay 

    $isActiveUpvote = ($voteUserStatus === 1) ? 'active' : '';
    $isActiveDownvote = ($voteUserStatus === -1) ? 'active' : '';

    if ($voteScore > 0) {
        $isActiveDisplay = 'text-green-600 dark:text-green-400';
    } else if ($voteScore < 0) {
        $isActiveDisplay = 'text-red-600 dark:text-red-400';
    } else {
        $isActiveDisplay = 'text-gray-600 dark:text-gray-400';
    }

    $postCommentController = new PostCommentController();
    $userController = new UserController();
    $numberComments = $postCommentController->getNumberComments($postId);
    $comments = $postCommentController->getComments($postId);

    ?>
    <div class="overlay fixed z-[1] top-0 left-0 w-full h-full bg-[#222222] hidden opacity-45 transition-opacity duration-300"></div>
    <div class="max-w-4xl mx-auto p-4 md:p-6 lg:p-8 post-card" data-post-id="<?= $postId ?>">


        <!-- Main Post Container -->
        <div class="bg-white dark:bg-darkmode rounded-lg shadow-md mb-6 overflow-hidden">
            <!-- Post Header -->
            <div class="p-4 md:p-6 border-b border-gray-200 dark:border-gray-700 relative">
                <?php
                $postUserId = $postController->getPostUserId($postId);

                ?>
                <div class="flex items-start">
                    <?php if ($isAuthor) { ?>
                        <button class="post-options">
                            <span class="post-card-dot w-8 h-8 flex justify-center items-center rounded-full text-gray-800 dark:text-white dark:hover:bg-gray-600 hover:bg-gray-300">•••</span>
                        </button>
                        <div class="post-card-dropdown hidden absolute right-12 top-1 mt-2 py-2 w-30 bg-white border border-gray-200 dark:bg-darkmode dark:text-gray-600 rounded-lg shadow-md z-10">
                            <a href="/posts/edit/<?= $postId ?>"
                                data-url="/posts/edit/<?= $postId ?>"
                                class="edit-action-advanced block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                Edit
                            </a>
                            <a href="/posts/delete/<?= $postId ?>"
                                data-url="/posts/delete/<?= $postId ?>"
                                class="delete-action block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                Delete
                            </a>
                        </div>
                    <?php } ?>
                    <!-- Author Avatar -->
                    <div class="flex-shrink-0 mr-4">
                        <?php if ($authorImage) { ?>
                            <a href="<?php echo $profileLinkAuthor; ?>"><img src="/<?php echo $authorImage; ?>" alt="<?php echo $authorUserName; ?>" class="w-12 h-12 rounded-full object-cover"></a>
                        <?php } else { ?>
                            <a href="<?php echo $profileLinkAuthor; ?>" class="block w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 dark:bg-gray-600 dark:text-gray-300">
                                <p class="text-lg"><?php echo strtoupper(substr($authorUserName, 0, 1)); ?></p>
                            </a>
                        <?php } ?>
                    </div>

                    <!-- Author Info and Post Title -->
                    <div class="flex-1">
                        <h1 class="text-xl md:text-2xl font-bold mb-2"><?= $title ?></h1>
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <span class="font-medium"><?= $authorFirstName . " " . $authorLastName ?></span>
                            <span class="mx-2">·</span>
                            <!-- <span>Software Developer at TechCorp</span> -->
                            <!-- <span class="mx-2">·</span> -->
                            <span><?= $createdAtFormatted ?></span>
                        </div>

                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <?= $moduleName ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post Content -->
            <div class="p-4 md:p-6">
                <div class="prose max-w-none dark:prose-invert prose-lg"><?= $content ?></div>

                <!-- Post Actions -->
                <div class="flex items-center justify-between mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-6">
                        <?php if ($currentUser) { ?>
                            <!-- Upvote/Downvote -->
                            <div class="vote-score flex items-center space-x-[50px] relative w-[150px]">
                                <button class="vote-btn upvote-btn flex items-center justify-center h-8 w-8 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 <?= $voteScore > 0 ? $isActiveUpvote : "" ?>">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                                <span class="font-medium absolute block <?= $isActiveDisplay ?>"><?= $voteDisplay ?></span>
                                <button class="vote-btn downvote-btn flex items-center justify-center h-8 w-8 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 <?= $voteScore <= 0 ? $isActiveDownvote : "" ?>">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </div>
                        <?php } else { ?>
                            <div class="vote-score flex items-center space-x-[50px] relative w-[150px]">
                                <button class="vote-btn upvote-btn flex items-center justify-center h-8 w-8 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 <?= $voteScore > 0 ? $isActiveUpvote : "" ?>">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                                <span class="font-medium absolute block <?= $isActiveDisplay ?>"><?= $voteDisplay ?></span>
                                <button class="vote-btn downvote-btn flex items-center justify-center h-8 w-8 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 <?= $voteScore <= 0 ? $isActiveDownvote : "" ?>">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </div>
                        <?php } ?>

                        <!-- Comment button -->
                        <label for="comment" class="flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 cursor-pointer">
                            <i class="far fa-comment mr-2"></i>
                            <span><?= $numberComments ? $numberComments : 0 ?> Comments</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="bg-white dark:bg-darkmode rounded-lg shadow-md overflow-hidden">
            <div class="p-4 md:p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold mb-4">Comments (<?= $numberComments ? $numberComments : 0 ?>)</h2>
                <!-- Comment Form -->
                <form class="flex space-x-4 mb-6" action="/comment" method="POST">
                    <input type="hidden" name="postId" value="<?= $postId ?>">
                    <a href="<?= $profileLink ?>" class="h-fit w-fit flex-shrink-0">
                        <?php if ($currentUser->getProfileImage()) { ?>
                            <img class="h-10 w-10 rounded-full" src="/<?= $currentUser->getProfileImage(); ?>" alt="Your avatar">
                        <?php } else { ?>
                            <div class="block w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 dark:bg-gray-600 dark:text-gray-300">
                                <p class="text-lg"><?= strtoupper(substr($currentUser->getUsername(), 0, 1)); ?></p>
                            </div>
                        <?php } ?>
                    </a>
                    <div class="flex-1">
                        <textarea id="comment" name="postCommentContent" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 dark:bg-darkmode2 resize-none" rows="2" placeholder="Add a comment..." required></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                                Comment
                            </button>
                        </div>
                    </div>
                </form>
                <!-- Comments List -->
                <div class="space-y-6">
                    <!-- Display comment -->
                    <?php
                    if (!isset($user)) {
                        die("Error: \$user is not defined or is not an instance of User.");
                    }
                    if (!isset($currentUser)) {
                        die("Error: \$currentUser is not defined or is not an instance of User.");
                    }
                    ?>
                    <?php echo renderCommentTree($comments, $postId, null, 0, $user, $currentUser); ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo render_quora_footer() ?>
    <script src="/js/validator.js"></script>
    <script src="/js/script.js"></script>
    <script>
        const addQuestion = document.querySelector('.add-question');

        if (addQuestion) {
            addQuestion.onclick = function() {
                //close old modal
                const addQuestionDropdown = document.querySelector('.add-question-dropdown');
                if (addQuestionDropdown) {
                    addQuestionDropdown.classList.toggle('hidden');

                    addQuestionDropdown.addEventListener('click', function(e) {
                        e.stopPropagation();
                        console.log(e.target)
                        if (e.target.classList.contains('create-new-post-quick')) {
                            //addQuestionDropdown.classList.add('hidden');
                            checkExistingDropdown(e);
                            checkExistingModal();
                            const modal = new Modal();
                            modal.openModal(`
<h2 class="text-xl text-red-500 font-bold mb-4">Create new post</h2>
<form action="/posts/store" method="POST" enctype="multipart/form-data" id="form-upload-post" class="space-y-4">
        <!-- Title Field (Optional) -->
        <div class="form-group py-4 mb-4">
            <div class="flex">
                <label for="title" class="block font-medium text-gray-700 dark:text-white mb-4">Title (Optional) Max(100 characters):</label>
                <p class="ml-auto font-medium text-gray-700 dark:text-white mb-4">Characters count: <span id="characterCount">0</span></p>
            </div>
            <input type="text" id="title" name="title" placeholder="Enter title (optional)"
                class="w-full h-12 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white">
            <span class="form-message text-red-500 text-sm"></span>
            <input type="hidden" name="user_id" value="<?php echo $user->getUserId(); ?>">
        </div>

        <!-- Content Field (Required) -->
        <div class="form-group">
            <div class="flex">
                <label for="content" class="block font-medium text-gray-700 dark:text-white mb-4">Content (Required):</label>
                <p class="ml-auto font-medium text-gray-700 dark:text-white mb-4">Word count: <span id="wordCount">0</span></p>
            </div>
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
                            <label for="image" class="block font-medium text-gray-700 dark:text-white mb-2">Upload Image:</label>
                            <label class="custom-file-upload text-gray-700 dark:text-white">
                                <input type="file" id="image" name="image" accept="image/*"
                                    class="w-2 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none title">
                                Choose an image
                            </label>
                            <span class="form-message text-red-500 font-medium text-sm"></span>
                        </div>
                        <!-- Preview Image -->
                        <div id="preview-container" class="hidden absolute -top-[100px] left-[440px]">
                            <h3 class="font-medium text-gray-700 dark:text-white">Preview Image:</h3>
                            <img id="preview" src="" alt="Preview Image" class="w-80 h-40 object-cover mt-2 rounded-lg border border-gray-300" />
                        </div>
                    </div>



                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <input class="btn bg-red-700 hover:bg-red-600 transition text-white font-bold" type="submit" value="Create post">
                    </div>
                    </form>
                    `);
                        }
                    });
                }

            }
        }

        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === "childList") {
                    const image = document.querySelector("#image");
                    if (image) {
                        image.addEventListener("change", handleImagePreview);
                    }

                    // Call again Validator when modal appear
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

                    const imageUpdate = document.querySelector("#image-update");
                    if (imageUpdate) {
                        imageUpdate.addEventListener("change", handleImagePreview);
                    }
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

                    if (document.querySelector(".modal-backdrop")) {
                        if (document.querySelector(".modal-container")) {
                            const content = document.getElementById("content");
                            const title = document.getElementById("title");
                            if (content && title) {
                                content.addEventListener("input", function() {
                                    const text = this.value.trim();
                                    const wordCount = text ? text.split(/\s+/).length : 0;
                                    document.getElementById("wordCount").textContent = wordCount;
                                });

                                title.addEventListener("input", function() {
                                    const text = this.value.trim();
                                    const wordCount = text ? text.length : 0;
                                    document.getElementById("characterCount").textContent = wordCount;
                                });
                            }
                        }
                    }

                }
            });
        });

        // Observe change in body (or container of modal)
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        const optionsPostCard = document.querySelector(".post-options");
        if (optionsPostCard) {
            optionsPostCard.addEventListener("click", function(e) {
                const dropdown = optionsPostCard.nextElementSibling;
                dropdown.classList.toggle("hidden");
            });
        }
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

        const deleteComments = document.querySelectorAll(".delete-comment");
        deleteComments.forEach(deleteComment => {
            deleteComment.addEventListener("click", function(e) {
                e.preventDefault();
                checkExistingDropdown(e);
                checkExistingModal();
                const deleteConfirmCard = new ConfirmCard();
                deleteConfirmCard.openConfirmCard(`
                    <h2 class="text-red-600 dark:text-white confirm-title" data-url="${e.target.href}">
                        Are you sure you want to delete this comment?
                    </h2>`);
            });
        });

        const editComments = document.querySelectorAll(".edit-comment");
        editComments.forEach(editComment => {
            editComment.addEventListener("click", function(e) {
                e.preventDefault();
                checkExistingDropdown(e);
                checkExistingModal();
                const editCommentModal = new Modal();
                const commentId = e.target.dataset.commentId;
                const commentContent = e.target.parentElement.parentElement.querySelector("p").textContent.trim();
                editCommentModal.openModal(`
                    <h2 class="text-red-600 dark:text-white text-2xl font-semibold">Edit Comment</h2>
                    <form action="/comment/update/${commentId}" method="POST" class="space-y-4 flex" id="form-update-comment">
                        <textarea name="postCommentContent" class="w-full h-20 resize-none p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white">${commentContent}</textarea>
                        <button class="btn bg-red-700 hover:bg-red-600 transition text-white font-bold" type="submit">Update</button>
                    </form>
                `);
            });
        })
    </script>
</body>

</html>