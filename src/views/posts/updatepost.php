<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update post</title>
    <style>
        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
        }

        .error {
            color: red;
        }

        .form-message {
            color: red;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Upload Form</h2>
        <form action="/posts/update/<?= $post->getPostId(); ?>" method="POST" enctype="multipart/form-data" id="form-upload-post">
            <!-- Title Field (Optional) -->
            <div class="form-group">
                <label for="title">Title (Optional):</label>
                <input type="text" id="title" name="title" placeholder="<?php echo $post->getTitle(); ?>" value="<?php echo $post->getTitle(); ?>">
                <span class="form-message"></span>
            </div>

            <!-- Content Field (Required) -->
            <div class="form-group">
                <label for="content">Content (Required):</label>
                <textarea id="content" name="content" rows="5" placeholder="Enter content" value="<?php echo $post->getContent(); ?>"></textarea>
                <span class="form-message"></span>
            </div>

            <!-- Module Select -->
            <div class="form-group">
                <label for="module">Module Name:</label>
                <select id="module" name="module">
                    <option value="">-- Select Module --</option>
                    <?php foreach ($modules as $module): ?>
                        <option value="<?php echo $module->getModuleId(); ?>"><?php echo $module->getModuleName(); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form-message"></span>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <span class="form-message"></span>
            </div>

            <!-- Preview Image -->
            <div id="preview-container" style="display:none;">
                <h3>Preview Image:</h3>
                <img id="preview" src="" alt="Preview Image" style="width: 200px;" />
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
        </form>

    </div>
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
                preview.src = e.target.result;
                document.getElementById("preview-container").style.display = "block"; // Show preview container
            };

            //if file , start read
            if (file) {
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>