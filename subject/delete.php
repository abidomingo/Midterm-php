<?php
require_once '../root/header.php'; // Include the header

// Guard to ensure only logged-in users can access
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

// Handle delete confirmation form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete the subject from the session
    unset($_SESSION['subjects'][$subjectIndex]);
    $_SESSION['subjects'] = array_values($_SESSION['subjects']); // Reindex array
    
    // Redirect to add.php with a success message after deletion
    $_SESSION['delete_success'] = "Subject deleted successfully!";
    header("Location: add.php");
    exit;
}
?>

<h3>Delete Subject</h3>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../root/dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
        <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
    </ol>
</nav>

<!-- Confirmation Message -->
<div class="card">
    <div class="card-body">
        <p>Are you sure you want to delete the following subject record?</p>
        <ul>
            <li><strong>Subject Code:</strong> <?php echo htmlspecialchars($subjectData['subject_code']); ?></li>
            <li><strong>Subject Name:</strong> <?php echo htmlspecialchars($subjectData['subject_name']); ?></li>
        </ul>

        <!-- Delete Confirmation Form -->
        <form action="delete.php?code=<?php echo urlencode($subjectCode); ?>" method="POST">
            <a href="add.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-danger">Delete Subject Record</button>
        </form>
    </div>
</div>

<?php
require_once '../root/footer.php'; // Include the footer