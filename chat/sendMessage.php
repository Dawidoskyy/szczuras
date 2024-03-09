<?php
    require "../inc/sql_connect.php";

    if(isset($_POST['message'])){
        $message = $_POST['message'];
        if(!empty($message)){
            $message = $conn->real_escape_string($message);
            $sender_ip = $_SERVER['REMOTE_ADDR'];
            
            // Wstaw now¹ wiadomoœæ do bazy danych
            $sql = "INSERT INTO messages (message, sender_ip) VALUES ('$message', '$sender_ip')";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // Zamknij po³¹czenie
    $conn->close();
?>