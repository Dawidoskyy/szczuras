<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_user'])) {
        // Zrobiæ tutaj jeszcze sprawdzanie czy jest zalogowany i ma suba

        $search_key = $_POST['search_user'];

        require "inc/sql_connect.php";
        require "inc/sql_funcs.php";

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
                </tr>";
        
            while ($row = $result->fetch_assoc()) {
                $_SESSION['modal_message'] .= "<tr>
                    <td>".$row['nickname']."</td>
                    <td>".$row['ip']."</td>
                    <td>".$row['fromwhere']."</td>
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