<?php 
include("header.php"); 
include("db.php");



if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($sql->num_rows > 0){
        $row = $sql->fetch_assoc();
        if(password_verify($pass, $row['password'])){
            // Start session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = trim($row['role']); // remove extra spaces
            $_SESSION['name'] = $row['name'];

            // Redirect based on role
            if($_SESSION['role'] === 'admin'){
                header("Location: admin.php"); // <-- Admin goes here
                exit;
            } else {
                header("Location: dashboard.php"); // <-- Users go here
                exit;
            }
        } else { 
            $error = "Incorrect password!"; 
        }
    } else { 
        $error = "Email not found!"; 
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-header">
                <h3 style="color:#fff;">Login</h3>
            </div>
            <div class="card-body">
                <?php if(isset($error)) echo '<div class="text-warning mb-2">'.$error.'</div>'; ?>
                <form method="POST">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button class="btn btn-primary w-100" name="login">Login</button>
                </form>
                <p class="mt-3" style="color:#e0e0e0;">
                    No account? <a href="register.php" style="color:var(--primary)">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
