<?php 
include("header.php"); 
include("db.php");

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if($check->num_rows > 0){
        $error = "Email already registered!";
    } else {
        $conn->query("INSERT INTO users (name,email,password,role) VALUES ('$name','$email','$password','user')");
        $success = "Registration successful! You can now login.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-header">
                <h3 style="color:#fff;">Register</h3>
            </div>
            <div class="card-body">
                <?php 
                if(isset($error)) echo '<div class="text-warning mb-2">'.$error.'</div>';
                if(isset($success)) echo '<div class="text-success mb-2">'.$success.'</div>';
                ?>
                <form method="POST">
                    <input type="text" name="name" placeholder="Full Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button class="btn btn-primary w-100" name="register">Register</button>
                </form>
                <p class="mt-3" style="color:#e0e0e0;">
                    Already have an account? <a href="login.php" style="color:var(--primary)">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
