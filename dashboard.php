<?php
include("header.php");
include("db.php");

$user_id = $_SESSION['user_id'];

$reports = $conn->query("SELECT * FROM reports WHERE user_id='$user_id' ORDER BY created_at DESC");
?>

<h2>Your Reports</h2>
<a href="add_report.php" class="btn-primary">Add New Report</a>
<table>
    <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $reports->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
            <a href="edit_report.php?id=<?= $row['report_id'] ?>" class="btn-success">Edit</a>
            <a href="delete_report.php?id=<?= $row['report_id'] ?>" class="btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include("footer.php"); ?>
