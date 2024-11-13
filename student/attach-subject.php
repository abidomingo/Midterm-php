<?php
session_start();
require_once '../root/functions.php';
require_once '../root/header.php';

// Guard to ensure only logged-in users can access this page
guard();

// Check if the student ID is provided in the URL
if (!isset($_GET['student_id'])) {
    header("Location: register.php");
    exit;
}

$student_id = $_GET['student_id'];
$studentIndex = getSelectedStudentIndex($student_id);
$studentData = getSelectedStudentData($studentIndex);

if (!$studentData) {
    header("Location: register.php");
    exit;
}

$errors = [];
$successMessage = "";

// Handle form submission for attaching subjects
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedSubjects = $_POST['subjects'] ?? [];

    // Validate that at least one subject is selected
    $errors = validateAttachedSubject($selectedSubjects);

    if (empty($errors)) {
        // Attach selected subjects to the student
        if (!isset($studentData['attached_subjects'])) {
            $studentData['attached_subjects'] = [];
        }

        foreach ($selectedSubjects as $subject_code) {
            // Avoid attaching the same subject more than once
            if (!in_array($subject_code, $studentData['attached_subjects'])) {
                $studentData['attached_subjects'][] = $subject_code;
            }
        }

        // Update student data in session
        $_SESSION['students'][$studentIndex] = $studentData;
        $successMessage = "Subjects successfully attached to the student!";

        // Optionally, redirect after successful form submission
        header("Location: attach-subject.php?student_id=" . urlencode($student_id));  // Stay on this page after submission
        exit;
    }
}

// Get all available subjects (i.e., subjects not yet attached to the student)
$availableSubjects = $_SESSION['subjects'] ?? [];

// Get already attached subjects for the student
$attachedSubjects = $studentData['attached_subjects'] ?? [];

// Filter out subjects already attached to the student (these shouldn't appear in the form)
$subjectsToAttach = array_filter($availableSubjects, function($subject) use ($attachedSubjects) {
    return !in_array($subject['subject_code'], $attachedSubjects);
});

?>

<div class="container my-5">
    <h3>Attach Subject to Student</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../root/dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Attach Subject to Student</li>
        </ol>
    </nav>

    <!-- Display success or error messages -->
    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>
    <?php echo displayErrors($errors); ?>

    <!-- Student Information -->
    <div>
        <strong>Selected Student Information:</strong>
        <ul>
            <li>Student ID: <?php echo htmlspecialchars($studentData['student_id']); ?></li>
            <li>Name: <?php echo htmlspecialchars($studentData['first_name'] . ' ' . $studentData['last_name']); ?></li>
        </ul>
    </div>

    <!-- Attach Subjects Form -->
    <form method="POST" action="attach-subject.php?student_id=<?php echo urlencode($student_id); ?>">
        <?php if (!empty($subjectsToAttach)): ?>
            <?php foreach ($subjectsToAttach as $subject): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="subjects[]" value="<?php echo htmlspecialchars($subject['subject_code']); ?>"
                        <?php echo isset($studentData['attached_subjects']) && in_array($subject['subject_code'], $studentData['attached_subjects']) ? 'checked' : ''; ?>>
                    <label class="form-check-label">
                        <?php echo htmlspecialchars($subject['subject_code'] . " - " . $subject['subject_name']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary mt-3">Attach Subjects</button>
        <?php else: ?>
            <p>No subjects available to attach.</p>
        <?php endif; ?>
    </form>

    <!-- Attached Subjects List -->
    <div class="mt-5">
        <h5>Subject List</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($studentData['attached_subjects'])): ?>
                    <tr>
                        <td colspan="3" class="text-center">No subject found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($studentData['attached_subjects'] as $subject_code): ?>
                        <?php
                        $subjectIndex = getSelectedSubjectIndex($subject_code);
                        $subject = getSelectedSubjectData($subjectIndex);
                        ?>
                        <?php if ($subject): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                                <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                <td>
                                    <a href="detach-subject.php?student_id=<?php echo urlencode($student_id); ?>&subject_code=<?php echo urlencode($subject['subject_code']); ?>" class="btn btn-sm btn-danger">Detach Subject</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../root/footer.php'; ?>