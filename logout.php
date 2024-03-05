<?php
    session_start();

    if (isset($_SESSION['authkey'])) {
        unset($_SESSION['authkey']);
        unset($_SESSION['username']);
        unset($_SESSION['admin']);
        unset($_SESSION['subscription']);
        unset($_SESSION['lookups']);
        unset($_SESSION['botnet_access']);
        unset($_COOKIE['auth_key_cookie']);

        $_SESSION['error_style'] = 1;
        $_SESSION['error_message'] = "Successfully logged out.";
    }

    header('Location: index.php');
    exit();
?>
