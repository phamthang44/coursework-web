<?php

/**
 * Post Card Component
 * 
 * A reusable component for displaying post cards with Tailwind CSS
 * Supports dark mode and can be easily integrated with JavaScript DOM
 */

/**
 * Renders a single post card
 * 
 * @param object $post The post object
 * @param array $assets Array of asset objects associated with the post
 * @param bool $showControls Whether to show edit/delete controls
 * @return string HTML for the post card
 */
function render_post_card($post, $assets = [], $showControls = false, $postController = null, $user = null)
{
    // Extract post data
    $postId = $post->getPostId();
    $title = $post->getTitle();
    $content = $post->getContent();
    $moduleId = $postController->getModuleName($post->getModuleId());
    $createdAt = $post->getTimestamp() ?? 'Unknown';
    $updatedAt = $post->getUpdatedTimestamp() ?? 'Unknown';

    // Format dates
    $createdAtFormatted = (is_string($createdAt)) ? $createdAt : date('M d, Y', strtotime($createdAt));
    $updatedAtFormatted = (is_string($updatedAt)) ? $updatedAt : date('M d, Y', strtotime($updatedAt));

    // Get author if available (assuming post has author method)
    $author = method_exists($post, 'getUserId') ? $postController->getUserName($post->getUserId()) : 'Unknown Author';
    $authorObj = $postController->getUser($post->getUserId());

    // Get vote score if available (assuming post has vote method)
    $voteScore = method_exists($post, 'getVoteScore') ? $post->getVoteScore() : 0;
    $voteDisplay = $voteScore > 0 ? "+{$voteScore}" : $voteScore;

    // Start building HTML
    ob_start();
?>
    <div id="post-card-<?= $postId ?>" class="post-card bg-white dark:bg-darkmode rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200 dark:border-gray-700 overflow-hidden mb-4 w-xl h-1vh" data-post-id="<?= $postId ?>"
        data-title="<?= htmlspecialchars($post->getTitle()) ?>"
        data-content="<?= htmlspecialchars($post->getContent()) ?>"
        data-module-id="<?= $post->getModuleId() ?>"
        data-module-name="<?= htmlspecialchars($moduleId) ?>"
        data-post-image="<?= !empty($assets) ? $assets[0]->getMediaKey() : '' ?>">
        <div class="pl-5 pr-5 pb-5">
            <?php
            $postUserId = $postController->getPostUserId($postId);
            if (!is_null($user)) {
                if ($user->getUserId() === $postUserId) {
                    echo '<button class="post-options">
                            <span class="post-card-dot w-8 h-8 rounded-full text-gray-800 dark:text-white">•••</span>
                          </button>';
                    echo '<div class="post-card-dropdown hidden absolute right-12 top-1 mt-2 py-2 w-30 bg-white border border-gray-200 dark:bg-darkmode dark:text-gray-600 rounded-lg shadow-md z-10">
                            <a href="/posts/edit/' . $postId . '" 
                               data-url="/posts/edit/' . $postId . '" 
                               class="edit-action-advanced block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                               Edit (advanced)
                            </a>
                            <button
                               class="edit-action-quick block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                               Edit (quick)
                            </button>
                            <a href="/posts/delete/' . $postId . '" 
                               data-url="/posts/delete/' . $postId . '" 
                               class="delete-action block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                               Delete
                            </a>
                          </div>';
                }
            }

            ?>
            <div class="flex justify-between text-xs text-gray-500 dark:text-white-400 items-center w-full">
                <div class="flex gap-3 mt-4 mb-4">
                    <!-- Author and dates -->
                    <?php if ($authorObj->getProfileImage() != null): ?>
                        <img src="<?php echo $authorObj->getProfileImage(); ?>" alt="<?php echo $author; ?>" class="w-12 h-12 rounded-full cursor-pointer">
                    <?php else: ?>
                        <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 dark:bg-gray-600 dark:text-gray-300 cursor-pointer">
                            <p class="text-lg"><?php echo substr($author, 0, 1); ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="flex gap-1 text-xs flex-col">
                        <p><span class="text-lg text-gray-500 dark:text-white"><?= htmlspecialchars($author) ?></span></p>
                        <div class="flex flex-row gap-3">
                            <p><?= $createdAtFormatted ?></p>
                            <?php if ($createdAtFormatted != $updatedAtFormatted): ?>
                                <p>Updated: <?= $updatedAtFormatted ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- Module badge -->
                <div>
                    <span class=" inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100 text-xs px-2 py-1 rounded-full">
                        Module: <?= htmlspecialchars($moduleId) ?>
                    </span>
                </div>
            </div>
            <!-- Post header with image if available -->
            <?php if (!empty($assets)): ?>
                <div class="post-image-container w-full h-58 overflow-hidden bg-gray-100 dark:bg-gray-700 mt-4 mb-4 rounded-lg">
                    <img src="/<?= htmlspecialchars($assets[0]->getMediaKey()) ?>"
                        alt="Post image"
                        class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            <!-- Post title -->
            <h2 class="text-xl font-bold mb-4 mt-4 text-gray-800 dark:text-gray-100">
                <?= htmlspecialchars($title) ?>
            </h2>
            <!-- Post content with line clamp -->
            <div class="mb-4 text-gray-600 dark:text-gray-300 text-sm line-clamp-6">
                <?= htmlspecialchars($content) ?>
            </div>
            <!-- Post metadata and controls -->
            <div class="flex items-center justify-between text-xl text-gray-500 dark:text-gray-400">
                <!-- Vote score -->
                <div class="vote-score flex items-center" data-score="<?= $voteScore ?>">
                    <button class="upvote-btn p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                    <span class="mx-1 font-bold <?= $voteScore > 0 ? 'text-green-600 dark:text-green-400' : ($voteScore < 0 ? 'text-red-600 dark:text-red-400' : '') ?>">
                        <?= $voteDisplay ?>
                    </span>
                    <button class="downvote-btn p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Action buttons -->
            <div class="mt-4 flex justify-between items-center">
                <div>
                    <?php if ($showControls): ?>
                        <a href="/posts/edit/<?= $postId ?>" class="inline-flex items-center px-3 py-1 mr-2 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <a href="/posts/delete/<?= $postId ?>" class="delete-btn inline-flex items-center px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </a>
                    <?php endif; ?>
                </div>
                <!-- Read more link need to fix here-->
                <a href="/index.php?action=view&postId=<?= $postId ?>" class="inline-block text-blue-600 dark:text-blue-400 hover:underline text-sm">
                    Read more &rarr;
                </a>
            </div>
        </div>
    </div>
<?php
    return ob_get_clean();
}
/**
 * 
 * 
 * @param array $postsData Array of post data arrays
 * @param bool $showControls Whether to show edit/delete controls
 * @return string HTML for the post cards grid
 */
function render_post_cards($postsData, $showControls = false, $postController = null, $user = null)
{
    ob_start();
?>
    <div>
        <?php foreach ($postsData as $postData): ?>
            <?php
            $post = $postData['post'];
            $assets = $postData['assets'] ?? [];
            echo render_post_card($post, $assets, $showControls, $postController, $user);
            ?>
        <?php endforeach; ?>
    </div>
<?php
    return ob_get_clean();
}


?>