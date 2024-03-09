<?php
    require "../inc/sql_connect.php";

    // Pobierz wiadomo�ci z bazy danych
    $sql = "SELECT * FROM messages ORDER BY timestamp ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Wy�wietl wiadomo�ci
        while($row = $result->fetch_assoc()) {
            echo "<div><strong>" . $row["timestamp"]. " - " . $row["sender_ip"]. "</strong>: " . $row["message"]. "</div>\n";
        }
    } else {
        echo "0 results";
    }

    // Zamknij po��czenie
    $conn->close();
?>