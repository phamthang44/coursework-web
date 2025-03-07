<?php
// Giả sử bạn đã gửi mảng $posts từ controller
if (!empty($posts)):
    ?>
    <table border="1">
        <thead>
        <tr>
            <th>Title</th>
            <th>Content</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo htmlspecialchars($post->getTitle()); ?></td>
                <td><?php echo htmlspecialchars($post->getContent()); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php
else:
    echo "No posts available.";
endif;
?>
