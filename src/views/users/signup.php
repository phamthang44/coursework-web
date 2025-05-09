<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/tailwind.css" rel="stylesheet">
    <title>Signup QuoraeHub</title>
    <style>
        .form-group.invalid input {
            border-color: red;
        }
    </style>
</head>

<body class="bg-[#262626] flex justify-center items-center min-h-screen p-5">
    <?php

    use utils\SessionManager;
    ?>

    <div class="flex max-w-[1000px] w-full bg-white rounded-lg overflow-hidden shadow-lg border border-white md:flex-row flex-col">
        <!-- Left Panel -->
        <div class="bg-[#f42935] md:w-2/5 w-full flex flex-col items-center justify-center text-white p-10 relative">
            <h1 class="text-2xl font-semibold mb-4">QuoraeHub</h1>
            <p class="text-center leading-relaxed mb-6">Join our community of curious minds. Ask questions, share knowledge, and connect with experts around the world.</p>
            <!-- Decorative circles -->
            <div class="absolute w-[200px] h-[200px] -bottom-[50px] -right-[50px] rounded-full bg-white bg-opacity-10">
                <div class="absolute w-[150px] h-[150px] rounded-full bg-white bg-opacity-10 -top-[80px] -left-[30px]"></div>
            </div>
        </div>

        <!-- Signup Form -->
        <div class="bg-[#262626] md:w-3/5 w-full p-12 flex flex-col justify-center">
            <h2 class="text-2xl mb-[20px] text-white">Welcome to QuoraeHub</h2>

            <form action="/signup" class="form-signup" id="form-signup" method="POST">
                <div class="form-group relative w-full mb-10">
                    <label for="firstName" class="block mb-2 text-sm text-[#777] font-medium">First name</label>
                    <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" autocomplete="off"
                        class="bg-[#181818] w-full py-3 px-4 mb-[5px] border border-[#2a2a2a] rounded-lg text-[15px] transition-all text-white focus:outline-none focus:border-[#f42935] focus:shadow">
                    <span class="form-message max-chars absolute right-[40px] -top-[2px] text-red-600"></span>
                </div>
                <div class="form-group relative mb-10">
                    <label for="lastName" class="block mb-[5px] text-sm text-[#777] font-medium">Last name</label>
                    <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" autocomplete="off"
                        class="bg-[#181818] w-full py-3 px-4 mb-[5px] border border-[#2a2a2a] rounded-lg text-[15px] transition-all text-white focus:outline-none focus:border-[#f42935] focus:shadow">
                    <span class="form-message max-chars absolute right-[40px] -top-[2px] text-red-600"></span>
                </div>
                <div class="mb-10 form-group relative">
                    <label for="email" class="block mb-2 text-sm text-[#777] font-medium">Email</label>
                    <input id="email" name="email" placeholder="Enter your email" autocomplete="off"
                        class="bg-[#181818] w-full py-3 px-4 border border-[#2a2a2a] rounded-lg text-[15px] transition-all text-white focus:outline-none focus:border-[#f42935] focus:shadow">
                    <?php
                    $error = SessionManager::get('error');
                    if ($error) {
                        if (strpos($error, 'Duplicate entry')) {
                            $error = 'Email already exists !';
                        }
                        if (strpos($error, '64 or 255')) {
                            $error = 'Invalid email format with length greater than 64 or 255 characters';
                        }
                        SessionManager::remove('error');
                    } else {
                        $error = '';
                    }
                    ?>
                    <?php if (strpos($error, 'exists')) { ?>
                        <span class="form-message email-message absolute right-[40px] -top-[2px] text-red-600"><?php echo $error; ?></span>
                    <?php } else if (strpos($error, '64 or 255')) { ?>
                        <span style="bottom: -21px;" class="form-message email-message absolute right-[40px] text-red-600"><?php echo $error; ?></span>
                    <?php } else { ?>
                        <span class="form-message email-message absolute right-[40px] -top-[2px] text-red-600"></span>
                    <?php } ?>
                </div>

                <div class="mb-10 form-group relative">
                    <label for="password" class="block mb-2 text-sm text-[#777] font-medium">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password"
                        class="bg-[#181818] w-full py-3 px-4 border border-[#2a2a2a] rounded-lg text-[15px] transition-all text-white focus:outline-none focus:border-[#f42935] focus:shadow">
                    <span class="form-message absolute right-[40px] -top-[2px] text-red-600"></span>
                </div>

                <div class="mb-10 form-group relative">
                    <label for="confirm-password" class="block mb-2 text-sm text-[#777] font-medium">Confirm your password</label>
                    <input type="password" id="confirm-password" placeholder="Confirm your password"
                        class="bg-[#181818] w-full py-3 px-4 border border-[#2a2a2a] rounded-lg text-[15px] transition-all text-white focus:outline-none focus:border-[#f42935] focus:shadow">
                    <span class="form-message absolute right-[40px] -top-[2px] text-red-600"></span>
                </div>

                <button type="submit" class="w-full bg-[#f42935] text-white border-none py-3 px-4 rounded-lg text-base font-medium cursor-pointer transition-all hover:bg-[#8f272e] active:bg-[#63262a]">Sign up</button>
            </form>

            <div class="mt-10 text-center">
                <p class="text-[#777] text-sm mb-4 relative before:content-[''] before:absolute before:w-[30%] before:h-[1px] before:bg-[#e1e4e8] before:top-1/2 before:left-0 after:content-[''] after:absolute after:w-[30%] after:h-[1px] after:bg-[#e1e4e8] after:top-1/2 after:right-0"></p>
            </div>

            <div class="text-center mt-6">
                <p class="text-sm text-[#777]">Already had an account? <a href="/login" class="text-[#f42935] no-underline font-medium">Login</a></p>
            </div>
        </div>
    </div>

    <script src="/js/validator.js"></script>
    <script src="/js/script.js"></script>
    <script>
        Validator({
            form: "#form-signup",
            formGroupSelector: ".form-group",
            formMessage: ".form-message",
            rules: [
                Validator.isRequired("#firstName", "(*required)"),
                Validator.isRequired("#lastName", "(*required)"),
                Validator.isRequired("#email", "(*required)"),
                Validator.isEmail("#email"),
                Validator.isRequired("#password", "(*required)"),
                Validator.isRequired("#confirm-password", "(*required)"),
                Validator.isConfirmed("#confirm-password", function() {
                    return document.querySelector("#password").value;
                }, "Passwords do not match"),
                Validator.hasNoWhiteSpace("#firstName"),
                Validator.hasNoWhiteSpace("#lastName"),
                Validator.hasNoWhiteSpace("#email"),
                Validator.hasNoWhiteSpace("#password"),
                Validator.hasNoWhiteSpace("#confirm-password"),
                Validator.maxLength("#firstName", 20, "First name must be less than 20 characters"),
                Validator.maxLength("#lastName", 20, "Last name must be less than 20 characters"),
                Validator.maxLengthLocalPartEmail("#email", 64, "Local part of email must be less than 64 characters"),
                Validator.maxLength("#email", 100, "Email must be less than 100 characters"),
                Validator.maxLength("#password", 255, "Password must be less than 255 characters"),
            ],
        });
        // handle sign up page for email message
        const emailMessage = document.querySelector(".email-message");
        const emailInput = document.querySelector("#email");
        if (emailInput) {
            emailInput.addEventListener("click", function() {
                if (emailMessage) {
                    if (emailMessage.innerText) {
                        emailMessage.className =
                            "form-message email-message absolute right-[40px] -top-[2px] text-red-600";
                        emailMessage.innerText = "";
                    }
                }
            });
        }
    </script>
</body>

</html>