<?php
require_once '../root/header.php'; // Include the header
require_once '../root/functions.php'; // Include the functions file

// Guard to restrict access to logged-in users only
guard();

$errors = [];
$successMessage = "";

// Display delete success message if it exists in the session
if (isset($_SESSION['delete_success'])) {
    $successMessage = $_SESSION['delete_success'];
    unset($_SESSION['delete_success']); // Clear the message after displaying
}

// Initialize subjects in session if not set
if (!isset($_SESSION['subjects'])) {
    $_SESSION['subjects'] = [];
}

// Handle form submission for adding a subject
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subjectCode = trim($_POST['subject_code']);
    $subjectName = trim($_POST['subject_name']);

    // Validate subject data
    $subject_data = [
        'subject_code' => $subjectCode,
        'subject_name' => $subjectName
    ];
    
    // Validate the subject data (using the validateSubjectData function)
    $errors = validateSubjectData($subject_data);

    // Check for duplicate subjects (using checkDuplicateSubjectData function)
    if (empty($errors)) {
        $duplicateError = checkDuplicateSubjectData($subject_data);
        if ($duplicateError) {
            $errors[] = $duplicateError; // Add duplicate error to errors array
        }
    }

    // If no errors, save the subject
    if (empty($errors)) {
        $_SESSION['subjects'][] = [
            'subject_code' => $subjectCode,
            'subject_name' => $subjectName
        ];
        $successMessage = "Subject added successfully!";

        // Clear the form fields after submission
        $subjectCode = $subjectName = ''; // Reset these variables to empty strings
    }
}

// Delete subject if delete action is triggered directly from add.php
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['code'])) {
    foreach ($_SESSION['subjects'] as $index => $subject) {
        if ($subject['subject_code'] === $_GET['code']) {
            unset($_SESSION['subjects'][$index]);
            $_SESSION['subjects'] = array_values($_SESSION['subjects']); // Reindex array
            $successMessage = "Subject deleted successfully!";
            break;
        }
    }
}
?>


<h3>Add a New Subject</h3>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
    </ol>
</nav>

<!-- Display success message if subject added or deleted -->
<?php if (!empty($successMessage)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
<?php endif; ?>

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

<!-- Add Subject Form -->
<div class="card mb-4">
    <div class="card-body">
        <form action="add.php" method="POST">
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code" placeholder="Enter Subject Code" value="<?php echo isset($subjectCode) ? htmlspecialchars($subjectCode) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Enter Subject Name" value="<?php echo isset($subjectName) ? htmlspecialchars($subjectName) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Add Subject</button>
        </form>
    </div>
</div>

<!-- Subject List Table -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Subject List</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($_SESSION['subjects'])): ?>
                    <tr>
                        <td colspan="3" class="text-center">No subject found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($_SESSION['subjects'] as $subject): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                            <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                            <td>
                                <a href="edit.php?code=<?php echo urlencode($subject['subject_code']); ?>" class="btn btn-sm btn-info">Edit</a>
                                <a href="delete.php?code=<?php echo urlencode($subject['subject_code']); ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once '../root/footer.php'; // Include the footer