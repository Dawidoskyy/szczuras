<?php
    session_start();

    require "inc/sql_connect.php";
    require "inc/sql_funcs.php";
    require "inc/main_funcs.php";

    if($_SESSION['admin'] <= 0) {
        header('Location: logout.php');
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
        if($_POST['action'] == "create_key") {
            $username = $_POST['generate_username'];
            $subdays = $_POST['generate_days'] * 86400;
            $randomkey = generateRandomString(32);

            $logsData = [
                'username' => $username,
                'authkey' => $randomkey,
                'subscription' => time() + $subdays
            ];
            addNewRecord($conn, 'users', $logsData);

            $_SESSION['error_style'] = 1;
            $_SESSION['error_message'] = "Generated new user!<br><br>Keyauth: ".$randomkey."<br>Username: ".$username;
        } else if($_POST['action'] == "add_days") {
            $keyauth = $_POST['add_key'];
            $subdays = $_POST['add_days'] * 86400;

            $activesub = fetchRecords($conn, "users", "authkey", $keyauth);
            if (!empty($activesub)) {
                foreach ($activesub as $record) { 
                    $subtime = $record['subscription'];
                    if(time() > $subtime) {
                        updateRecord($conn, 'users', 'subscription', time() + $subdays, 'authkey', $keyauth);
                    } else {
                        updateRecord($conn, 'users', 'subscription', $subtime + $subdays, 'authkey', $keyauth);
                    }

                    $_SESSION['error_style'] = 1;
                    $_SESSION['error_message'] = "Subscription added!";
                }
            } else {
                $_SESSION['error_style'] = 0;
                $_SESSION['error_message'] = "No key found!";
            }
        } else if($_POST['action'] == "blacklist_username") {
            $username =  $_POST['username'];

            $blacklistedUsers = fetchRecords($conn, "blacklist", "name", $username);
            if (!empty($blacklistedUsers)) {
                $_SESSION['error_style'] = 0;
                $_SESSION['error_message'] = "This user is already blacklisted!";
            } else {
                $blacklistData = [
                    'name' => $username,
                    'added_by' => $_SESSION['username']
                ];
                addNewRecord($conn, 'blacklist', $blacklistData);

                $_SESSION['error_style'] = 1;
                $_SESSION['error_message'] = "Blacklist successfully added!";
            }
        } else if($_POST['action'] == "remove_blacklist") {
            $username =  $_POST['username'];

            $blacklistedUsers = fetchRecords($conn, "blacklist", "name", $username);
            if (empty($blacklistedUsers)) {
                $_SESSION['error_style'] = 0;
                $_SESSION['error_message'] = "This user is not blacklisted!";
            } else {
                deleteRecord($conn, "blacklist", "name", $username);

                $_SESSION['error_style'] = 1;
                $_SESSION['error_message'] = "Blacklist successfully removed!";
            }
        } else if($_POST['action'] == "add_new_leak") {
            $username =  $_POST['username'];
            $IP =  $_POST['user_ip'];
            $from =  $_POST['leak_from'];

            $leakData = [
                'nickname' => $username,
                'ip' => $IP,
                'fromwhere' => $from
            ];
            addNewRecord($conn, 'ipki', $leakData);

            $_SESSION['error_style'] = 1;
            $_SESSION['error_message'] = "Blacklist successfully added!";
        }
    }
    header('Location: admin.php');
    exit();
?>