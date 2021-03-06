<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/google-material-icons.css">
    <link rel="stylesheet" href="../styles/login.css">
    <link rel="stylesheet" href="../styles/mini-footer.css">
</head>

<body>
    <div class="login-outer-container">
        <a href="../index.php" class="logo">
            <img src="../images/logo-black.png" class="logo">
        </a>
        <form id="login-form" class="login-form">
            <h1>Sign-In</h1>
            <label for="email"><b>Email</b></label>
            <input id="login-email-input" type="text" name="email" autofocus spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off">
            <div id="error-message-container-email" style="display: none; color: #c41829;">
                <span class="material-icons" style="font-size: 16px;">error</span>
                <span id="error-message-email" style="font-size: 0.8em;"></span>
            </div>
            <div class="form-spacer"></div>
            <label for="password"><b>Password</b></label>
            <input id="login-password-input" type="password" name="password" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off">
            <div id="error-message-container-password" style="display: none; color: #c41829;">
                <span class="material-icons" style="font-size: 16px;">error</span>
                <span id="error-message-password" style="font-size: 0.8em;"></span>
            </div>
            <div class="form-spacer"></div>
            <button type="submit" class="sign-in-button">Continue</button>
            <div id="error-message-container-general" style="display: none; color: #c41829;">
                <span class="material-icons" style="font-size: 16px;">error</span>
                <span id="error-message-general" style="font-size: 0.8em;"></span>
            </div>
            <br>
            <span id="disclaimer">By continuing, you agree to our <a href="./tos.php">Terms of Service</a> and <a href="./privacy-policy.php">Privacy Policy</a>.</span>
            <br>
            <div class="form-spacer"></div>
            <a href="./faq.php" id="need-help">Need help?</a>
        </form>
        <div class="create-account-container">
            <span>New to PandemicEssentials?</span>
            <a href="./register.php">
                <button type="button" class="create-account-link">Create your PandemicEssentials Account</button>
            </a>
        </div>
    </div>
    <?php include $_SERVER['DOCUMENT_ROOT']."/html/mini-footer.php";?>
    <script src="../scripts/handle-login.js" type="module"></script>
</body>