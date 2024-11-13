<?php
require_once '../root/header.php'; // Include the header

// Guard to restrict access to logged-in users only
guard();

// Check if the subject code is provided in the URL
if (!isset($_GET['code'])) {
    // Redirect back to the add subject page if no code is provided
    header("Location: add.php");
    exit;
}

$subjectCode = $_GET['code'];
$subjectData = null;

// Find the subject in the session based on the subject code
foreach ($_SESSION['subjects'] as $index => $subject) {
    if ($subject['subject_code'] === $subjectCode) {
        $subjectData = $subject;
        $subjectIndex = $index;
        break;
    }
}

// If subject is not found, redirect to the add subject page
if (!$subjectData) {
    header("Location: add.php");
    exit;
}

$errors = [];
$successMessage = "";

// Handle form submission for editing the subject
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subjectName = trim($_POST['subject_name']);

    // Validation: Check if subject name is empty
    if (empty($subjectName)) {
        $errors[] = "Subject Name is required";
    }

    // Update the subject in session if there are no errors
    if (empty($errors)) {
        $_SESSION['subjects'][$subjectIndex]['subject_name'] = $subjectName;
        $successMessage = "Subject updated successfully!";
        
        // Redirect back to the add subject page after update
        header("Location: add.php");
        exit;
    }
}
?>

<h3>Edit Subject</h3>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../root/dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
    </ol>
</nav>

<!-- Display error messages -->
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>System Errors</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Edit Subject Form -->
<div class="card mb-4">
    <div class="card-body">
        <form action="edit.php?code=<?php echo urlencode($subjectCode); ?>" method="POST">
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?php echo htmlspecialchars($subjectData['subject_code']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Enter Subject Name" value="<?php echo htmlspecialchars($subjectData['subject_name']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Subject</button>
        </form>
    </div>
</div>

<?php
require_once '../root/footer.php'; // Include the footer