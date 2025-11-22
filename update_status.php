<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include("db.php");

// Only admin
if(!isset($_SESSION['role']) || trim($_SESSION['role']) !== 'admin'){
    header("Location: dashboard.php"); exit;
}

// Validate input
if(!isset($_GET['id']) || !isset($_GET['status'])){
    header("Location: admin.php"); exit;
}

$report_id = intval($_GET['id']);
$status = $_GET['status'];

// Update report
$stmt = $conn->prepare("UPDATE reports SET status=? WHERE report_id=?");
$stmt->bind_param("si", $status, $report_id);
$stmt->execute();
$stmt->close();

// Redirect
header("Location: admin.php");
exit;
?>
