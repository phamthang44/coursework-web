<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    // Giả sử bạn đã gửi mảng $posts từ controller

    if (!empty($postsData)):
    ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Create</th>
                    <th>Update</th>
                    <th>Delete</th>
                    <th>Module ID</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postsData as $postData): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($postData['post']->getTitle()); ?></td>
                        <td><?php echo htmlspecialchars($postData['post']->getContent()); ?></td>
                        <td><a href="/index.php?action=create&postId=<?= $postData['post']->getPostId(); ?>">Create</a></td>
                        <td><a href="/index.php?action=edit&postId=<?= $postData['post']->getPostId(); ?>">Update</a></td>
                        <td><a id="delete-btn" href="/index.php?action=delete&postId=<?= $postData['post']->getPostId(); ?>">Delete</a></td>
                        <td><?php echo htmlspecialchars($postData['post']->getModuleId()); ?></td>
                        <td>
                            <?php if (!empty($postData['assets'])): ?>
                                <?php foreach ($postData['assets'] as $asset): ?>
                                    <img src="<?php echo htmlspecialchars('/' . $asset->getMediaKey()); ?>" alt="Post image" width="100" height="100">

                                    <!-- src="/uploads/bd1fa0748aadcc13365f8be654d4dbc7.png" -->

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php
    else:
        echo "No posts available.";
    endif;
    ?>

    <script>
        // Select all delete buttons
        let deleteLinks = document.querySelectorAll("#delete-btn");

        function askConfirm(event) {
            event.preventDefault(); // Prevent the default link behavior
            let confirmation = confirm('Are you sure?');
            if (confirmation) {
                window.location.href = event.target.href; // Redirect to the link's href if confirmed
            }
        }

        // Attach the event listener to each delete button
        deleteLinks.forEach(function(deleteLink) {
            deleteLink.addEventListener('click', askConfirm);
        });
    </script>
</body>

</html>