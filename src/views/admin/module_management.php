<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Management</title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="bg-gray-100 dark:bg-darkmode2 min-h-screen">

    <!-- Main Content -->

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">
        <!-- Modules Tab Panel -->
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Module Management</h1>
        <div class="bg-white dark:bg-darkmode rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Modules</h2>
                <button @click="showModuleModal = true; resetModuleForm()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    Add Module
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-darkmode2">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Module Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-darkmode divide-y divide-gray-200 dark:divide-gray-600">
                        <!-- Replace this section with dynamic content -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Introduction to Programming</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Learn the basics of programming concepts and logic.</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="editModule(module)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</button>
                                <button @click="deleteModule(module.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">2</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Web Development Basics</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Understand the fundamentals of web development, including HTML, CSS, and JavaScript.</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="editModule(module)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</button>
                                <button @click="deleteModule(module.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Module Modal -->
    <!-- <div x-show="showModuleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-darkmode max-w-md w-full rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4" x-text="editMode ? 'Edit Module' : 'Add Module'"></h3>
            <form>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Module Name</label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:dark-mode-border shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 bg-white dark:bg-darkmode2 text-gray-900 dark:text-white" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:dark-mode-border shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 bg-white dark:bg-darkmode2 text-gray-900 dark:text-white" required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div> -->

</body>

</html>