<?php
$conn = new mysqli("localhost", "root", "", "citycare");

if ($conn->connect_error) {
    die("Database Error: " . $conn->connect_error);
}
?>
