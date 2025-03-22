<?php

use controllers\UserController;
use controllers\PostCommentController;

function renderCommentTree($comments, $postId, $parentId = null, $level = 0, $postAuthor = null, $currentUser = null)
{
    $commentTag = '';
    $userController = new UserController();
    $postCommentController = new PostCommentController();
    foreach ($comments as $comment) {
        if ($comment->getParentCommentId() === $parentId) {
            $commentUser = $userController->getUser($comment->getPostCommentUserId()); // Lấy user của comment
            $fullName = $commentUser ? htmlspecialchars($commentUser->getFirstName() . " " . $commentUser->getLastName()) : 'Unknown User';
            $profileLink = $commentUser ? '/profile/' . $commentUser->getFirstName() . "-" . $commentUser->getLastName() . "-" . $commentUser->getUserId() : '#';
            $isPostAuthor = $commentUser && $postAuthor && $commentUser->getUserId() === $postAuthor->getUserId();
            $isCurrentUser = $commentUser && $currentUser && $commentUser->getUserId() === $currentUser->getUserId();


            ob_start(); ?>
            <div class="flex mt-[20px] space-x-4 <?= $level > 0 ? 'pl-' . min(6 * $level, 24) . ' border-l border-gray-300 dark:border-gray-700' : '' ?>">
                <a href="<?= $profileLink ?>" class="h-fit w-fit flex-shrink-0">
                    <?php if ($commentUser->getProfileImage()) { ?>
                        <img class="h-10 w-10 rounded-full" src="/<?= $commentUser->getProfileImage(); ?>" alt="Your avatar">
                    <?php } else { ?>
                        <div class="block w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 dark:bg-gray-600 dark:text-gray-300">
                            <p class="text-lg"><?= strtoupper(substr($commentUser->getUsername(), 0, 1)); ?></p>
                        </div>
                    <?php } ?>
                </a>
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <h3 class="font-medium mr-2"><?= $fullName ?></h3>
                        <?php if ($isPostAuthor): ?>
                            <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full mr-3">Author</span>
                        <?php endif; ?>
                        <span class="text-sm text-gray-500 dark:text-gray-400"><?= $comment->timeAgo($comment->getPostCommentTimeStamp()) ?></span>
                    </div>
                    <p class="text-gray-800 dark:text-gray-300 mb-2">
                        <?= nl2br(htmlspecialchars($comment->getPostCommentContent())) ?>
                    </p>
                    <div class="flex items-center space-x-4 text-sm">
                        <?php $voteData = $postCommentController->getVoteData($comment->getPostCommentId(), $currentUser->getUserId()) ?>
                        <span class="like-count"><?= $voteData['voteScore'] ?></span>
                        <button class="text-gray-500 hover:text-gray-700 like-button" data-comment-id="<?= $comment->getPostCommentId() ?>">
                            <?php if ($voteData['userLiked']) { ?>
                                <i class="far fa-thumbs-up text-white icon-active"></i>
                            <?php } else { ?>
                                <i class="far fa-thumbs-up"></i>
                            <?php } ?>
                        </button>
                        <button class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 reply-button" data-comment-id="<?= $comment->getPostCommentId() ?>">
                            Reply
                        </button>
                        <?php if ($isCurrentUser): ?>
                            <button class="text-blue-500 hover:text-blue-700 edit-comment" data-comment-id="<?= $comment->getPostCommentId() ?>">Edit</button>
                            <button class="text-red-500 hover:text-red-700 delete-comment" data-comment-id="<?= $comment->getPostCommentId() ?>">Delete</button>
                        <?php endif; ?>
                    </div>

                    <!-- Reply form (hide as default) -->
                    <form class="hidden reply-form mt-2" action="/reply" method="POST" data-comment-id="<?= $comment->getPostCommentId() ?>">
                        <input type="hidden" name="postId" value="<?= $postId ?>">
                        <input type="hidden" name="parentCommentId" value="<?= $comment->getPostCommentId() ?>">
                        <input type="text" name="postCommentContent" class="w-full border border-gray-300 rounded-lg p-3 dark:bg-darkmode2" placeholder="Reply to this comment..." autocomplete="off">
                        <div class="flex space-x-2 mt-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                                Comment
                            </button>
                            <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium cancel-reply-comment">Cancel</button>
                        </div>
                    </form>

                    <!-- Show all comments child -->
                    <?= renderCommentTree($comments, $postId, $comment->getPostCommentId(), $level + 1, $postAuthor, $currentUser); ?>
                </div>
            </div>
<?php
            $commentTag .= ob_get_clean();
        }
    }
    return $commentTag;
}
?>