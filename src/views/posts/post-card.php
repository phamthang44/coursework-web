<?php

/**
 * Post Card Component
 * 
 * A reusable component for displaying post cards with Tailwind CSS
 * Supports dark mode and can be easily integrated with JavaScript DOM
 */

use utils\SessionManager;

/**
 * Renders a single post card
 * 
 * @param object $post The post object
 * @param array $assets Array of asset objects associated with the post
 * @param bool $showControls Whether to show edit/delete controls
 * @return string HTML for the post card
 */

function render_post_card($post, $assets = [], $showControls = false, $postController = null, $user = null, $voteScore = 0, $currentUser = null, $voteUserStatus = false, $numberComments = 0)
{
    // Extract post data

    $postId = $post->getPostId();
    $title = $post->getTitle();
    $content = $post->getContent();
    $moduleId = $postController->getModuleName($post->getModuleId());
    $createdAt = $post->getCreateDate() ?? 'Unknown';
    $updatedAt = $post->getUpdatedDate() ?? 'Unknown';

    // Format dates
    $createdAtFormatted = date('M d, Y h:i A', strtotime($createdAt));
    $updatedAtFormatted = date('M d, Y h:i A', strtotime($updatedAt));

    // Get author if available (assuming post has author method)

    $author = method_exists($post, 'getUserId') ? $postController->getUserName($post->getUserId()) : 'Unknown Author';
    $authorObj = $postController->getUser($post->getUserId());

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

    $profileLink = 'profile/' . $authorObj->getFirstName() . '-' . $authorObj->getLastName() . '-' . $authorObj->getUserId();
    // Start building HTML
    ob_start();
?>
    <div id="post-card-<?= $postId ?>" class="post-card bg-white dark:bg-darkmode rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200 dark:border-gray-700 overflow-hidden mb-4 w-xl h-1vh" data-post-id="<?= $postId ?>"
        data-title="<?= htmlspecialchars($post->getTitle(), ENT_QUOTES, 'UTF-8') ?>"
        data-content="<?= htmlspecialchars($post->getContent(), ENT_QUOTES, 'UTF-8') ?>"
        data-module-id="<?= $post->getModuleId() ?>"
        data-module-name="<?= htmlspecialchars($moduleId, ENT_QUOTES, 'UTF-8') ?>"
        data-post-image="<?= !empty($assets) ? $assets[0]->getMediaKey() : '' ?>">
        <div class="pl-5 pr-5 pb-5">
            <?php
            $postUserId = $postController->getPostUserId($postId);
            if (!is_null($user)) {
                if ($user->getUserId() === $postUserId) {
                    echo '<button class="post-options">
                            <span class="post-card-dot w-8 h-8 flex justify-center items-center rounded-full text-gray-800 dark:text-white dark:hover:bg-gray-600 hover:bg-gray-300">•••</span>
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
                        <a href="<?php echo removeVietnameseAccents($profileLink); ?>"><img src="<?php echo $authorObj->getProfileImage(); ?>" alt="<?php echo $author; ?>" class="w-12 h-12 rounded-full object-cover"></a>
                    <?php else: ?>
                        <a href="<?php echo removeVietnameseAccents($profileLink); ?>" class="block w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 dark:bg-gray-600 dark:text-gray-300">
                            <p class="text-lg"><?php echo strtoupper(substr($author, 0, 1)); ?></p>
                        </a>
                    <?php endif; ?>
                    <div class="flex gap-1 text-xs flex-col">
                        <p class="flex items-center justify-start">
                            <?php
                            $authorName = htmlspecialchars($author, ENT_QUOTES, 'UTF-8');
                            if ($authorObj->getRole() === 'admin') {
                                echo "<span class='text-lg text-red-500 dark:text-red-600 font-medium'>{$authorName}</span>";
                                echo "<span class='ml-[10px] flex justify-center items-center rounded-full bg-red-600 text-white w-[50px] h-[20px] font-bold px-2'>Admin</span>";
                            } else {
                                $authorClass = 'text-lg text-gray-500 dark:text-white';
                                if (!is_null($currentUser) && $authorObj->getUserId() === $currentUser->getUserId()) {
                                    $authorClass = 'text-lg font-medium text-orange-600 dark:text-orange-600';
                                }
                                echo "<span class='{$authorClass}'>{$authorName}</span>";
                            }
                            ?>
                        </p>

                        <div class="flex flex-row gap-3">
                            <p class="dark:text-[#e6e7e8] text-gray-600"><?= htmlspecialchars($createdAtFormatted, ENT_QUOTES, 'UTF-8') ?></p>
                            <?php if ($createdAtFormatted !== $updatedAtFormatted): ?>
                                <p class="dark:text-[#e6e7e8] text-gray-600">Updated: <?= htmlspecialchars($updatedAtFormatted, ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- Module badge -->
                <div>
                    <span class=" inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100 text-xs px-2 py-1 rounded-full">
                        Module: <?= htmlspecialchars($moduleId, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>
            </div>
            <!-- Post header with image if available -->
            <?php if (!empty($assets)): ?>
                <div class="post-image-container w-full h-58 overflow-hidden bg-gray-100 dark:bg-gray-700 mt-4 mb-4 rounded-lg">
                    <img src="/<?= htmlspecialchars($assets[0]->getMediaKey(), ENT_QUOTES, 'UTF-8') ?>"
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

                <?php if ($currentUser) { ?>
                    <div class="vote-score flex items-center relative w-[100px]" data-score="<?= $voteScore ?>">
                        <button class="vote-btn upvote-btn p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 <?= $voteScore > 0 ? $isActiveUpvote : "" ?>" aria-label="Upvote">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>

                        <span class="absolute right-[40px] block font-bold <?= $isActiveDisplay ?>">
                            <?= $voteDisplay ?>
                        </span>

                        <button class="vote-btn downvote-btn ml-auto p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 <?= $voteScore <= 0 ? $isActiveDownvote : "" ?>" aria-label="Downvote">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                <?php } else { ?>
                    <div class="vote-score flex justify-center items-center relative w-[100px]" data-score="<?= $voteScore ?>">
                        <button class="vote-btn upvote-btn p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 <?= $voteScore > 0 ? $isActiveUpvote : "" ?>" aria-label="Upvote">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                        </button>

                        <span class="absolute right-[40px] block font-bold <?= $isActiveDisplay ?>">
                            <?= $voteDisplay ?>
                        </span>

                        <button class="vote-btn downvote-btn ml-auto p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 <?= $voteScore <= 0 ? $isActiveDownvote : "" ?>" aria-label="Downvote">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                <?php } ?>
                <a href="/post/view/<?= $postId ?>" class="flex items-center space-x-2 text-[14px] text-gray-500 dark:text-gray-400 flex-1 ml-[50px]">
                    <i class="far fa-comment"></i>
                    <span><?= $numberComments > 0 ? $numberComments . " comments" : $numberComments . " comment" ?> </span>
                </a>
                <!-- Read more link need to fix here-->
                <a href="/post/view/<?= $postId ?>" class="inline-block text-blue-600 dark:text-blue-400 hover:underline text-sm">
                    Read more &rarr;
                </a>
            </div>
            <!-- Action buttons -->
            <?php if ($showControls): ?>
                <div class="mt-4 flex justify-between items-center">
                    <div>
                        <a href="/posts/edit/<?= $postId ?>" class="inline-flex items-center px-3 py-1 mr-2 bg-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-xs rounded hover:bg-blue-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <a href="/posts/delete/<?= $postId ?>" class="delete-btn inline-flex items-center px-3 py-1 bg-red-500 dark:bg-red-600 dark:hover:bg-red-700 text-white text-xs rounded hover:bg-red-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </a>
                    </div>

                </div>
            <?php endif; ?>
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
function render_post_cards($postsData, $showControls = false, $postController = null, $user = null, $voteScores = [], $currentUser = null, $votesUserStatus = [], $postCommentController = null)
{
    ob_start();
?>
    <div>
        <?php foreach ($postsData as $postData): ?>
            <?php
            $post = $postData['post'];
            $assets = $postData['assets'] ?? [];
            $postId = $post->getPostId();
            $voteScores = $voteScores ?? []; // ensure not null
            $voteScore = isset($voteScores[$postId]) ? $voteScores[$postId] : 0;
            $voteUserStatus = isset($votesUserStatus[$postId]) ? $votesUserStatus[$postId] : null;
            // $voteUserStatus = isset($votesUserStatus) && isset($votesUserStatus[$post->getPostId()]) ? $votesUserStatus[$post->getPostId()] : null;
            $numberComments = $postCommentController->getNumberComments($postId);

            echo render_post_card($post, $assets, $showControls, $postController, $user, $voteScore, $currentUser, $voteUserStatus, $numberComments);
            ?>
        <?php endforeach; ?>
    </div>
<?php
    return ob_get_clean();
}


?>