<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
        integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
        crossorigin="anonymous" />
    <title>QuoraeHub</title>
    <style>
        /* Add required line-clamp utility if not provided by Tailwind */
        .line-clamp-6 {
            display: -webkit-box;
            -webkit-line-clamp: 6;
            line-clamp: 6;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-darkmode2 relative" id="home-page">
    <div class="overlay fixed z-[1] top-0 left-0 w-full h-full bg-[#222222] hidden opacity-45 transition-opacity duration-300"></div>
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

    $user = null;

    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $user = $userController->getUser($userId);
        $user_logged_in = true;
        $user_name = $user->getUsername();
        $user_avatar = $user->getProfileImage() ?? '';
        $user_email = $user->getEmail();
        $currentUser = SessionManager::get('user');
    } else {
        $user_logged_in = false;
        $user_name = '';
        $user_avatar = '';
        $user_email = '';
    }
    if (isset($user)) {
        $userObj = $user;
    } else {
        $userObj = null;
    }
    $showControls = false;
    echo render_quora_header($user_logged_in, $user_name, $user_avatar, $user_email, $userObj);
    ?>
    <main>
        <div class="container mx-auto py-6 w-1/3">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 px-4">Posts</h1>
            <?php
            if ($user_logged_in) {
                if (!is_null($user)) {
                    if ($user->getRole() === 'user') {
                        $showControls = false;
                    } else {
                        $showControls = true;
                    }
                } else {
                    $showControls = false;
                }
            }
            if (!empty($postsData)) {
                echo render_post_cards($postsData, $showControls, $postController, $user, $voteScores, $currentUser, $votesUserStatus);
            } else {
                echo '<div class="p-4 text-gray-600 dark:text-gray-300">No posts available.</div>';
            }
            ?>
        </div>
        <div class="flex items-center justify-center space-x-2 mt-8">
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
    </main>
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
                            dropdown.classList.add("hidden");
                        }
                        if (e.target.classList.contains("edit-action-advanced")) {
                            dropdown.classList.add("hidden");
                        }
                        if (e.target.classList.contains("edit-action-quick")) {
                            // e.preventDefault();
                            // e.stopPropagation();

                            checkExistingDropdown(e);
                            checkExistingModal();

                            // Get the post ID from the clicked element's data attribute
                            const postCard = e.target.closest('.post-card');
                            const postId = postCard.dataset.postId;

                            // Get post data (you'll need to add this data to your HTML elements)
                            const postTitle = postCard.dataset.title || '';
                            const postContent = postCard.dataset.content || '';
                            const postModuleId = postCard.dataset.moduleId || '';
                            const postModuleName = postCard.dataset.moduleName || '';
                            const postImage = postCard.dataset.postImage;
                            let hiddenClass = 'hidden';
                            if (postImage) {
                                hiddenClass = '';
                            }

                            const editModalQuick = new Modal();
                            editModalQuick.openModal(`<h2 class="text-xl text-red-500 font-bold mb-4">Edit the post</h2>
                                    <form action="/posts/update/${postId}" method="POST" enctype="multipart/form-data" id="form-update-post" class="space-y-4">
                                        <!-- Title Field (Optional) -->
                                        <div class="form-group py-4 mb-4">
                                            <div class="flex">
                                                <label for="title" class="block font-medium text-gray-700 dark:text-white mb-4">Title (Optional) Max(100 characters):</label>
                                                <p class="ml-auto font-medium text-gray-700 dark:text-white mb-4">Characters count: <span id="characterCount">0</span></p>
                                            </div>
                                            <input type="text" id="title" name="title" placeholder="Enter title (optional)"
                                                class="w-full h-12 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"
                                                value="${postTitle}">
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
                                                class="w-full h-40 resize-none p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white">${postContent}</textarea>
                                            <span class="form-message text-red-500 font-medium text-sm"></span>
                                        </div>
                                        <!-- Module Select -->
                                        <div class="form-group">
                                            <label for="module" class="block font-medium text-gray-700 dark:text-white mb-4">Module Name:</label>
                                            <select id="module" name="module"
                                                class="w-50 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 text-black dark:text-white">
                                                <option value="" class="text-black dark:text-white">Selected : ${postModuleName}</option>
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
                                            <div id="preview-container" class="${hiddenClass} absolute -top-[100px] left-[440px]">
                                                <h3 class="font-medium text-gray-700 dark:text-white">Preview Image:</h3>
                                                <img id="preview" src="${postImage}" alt="Preview Image" class="w-80 h-40 object-cover mt-2 rounded-lg border border-gray-300" />
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


        const deleteBtnsAdmin = document.querySelectorAll(".delete-btn");
        deleteBtnsAdmin.forEach((btn) => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                checkExistingModal();
                const deleteConfirmCard = new ConfirmCard();
                deleteConfirmCard.openConfirmCard(`
<h2 class="text-red-600 dark:text-white confirm-title" data-url="${e.target.href}">
    Are you sure you want to delete this post?
</h2>`);
            });
        });
        <?php
        $error = SessionManager::get('error');
        SessionManager::remove('error');
        ?>
        const ErrorModal = new Modal();
        const messageError = <?php echo json_encode($error, JSON_HEX_TAG); ?>;
        if (messageError) {
            ErrorModal.openModal(`<div class="error-modal">
                                    <h1 class="text-2xl text-red-600 dark:text-red-500 font-medium mb-4">Error !</h1>
                                    <h2 class="text-gray-600 dark:text-white ">${messageError} !</h2>
                                </div>`);
        }
    </script>

</body>

</html>