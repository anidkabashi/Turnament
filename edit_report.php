<?php
include("header.php");
include("db.php");
session_start();

if(!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$report_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch report
$result = $conn->query("SELECT * FROM reports WHERE report_id='$report_id' AND user_id='$user_id'");
$report = $result->fetch_assoc();

if(!$report) {
    echo "<p class='card'>Report not found.</p>";
    include("footer.php");
    exit;
}

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $cat = $_POST['category'];

    $conn->query("UPDATE reports SET title='$title', description='$desc', category='$cat' WHERE report_id='$report_id'");
    header("Location: dashboard.php");
}

?>

<h2>Edit Report</h2>
<form method="POST">
    <input type="text" name="title" value="<?= htmlspecialchars($report['title']) ?>" required>
    <textarea name="description"><?= htmlspecialchars($report['description']) ?></textarea>
    <select name="category" required>
        <option value="Pollution" <?= $report['category']=='Pollution'?'selected':'' ?>>Pollution</option>
        <option value="Broken Lights" <?= $report['category']=='Broken Lights'?'selected':'' ?>>Broken Lights</option>
        <option value="Roads" <?= $report['category']=='Roads'?'selected':'' ?>>Roads</option>
        <option value="Other" <?= $report['category']=='Other'?'selected':'' ?>>Other</option>
    </select>
    <button class="btn-primary" name="submit">Save Changes</button>
</form>

<?php include("footer.php"); ?>
