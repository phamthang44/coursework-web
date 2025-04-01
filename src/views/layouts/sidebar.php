<?php function render_sidebar($dashboardLink, $adminProfileLink)
{ ?>
    <!-- Sidebar Navigation -->
    <div class="w-64 bg-white dark:bg-darkmode border dark:border-red-600 text-gray-700 dark:text-white rounded-lg fixed h-full overflow-y-auto transition-colors duration-200">
        <div class="p-5 border-b border-white-700 dark:border-gray-800">
            <h1 class="text-xl font-semibold">Course Manager</h1>
            <p class="text-sm opacity-80">Admin Dashboard</p>
        </div>

        <div class="py-5">
            <div class="mb-4">
                <div class="px-5 py-2 text-xs uppercase tracking-wider opacity-70">Dashboard</div>
                <a href="<?= $dashboardLink ?>" class="px-5 py-3 flex items-center cursor-pointer dark:bg-darkmode hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                    <i class="fas fa-home mr-3 text-lg"></i>
                    <span>Overview</span>
                </a>
            </div>

            <div class="mb-4">
                <div class="px-5 py-2 text-xs uppercase tracking-wider opacity-70">Content Management</div>
                <a href="/posts" class="px-5 py-3 flex items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                    <i class="fas fa-file-alt mr-3 text-lg"></i>
                    <span>Posts</span>
                </a>
                <a href="/admin/user-management" class="px-5 py-3 flex items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                    <i class="fas fa-users mr-3 text-lg"></i>
                    <span>Users</span>
                </a>
                <a href="/admin/module-management" class="px-5 py-3 flex items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                    <i class="fas fa-book mr-3 text-lg"></i>
                    <span>Modules</span>
                </a>
            </div>

            <div class="mb-4">
                <div class="px-5 py-2 text-xs uppercase tracking-wider opacity-70">Settings</div>
                <a href="<?= removeVietnameseAccents($adminProfileLink) ?>" class="px-5 py-3 flex items-center cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors">
                    <i class="fas fa-user-shield mr-3 text-lg"></i>
                    <span>Admin Account</span>
                </a>
            </div>
        </div>
    </div>
<?php } ?>