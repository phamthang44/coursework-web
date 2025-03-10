<!DOCTYPE html>
<html lang="en">

<head>
    <title>Form Upload</title>
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
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Upload Form</h2>
        <form action="index.php?action=store" method="POST" enctype="multipart/form-data">
            <!-- Title Field (Optional) -->
            <div class="form-group">
                <label for="title">Title (Optional):</label>
                <input type="text" id="title" name="title" placeholder="Enter title (optional)">
            </div>

            <!-- Content Field (Required) -->
            <div class="form-group">
                <label for="content">Content (Required):</label>
                <textarea id="content" name="content" rows="5" required placeholder="Enter content"></textarea>
            </div>

            <!-- Module Select -->
            <div class="form-group">
                <label for="module">Module Name:</label>
                <select id="module" name="module" required>
                    <option value="">-- Select Module --</option>
                    <?php foreach ($modules as $module): ?>
                        <option value="<?php echo $module->getModuleId(); ?>"><?php echo $module->getModuleName(); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>

</html>