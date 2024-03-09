<?php
    $conn = new mysqli("localhost", "skillhost", "tjau3O3SkJHsuL", "gicik");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>