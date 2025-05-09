<?php

/**
 * Support function to convert Vietnamese characters to English characters
 * 
 * @param string $string The string to be converted
 * @return string The converted string
 */
function removeVietnameseAccents($string)
{
    $unwanted = [
        'à' => 'a',
        'á' => 'a',
        'ả' => 'a',
        'ã' => 'a',
        'ạ' => 'a',
        'ă' => 'a',
        'ằ' => 'a',
        'ắ' => 'a',
        'ẳ' => 'a',
        'ẵ' => 'a',
        'ặ' => 'a',
        'â' => 'a',
        'ầ' => 'a',
        'ấ' => 'a',
        'ẩ' => 'a',
        'ẫ' => 'a',
        'ậ' => 'a',
        'è' => 'e',
        'é' => 'e',
        'ẻ' => 'e',
        'ẽ' => 'e',
        'ẹ' => 'e',
        'ê' => 'e',
        'ề' => 'e',
        'ế' => 'e',
        'ể' => 'e',
        'ễ' => 'e',
        'ệ' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'ỉ' => 'i',
        'ĩ' => 'i',
        'ị' => 'i',
        'ò' => 'o',
        'ó' => 'o',
        'ỏ' => 'o',
        'õ' => 'o',
        'ọ' => 'o',
        'ô' => 'o',
        'ồ' => 'o',
        'ố' => 'o',
        'ổ' => 'o',
        'ỗ' => 'o',
        'ộ' => 'o',
        'ơ' => 'o',
        'ờ' => 'o',
        'ớ' => 'o',
        'ở' => 'o',
        'ỡ' => 'o',
        'ợ' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'ủ' => 'u',
        'ũ' => 'u',
        'ụ' => 'u',
        'ư' => 'u',
        'ừ' => 'u',
        'ứ' => 'u',
        'ử' => 'u',
        'ữ' => 'u',
        'ự' => 'u',
        'ỳ' => 'y',
        'ý' => 'y',
        'ỷ' => 'y',
        'ỹ' => 'y',
        'ỵ' => 'y',
        'đ' => 'd',
        'À' => 'A',
        'Á' => 'A',
        'Ả' => 'A',
        'Ã' => 'A',
        'Ạ' => 'A',
        'Ă' => 'A',
        'Ằ' => 'A',
        'Ắ' => 'A',
        'Ẳ' => 'A',
        'Ẵ' => 'A',
        'Ặ' => 'A',
        'Â' => 'A',
        'Ầ' => 'A',
        'Ấ' => 'A',
        'Ẩ' => 'A',
        'Ẫ' => 'A',
        'Ậ' => 'A',
        'È' => 'E',
        'É' => 'E',
        'Ẻ' => 'E',
        'Ẽ' => 'E',
        'Ẹ' => 'E',
        'Ê' => 'E',
        'Ề' => 'E',
        'Ế' => 'E',
        'Ể' => 'E',
        'Ễ' => 'E',
        'Ệ' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Ỉ' => 'I',
        'Ĩ' => 'I',
        'Ị' => 'I',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ỏ' => 'O',
        'Õ' => 'O',
        'Ọ' => 'O',
        'Ô' => 'O',
        'Ồ' => 'O',
        'Ố' => 'O',
        'Ổ' => 'O',
        'Ỗ' => 'O',
        'Ộ' => 'O',
        'Ơ' => 'O',
        'Ờ' => 'O',
        'Ớ' => 'O',
        'Ở' => 'O',
        'Ỡ' => 'O',
        'Ợ' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Ủ' => 'U',
        'Ũ' => 'U',
        'Ụ' => 'U',
        'Ư' => 'U',
        'Ừ' => 'U',
        'Ứ' => 'U',
        'Ử' => 'U',
        'Ữ' => 'U',
        'Ự' => 'U',
        'Ỳ' => 'Y',
        'Ý' => 'Y',
        'Ỷ' => 'Y',
        'Ỹ' => 'Y',
        'Ỵ' => 'Y',
        'Đ' => 'D'
    ];
    return strtr($string, $unwanted);
}
/**
 * Quora-style Header Component
 * 
 * A reusable PHP component that mimics the Quora header using Tailwind CSS.
 * This file can be included in other PHP files.
 */

function render_quora_header($user_logged_in = false, $user_name = '', $user_avatar = '', $user_email = '', $user = null)
{
    // Sanitize inputs for security
    if ($user_avatar !== null) {
        $user_avatar = htmlspecialchars($user_avatar, ENT_QUOTES, 'UTF-8');
    }
    $user_name = htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8');
    //$user_avatar = htmlspecialchars($user_avatar, ENT_QUOTES, 'UTF-8');
    $profileLink = '';
    if (!is_null($user)) {
        if ($user->getRole() === 'user') {
            $profileLink = 'profile/' . $user->getFirstName() . "-" . $user->getLastName() . "-" . $user->getUserId();
        } else {
            $profileLink = $user->getRole() . '/profile/' . $user->getFirstName() . "-" . $user->getLastName() . "-" . $user->getUserId(); //admin/Thang-Pham-1
        }
    }
    ob_start();
?>
    <header class="sticky top-0 z-50 bg-white shadow-sm dark:text-white dark:bg-darkmode transition-colors duration-300">
        <div class="container mx-auto px-4 flex items-center justify-between h-14">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/quorae" class="text-red-600 font-bold text-2xl mr-4">QuoraeHub</a>

                <!-- Search Bar -->
                <div class="relative hidden md:block search-bar">
                    <?php if ($user_logged_in) {
                        if ($_SERVER['REQUEST_URI'] === '/posts/create/') {
                            echo '';
                        } else if (str_contains($_SERVER['REQUEST_URI'], '/posts/edit/')) {
                            echo '';
                        } else {
                            echo '<input type="text" name="searchInput" placeholder="Search QuoraeHub" class="search-input w-64 bg-gray-100 dark:bg-[#181818] dark:text-white rounded-xs pl-10 pr-4 py-1 border border-[#2b2b2b] focus:outline-none focus:border-red-400 focus:w-[400px] transition-all duration-500 text-sm" autocomplete="off">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-2 text-gray-500 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>';
                        }
                    } ?>
                    <div class="search-results absolute top-[calc(100%)] left-0 bg-white dark:bg-darkmode border border-gray-300 w-[400px] h-[350px] z-[60] opacity-0 scale-y-0 origin-top transition-all duration-500 hidden"></div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex items-center space-x-4">
                <a href="/quorae" class="text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 px-3 py-2 rounded text-sm font-medium">Home</a>
                <a href="/contact" class="text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 px-3 py-2 rounded text-sm font-medium">Contact us</a>
                <?php
                if ($user_logged_in) {
                    if ($user !== null) {
                        if ($user->getRole() === 'user') {
                            echo '<a href="/posts/create" class="text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 px-3 py-2 rounded text-sm font-medium">Create post</a>';
                            echo '<button class="help-btn text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 px-3 py-2 rounded text-sm font-medium">Help</button>';
                        }
                    }
                }
                ?>
                <?php
                if ($user_logged_in) {
                    if ($user !== null) {
                        if ($user->getRole() === 'admin') {
                            echo '<a href="/admin/dashboard" class="text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 px-3 py-2 rounded text-sm font-medium">Admin Dashboard</a>';
                            echo '<a href="/messages" class="text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 px-3 py-2 rounded text-sm font-medium">Messages Box</a>';
                        } else {
                            echo '';
                        }
                    }
                }
                ?>
            </nav>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-gray-700 dark:text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- User Menu / Auth Links with Dropdown -->
            <div class="flex items-center relative">
                <?php if ($user_logged_in): ?>
                    <div class="relative">
                        <button id="avatar-menu-button" class="flex items-center space-x-2 focus:outline-none">
                            <?php if ($user_avatar): ?>
                                <img src="/<?php echo $user_avatar; ?>" class="w-8 h-8 rounded-full cursor-pointer avatar-user object-cover" alt="">
                            <?php else: ?>
                                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 dark:bg-gray-600 dark:text-gray-300 cursor-pointer">
                                    <?php echo strtoupper(substr(htmlspecialchars($user_name), 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <span class="hidden md:inline text-sm dark:text-gray-300"><?php echo htmlspecialchars($user_name); ?></span>
                            <!-- Dropdown arrow -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="avatar-dropdown" class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg py-1 z-50 hidden dark:bg-darkmode border border-gray-200 dark:border-gray-700">
                            <!-- User Profile Header -->
                            <div class="px-2 py-3 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <?php if ($user_avatar): ?>
                                        <img src="/<?php echo $user_avatar; ?>" alt="" class="w-12 h-12 rounded-full mr-3 avatar-user object-cover">
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 dark:bg-gray-600 dark:text-gray-300 mr-3">
                                            <?php echo strtoupper(substr(htmlspecialchars($user_name), 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 dark:text-white"><?php echo htmlspecialchars($user_name); ?></h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo $user_email; ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <a href="/<?php echo removeVietnameseAccents($profileLink); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    See Profile
                                </div>
                            </a>
                            <button class="help-btn w-full block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Help
                                </div>
                            </button>

                            <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                            <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:text-red-400 dark:hover:bg-gray-700">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </div>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/login" class="text-sm text-gray-700 hover:underline mr-4 dark:text-gray-300">Login</a>
                    <a href="/signup" class="text-white bg-red-600 hover:bg-red-700 px-4 py-1 rounded-full text-sm font-medium">Sign up</a>
                <?php endif; ?>

                <!-- Add Question Button -->
                <?php if ($user_logged_in) {
                    if ($_SERVER['REQUEST_URI'] === '/posts/create/') {
                        echo '';
                    } else if (str_contains($_SERVER['REQUEST_URI'], '/posts/edit/')) {
                        echo '';
                    } else {
                        echo '<button class="add-question ml-4 text-white bg-red-600 hover:bg-red-700 px-4 py-1 rounded-full text-sm font-medium">Add Post</button>';
                    }
                }

                ?>
                <!-- Drop down menu to choose option -->
                <div class="add-question-dropdown hidden absolute right-12 top-[45px] mt-2 py-2 w-54 bg-white border border-red-700 dark:bg-darkmode dark:text-gray-600 rounded-lg shadow-md z-10">
                    <a href="/posts/create/"
                        class="create-new-post-ad block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        Add new post
                    </a>
                    <button
                        class="create-new-post-quick block w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                        Add new post (quick)
                    </button>
                </div>

                <!-- Dark Mode Toggle -->
                <label class="ui-switch ml-4">
                    <input type="checkbox" id="dark-mode-toggle">
                    <div class="slider">
                        <div class="circle"></div>
                    </div>
                </label>
            </div>
        </div>
    </header>
    <!-- Add this script at the end of your HTML, before the closing body tag -->
    <script>
        // Check for saved theme preference or prefer-color-scheme
        document.addEventListener('DOMContentLoaded', function() {
            // Avatar dropdown functionality
            const avatarButton = document.getElementById('avatar-menu-button');
            const avatarDropdown = document.getElementById('avatar-dropdown');

            if (avatarButton && avatarDropdown) {
                // Toggle dropdown when avatar is clicked
                avatarButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    avatarDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!avatarButton.contains(event.target) && !avatarDropdown.contains(event.target)) {
                        avatarDropdown.classList.add('hidden');
                    }
                });
            }

            const darkModeToggle = document.getElementById('dark-mode-toggle');

            // Check for saved theme preference or use prefer-color-scheme
            if (localStorage.getItem('darkMode') === 'true' ||
                (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                darkModeToggle.checked = true;
            } else {
                document.documentElement.classList.remove('dark');
                darkModeToggle.checked = false;
            }

            // Add toggle event listener
            darkModeToggle.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('darkMode', 'true');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('darkMode', 'false');
                }
            });
        });
    </script>

<?php
    return ob_get_clean();
}
