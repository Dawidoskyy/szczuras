<?php
session_start();

if(isset($_SESSION['authkey'])) {
    $_SESSION['lastaction'] = time();

    header('Location: index.php');
    exit();
}

if(isset($_COOKIE['login_key'])) {
    $login_key = $_COOKIE['login_key'];

    require "inc/sql_connect.php";
    require "inc/sql_funcs.php";

    $sql = "SELECT * FROM users WHERE authkey = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login_key);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $_SESSION['authkey'] = $login_key;
        $_SESSION['username'] = $row['username'];
        $_SESSION['subscription'] = $row['subscription'];
        $_SESSION['lookups'] = $row['lookups'];
        $_SESSION['admin'] = $row['admin'];

        $_SESSION['lastaction'] = time();

        header('Location: index.php');
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_key'])) {
    $login_key = $_POST['login_key'];

    require "inc/sql_connect.php";
    require "inc/sql_funcs.php";

    $sql = "SELECT * FROM users WHERE authkey = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login_key);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $_SESSION['authkey'] = $login_key;
        $_SESSION['username'] = $row['username'];
        $_SESSION['subscription'] = $row['subscription'];
        $_SESSION['lookups'] = $row['lookups'];
        $_SESSION['admin'] = $row['admin'];

        setcookie("login_key", $login_key, time() + 604800, "/");

        $_SESSION['lastaction'] = time();

        header('Location: index.php');
        exit();
    } else {
        $_SESSION['error_style'] = 0;
        $_SESSION['error_message'] = "Invalid key.";

        unset($_SESSION['authkey']);
        
        header('Location: index.php');
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
