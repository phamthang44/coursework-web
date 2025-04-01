<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="This is edit post page of QuoraeHub, where users can edit their posts.">
    <meta name="author" content="QuoraeHub Team">
    <title>QuoraeHub</title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="bg-white dark:bg-darkmode2">
    <?php
    // User authentication setup
    use utils\SessionManager;
    use utils\Template;

    Template::header();
    Template::footer();

    $currentUser = SessionManager::get('user');
    if ($currentUser->getStatus() === 'banned') {
        SessionManager::set('error', 'You are banned from the site');
        header('Location: /403');
    }
    if (isset($_SESSION['user_id'])) {
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
    $post = $postController->getPostByIdAndUserId($postId, $userId);

    if (SessionManager::get('role') === 'admin') {
        $post = $postAdminDisplay;
    }

    $postContentArray = explode(' ', $post->getContent());
    $postContentCountWords = count($postContentArray);
    $postTitleLength = strlen($post->getTitle());

    echo render_quora_header($user_logged_in, $user_name, $user_avatar, $user_email, $user);
    ?>
    <div class="container mx-auto py-6 w-4/5 rounded-lg">
        <h2 class="text-2xl text-red-500 font-bold mb-4">Edit post</h2>
        <form action="/posts/update/<?= $post->getPostId() ?>" method="POST" enctype="multipart/form-data" id="form-upload-post" class="space-y-4">
            <!-- Title Field (Optional) -->
            <div class="form-group py-4 mb-4">
                <label for="title" class="block font-medium text-gray-700 dark:text-white mb-4">Title:</label>
                <p class="font-medium text-gray-700 dark:text-white mb-4">Characters count: <span id="characterCount"><?php echo $postTitleLength; ?></span></p>
                <input type="text" id="title" name="title" placeholder="Enter new title"
                    class="w-full h-12 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"
                    value="<?php echo $post->getTitle() ?>">
                <span class="form-message text-red-500 text-sm"></span>
                <input type="hidden" name="user_id" value="<?php echo $user->getUserId(); ?>">
            </div>

            <!-- Content Field (Required) -->
            <div class="form-group">
                <label for="content" class="block font-medium text-gray-700 dark:text-white mb-4">Content (Required):</label>
                <p class="font-medium text-gray-700 dark:text-white mb-4">Word count: <span id="wordCount"><?= $postContentCountWords ?></span></p>
                <textarea id="content" name="content" rows="5" placeholder="Enter new content"
                    class="w-full h-[300px] p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"><?php echo $post->getContent(); ?></textarea>
                <span class="form-message text-red-500 font-medium text-sm"></span>
            </div>

            <!-- Module Select -->
            <div class="form-group">
                <label for="module" class="block font-medium text-gray-700 dark:text-white mb-4">Module Name:</label>
                <select id="module" name="module"
                    class="w-50 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 text-black dark:text-white">
                    <?php foreach ($modules as $module): ?>
                        <?php if (!$moduleName) { ?>
                            <option value="" class="text-black dark:text-white"> -- Select Module -- </option>
                        <?php } ?>
                        <option class="text-black dark:text-white" value="<?php echo $module->getModuleId(); ?>"><?php echo $module->getModuleName(); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form-message text-red-500 font-medium text-sm ml-5"></span>
            </div>

            <!-- Image Upload -->
            <div class="form-group flex gap-7 flex-col relative">
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
                <div id="preview-container" class="absolute -top-[110px] right-[400px] mt-4 pt-4">
                    <?php if ($postImage) { ?>
                        <h3 class="preview-label font-medium text-gray-700 dark:text-white">Preview Image:</h3>
                        <img id="preview" src="/<?php echo $postImage; ?>" alt="Preview Image" class="w-[500px] h-[500px] object-cover mt-1 rounded-lg border border-gray-300" />
                    <?php } else { ?>
                        <h3 class="preview-label font-medium text-gray-700 dark:text-white hidden">Preview Image:</h3>
                        <img id="preview" src="" alt="Preview Image" class="w-[500px] h-[500px] object-cover mt-1 rounded-lg border border-gray-300 hidden" />
                    <?php } ?>
                </div>
                <input class="w-[200px] h-[40px] rounded-lg bg-red-700 hover:bg-red-600 transition text-white font-bold" type="submit" value="Update post">
            </div>
        </form>
    </div>
    <div class="mt-[300px]"></div>
    <?php
    echo render_quora_footer();
    ?>
    <script src="/js/validator.js"></script>
    <script>
        Validator({
            form: "#form-upload-post",
            formGroupSelector: ".form-group",
            formMessage: ".form-message",
            rules: [
                Validator.isRequired("#content"),
                Validator.isRequiredSelection("#module"),
            ],
        });

        const image = document.getElementById("image");
        image.addEventListener('change', function(e) {
            let file = e.target.files[0];
            let reader = new FileReader();

            //read file, show image
            reader.onload = function(e) {
                let preview = document.getElementById("preview");
                console.log(preview);
                preview.src = e.target.result;
                document.getElementById("preview-container").style.display = "block"; // Show preview container
                const previewLabel = document.getElementById("preview-container").querySelector(".preview-label");
                const imgShow = document.getElementById("preview-container").querySelector("#preview");
                if (previewLabel.classList.contains("hidden") && imgShow.classList.contains("hidden")) {
                    imgShow.classList.remove("hidden");
                    previewLabel.classList.remove("hidden");
                }

            };

            //if file , start read
            if (file) {
                reader.readAsDataURL(file);
            }
        });

        document.getElementById("content").addEventListener("input", function() {
            const text = this.value.trim();
            const wordCount = text ? text.split(/\s+/).length : 0;
            document.getElementById("wordCount").textContent = wordCount; // update immediately
        });

        document.getElementById("title").addEventListener("input", function() {
            const text = this.value.trim();
            const characterCount = text ? text.length : 0;
            document.getElementById("characterCount").textContent = characterCount; // update immediately
        });
    </script>
</body>

</html>