<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .remember-forgot {
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
                    <input type="email" id="email" name="email" placeholder="Enter your email" autocomplete="off">
                    <span class="form-message"></span>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                    <span class="form-message"></span>
                </div>

                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#!" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="login-button">Log In</button>
            </form>

            <div class="social-login">
                <p>Or continue with</p>
                <div class="social-icons">
                    <button>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.283 10.356h-8.327v3.451h4.792c-.446 2.193-2.313 3.453-4.792 3.453a5.27 5.27 0 0 1-5.279-5.28 5.27 5.27 0 0 1 5.279-5.279c1.259 0 2.397.447 3.29 1.178l2.6-2.599c-1.584-1.381-3.615-2.233-5.89-2.233a8.908 8.908 0 0 0-8.934 8.934 8.907 8.907 0 0 0 8.934 8.934c4.467 0 8.529-3.249 8.529-8.934 0-.528-.081-1.097-.202-1.625z" fill="#4285F4" />
                            <path d="M4.17 14.406l3.095 2.292A4.93 4.93 0 0 0 11.956 19.5c2.484 0 4.34-1.258 4.786-3.453h-4.778l-7.794-1.641z" fill="#34A853" />
                            <path d="M11.956 4.029c-1.586 0-3.042.544-4.256 1.667.003-.003.003-.003 0 0L4.17 3.28A8.939 8.939 0 0 1 11.956 0c2.275 0 4.306.852 5.89 2.234l-2.601 2.599c-.889-.731-2.027-1.18-3.289-1.18v.376z" fill="#EA4335" />
                            <path d="M11.956 19.5a8.932 8.932 0 0 1-8.202-5.452l.367.275 3.038 2.236.005.003A5.273 5.273 0 0 0 11.956 19.5V19.5z" fill="#FBBC05" />
                        </svg>
                    </button>
                    <button>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24 12.073C24 5.404 18.627 0 12 0S0 5.404 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047v-2.66c0-3.025 1.79-4.697 4.533-4.697 1.312 0 2.687.235 2.687.235v2.969H15.83c-1.491 0-1.955.93-1.955 1.886v2.267h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z" fill="#1877F2" />
                        </svg>
                    </button>
                    <button>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231 5.45-6.231zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z" fill="#000000" />
                        </svg>
                    </button>
                </div>
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