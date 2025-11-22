<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("db.php");

// Only admin can access
if(!isset($_SESSION['role']) || trim($_SESSION['role']) !== 'admin'){
    header("Location: dashboard.php");
    exit;
}

// Fetch stats
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$totalReports = $conn->query("SELECT COUNT(*) as total FROM reports")->fetch_assoc()['total'];
$pendingReports = $conn->query("SELECT COUNT(*) as total FROM reports WHERE status='pending'")->fetch_assoc()['total'];
$resolvedReports = $conn->query("SELECT COUNT(*) as total FROM reports WHERE status='resolved'")->fetch_assoc()['total'];
?>

<?php include("header.php"); ?>

<div class="container">

    <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?> <span class="badge-admin">(Admin)</span></h2>

    <!-- Summary Cards -->
    <div class="row" style="margin-top:20px;">
        <div class="card" style="margin-right:20px; padding:20px; flex:1; color:white;">
            <h3>Total Users</h3>
            <p><?= $totalUsers ?></p>
        </div>
        <div class="card" style="margin-right:20px; padding:20px; flex:1; color:white;">
            <h3>Total Reports</h3>
            <p><?= $totalReports ?></p>
        </div>
        <div class="card" style="margin-right:20px; padding:20px; flex:1; color:white;">
            <h3>Pending Reports</h3>
            <p><?= $pendingReports ?></p>
        </div>
        <div class="card" style="padding:20px; flex:1; color:white;">
            <h3>Resolved Reports</h3>
            <p><?= $resolvedReports ?></p>
        </div>
    </div>

    <!-- Users Table -->
    <h3 style="margin-top:40px;">Users</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Reports Submitted</th>
        </tr>
        <?php
        $users = $conn->query("SELECT u.user_id, u.name, u.email, u.role, COUNT(r.report_id) as reports_count 
                               FROM users u 
                               LEFT JOIN reports r ON u.user_id = r.user_id
                               GROUP BY u.user_id");
        while($user = $users->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td><?= $user['reports_count'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Reports Table -->
    <h3 style="margin-top:40px;">Reports</h3>
    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Submitted By</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        $reports = $conn->query("SELECT r.report_id, r.title, r.description, r.status, u.name as user_name
                                 FROM reports r
                                 JOIN users u ON r.user_id = u.user_id
                                 ORDER BY r.report_id DESC");
        while($report = $reports->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($report['title']) ?></td>
            <td><?= htmlspecialchars($report['description']) ?></td>
            <td><?= htmlspecialchars($report['user_name']) ?></td>
            <td><?= htmlspecialchars($report['status']) ?></td>
            <td>
                <?php if($report['status'] == 'pending'): ?>
                    <a href="update_status.php?id=<?= $report['report_id'] ?>&status=resolved" class="btn btn-success">Resolve</a>
                <?php else: ?>
                    <span class="btn btn-primary" style="cursor: default;">Resolved</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>

<?php include("footer.php"); ?>
