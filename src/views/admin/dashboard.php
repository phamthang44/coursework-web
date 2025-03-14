<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Management System - Admin Dashboard</title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            light: '#3f51b5',
                            dark: '#b92b27' // Quora red
                        },
                        secondary: {
                            light: '#f50057',
                            dark: '#f50057'
                        },
                        background: {
                            light: '#f5f5f5',
                            dark: '#262626'
                        },
                        card: {
                            light: '#ffffff',
                            dark: '#1e1e1e'
                        },
                        border: {
                            light: '#e0e0e0',
                            dark: '#2e2e2e'
                        },
                        success: {
                            light: '#4caf50',
                            dark: '#4caf50'
                        },
                        warning: {
                            light: '#ff9800',
                            dark: '#ff9800'
                        },
                        danger: {
                            light: '#f44336',
                            dark: '#f44336'
                        }
                    }
                }
            }
        };
    </script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Any custom styles that can't be handled with Tailwind */
    </style>
</head>

<body class="bg-background-light dark:bg-darkmode text-gray-800 dark:text-gray-200 transition-colors duration-200">
    <?php

    use controllers\UserController;

    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../../controllers/UserController.php';
    $userController = new UserController();
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $user = $userController->getUser($userId);
        $user_logged_in = true;
        $userName = $user->getUsername();
        $userAvatar = $user->getProfileImage() ?? '';
        $userEmail = $user->getEmail();
    } else {
        $user_logged_in = false;
        $userName = '';
        $userAvatar = '';
        $userEmail = '';
    }
    echo render_quora_header($user_logged_in, $userName, $userAvatar, $userEmail);
    ?>
    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <div class="w-64 bg-white dark:bg-darkmode border dark:border-red-600 text-gray-700 dark:text-white rounded-lg fixed h-full overflow-y-auto transition-colors duration-200">
            <div class="p-5 border-b border-white-700 dark:border-gray-800">
                <h1 class="text-xl font-semibold">Course Manager</h1>
                <p class="text-sm opacity-80">Admin Dashboard</p>
            </div>

            <div class="py-5">
                <div class="mb-4">
                    <div class="px-5 py-2 text-xs uppercase tracking-wider opacity-70">Dashboard</div>
                    <div class="px-5 py-3 flex items-center cursor-pointer dark:bg-darkmode hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                        <i class="fas fa-home mr-3 text-lg"></i>
                        <span>Overview</span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="px-5 py-2 text-xs uppercase tracking-wider opacity-70">Content Management</div>
                    <div class="px-5 py-3 flex items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                        <i class="fas fa-file-alt mr-3 text-lg"></i>
                        <span>Posts</span>
                    </div>
                    <div class="px-5 py-3 flex items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                        <i class="fas fa-users mr-3 text-lg"></i>
                        <span>Users</span>
                    </div>
                    <div class="px-5 py-3 flex items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                        <i class="fas fa-book mr-3 text-lg"></i>
                        <span>Modules</span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="px-5 py-2 text-xs uppercase tracking-wider opacity-70">Settings</div>
                    <div class="px-5 py-3 flex items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                        <i class="fas fa-cog mr-3 text-lg"></i>
                        <span>System Settings</span>
                    </div>
                    <div class="px-5 py-3 flex items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                        <i class="fas fa-user-shield mr-3 text-lg"></i>
                        <span>Admin Account</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="ml-64 flex-1 p-5">
            <!-- Dashboard Overview -->
            <div id="dashboard-overview">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-border-light dark:border-border-dark">
                    <h2 class="text-2xl font-medium">Dashboard Overview</h2>
                    <div class="flex gap-3">
                        <button class="flex items-center px-4 py-2 bg-primary-light dark:bg-primary-dark text-white rounded hover:opacity-90 transition-opacity">
                            <i class="fas fa-sync-alt mr-2"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div class="bg-card-light dark:bg-card-dark rounded-lg shadow p-5 flex items-center transition-colors duration-200">
                        <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900 text-primary-light dark:text-blue-400 flex items-center justify-center mr-4 text-2xl">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-medium">48</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Posts</p>
                        </div>
                    </div>

                    <div class="bg-card-light dark:bg-card-dark rounded-lg shadow p-5 flex items-center transition-colors duration-200">
                        <div class="w-12 h-12 rounded-lg bg-pink-100 dark:bg-pink-900 text-secondary-light dark:text-pink-400 flex items-center justify-center mr-4 text-2xl">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-medium">156</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Registered Users</p>
                        </div>
                    </div>

                    <div class="bg-card-light dark:bg-card-dark rounded-lg shadow p-5 flex items-center transition-colors duration-200">
                        <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900 text-success-light dark:text-green-400 flex items-center justify-center mr-4 text-2xl">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-medium">12</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Active Modules</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Posts Section -->
                <div class="bg-card-light dark:bg-card-dark rounded-lg shadow mb-8 overflow-hidden transition-colors duration-200">
                    <div class="flex justify-between items-center p-4 border-b border-border-light dark:border-border-dark">
                        <h3 class="text-lg font-medium">Recent Posts</h3>
                        <div class="flex items-center gap-3">
                            <input
                                type="text"
                                class="px-3 py-2 border border-border-light dark:border-border-dark rounded bg-white dark:bg-gray-800 text-sm w-48 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark"
                                placeholder="Search posts..." />
                            <button class="flex items-center px-4 py-2 bg-blue-400 dark:bg-primary-dark text-black dark:text-white rounded hover:opacity-90 transition-opacity">
                                <i class="fas fa-plus mr-2"></i> Add New
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800 transition-colors duration-200">
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Title</th>
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Module</th>
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Author</th>
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Status</th>
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Date</th>
                                    <th class="px-5 py-3 text-left text-gray-500 dark:text-gray-400 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                    <td class="px-5 py-4">Introduction to Data Structures</td>
                                    <td class="px-5 py-4">CS101: Programming Fundamentals</td>
                                    <td class="px-5 py-4">Dr. John Smith</td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">Published</span>
                                    </td>
                                    <td class="px-5 py-4">Feb 25, 2025</td>
                                    <td class="px-5 py-4 flex gap-2">
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-primary-light dark:text-blue-400 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-danger-light dark:text-danger-dark transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                    <td class="px-5 py-4">Advanced Database Concepts</td>
                                    <td class="px-5 py-4">CS305: Database Systems</td>
                                    <td class="px-5 py-4">Prof. Sarah Johnson</td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">Published</span>
                                    </td>
                                    <td class="px-5 py-4">Feb 22, 2025</td>
                                    <td class="px-5 py-4 flex gap-2">
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-primary-light dark:text-blue-400 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-danger-light dark:text-danger-dark transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                    <td class="px-5 py-4">Web Security Best Practices</td>
                                    <td class="px-5 py-4">CS405: Cybersecurity</td>
                                    <td class="px-5 py-4">Dr. Michael Chen</td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-400">Draft</span>
                                    </td>
                                    <td class="px-5 py-4">Feb 20, 2025</td>
                                    <td class="px-5 py-4 flex gap-2">
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-primary-light dark:text-blue-400 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-danger-light dark:text-danger-dark transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b border-border-light dark:border-border-dark hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                    <td class="px-5 py-4">Machine Learning Algorithms</td>
                                    <td class="px-5 py-4">CS450: Artificial Intelligence</td>
                                    <td class="px-5 py-4">Prof. Emily Rodriguez</td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">Published</span>
                                    </td>
                                    <td class="px-5 py-4">Feb 18, 2025</td>
                                    <td class="px-5 py-4 flex gap-2">
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-primary-light dark:text-blue-400 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-danger-light dark:text-danger-dark transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                                    <td class="px-5 py-4">Mobile App Development</td>
                                    <td class="px-5 py-4">CS320: Software Engineering</td>
                                    <td class="px-5 py-4">Dr. David Wilson</td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-400">Draft</span>
                                    </td>
                                    <td class="px-5 py-4">Feb 15, 2025</td>
                                    <td class="px-5 py-4 flex gap-2">
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-primary-light dark:text-blue-400 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-danger-light dark:text-danger-dark transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-center p-4">
                        <div class="flex">
                            <button class="w-9 h-9 flex items-center justify-center rounded mx-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="w-9 h-9 flex items-center justify-center rounded mx-1 bg-primary-light dark:bg-primary-dark text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800">1</button>
                            <button class="w-9 h-9 flex items-center justify-center rounded mx-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">2</button>
                            <button class="w-9 h-9 flex items-center justify-center rounded mx-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">3</button>
                            <button class="w-9 h-9 flex items-center justify-center rounded mx-1 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional views (hidden by default) -->
            <!-- Post Management View -->
            <div id="post-management" class="hidden">
                <!-- Content similar to dashboard but for post management -->
            </div>

            <!-- User Management View -->
            <div id="user-management" class="hidden">
                <!-- Content similar to dashboard but for user management -->
            </div>

            <!-- Module Management View -->
            <div id="module-management" class="hidden">
                <!-- Content similar to dashboard but for module management -->
            </div>
        </div>
    </div>

    <script>
        // Navigation functionality (simplified)
        document.querySelectorAll('.nav-item').forEach((item, index) => {
            item.addEventListener('click', () => {
                // Remove active class from all items
                document.querySelectorAll('.nav-item').forEach(el => {
                    el.classList.remove('bg-blue-900', 'dark:bg-gray-800');
                });

                // Add active class to clicked item
                item.classList.add('bg-blue-900', 'dark:bg-gray-800');

                // Show corresponding view and hide others
                const views = ['dashboard-overview', 'post-management', 'user-management', 'module-management'];
                views.forEach((view, i) => {
                    const el = document.getElementById(view);
                    if (el) {
                        if (i === index) {
                            el.classList.remove('hidden');
                        } else {
                            el.classList.add('hidden');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>