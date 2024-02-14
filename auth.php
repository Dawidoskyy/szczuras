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

    // Sprawdza blacklistê
    $blacklistedUsers = fetchRecords($conn, "blacklist", "name", $search_key);
    if (!empty($blacklistedUsers)) {
        $_SESSION['error_style'] = 0;
        $_SESSION['error_message'] = "User is blacklisted.";
        header('Location: index.php');
        exit();
    }

    // Pobieranie danych z API Mojang za pomoc¹ cURL
    $mojang_api_url = "https://api.mojang.com/users/profiles/minecraft/" . $search_key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $mojang_api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $mojang_response = curl_exec($ch);
    curl_close($ch);

    if ($mojang_response === false) {
        $_SESSION['error_style'] = 0;
        $_SESSION['error_message'] = "Error retrieving data from Mojang API.";
        header('Location: index.php');
        exit();
    }

    $mojang_data = json_decode($mojang_response, true);

    // Pobieranie historii nicków z API LabyMod za pomoc¹ cURL
    if ($mojang_data !== null && isset($mojang_data['id'])) {
        $player_id = $mojang_data['id'];
        $labymod_api_url = "https://laby.net/api/v2/user/$player_id/get-profile";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $labymod_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $labymod_response = curl_exec($ch);
        curl_close($ch);

        if ($labymod_response === false) {
            $_SESSION['error_style'] = 0;
            $_SESSION['error_message'] = "Error retrieving data from LabyMod API.";
            header('Location: index.php');
            exit();
        }

        $labymod_data = json_decode($labymod_response, true);

        if ($labymod_data !== null && isset($labymod_data['username_history'])) {
            $history = $labymod_data['username_history'];
            
            // Dodaj aktualny nick do historii nicków, aby wyszukaæ równie¿ po nim
            $history[] = array('username' => $search_key);

            // Przechowuj wyniki wyszukiwania w tablicy
            $search_results = [];

            // Przeszukiwanie starych nicków w bazie danych ipki
            foreach ($history as $entry) {
                $old_username = $entry['username'];
                
                $sql = "SELECT * FROM ipki WHERE nickname = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $old_username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Jeœli znaleziono u¿ytkownika, dodaj wynik do tablicy
                    while ($row = $result->fetch_assoc()) {
                        $search_results[] = $row;
                    }
                }
            }

            if (!empty($search_results)) {
                // Jeœli znaleziono wyniki, wyœwietl informacje
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
                    'date' => time(),
                    'status' => 1
                ];
                addNewRecord($conn, 'search_logs', $logsData);
            } else {
                // Jeœli nie znaleziono wyników, wyœwietl odpowiedni komunikat
                $_SESSION['error_style'] = 0;
                $_SESSION['error_message'] = "User not found!";
                unset($_SESSION['search_user']);
            }
        } else {
            // Obs³uga b³êdu braku danych z API LabyMod
            $_SESSION['error_style'] = 0;
            $_SESSION['error_message'] = "No username history found for this user.";
            header('Location: index.php');
            exit();
        }
    } else {
        // Obs³uga b³êdu braku danych z API Mojang
        $_SESSION['error_style'] = 0;
        $_SESSION['error_message'] = "No user found with this username.";
        header('Location: index.php');
        exit();
    }

    header('Location: index.php');
    exit();
}
?>
