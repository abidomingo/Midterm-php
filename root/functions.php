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












?>