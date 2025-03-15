<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body class="bg-gray-100 dark:bg-darkmode2 transition-colors duration-200" id="edit-profile-page">
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
    <div class="bg-white dark:bg-darkmode rounded-lg w-full max-w-xl mx-4">
        <div class="flex justify-between items-center border-b dark:border-gray-700 p-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Profile</h3>
        </div>
        <form class="pr-4 pt-4 pb-4" action="/users/update/<?= $user->getUserId() ?>" method="POST" id="edit-profile-form">
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
                        <label class="w-[50px] p-2 text-lg text-center bg-gray-600 border-none rounded-xl shadow-lg cursor-pointer transition-all duration-300 ease-in-out hover:bg-gray-500 text-gray-700 dark:text-white">
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
                <button type="button" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md transition-colors duration-200">Cancel</button>
                <button type="submit" class="bg-primary-light dark:bg-primary-dark hover:bg-red-700 dark:hover:bg-red-800 text-white px-4 py-2 rounded-md transition-colors duration-200">Save Changes</button>
            </div>
        </form>
    </div>
</body>

</html>