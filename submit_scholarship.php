<?php
// submit_scholarship.php

// Enable error reporting for debug (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$input = $_POST; // Use standard POST since we'll likely use FormData / jQuery serialize

// Extract inputs
$name = isset($input['student_name']) ? trim($input['student_name']) : '';
$email = isset($input['student_email']) ? trim($input['student_email']) : '';
$mobile = isset($input['student_mobile']) ? trim($input['student_mobile']) : '';
$qualification = isset($input['qualification']) ? trim($input['qualification']) : '';
$percentage = isset($input['percentage']) ? trim($input['percentage']) : '';
$course = isset($input['course_interest']) ? trim($input['course_interest']) : '';
$message = isset($input['enquiry']) ? trim($input['enquiry']) : '';

// Validation
if (empty($name) || empty($email) || empty($mobile) || empty($course) || empty($qualification)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit;
}

try {
    $sql = "INSERT INTO scholarship_registrations (name, email, mobile, qualification, percentage, course, message, registration_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $mobile, $qualification, $percentage, $course, $message]);

    echo json_encode(['success' => true, 'message' => 'Scholarship application submitted successfully!']);
} catch (PDOException $e) {
    error_log("Database Error (Scholarship): " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
}
?>
