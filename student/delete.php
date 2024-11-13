<?php
session_start();
require_once '../functions.php';

// Guard to ensure only logged-in users can access this page
guard();

$errors = [];
$successMessage = "";

// Check if the student ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: register.php");
    exit;
}

$student_id = $_GET['id'];
$studentIndex = getSelectedStudentIndex($student_id);
$studentData = getSelectedStudentData($studentIndex);

if (!$studentData) {
    header("Location: register.php");
    exit;
}

// Handle form submission for deleting a student
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete student from session
    unset($_SESSION['students'][$studentIndex]);

    // Re-index the session array after deletion
    $_SESSION['students'] = array_values($_SESSION['students']);

    // Set success message and redirect
    $successMessage = "Student record deleted successfully!";
    header("Location: register.php");  // Redirect to the student registration page
    exit;
}

?>

<?php require_once '../header.php'; ?>

<div class="container my-5">
    <h3>Delete a Student</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
        </ol>
    </nav>

    <!-- Display success or error messages -->
    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <!-- Display student details for confirmation -->
    <div class="card mb-4">
        <div class="card-body">
            <p>Are you sure you want to delete the following student record?</p>
            <ul>
                <li><strong>Student ID:</strong> <?php echo htmlspecialchars($studentData['student_id']); ?></li>
                <li><strong>First Name:</strong> <?php echo htmlspecialchars($studentData['first_name']); ?></li>
                <li><strong>Last Name:</strong> <?php echo htmlspecialchars($studentData['last_name']); ?></li>
            </ul>

            <!-- Confirmation form -->
            <form method="POST" action="delete.php?id=<?php echo urlencode($student_id); ?>">
                <!-- Cancel Button: Goes back to the previous page -->
                <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
                <!-- Delete Button: Submits the form and deletes the student -->
                <button type="submit" class="btn btn-danger">Delete Student Record</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../footer.php'; ?>