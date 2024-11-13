<?php
require_once 'header.php';
require_once 'functions.php'; // Ensure functions.php is included

// Ensure the user is logged in
guard();
?>

<!-- Welcome Message -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Welcome to the System: <?php echo htmlspecialchars($_SESSION['email']); ?></h3>
    <!-- Redirect logout form to logout.php -->
    <form method="POST" action="logout.php" style="margin: 0;">
        <button type="submit" name="logout" class="btn btn-danger">Logout</button>
    </form>
</div>

<!-- Card Container -->
<div class="row">
    <!-- Add a Subject Card -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header">Add a Subject</div>
            <div class="card-body">
                <p>This section allows you to add a new subject in the system. Click the button below to proceed with the adding process.</p>
                <a href="subject/add.php" class="btn btn-primary">Add Subject</a>
            </div>
        </div>
    </div>

    <!-- Register a Student Card -->
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header">Register a Student</div>
            <div class="card-body">
                <p>This section allows you to register a new student in the system. Click the button below to proceed with the registration process.</p>
                <a href="../student/register.php" class="btn btn-primary">Register</a>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>