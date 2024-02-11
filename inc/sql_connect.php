<?php
    $conn = new mysqli("localhost", "root", "SDGsfhfgdzhgdhSDFIHSFmhfsHfsYHsfhn", "gicik");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>