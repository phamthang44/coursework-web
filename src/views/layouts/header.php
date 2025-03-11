<?php

/**
 * Quora-style Header Component
 * 
 * A reusable PHP component that mimics the Quora header using Tailwind CSS.
 * This file can be included in other PHP files.
 */

function render_quora_header($user_logged_in = false, $user_name = '', $user_avatar = '')
{
    // Sanitize inputs for security
    $user_name = htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');
    $user_avatar = htmlspecialchars($user_avatar, ENT_QUOTES, 'UTF-8');

    ob_start();
?>
    <header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
        <div class="container mx-auto px-4 flex items-center justify-between h-14">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="index.php" class="text-red-600 font-bold text-2xl mr-4">Quora</a>

                <!-- Search Bar -->
                <div class="relative hidden md:block">
                    <input type="text" placeholder="Search Quora" class="w-64 bg-gray-100 rounded-sm pl-10 pr-4 py-1 border border-gray-300 focus:outline-none focus:border-red-400 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex items-center space-x-4">
                <a href="index.php" class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded text-sm font-medium">Home</a>
                <a href="#" class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded text-sm font-medium">Following</a>
                <a href="#" class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded text-sm font-medium">Answer</a>
                <a href="#" class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded text-sm font-medium">Spaces</a>
                <a href="#" class="text-gray-700 hover:bg-gray-100 px-3 py-2 rounded text-sm font-medium">Notifications</a>
            </nav>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-gray-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- User Menu / Auth Links -->
            <div class="flex items-center">
                <?php if ($user_logged_in): ?>
                    <div class="relative">
                        <button class="flex items-center space-x-2 focus:outline-none">
                            <?php if ($user_avatar): ?>
                                <img src="<?php echo $user_avatar; ?>" alt="<?php echo $user_name; ?>" class="w-8 h-8 rounded-full">
                            <?php else: ?>
                                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                                    <?php echo substr($user_name, 0, 1); ?>
                                </div>
                            <?php endif; ?>
                            <span class="hidden md:inline text-sm"><?php echo $user_name; ?></span>
                        </button>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="text-sm text-gray-700 hover:underline mr-4">Login</a>
                    <a href="signup.php" class="text-white bg-red-600 hover:bg-red-700 px-4 py-1 rounded-full text-sm font-medium">Sign up</a>
                <?php endif; ?>

                <!-- Add Question Button -->
                <a href="#" class="ml-4 text-white bg-red-600 hover:bg-red-700 px-4 py-1 rounded-full text-sm font-medium">Add Question</a>
            </div>
        </div>
    </header>
<?php
    return ob_get_clean();
}
