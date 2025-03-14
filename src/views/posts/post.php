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
    use controllers\ModuleController;
    use controllers\UserController;
    use controllers\PostController;

    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../layouts/footer.php';
    require_once __DIR__ . '/../../controllers/UserController.php';
    require_once __DIR__ . '/../posts/post-card.php'; // Include our new post-card component

    $userController = new UserController();
    $postController = new PostController();
    $moduleController = new ModuleController();



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

    $post = $postController->getPostByIdAndUserId($postId, $userId);
    $moduleName = $moduleController->getModule($post->getModuleId());
    $postImageObj = $postController->getPostImage($post->getPostId());
    $postImageStr = $postImageObj->getMediaKey();
    $postImage = $postImageStr ?? '';
    echo render_quora_header($user_logged_in, $user_name, $user_avatar, $user_email);
    ?>

    <div class="container mx-auto py-6 w-1/3">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 px-4">Posts</h1>
        <?php
        // Render create button if user is logged in
        // if ($user_logged_in) {
        //     echo render_create_post_button();
        // }
        if ($user_logged_in) {
            if ($user->getRole() === 'user') {
                $showControls = false;
            } else {
                $showControls = true;
            }
        }

        // Render posts with grid layout if posts exist
        if (!empty($postsData)) {
            echo render_post_cards($postsData, $showControls, $postController, $user);
        } else {
            echo '<div class="p-4 text-gray-600 dark:text-gray-300">No posts available.</div>';
        }


        ?>
    </div>

    <?php
    echo render_quora_footer();
    ?>
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
                            addQuestionDropdown.classList.add('hidden');
                            // checkExistingDropdown();
                            checkExistingModal();
                            const modal = new Modal();
                            modal.openModal(`
        <h2 class="text-xl text-red-500 font-bold mb-4">Create new post</h2>
        <form action="/posts/store" method="POST" enctype="multipart/form-data" id="form-upload-post" class="space-y-4">
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
                    <input class="btn bg-red-700 hover:bg-red-600 transition text-white font-bold" type="submit" value="Submit">
                </div>
            </form>
`);
                        }
                    });
                }

            }
        }

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

        //delete confirm
        // const deleteConfirmCard = new ConfirmCard();
        // deleteConfirmCard.openConfirmCard(`<h2 class="text-red-600 dark:text-white">Are you sure you want to delete this post?</h2>`);


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
                }
            });
        });

        // Observe change in body (or container of modal)
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        function handleImagePreview(e) {
            let file = e.target.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                let preview = document.getElementById("preview");
                preview.src = e.target.result;
                document.getElementById("preview-container").style.display = "block";
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function setupEventListeners() {
            const optionsPostCard = document.querySelectorAll('.post-options');
            optionsPostCard.forEach((option) => {
                option.addEventListener("click", function(e) {
                    const dropdown = option.nextElementSibling;
                    dropdown.classList.toggle("hidden");

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
                        }
                        if (e.target.classList.contains("edit-action-quick")) {
                            // e.preventDefault();
                            // e.stopPropagation();

                            checkExistingDropdown(e);
                            checkExistingModal();

                            const editModalQuick = new Modal();
                            editModalQuick.openModal(`<h2 class="text-xl text-red-500 font-bold mb-4">Edit the post</h2>
                            <form action="/posts/update/<?php echo $post->getPostId(); ?>" method="POST" enctype="multipart/form-data" id="form-update-post" class="space-y-4">
                                <!-- Title Field (Optional) -->
                                <div class="form-group py-4 mb-4">
                                    <label for="title" class="block font-medium text-gray-700 dark:text-white mb-4">Title (Optional):</label>
                                    <input type="text" id="title" name="title" placeholder="Enter title (optional)"
                                        class="w-full h-12 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"
                                        value="<?php echo $post->getTitle(); ?>">
                                    <span class="form-message text-red-500 text-sm"></span>
                                    <input type="hidden" name="user_id" value="<?php echo $user->getUserId(); ?>">
                                </div>
                                <!-- Content Field (Required) -->
                                <div class="form-group">
                                    <label for="content" class="block font-medium text-gray-700 dark:text-white mb-4">Content (Required):</label>
                                    <textarea id="content" name="content" rows="5" placeholder="Enter content"
                                        class="w-full h-40 resize-none p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"><?php echo $post->getContent(); ?></textarea>
                                    <span class="form-message text-red-500 font-medium text-sm"></span>
                                </div>
                                <!-- Module Select -->
                                <div class="form-group">
                                    <label for="module" class="block font-medium text-gray-700 dark:text-white mb-4">Module Name:</label>
                                    <select id="module" name="module"
                                        class="w-50 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 text-black dark:text-white">
                                        <option value="" class="text-black dark:text-white">Selected : <?= $moduleName ?></option>
                                        <?php foreach ($modules as $module): ?>
                                            <option class="text-black dark:text-white" value="<?php echo $module->getModuleId(); ?>"><?php echo $module->getModuleName(); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="form-message text-red-500 font-medium text-sm ml-5"></span>
                                </div>
                                <!-- Image Upload -->
                                <div class="form-group flex gap-7 relative">
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
                                    <div id="preview-container" class="absolute -top-[100px] left-[440px]">
                                        <h3 class="font-medium text-gray-700 dark:text-white">Preview Image:</h3>
                                        <img id="preview" src="<?= $postImage ? $postImage : "" ?>" alt="Preview Image" class="w-80 h-40 object-cover mt-2 rounded-lg border border-gray-300" />
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="flex justify-end">
                                    <input class="btn bg-red-700 hover:bg-red-600 transition text-white font-bold" type="submit" value="Save">
                                </div>
                            </form>
`);
                        }
                    });

                });
            });


        }
        requestAnimationFrame(() => {
            setupEventListeners();
        });

        function checkExistingModal() {
            const existingModal = document.querySelector(".modal-backdrop");
            if (existingModal) {
                existingModal.remove();
            }
        }

        function checkExistingDropdown(e) {
            const dropdown = e.target.closest(".post-card-dropdown ");
            if (dropdown) {
                dropdown.classList.add("hidden");
            }
        }
    </script>

</body>

</html>