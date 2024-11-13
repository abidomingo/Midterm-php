<?php
require_once 'header.php';
require_once 'functions.php'; 

checkUserSessionIsActive();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate login credentials using the validateLoginCredentials function
    $errors = validateLoginCredentials($email, $password);

    // Check login credentials if no validation errors
    if (empty($errors)) {
        if (!checkLoginCredentials($email, $password, getUsers())) {
            $errors[] = "Invalid email or password";
        } else {
            // Set session and redirect to dashboard if login is successful
            $_SESSION['email'] = $email;
            header("Location: dashboard.php");
            exit;
        }
    }
}
?>

<div class="container d-flex flex-column justify-content-center align-items-center vh-100">
    <!-- Display error messages above the card -->
    <?php echo displayErrors($errors); ?>

    <!-- Login card -->
    <div class="card shadow-sm" style="width: 400px;">
        <div class="card-body">
            <h5 class="card-title text-center mb-4">Login</h5>

            <!-- Form -->
            <form action="index.php" method="POST" novalidate>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>