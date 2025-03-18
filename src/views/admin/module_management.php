<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Management</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.5/cdn.min.js" defer></script>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script> -->
    <style>
        .bg-darkmode {
            background-color: #1f2937;
        }

        .bg-darkmode2 {
            background-color: #111827;
        }

        .dark-mode-border {
            border-color: #374151;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-darkmode2 min-h-screen" x-data="{ 
  activeTab: 'users',
  showUserModal: false,
  showModuleModal: false,
  showPostModal: false,
  editMode: false,
  currentUser: { id: '', username: '', email: '' },
  currentModule: { id: '', name: '' },
  currentPost: { id: '', title: '', content: '', userId: '', moduleId: '' },
  users: [
    { id: 1, username: 'johndoe', email: 'john@example.com' },
    { id: 2, username: 'janedoe', email: 'jane@example.com' }
  ],
  modules: [
    { id: 1, name: 'Introduction to Programming' },
    { id: 2, name: 'Web Development Basics' }
  ],
  posts: [
    { id: 1, title: 'Getting Started with JS', content: 'JavaScript basics...', userId: 1, moduleId: 2 },
    { id: 2, title: 'Variables in Python', content: 'Python variables...', userId: 2, moduleId: 1 }
  ],
  resetUserForm() {
    this.currentUser = { id: '', username: '', email: '' };
    this.editMode = false;
  },
  resetModuleForm() {
    this.currentModule = { id: '', name: '' };
    this.editMode = false;
  },
  resetPostForm() {
    this.currentPost = { id: '', title: '', content: '', userId: '', moduleId: '' };
    this.editMode = false;
  },
  editUser(user) {
    this.currentUser = {...user};
    this.editMode = true;
    this.showUserModal = true;
  },
  editModule(module) {
    this.currentModule = {...module};
    this.editMode = true;
    this.showModuleModal = true;
  },
  editPost(post) {
    this.currentPost = {...post};
    this.editMode = true;
    this.showPostModal = true;
  },
  saveUser() {
    if(this.editMode) {
      const index = this.users.findIndex(u => u.id === this.currentUser.id);
      if(index !== -1) {
        this.users[index] = {...this.currentUser};
      }
    } else {
      this.currentUser.id = this.users.length + 1;
      this.users.push({...this.currentUser});
    }
    this.showUserModal = false;
    this.resetUserForm();
  },
  saveModule() {
    if(this.editMode) {
      const index = this.modules.findIndex(m => m.id === this.currentModule.id);
      if(index !== -1) {
        this.modules[index] = {...this.currentModule};
      }
    } else {
      this.currentModule.id = this.modules.length + 1;
      this.modules.push({...this.currentModule});
    }
    this.showModuleModal = false;
    this.resetModuleForm();
  },
  savePost() {
    if(this.editMode) {
      const index = this.posts.findIndex(p => p.id === this.currentPost.id);
      if(index !== -1) {
        this.posts[index] = {...this.currentPost};
      }
    } else {
      this.currentPost.id = this.posts.length + 1;
      this.posts.push({...this.currentPost});
    }
    this.showPostModal = false;
    this.resetPostForm();
  },
  deleteUser(id) {
    this.users = this.users.filter(user => user.id !== id);
  },
  deleteModule(id) {
    this.modules = this.modules.filter(module => module.id !== id);
  },
  deletePost(id) {
    this.posts = this.posts.filter(post => post.id !== id);
  },
  getUserName(id) {
    const user = this.users.find(u => u.id === id);
    return user ? user.username : 'Unknown';
  },
  getModuleName(id) {
    const module = this.modules.find(m => m.id === id);
    return module ? module.name : 'Unknown';
  }
}">

    <!-- Navigation -->
    <nav class="bg-white dark:bg-darkmode shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Module Management</h1>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Tabs -->
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                <li class="mr-2">
                    <button @click="activeTab = 'users'" :class="activeTab === 'users' ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'text-gray-500 hover:text-gray-600 dark:text-gray-400 border-transparent'" class="inline-block p-4 border-b-2 rounded-t-lg">
                        Users
                    </button>
                </li>
                <li class="mr-2">
                    <button @click="activeTab = 'modules'" :class="activeTab === 'modules' ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'text-gray-500 hover:text-gray-600 dark:text-gray-400 border-transparent'" class="inline-block p-4 border-b-2 rounded-t-lg">
                        Modules
                    </button>
                </li>
                <li class="mr-2">
                    <button @click="activeTab = 'posts'" :class="activeTab === 'posts' ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'text-gray-500 hover:text-gray-600 dark:text-gray-400 border-transparent'" class="inline-block p-4 border-b-2 rounded-t-lg">
                        Posts
                    </button>
                </li>
            </ul>
        </div>

        <!-- Users Tab Panel -->
        <div x-show="activeTab === 'users'" class="bg-white dark:bg-darkmode rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Users</h2>
                <button @click="showUserModal = true; resetUserForm()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    Add User
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-darkmode2">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Username</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-darkmode divide-y divide-gray-200 dark:divide-gray-600">
                        <template x-for="user in users" :key="user.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300" x-text="user.id"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300" x-text="user.username"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300" x-text="user.email"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="editUser(user)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</button>
                                    <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modules Tab Panel -->
        <div x-show="activeTab === 'modules'" class="bg-white dark:bg-darkmode rounded-lg shadow p-6">
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
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-darkmode divide-y divide-gray-200 dark:divide-gray-600">
                        <template x-for="module in modules" :key="module.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300" x-text="module.id"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300" x-text="module.name"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="editModule(module)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</button>
                                    <button @click="deleteModule(module.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Posts Tab Panel -->
        <div x-show="activeTab === 'posts'" class="bg-white dark:bg-darkmode rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Posts</h2>
                <button @click="showPostModal = true; resetPostForm()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    Add Post
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-darkmode2">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Module</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Author</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-darkmode divide-y divide-gray-200 dark:divide-gray-600">
                        <template x-for="post in posts" :key="post.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300" x-text="post.id"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300" x-text="post.title"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300" x-text="getModuleName(post.moduleId)"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300" x-text="getUserName(post.userId)"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="editPost(post)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</button>
                                    <button @click="deletePost(post.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div x-show="showUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-darkmode max-w-md w-full rounded-lg shadow-lg p-6" @click.outside="showUserModal = false">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4" x-text="editMode ? 'Edit User' : 'Add User'"></h3>
            <form @submit.prevent="saveUser()">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                    <input x-model="currentUser.username" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:dark-mode-border shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 bg-white dark:bg-darkmode2 text-gray-900 dark:text-white" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                    <input x-model="currentUser.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:dark-mode-border shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 bg-white dark:bg-darkmode2 text-gray-900 dark:text-white" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="showUserModal = false" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Module Modal -->
    <div x-show="showModuleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-darkmode max-w-md w-full rounded-lg shadow-lg p-6" @click.outside="showModuleModal = false">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4" x-text="editMode ? 'Edit Module' : 'Add Module'"></h3>
            <form @submit.prevent="saveModule()">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Module Name</label>
                    <input x-model="currentModule.name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:dark-mode-border shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 bg-white dark:bg-darkmode2 text-gray-900 dark:text-white" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="showModuleModal = false" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Post Modal -->
    <div x-show="showPostModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-darkmode max-w-md w-full rounded-lg shadow-lg p-6" @click.outside="showPostModal = false">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4" x-text="editMode ? 'Edit Post' : 'Add Post'"></h3>
            <form @submit.prevent="savePost()">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input x-model="currentPost.title" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:dark-mode-border shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 bg-white dark:bg-darkmode2 text-gray-900 dark:text-white" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                    <textarea x-model="currentPost.content" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:dark-mode-border shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 bg-white dark:bg-darkmode2 text-gray-900 dark:text-white" rows="3" required></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Module</label>
                    <select x-model="currentPost.moduleId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:dark-mode-border shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 bg-white dark:bg-darkmode2 text-gray-900 dark:text-white" required>
                        <option value="">Select Module</option>
                        <template x-for="module in modules" :key="module.id">
                            <option :value="module.id" x-text="module.name"></option>
                        </template>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Author</label>
                    <select x-model="currentPost.userId" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:dark-mode-border shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 bg-white dark:bg-darkmode2 text-gray-900 dark:text-white" required>
                        <option value="">Select User</option>
                        <template x-for="user in users" :key="user.id">
                            <option :value="user.id" x-text="user.username"></option>
                        </template>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="showPostModal = false" class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium py-2 px-4 rounded mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>