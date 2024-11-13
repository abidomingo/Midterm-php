<?php
session_start();
require_once '../root/functions.php';

// Guard to ensure only logged-in users can access this page
guard();

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

$errors = [];
$successMessage = "";

// Handle form submission for updating student data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated data from the form
    $updatedStudentData = [
        'student_id' => $student_id,  // Student ID cannot be changed
        'first_name' => trim($_POST['first_name']),
        'last_name' => trim($_POST['last_name']),
    ];

    // Validate updated data
    $errors = validateStudentData($updatedStudentData);

    // If no validation errors, update the student data
    if (empty($errors)) {
        $_SESSION['students'][$studentIndex] = $updatedStudentData;  // Update session data
        $successMessage = "Student information updated successfully!";
        // Optionally, redirect to register.php or another page after success
        header("Location: register.php");
        exit;
    }
}

?>

<?php require_once '../root/header.php'; ?>

<div class="container my-5">
    <h3>Edit Student</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../root/dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
        </ol>
    </nav>

    <!-- Display success or error messages -->
    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>
    <?php echo displayErrors($errors); ?>

    <!-- Edit Student Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="edit.php?id=<?php echo urlencode($student_id); ?>">
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo htmlspecialchars($studentData['student_id']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($studentData['first_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($studentData['last_name']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Student</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../root/footer.php'; ?>