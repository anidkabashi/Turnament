<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("header.php");
include("db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$user_role = $_SESSION['role'];

// Fetch stats for the logged-in user
$totalReports = $conn->query("SELECT COUNT(*) as total FROM reports WHERE user_id='$user_id'")->fetch_assoc()['total'];
$pendingReports = $conn->query("SELECT COUNT(*) as total FROM reports WHERE user_id='$user_id' AND status='pending'")->fetch_assoc()['total'];
$resolvedReports = $conn->query("SELECT COUNT(*) as total FROM reports WHERE user_id='$user_id' AND status='resolved'")->fetch_assoc()['total'];

// Fetch all reports for the logged-in user
$reports = $conn->query("SELECT * FROM reports WHERE user_id='$user_id' ORDER BY created_at DESC");

?>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($user_name) ?> <span style="color:#888; font-size: 14px;">(<?= htmlspecialchars(ucfirst($user_role)) ?>)</span></h2>

    <div style="margin: 15px 0; padding: 15px; background: #222; border-radius: 8px; color: white;">
        <h3>Your Profile</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($user_name) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars(ucfirst($user_role)) ?></p>
    </div>

    <!-- Summary Cards -->
    <div style="display: flex; gap: 20px; margin-top: 20px;">
        <div style="flex: 1; background: #2c3e50; padding: 20px; border-radius: 8px; color: white; text-align: center;">
            <h3>Total Reports</h3>
            <p style="font-size: 24px;"><?= $totalReports ?></p>
        </div>
        <div style="flex: 1; background: #e67e22; padding: 20px; border-radius: 8px; color: white; text-align: center;">
            <h3>Pending Reports</h3>
            <p style="font-size: 24px;"><?= $pendingReports ?></p>
        </div>
        <div style="flex: 1; background: #27ae60; padding: 20px; border-radius: 8px; color: white; text-align: center;">
            <h3>Resolved Reports</h3>
            <p style="font-size: 24px;"><?= $resolvedReports ?></p>
        </div>
    </div>

    <!-- Chart Section -->
    <div style="margin-top: 30px; max-width: 400px;">
        <canvas id="reportChart"></canvas>
    </div>

    <!-- Add New Report Button -->
    <a href="add_report.php" class="btn-primary" style="margin-top: 20px; display: inline-block;">Add New Report</a>

    <!-- Reports Table -->
    <h3 style="margin-top: 20px;">Your Reports</h3>
    <table id="reportsTable" style="width: 100%; border-collapse: collapse; background: #2c3e50; border-radius: 8px; overflow: hidden;">
        <thead style="background: #34495e;">
            <tr>
                <th style="padding: 12px; text-align: left; color: white;">Title</th>
                <th style="padding: 12px; text-align: left; color: white;">Category</th>
                <th style="padding: 12px; text-align: left; color: white;">Status</th>
                <th style="padding: 12px; text-align: left; color: white;">Created</th>
                <th style="padding: 12px; text-align: left; color: white;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($reports->num_rows > 0): ?>
                <?php while($row = $reports->fetch_assoc()): ?>
                <tr>
                    <td style="padding: 12px;"><?= htmlspecialchars($row['title']) ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($row['category']) ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                    <td style="padding: 12px;"><?= $row['created_at'] ?></td>
                    <td style="padding: 12px;">
                        <a href="edit_report.php?id=<?= $row['report_id'] ?>" class="btn-success" style="margin-right: 8px;">Edit</a>
                        <a href="delete_report.php?id=<?= $row['report_id'] ?>" class="btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="padding: 12px; text-align: center; color: #ccc;">No reports found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js pie chart for report status
const ctx = document.getElementById('reportChart').getContext('2d');
const reportChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Pending', 'Resolved'],
        datasets: [{
            data: [<?= $pendingReports ?>, <?= $resolvedReports ?>],
            backgroundColor: ['#e67e22', '#27ae60']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>

<?php include("footer.php"); ?>
