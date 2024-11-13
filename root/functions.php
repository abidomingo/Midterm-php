<?php
function checkUserSessionIsActive() {
    if (isset($_SESSION['email']) && !empty($_SESSION['email']) && isset($_SESSION['current_page'])) {
        // Redirect to the current page if session is active
        header("Location: " . $_SESSION['current_page']);
        exit;
    }
}

function getUsers() {
    return [
        ['email' => 'user1@email.com', 'password' => 'password1'],
        ['email' => 'user2@email.com', 'password' => 'password2'],
        ['email' => 'user3@email.com', 'password' => 'password3'],
        ['email' => 'user4@email.com', 'password' => 'password4'],
        ['email' => 'user5@email.com', 'password' => 'password5'],
    ];
}

function validateLoginCredentials($email, $password) {
    $errors = [];
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    return $errors;
}

function checkLoginCredentials($email, $password, $users) {
    foreach ($users as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            return true;
        }
    }
    return false;
}

function displayErrors($errors) {
    if (empty($errors)) return '';
    $html = '<div class="alert alert-danger"><strong>System Errors</strong><ul>';
    foreach ($errors as $error) {
        $html .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $html .= '</ul></div>';
    return $html;
}

function guard() {
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header("Location: index.php");
        exit;
    }
}


function validateStudentData($student_data) {
    $errors = [];
    if (empty($student_data['student_id'])) {
        $errors[] = "Student ID is required";
    }
    if (empty($student_data['first_name'])) {
        $errors[] = "First Name is required";
    }
    if (empty($student_data['last_name'])) {
        $errors[] = "Last Name is required";
    }
    return $errors;
}

function checkDuplicateStudentData($student_data) {
    foreach ($_SESSION['students'] as $student) {
        if ($student['student_id'] === $student_data['student_id']) {
            return "Duplicate Student ID";
        }
    }
    return "";
}

function getSelectedStudentIndex($student_id) {
    foreach ($_SESSION['students'] as $index => $student) {
        if ($student['student_id'] === $student_id) {
            return $index;
        }
    }
    return null;
}

function getSelectedStudentData($index) {
    return $_SESSION['students'][$index] ?? null;
}

function validateAttachedSubject($subject_data) {
    if (empty($subject_data)) {
        return ["At least one subject should be selected"];
    }
    return [];
}


function getSelectedSubjectIndex($subject_code) {
    foreach ($_SESSION['subjects'] as $index => $subject) {
        if ($subject['subject_code'] === $subject_code) {
            return $index;
        }
    }
    return null;
}
function getSelectedSubjectData($index) {
    return $_SESSION['subjects'][$index] ?? null;
}

function validateSubjectData($subject_data) {
    $errors = [];
    
    // Check if the subject code is empty
    if (empty($subject_data['subject_code'])) {
        $errors[] = "Subject Code is required";
    }
    
    // Check if the subject name is empty
    if (empty($subject_data['subject_name'])) {
        $errors[] = "Subject Name is required";
    }
    
    return $errors;
}

function checkDuplicateSubjectData($subject_data) {
    // Assuming subjects are stored in session
    foreach ($_SESSION['subjects'] as $subject) {
        // Check if the subject code or name already exists
        if ($subject['subject_code'] === $subject_data['subject_code'] || $subject['subject_name'] === $subject_data['subject_name']) {
            return "Duplicate Subject: " . $subject_data['subject_code'] . " or " . $subject_data['subject_name'] . " already exists.";
        }
    }
    
    return false;  // No duplicates found
}



?>