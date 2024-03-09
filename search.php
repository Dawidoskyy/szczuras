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

function search_api_error($message, $search)
{
    $_SESSION['error_style'] = 0;
    $_SESSION['error_message'] = $message;
    header('Location: index.php');
    exit;
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
        search_api_error("User is blacklisted.", $search_key);
    }

    $mojang_api_url = "https://api.mojang.com/users/profiles/minecraft/" . $search_key;
    $mojang_response = file_get_contents($mojang_api_url);

    if ($mojang_response === false) {
        search_api_error("Error retrieving data from API #1.", $search_key);
    }

    $mojang_data = json_decode($mojang_response, true);

    if ($mojang_data !== null && isset($mojang_data['id'])) {
        $player_id = $mojang_data['id'];
        $labymod_api_url = "https://laby.net/api/v2/user/$player_id/get-profile";
        $labymod_response = file_get_contents($labymod_api_url);

        if ($labymod_response === false) {
            search_api_error("Error retrieving data from API #2.", $search_key);
        }

        $labymod_data = json_decode($labymod_response, true);

        if ($labymod_data !== null && isset($labymod_data['username_history'])) {
            $history = $labymod_data['username_history'];
            
            $history[] = array('username' => $search_key);

            $search_results = [];

            foreach ($history as $entry) {
                $old_username = $entry['username'];
                
                $sql = "SELECT * FROM ipki WHERE nickname = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $old_username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $search_results[] = $row;
                    }
                }
            }

            if (!empty($search_results)) {
                $_SESSION['modal_message'] = "<center><h1 class='jebanelogo' style='font-size: 35px;'>User found!</h1><br>
                <table>
                    <tr>
                        <th>Username</th>
                        <th>IP</th>
                        <th>From</th>
                        <th></th>
                    </tr>";

                foreach ($search_results as $row) {
                    $_SESSION['modal_message'] .= "<tr>
                            <td>" . $row['nickname'] . "</td>
                            <td>" . $row['ip'] . "</td>
                            <td>" . $row['fromwhere'] . "</td>
                            <td><button class='chujowyprzycisk' onclick=\"var textarea = document.createElement('textarea');textarea.value = '" . $row['ip'] . "';document.body.appendChild(textarea);textarea.select();document.execCommand('copy');document.body.removeChild(textarea);\">Copy IP</button></td>
                        </tr>";
                }

                $_SESSION['lookups'] += count($search_results);
                updateRecord($conn, 'users', 'lookups', $_SESSION['lookups'], 'authkey', $_SESSION['authkey']);

                $logsData = [
                    'user' => $_SESSION['username'],
                    'value' => $search_key,
                    'status' => 1
                ];
                addNewRecord($conn, 'search_logs', $logsData);
            } else {
                search_api_error("User not found!", $search_key);
            }
        } else {
            search_api_error("No username history found for this user.", $search_key);
        }
    } else {
        search_api_error("No user found with this username.", $search_key);
    }

    header('Location: index.php');
    exit();
}
?>