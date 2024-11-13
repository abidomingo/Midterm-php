<?php
require_once '../header.php';
require_once '../functions.php';

// Guard to ensure only logged-in users can access this page
guard();

// Check if the student ID and subject code are provided in the URL
if (!isset($_GET['student_id']) || !isset($_GET['subject_code'])) {
    header("Location: register.php");
    exit;
}

$student_id = $_GET['student_id'];
$subject_code = $_GET['subject_code'];
$studentIndex = getSelectedStudentIndex($student_id);
$studentData = getSelectedStudentData($studentIndex);

// Ensure the student exists and the subject is attached to them
if (!$studentData || !in_array($subject_code, $studentData['attached_subjects'])) {
    header("Location: register.php");
    exit;
}

// Define the `getSubjectName` function here, specifically for this file
function getSubjectName($subject_code) {
    foreach ($_SESSION['subjects'] as $subject) {
        if ($subject['subject_code'] === $subject_code) {
            return $subject['subject_name'];
        }
    }
    return null;
}

// Handle form submission for detaching the subject
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attachedSubjects = $studentData['attached_subjects'];
    if (($key = array_search($subject_code, $attachedSubjects)) !== false) {
        unset($attachedSubjects[$key]);
        $studentData['attached_subjects'] = array_values($attachedSubjects); // Reindex array
        $_SESSION['students'][$studentIndex] = $studentData;

        // After successful detachment, redirect to attach-subject.php
        header("Location: attach-subject.php?student_id=" . urlencode($student_id));
        exit;
    }
}

?>

<div class="container my-5">
    <h3>Detach Subject from Student</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detach Subject from Student</li>
        </ol>
    </nav>

    <!-- Confirmation form for detaching subject -->
    <p>Are you sure you want to detach the following subject from this student record?</p>
    <ul>
        <li>Student ID: <?php echo htmlspecialchars($studentData['student_id']); ?></li>
        <li>First Name: <?php echo htmlspecialchars($studentData['first_name']); ?></li>
        <li>Last Name: <?php echo htmlspecialchars($studentData['last_name']); ?></li>
        <li>Subject Code: <?php echo htmlspecialchars($subject_code); ?></li>
        <li>Subject Name: <?php echo htmlspecialchars(getSubjectName($subject_code)); ?></li>
    </ul>

    <!-- Form to confirm detachment -->
    <form method="POST" action="dettach-subject.php?student_id=<?php echo urlencode($student_id); ?>&subject_code=<?php echo urlencode($subject_code); ?>">
        <a href="register.php" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-danger">Detach Subject from Student</button>
    </form>
</div>

<?php require_once '../footer.php'; ?>