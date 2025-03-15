<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - KnowledgeHub</title>
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>

<body class="bg-gray-100 dark:bg-darkmode2 transition-colors duration-200" id="profile-page">
    <?php

    require_once __DIR__ . '/../layouts/header.php';
    require_once __DIR__ . '/../layouts/footer.php';
    if (!is_null($user)) {
        echo render_quora_header(true, $user->getUserName(), $user->getProfileImage(), $user->getEmail(), $user);

        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $username = $user->getUsername();
        $profileImage = $user->getProfileImage() ?? '';
        $email = $user->getEmail();
        $bio = $user->getBio() ?? 'Write a description about yourself';
        $accountCreated = $user->getCreatedAccountDate();
        $datetime = new DateTime($accountCreated);
        $formattedAccountCreated = $datetime->format('F Y');
    }
    ?>
    <!-- Profile Section -->
    <div class="container mx-auto py-8 px-4">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Column: Profile Info -->
            <div class="md:w-1/3">
                <div class="bg-white dark:bg-darkmode rounded-lg shadow-md p-6">
                    <div class="flex flex-col items-center">
                        <div class="relative group">
                            <?php
                            if (is_null($user->getProfileImage()) || empty($user->getProfileImage())) {
                                echo '<div class="w-32 h-32 rounded-full bg-purple-600 dark:bg-purple-700 text-white text-6xl font-bold flex items-center justify-center">' . strtoupper(substr($username, 0, 1)) . '</div>';
                            } else {
                                echo '
                                <div class="w-32 h-32 rounded-full"><img id="preview" src="/' . $profileImage . '" alt="Preview Image" class="w-full h-full object-cover rounded-full border border-gray-300" /></div>';
                            }
                            ?>
                            <div class="absolute bottom-0 right-0 bg-gray-100 dark:bg-gray-700 rounded-full p-2 cursor-pointer">
                                <i class="fas fa-camera text-gray-700 dark:text-gray-300"></i>
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold mt-4 text-gray-900 dark:text-white"><?php echo $firstName . " " . $lastName ?></h2>
                        <p class="text-gray-500 dark:text-gray-400"><?php echo $email ?></p>

                        <button class="edit-profile mt-4 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold py-2 px-4 rounded-md transition-colors duration-200">
                            Edit Profile
                        </button>

                        <div class="w-full mt-6 border-t dark:border-gray-700 pt-4">
                            <form class="mt-4 add-bio-container h-fit" action="/update-bio" method="POST">
                                <p class="bio-text text-gray-500 dark:text-gray-400 text-sm"><?php echo $bio ?></p>
                                <button type="button" class="add-bio mt-2 text-primary-light dark:text-primary-dark text-sm hover:underline">Add description</button>
                                <div class="flex gap-2 mt-2">
                                    <button type="submit" class="save-bio bg-primary-light dark:bg-primary-dark hover:bg-red-700 dark:hover:bg-red-800 text-white px-4 py-2 rounded-md transition-colors duration-200" style="display: none;">Save</button>
                                    <button type="button" class="cancel-bio bg-primary-light dark:bg-primary-dark hover:bg-red-700 dark:hover:bg-red-800 text-white px-4 py-2 rounded-md transition-colors duration-200" style="display: none;">Cancel</button>
                                </div>
                            </form>

                            <div class="mt-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Account Information</h3>
                                <ul class="mt-2 space-y-2">
                                    <li class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <i class="fas fa-calendar-alt text-gray-400 dark:text-gray-500 mr-2"></i>
                                        <span>Joined <?php echo $formattedAccountCreated ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-[49px]"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Content Tabs -->
            <div class="md:w-2/3">
                <div class="bg-white dark:bg-darkmode rounded-lg shadow-md mb-6">
                    <div class="flex border-b dark:border-gray-700 overflow-x-auto">
                        <button class="px-6 py-3 font-medium border-b-2 border-primary-light dark:border-primary-dark text-primary-light dark:text-primary-dark">Profile</button>
                        <button class="px-6 py-3 font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Posts</button>
                        <button class="px-6 py-3 font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Comments</button>
                        <button class="px-6 py-3 font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Edits</button>
                    </div>

                    <div class="p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Posts & Comments</h3>
                            <select class="bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-md px-2 py-1 text-sm text-gray-700 dark:text-gray-300">
                                <option>Most recent</option>
                                <option>Most viewed</option>
                                <option>Most liked</option>
                            </select>
                        </div>

                        <!-- Search for posts -->
                        <div class="mb-6">
                            <div class="relative">
                                <input type="text" placeholder="Search content" class="w-full border dark:border-gray-600 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                                <button class="absolute right-3 top-2 text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Empty state -->
                        <div class="flex flex-col items-center justify-center py-16">
                            <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-400 dark:text-gray-500 mb-4">
                                <i class="fas fa-inbox text-4xl"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">You haven't shared, answered or posted anything yet.</p>
                            <a href="#" class="bg-primary-light dark:bg-primary-dark text-white px-6 py-3 rounded-full font-medium hover:bg-red-700 dark:hover:bg-red-800 transition-colors duration-200">
                                Create New Post
                            </a>
                        </div>
                        <!-- Example Post (hidden by default, remove hidden class to show) -->
                        <div class="border dark:border-gray-700 rounded-lg p-4 mb-4 hidden">
                            <div class="flex justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-purple-600 dark:bg-purple-700 text-white font-bold flex items-center justify-center mr-3">
                                        T
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">Thắng Phạm</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Posted on March 10, 2025</p>
                                    </div>
                                </div>
                                <div class="relative">
                                    <button class="text-gray-400 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">How to optimize database queries?</h3>
                                <p class="mt-2 text-gray-700 dark:text-gray-300">
                                    I'm working on a project with a large dataset and need advice on optimizing my database queries for better performance.
                                </p>
                                <div class="mt-3">
                                    <img src="" alt="Database diagram" class="rounded-lg w-full">
                                </div>
                            </div>

                            <div class="mt-4 flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex space-x-4">
                                    <button class="flex items-center space-x-1">
                                        <i class="far fa-thumbs-up"></i>
                                        <span>15 Upvotes</span>
                                    </button>
                                    <button class="flex items-center space-x-1">
                                        <i class="far fa-comment"></i>
                                        <span>3 Comments</span>
                                    </button>
                                </div>
                                <div>
                                    <span>312 Views</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <!-- <div class="fixed inset-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-70 flex items-center justify-center hidden z-50" id="editProfileModal">

    </div> -->
    <script src="/js/script.js"></script>
    <script src="/js/Validator.js"></script>
    <script>
        // console.log(Modal)
        document.querySelector('.edit-profile').addEventListener('click', function() {
            const editProfileModal = new Modal();
            editProfileModal.openModal(`<div class="bg-white dark:bg-darkmode rounded-lg w-full max-w-xl mx-4">
            <div class="flex justify-between items-center border-b dark:border-gray-700 p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Profile</h3>
            </div>
            <form class="pr-4 pt-4 pb-4" action="/users/update/<?= $user->getUserId() ?>" method="POST" id="edit-profile-form" enctype="multipart/form-data">
                <input type="hidden" name="userId" value="<?= $user->getUserId() ?>">
                <div class="flex flex-col md:flex-row md:space-x-4">
                    <div class="md:w-1/3 flex flex-col items-center mb-3 md:mb-0">
                        <div id="preview-container" class="w-32 h-32 rounded-full overflow-hidden mb-4">
                        <?php
                        if (is_null($user->getProfileImage()) || empty($user->getProfileImage())) {
                            echo '<div class="w-full h-full rounded-full bg-purple-600 dark:bg-purple-700 text-white text-6xl font-bold flex items-center justify-center">' . strtoupper(substr($username, 0, 1)) . '</div>';
                        } else {
                            echo '<img id="preview" src="/' . $profileImage . '" alt="Preview Image" class="w-full h-full object-cover rounded-full border border-gray-300" />';
                        }
                        ?>
                        </div>
                        <div class="form-group mt-4">
                            <label class="w-[50px] p-2 text-lg text-center bg-gray-300 border-none rounded-xl shadow-lg cursor-pointer transition-all duration-300 ease-in-out hover:bg-gray-200 text-gray-700 dark:text-white dark:bg-gray-700 dark:hover:bg-gray-600" for="image">
                                <input type="file" id="image" name="image" accept="image/*" class="hidden">
                                Change Avatar
                            </label>
                            <span class="form-message text-red-500 font-medium text-sm"></span>
                        </div>

                    </div>
                    <div class="md:w-2/3">
                        <div class="mb-4 form-group relative">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">First Name</label>
                            <input type="text" id="firstName" name="firstName" value="<?= $firstName ?>" class="w-full border dark:border-gray-600 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                            <span class="form-message text-red-500 font-medium text-sm absolute top-0 right-0"></span>
                        </div>
                        <div class="mb-4 form-group relative">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Last Name</label>
                            <input type="text" id="lastName" name="lastName" value="<?= $lastName ?>" class="w-full border dark:border-gray-600 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                            <span class="form-message text-red-500 font-medium text-sm absolute top-0 right-0"></span>
                        </div>
                        <div class="mb-4 form-group relative">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="<?= $email ?>" class="w-full border dark:border-gray-600 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                            <span class="form-message text-red-500 font-medium text-sm absolute top-0 right-0"></span>
                        </div>
                        <div class="mb-4 form-group">
                            <label class="block text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                            <textarea rows="3" id="bio" name="bio" class="w-full border dark:border-gray-600 rounded-md px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark"><?php echo $bio ?></textarea>
                            <span class="form-message text-red-500 font-medium text-sm"></span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" class="cancel-edit-profile bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md transition-colors duration-200">Cancel</button>
                    <button type="submit" class="bg-primary-light dark:bg-primary-dark hover:bg-red-700 dark:hover:bg-red-800 text-white px-4 py-2 rounded-md transition-colors duration-200">Save Changes</button>
                </div>
            </form>
        </div>`);
        });

        // document.querySelector('#editProfileModal button').addEventListener('click', function() {
        //     document.getElementById('editProfileModal').classList.add('hidden');
        // });

        function handleImagePreview(e) {
            let file = e.target.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                let preview = document.getElementById("preview");
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    let imgElement = document.createElement("img");
                    imgElement.id = "preview";
                    imgElement.src = e.target.result;
                    imgElement.className = "w-full h-full object-cover rounded-full border border-gray-300";

                    let container = document.getElementById("preview-container");
                    container.innerHTML = "";
                    container.appendChild(imgElement);
                }
                document.getElementById("preview-container").style.display = "block";
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === "childList") {
                    const image = document.querySelector("#image");
                    if (image) {
                        image.addEventListener("change", handleImagePreview);
                    }
                    // Call again Validator when modal appear
                    Validator({
                        form: "#edit-profile-form",
                        formGroupSelector: ".form-group",
                        formMessage: ".form-message",
                        rules: [
                            Validator.isRequired("#firstName"),
                            Validator.isRequired("#lastName"),
                            Validator.isRequired("#email"),
                            Validator.isEmail("#email"),
                        ],
                    });
                    const cancelEditProfile = document.querySelector('.cancel-edit-profile');
                    if (cancelEditProfile) {
                        cancelEditProfile.onclick = function(e) {
                            const modalElement = document.querySelector('.modal-backdrop');
                            if (modalElement) {
                                modalElement.classList.remove("show");
                                modalElement.addEventListener("transitionend", () => {
                                    modalElement.remove();
                                });
                                // If transition does not work, still ensure Modal be removed after 300ms
                                setTimeout(() => {
                                    if (document.body.contains(modalElement)) {
                                        modalElement.remove();
                                    }
                                }, 300);
                            }
                        }
                    }
                }
            });
        });

        // Observe change in body (or container of modal)
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        const addBioContainer = document.querySelector('.add-bio-container');
        const saveButton = document.querySelector('.save-bio');
        const cancelButton = document.querySelector('.cancel-bio');
        const addBioBtn = document.querySelector('.add-bio');
        if (addBioContainer) {
            addBioContainer.onclick = function(e) {
                if (e.target.classList.contains('add-bio')) {
                    e.target.classList.add('hidden');
                    const textBio = document.querySelector(".bio-text");
                    if (textBio) {
                        const bioValue = textBio.innerText; //old text
                        const input = document.createElement("input");
                        input.type = "text";
                        input.value = bioValue;
                        input.classList = "bio-input w-full border dark:border-gray-600 rounded-md ml-auto p-[10px] bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark";
                        textBio.parentNode.replaceChild(input, textBio);
                        saveButton.style.display = "inline-block";
                        cancelButton.style.display = "inline-block";
                    }
                }
            }
            cancelButton.onclick = function(e) {
                const input = document.querySelector(".bio-input");
                const textBio = document.createElement("p");
                textBio.classList = "bio-text text-gray-500 dark:text-gray-400 text-sm";
                textBio.innerText = input.value;
                input.parentNode.replaceChild(textBio, input);
                saveButton.style.display = "none";
                cancelButton.style.display = "none";
                addBioBtn.classList.remove('hidden');
            }
        }
    </script>
</body>

</html>