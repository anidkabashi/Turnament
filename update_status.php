<?php
session_start();
include("db.php");

// Check admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access");
}

// Check input
if (isset($_GET['id']) && isset($_GET['status'])) {

    $id = intval($_GET['id']);
    $status = $_GET['status'];

    // Only allow valid statuses
    if ($status !== "resolved" && $status !== "pending") {
        $_SESSION['msg'] = "Invalid status!";
        header("Location: admin.php");
        exit;
    }

    // Update database
    $stmt = $conn->prepare("UPDATE reports SET status = ? WHERE report_id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Report status updated successfully!";
    } else {
        $_SESSION['msg'] = "Failed to update report!";
    }

    header("Location: admin.php");
    exit;
} else {
    $_SESSION['msg'] = "Invalid request!";
    header("Location: admin.php");
    exit;
}
?>
