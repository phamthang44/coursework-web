<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/tailwind.css" rel="stylesheet">
    <title>Login QuoraeHub</title>
    <style>
        :root {
            --primary-color: #f42935;
            --secondary-color: #f5f7fa;
            --text-color: #fff;
            --light-text: #777;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        body {
            background-color: #262626;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            border: 1px solid #fff;
        }

        .left-panel {
            background-color: var(--primary-color);
            width: 40%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 40px;
            position: relative;
        }

        .left-panel h1 {
            font-size: 28px;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .left-panel p {
            text-align: center;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .circles {
            position: absolute;
            width: 200px;
            height: 200px;
            bottom: -50px;
            right: -50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .circles:before {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            top: -80px;
            left: -30px;
        }

        .login-form {
            background: #262626;
            width: 60%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h2 {
            font-size: 24px;
            margin-bottom: 30px;
            color: var(--text-color);
        }

        .input-group {
            margin-bottom: 40px;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: var(--light-text);
            font-weight: 500;

        }

        .input-group input {
            background: #181818;
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #2a2a2a;
            border-radius: var(--border-radius);
            font-size: 15px;
            transition: all 0.2s;
            color: var(--text-color);
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(45, 111, 247, 0.2);
        }

        .remember {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
            accent-color: var(--primary-color);
        }

        .remember-me label {
            color: var(--text-color);
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-button {
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .login-button:hover {
            background-color: #8f272e;
        }

        .login-button:active {
            background-color: #63262a;
        }

        .social-login {
            margin-top: 40px;
            text-align: center;
        }

        .social-login p {
            color: var(--light-text);
            font-size: 14px;
            margin-bottom: 16px;
            position: relative;
        }

        .social-login p:before,
        .social-login p:after {
            content: "";
            position: absolute;
            width: 30%;
            height: 1px;
            background-color: #e1e4e8;
            top: 50%;
        }

        .social-login p:before {
            left: 0;
        }

        .social-login p:after {
            right: 0;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 16px;
        }

        .social-icons button {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 1px solid #e1e4e8;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .social-icons button:hover {
            border-color: var(--primary-color);
            background-color: var(--secondary-color);
        }

        .signup-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--light-text);
        }

        .signup-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .form-message {

            position: absolute;
            right: 0;
            top: -2px;
        }

        .input-group.invalid .form-message {

            color: red;
        }

        .input-group.invalid input {
            border-color: red;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-panel {
                width: 100%;
                padding: 30px;
            }

            .login-form {
                width: 100%;
                padding: 30px;
            }
        }
    </style>
</head>

<body>
    <?php

    use utils\SessionManager;

    if (SessionManager::get('invalid-credentials')) {
        $invalidCredentials = SessionManager::get('invalid-credentials');
        SessionManager::remove('invalid-credentials');
    }


    ?>
    <div class="container">
        <div class="left-panel">
            <h1>QuoraeHub</h1>
            <p>Join our community of curious minds. Ask questions, share knowledge, and connect with experts around the world.</p>
            <div class="circles"></div>
        </div>

        <div class="login-form">
            <h2>Welcome back</h2>

            <form action="/login" class="form-login" id="form-login" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" placeholder="Enter your email" autocomplete="off">
                    <span class="form-message"></span>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                    <span class="form-message"></span>
                </div>

                <div class="remember">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="rememberme" value="yes">
                        <label for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="login-button">Log In</button>
            </form>

            <div class="p-4 mt-4 mb-4 relative">
                <?php if (isset($invalidCredentials)) : ?>
                    <span class="absolute top-0 right-1 text-xl text-red-600 font-medium"><?= $invalidCredentials ?></span>
                <?php endif; ?>
            </div>

            <div class="signup-link">
                <p>Don't have an account? <a href="/signup">Sign up</a></p>
            </div>
        </div>
    </div>
    <script src="/js/validator.js"></script>
    <script>
        Validator({
            form: "#form-login",
            formGroupSelector: ".input-group",
            formMessage: ".form-message",
            rules: [
                Validator.isRequired("#email", "(*required)"),
                Validator.isEmail("#email"),
                Validator.isRequired("#password", "(*required)"),
            ],
        });
    </script>
</body>

</html>