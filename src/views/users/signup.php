<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/tailwind.css" rel="stylesheet">
    <title>Forum Login</title>
    <style>
        .form-group.invalid input {
            border-color: red;
        }
    </style>
</head>

<body class="bg-[#262626] flex justify-center items-center min-h-screen p-5">
    <?php $error = $_GET['error'] ?? ""; ?>

    <div class="flex max-w-[1000px] w-full bg-white rounded-lg overflow-hidden shadow-lg border border-white md:flex-row flex-col">
        <!-- Left Panel -->
        <div class="bg-[#f42935] md:w-2/5 w-full flex flex-col items-center justify-center text-white p-10 relative">
            <h1 class="text-2xl font-semibold mb-4">KnowledgeHub</h1>
            <p class="text-center leading-relaxed mb-6">Join our community of curious minds. Ask questions, share knowledge, and connect with experts around the world.</p>
            <!-- Decorative circles -->
            <div class="absolute w-[200px] h-[200px] -bottom-[50px] -right-[50px] rounded-full bg-white bg-opacity-10">
                <div class="absolute w-[150px] h-[150px] rounded-full bg-white bg-opacity-10 -top-[80px] -left-[30px]"></div>
            </div>
        </div>

        <!-- Signup Form -->
        <div class="bg-[#262626] md:w-3/5 w-full p-12 flex flex-col justify-center">
            <h2 class="text-2xl mb-[20px] text-white">Welcome to KnowledgeHub</h2>

            <form action="/signup" class="form-signup" id="form-signup" method="POST">
                <div class="flex">
                    <div class="form-group pb-[20px] relative">
                        <label for="firstName" class="block mb-2 text-sm text-[#777] font-medium">First name</label>
                        <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" autocomplete="off"
                            class="bg-[#181818] py-3 px-4 mb-[5px] border border-[#2a2a2a] rounded-lg text-[15px] transition-all text-white focus:outline-none focus:border-[#f42935] focus:shadow">
                        <span class="form-message absolute right-[40px] -top-[2px] text-red-600"></span>
                    </div>
                    <div class="form-group ml-auto relative">
                        <label for="lastName" class="block mb-[5px] text-sm text-[#777] font-medium">Last name</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" autocomplete="off"
                            class="bg-[#181818] py-3 px-4 mb-[5px] border border-[#2a2a2a] rounded-lg text-[15px] transition-all text-white focus:outline-none focus:border-[#f42935] focus:shadow">
                        <span class="form-message absolute right-[40px] -top-[2px] text-red-600"></span>
                    </div>
                </div>

                <div class="mb-10 form-group relative">
                    <label for="email" class="block mb-2 text-sm text-[#777] font-medium">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" autocomplete="off"
                        class="bg-[#181818] w-full py-3 px-4 border border-[#2a2a2a] rounded-lg text-[15px] transition-all text-white focus:outline-none focus:border-[#f42935] focus:shadow">
                    <span class="form-message absolute right-[40px] -top-[2px] text-red-600"><?php echo $error ?? ""; ?></span>
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
                <p class="text-[#777] text-sm mb-4 relative before:content-[''] before:absolute before:w-[30%] before:h-[1px] before:bg-[#e1e4e8] before:top-1/2 before:left-0 after:content-[''] after:absolute after:w-[30%] after:h-[1px] after:bg-[#e1e4e8] after:top-1/2 after:right-0">Or continue with</p>

                <div class="flex justify-center gap-4">
                    <button class="w-12 h-12 rounded-full border border-[#e1e4e8] bg-white flex items-center justify-center cursor-pointer transition-all hover:border-[#f42935] hover:bg-[#f5f7fa]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.283 10.356h-8.327v3.451h4.792c-.446 2.193-2.313 3.453-4.792 3.453a5.27 5.27 0 0 1-5.279-5.28 5.27 5.27 0 0 1 5.279-5.279c1.259 0 2.397.447 3.29 1.178l2.6-2.599c-1.584-1.381-3.615-2.233-5.89-2.233a8.908 8.908 0 0 0-8.934 8.934 8.907 8.907 0 0 0 8.934 8.934c4.467 0 8.529-3.249 8.529-8.934 0-.528-.081-1.097-.202-1.625z" fill="#4285F4" />
                            <path d="M4.17 14.406l3.095 2.292A4.93 4.93 0 0 0 11.956 19.5c2.484 0 4.34-1.258 4.786-3.453h-4.778l-7.794-1.641z" fill="#34A853" />
                            <path d="M11.956 4.029c-1.586 0-3.042.544-4.256 1.667.003-.003.003-.003 0 0L4.17 3.28A8.939 8.939 0 0 1 11.956 0c2.275 0 4.306.852 5.89 2.234l-2.601 2.599c-.889-.731-2.027-1.18-3.289-1.18v.376z" fill="#EA4335" />
                            <path d="M11.956 19.5a8.932 8.932 0 0 1-8.202-5.452l.367.275 3.038 2.236.005.003A5.273 5.273 0 0 0 11.956 19.5V19.5z" fill="#FBBC05" />
                        </svg>
                    </button>
                    <button class="w-12 h-12 rounded-full border border-[#e1e4e8] bg-white flex items-center justify-center cursor-pointer transition-all hover:border-[#f42935] hover:bg-[#f5f7fa]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24 12.073C24 5.404 18.627 0 12 0S0 5.404 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047v-2.66c0-3.025 1.79-4.697 4.533-4.697 1.312 0 2.687.235 2.687.235v2.969H15.83c-1.491 0-1.955.93-1.955 1.886v2.267h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z" fill="#1877F2" />
                        </svg>
                    </button>
                    <button class="w-12 h-12 rounded-full border border-[#e1e4e8] bg-white flex items-center justify-center cursor-pointer transition-all hover:border-[#f42935] hover:bg-[#f5f7fa]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231 5.45-6.231zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z" fill="#000000" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="text-center mt-6">
                <p class="text-sm text-[#777]">Already had an account? <a href="/login" class="text-[#f42935] no-underline font-medium">Login</a></p>
            </div>
        </div>
    </div>

    <script src="/js/validator.js"></script>
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
            ],
        });
    </script>
</body>

</html>