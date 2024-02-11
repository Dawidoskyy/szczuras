<?php
    session_start();

    if (isset($_SESSION['authkey'])) {
        unset($_SESSION['authkey']);
        unset($_SESSION['username']);

        $_SESSION['username'] = $row['username'];

        $_SESSION['error_style'] = 1;
        $_SESSION['error_message'] = "Successfully logged out.";
    }

    header('Location: index.php');
    exit();
?>