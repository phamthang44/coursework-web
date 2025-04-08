<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Management</title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="bg-gray-100 dark:bg-darkmode2 min-h-screen" id="module-page">
    <?php

    use controllers\UserController;
    use controllers\PostController;
    use controllers\ModuleController;
    use utils\Template;
    use utils\SessionManager;

    Template::header();
    Template::footer();

    $userController = new UserController();
    $moduleController = new ModuleController();

    if ((SessionManager::get('user_id')) !== null) {
        $currentUser = $userController->getUser(SessionManager::get('user_id'));
        $currentUser = SessionManager::get('user');
        $user_logged_in = true;
        $userName = $currentUser->getUsername();
        $userAvatar = $currentUser->getProfileImage() ?? '';
        $userEmail = $currentUser->getEmail();
        // $profileLink = '/profile/' . $user->getFirstName() . '-' . $user->getLastName() . '-' . $user->getUserId();
        $modulesPerPage = 10;
        $currentPage = $moduleController->getCurrentPage();
        $offset = ($currentPage - 1) * $modulesPerPage;
        $totalModuleNums = $moduleController->getTotalModuleNums();
        $totalPages = ceil($totalModuleNums / $modulesPerPage);
        $modules = $moduleController->getModulesPerPage($offset, $modulesPerPage);
        $dashboardLink = '/admin/dashboard';
        $adminProfileLink = '/admin/profile/' . $currentUser->getFirstName() . '-' . $currentUser->getLastName() . '-' . $currentUser->getUserId();
    } else {
        $user_logged_in = false;
        $userName = '';
        $userAvatar = '';
        $userEmail = '';
    }
    echo render_quora_header($user_logged_in, $userName, $userAvatar, $userEmail, $currentUser);
    ?>
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">

        <!-- Modules Tab Panel -->
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Module Management</h1>
        <div class="bg-white dark:bg-darkmode rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Modules</h2>
                <button class="add-module-btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    Add Module
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Module Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-darkmode divide-y divide-gray-200 dark:divide-gray-600 over-flow-y-auto">
                        <!-- Replace this section with dynamic content -->
                        <?php foreach ($modules as $module) { ?>

                            <tr class="module-item hover:bg-gray-50 dark:hover:bg-gray-700"
                                data-module-id="<?= htmlspecialchars($module->getModuleId(), ENT_QUOTES, 'UTF-8'); ?>"
                                data-module-name="<?= htmlspecialchars($module->getModuleName(), ENT_QUOTES, 'UTF-8'); ?>"
                                data-module-description="<?= htmlspecialchars($module->getModuleDescription(), ENT_QUOTES, 'UTF-8'); ?>">
                                <td class="module-id px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    <?= htmlspecialchars($module->getModuleId(), ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td class="module-name px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    <?= htmlspecialchars($module->getModuleName(), ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td class="module-description px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    <?= htmlspecialchars($module->getModuleDescription(), ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="edit-module-btn text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                        Edit
                                    </button>
                                    <a href="/admin/modules/delete/<?php echo $module->getModuleId(); ?>" class="delete-module-btn text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="flex items-center justify-center space-x-2 mt-8">
                    <!-- First Button -->
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=1"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200">
                            First
                        </a>
                    <?php endif; ?>

                    <!-- Previous Button -->
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage - 1 ?>"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200">
                            Previous
                        </a>
                    <?php else: ?>
                        <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                            Previous
                        </span>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <div class="flex space-x-1">
                        <?php
                        $maxPagesToShow = 3;
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);

                        if ($startPage > 1) {
                            echo '<span class="px-4 py-2 text-sm font-medium text-gray-400">...</span>';
                        }

                        for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="?page=<?= $i ?>"
                                class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 <?= ($i == $currentPage) ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor;

                        if ($endPage < $totalPages) {
                            echo '<span class="px-4 py-2 text-sm font-medium text-gray-400">...</span>';
                        }
                        ?>
                    </div>

                    <!-- Next Button -->
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage + 1 ?>"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200">
                            Next
                        </a>
                    <?php else: ?>
                        <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                            Next
                        </span>
                    <?php endif; ?>

                    <!-- Last Button -->
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $totalPages ?>"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200">
                            Last
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo render_quora_footer() ?>
    <script src="/js/validator.js"></script>
    <script src="/js/script.js"></script>
    <script>
        const moduleItems = document.querySelectorAll('.module-item');
        moduleItems.forEach(item => {
            item.addEventListener('click', (e) => {
                checkExistingModal();
                const moduleModal = new Modal();
                const moduleId = item.dataset.moduleId;
                const moduleName = item.dataset.moduleName;
                const moduleDescription = item.dataset.moduleDescription;

                if (e.target.classList.contains("edit-module-btn")) {
                    checkExistingModal();
                    moduleModal.openModal(`<h2 class="text-xl text-red-500 font-bold mb-4">Edit the module</h2>
                            <form action="/admin/modules/update/${moduleId}" method="POST" enctype="multipart/form-data" id="form-update-module" class="space-y-4">
                                <div class="form-group py-4 mb-4">
                                    <label for="moduleName" class="block font-medium text-gray-700 dark:text-white mb-4">Module name (Required):</label>
                                    <input type="text" id="moduleName" name="moduleName" placeholder="Enter module name (Required)"
                                        class="w-full h-12 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"
                                        value="${moduleName}">
                                    <span class="form-message text-red-500 text-sm"></span>
                                </div>
                                <!-- Content Field (Required) -->
                                <div class="form-group">
                                    <label for="moduleDescription" class="block font-medium text-gray-700 dark:text-white mb-4">Description (Required):</label>
                                    <textarea id="moduleDescription" name="moduleDescription" rows="5" placeholder="Enter description (Required)"
                                        class="w-full h-40 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white">${moduleDescription}</textarea>
                                    <span class="form-message text-red-500 font-medium text-sm"></span>
                                </div>
                                <!-- Submit Button -->
                                <div class="flex">
                                    <button type="button" class="cancel-edit-module-btn bg-gray-300 hover:bg-gray-400 transition text-gray-800 font-bold w-[100px] h-[50px] mr-[20px] rounded-full">
                                        Cancel
                                    </button>
                                    <input class="save-module-btn bg-red-700 hover:bg-red-600 transition text-white font-bold w-[100px] h-[50px] rounded-full" type="submit" value="Save">
                                </div>
                            </form> 
                            
                            `);
                } else if (e.target.classList.contains("delete-module-btn")) {
                    e.preventDefault();
                    checkExistingModal();
                    const deleteConfirmCard = new ConfirmCard();
                    deleteConfirmCard.openConfirmCard(`
    <h2 class="text-gray-800 dark:text-white confirm-title" data-url="${e.target.href}">
        Are you sure you want to delete <span class="text-red-500 dark:text-red-600 font-medium">${moduleName}</span> ?
    </h2>`);
                }
            });
        });
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === "childList") {

                    Validator({
                        form: "#form-update-module",
                        formGroupSelector: ".form-group",
                        formMessage: ".form-message",
                        rules: [
                            Validator.isRequired("#moduleName"),
                            Validator.isRequired("#moduleDescription"),
                            Validator.maxLength("#moduleName", 100),
                        ],
                        onSubmit: async function(data) {
                            const form = document.querySelector("#form-update-module");
                            const formData = new FormData(form);
                            try {
                                const response = await fetch(form.action, {
                                    method: "POST",
                                    body: formData,
                                    headers: {
                                        "Accept": "application/json"
                                    }
                                });
                                const data = await response.json();
                                if (data.status) {
                                    console.log(data)
                                    const moduleItem = document.querySelector(`.module-item[data-module-id="${data.module.moduleId}"]`);

                                    checkExistingModal();
                                    moduleItem.querySelector(".module-id").textContent = data.module.moduleId;
                                    moduleItem.querySelector(".module-name").textContent = data.module.moduleName;
                                    moduleItem.querySelector(".module-description").textContent = data.module.moduleDescription;
                                    moduleItem.setAttribute("data-module-name", data.module.moduleName);
                                    moduleItem.setAttribute("data-module-description", data.module.moduleDescription);
                                    moduleItem.setAttribute("data-module-id", data.module.moduleId);

                                }
                            } catch (error) {
                                console.error("Error:", error);
                            }
                        }
                    });

                    Validator({
                        form: "#form-create-module",
                        formGroupSelector: ".form-group",
                        formMessage: ".form-message",
                        rules: [
                            Validator.isRequired("#moduleName"),
                            Validator.isRequired("#moduleDescription"),
                            Validator.maxLength("#moduleName", 100),
                        ],
                        onSubmit: async function(data) {
                            const form = document.querySelector("#form-create-module");
                            const formData = new FormData(form);
                            try {
                                const response = await fetch(form.action, {
                                    method: "POST",
                                    body: formData,
                                    headers: {
                                        "Accept": "application/json"
                                    }
                                });
                                const data = await response.json();
                                if (data.status) {
                                    console.log(data)
                                    if (data.module.moduleId >= 10) {
                                        window.location.reload();
                                    }
                                    checkExistingModal();
                                    const moduleTable = document.querySelector("tbody");
                                    const newModule = document.createElement("tr");
                                    newModule.className = "module-item hover:bg-gray-50 dark:hover:bg-gray-700";
                                    newModule.setAttribute("data-module-id", data.module.moduleId);
                                    newModule.setAttribute("data-module-name", data.module.moduleName);
                                    newModule.setAttribute("data-module-description", data.module.moduleDescription);
                                    newModule.innerHTML = `
                                    <td class="module-id px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        ${data.module.moduleId}
                                    </td>
                                    <td class="module-name px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        ${data.module.moduleName}
                                    </td>
                                    <td class="module-description px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        ${data.module.moduleDescription}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="edit-module-btn text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                                            Edit
                                        </button>
                                        <button class="delete-module-btn text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Delete
                                        </button>
                                    </td>
                                    `;
                                    moduleTable.appendChild(newModule);
                                }
                            } catch (error) {
                                console.error("Error:", error);
                            }
                        }
                    });
                    const cancelEditModuleBtn = document.querySelector(".cancel-edit-module-btn");
                    if (cancelEditModuleBtn) {
                        cancelEditModuleBtn.addEventListener("click", () => {
                            checkExistingModal();
                        });
                    }
                }
            });
        });

        // Observe change in body (or container of modal)
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });



        function checkExistingModal() {
            const existingModal = document.querySelector(".modal-backdrop");
            if (existingModal) {
                existingModal.classList.remove("show");
                existingModal.ontransitionend = () => {
                    existingModal.remove();
                };
            }
        }

        const addModuleBtn = document.querySelector(".add-module-btn");
        addModuleBtn.addEventListener("click", () => {
            const moduleModal = new Modal();
            moduleModal.openModal(`<h2 class="text-xl text-red-500 font-bold mb-4">Add new module</h2>
    <form action="/admin/modules/create" method="POST" enctype="multipart/form-data" id="form-create-module" class="space-y-4">
        <div class="form-group py-4 mb-4">
            <label for="moduleName" class="block font-medium text-gray-700 dark:text-white mb-4">Module name (Required):</label>
            <input type="text" id="moduleName" name="moduleName" placeholder="Enter module name (Required)"
                class="w-full h-12 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"
                value="">
            <span class="form-message text-red-500 text-sm"></span>
        </div>
        <!-- Content Field (Required) -->
        <div class="form-group">
            <label for="moduleDescription" class="block font-medium text-gray-700 dark:text-white mb-4">Description (Required):</label>
            <textarea id="moduleDescription" name="moduleDescription" rows="5" placeholder="Enter description (Required)"
                class="w-full h-40 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-100 dark:bg-gray-700 dark:text-white"></textarea>
            <span class="form-message text-red-500 font-medium text-sm"></span>
        </div>
        <!-- Submit Button -->
        <div class="flex">
            <input class="create-module-btn bg-red-700 hover:bg-red-600 transition text-white font-bold w-[150px] h-[50px] rounded-full" type="submit" value="Add new module">
        </div>
    </form>`);
        });

        const addQuestion = document.querySelector(".add-question");
        addQuestion.style.display = "none";
    </script>
</body>

</html>