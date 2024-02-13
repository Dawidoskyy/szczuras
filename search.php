<?php
session_start();

require "inc/sql_connect.php";
require "inc/sql_funcs.php";

if ($_SESSION['lastaction'] + 3 > time()) {
    $_SESSION['error_style'] = 0;
    $_SESSION['error_message'] = "Too many requests to api. Try again later.";
    header('Location: index.php');
    exit();
}

$_SESSION['lastaction'] = time();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_user'])) {
    // Sprawdzanie czy ma suba
    if (time() > $_SESSION['subscription']) {
        $_SESSION['error_style'] = 0;
        $_SESSION['error_message'] = "Subscription error";
        header('Location: index.php');
        exit();
    }

    $search_key = $_POST['search_user'];

    // Sprawdza kurwa blackliste
    $blacklistedUsers = fetchRecords($conn, "blacklist", "name", $search_key);
    if (!empty($blacklistedUsers)) {
        $_SESSION['error_style'] = 0;
        $_SESSION['error_message'] = "User is blacklisted.";
        header('Location: index.php');
        exit();
    }

    $sql = "SELECT * FROM ipki WHERE nickname = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search_key);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['modal_message'] = "<center><h1 class='jebanelogo' style='font-size: 35px;'>User found!</h1><br>
        <table>
            <tr>
                <th>Username</th>
                <th>IP</th>
                <th>From</th>
                <th></th>
            </tr>";

            while ($row = $result->fetch_assoc()) {
                $_SESSION['modal_message'] .= "<tr>
                    <td>" . $row['nickname'] . "</td>
                    <td>" . $row['ip'] . "</td>
                    <td>" . $row['fromwhere'] . "</td>
                    <td><button class='chujowyprzycisk' onclick=\"var textarea = document.createElement('textarea');textarea.value = '" . $row['ip'] . "';document.body.appendChild(textarea);textarea.select();document.execCommand('copy');document.body.removeChild(textarea);\">Copy IP</button></td>
                </tr>";
            }
            
            

        $_SESSION['lookups'] += $result->num_rows;
        updateRecord($conn, 'users', 'lookups', $_SESSION['lookups'], 'authkey', $_SESSION['authkey']);

        $logsData = [
            'user' => $_SESSION['username'],
            'value' => $search_key,
            'date' => time(),
            'status' => 1
        ];
        addNewRecord($conn, 'search_logs', $logsData);
    } else {
        $logsData = [
            'user' => $_SESSION['username'],
            'value' => $search_key,
            'date' => time(),
            'status' => 0
        ];
        addNewRecord($conn, 'search_logs', $logsData);

        $_SESSION['error_style'] = 0;
        $_SESSION['error_message'] = "User not found!";
        unset($_SESSION['search_user']);
    }

    header('Location: index.php');
    exit();
}
?>