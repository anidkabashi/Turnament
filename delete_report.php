<?php
include("db.php");
session_start();

if(!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$report_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Delete report (only user's own report)
$conn->query("DELETE FROM reports WHERE report_id='$report_id' AND user_id='$user_id'");
header("Location: dashboard.php");
?>
