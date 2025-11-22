<?php
include("header.php");
include("db.php");


if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $cat = $_POST['category'];
    $user_id = $_SESSION['user_id'];

    $conn->query("INSERT INTO reports (user_id,title,description,category) VALUES ('$user_id','$title','$desc','$cat')");
    header("Location: dashboard.php");
}
?>

<h2>Add New Report</h2>
<form method="POST">
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="description" placeholder="Description"></textarea>
    <select name="category" required>
        <option value="Pollution">Pollution</option>
        <option value="Broken Lights">Broken Lights</option>
        <option value="Roads">Roads</option>
        <option value="Other">Other</option>
    </select>
    <button class="btn-primary" name="submit">Submit</button>
</form>

<?php include("footer.php"); ?>
