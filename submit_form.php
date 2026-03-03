<?php
// submit_form.php

// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
require_once 'database.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Get raw input data (for JSON requests) or use $_POST
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

// Extract and sanitize inputs
$name = isset($input['student_name']) ? trim($input['student_name']) : '';
$email = isset($input['student_email']) ? trim($input['student_email']) : '';
$phone = isset($input['student_mobile']) ? trim($input['student_mobile']) : '';
$state = isset($input['student_state']) ? trim($input['student_state']) : '';
$city = isset($input['student_city']) ? trim($input['student_city']) : '';
$course = isset($input['course_interest']) ? trim($input['course_interest']) : '';
$enquiry = isset($input['enquiry']) ? trim($input['enquiry']) : '';

// Basic Validation
if (empty($name) || empty($email) || empty($phone) || empty($course)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

// Append connection info to Message Body
$final_message = "Course: $course\nLocation: $city, $state\n\n$enquiry";

try {
    // Prepare SQL statement
    // Using 'contact_messages' table as identified in admin/panel.php
    // Columns presumed: name, email, phone, message, submitted_at (auto), status (default 'unread')
    $sql = "INSERT INTO contact_messages (name, email, phone, message, submitted_at, status) VALUES (?, ?, ?, ?, NOW(), 'unread')";
    $stmt = $pdo->prepare($sql);
    
    // Execute insertion
    $stmt->execute([$name, $email, $phone, $final_message]);

    echo json_encode(['success' => true, 'message' => 'Thank you! We have received your query.']);
} catch (PDOException $e) {
    // Log detailed error internally
    error_log("Database Error: " . $e->getMessage());
    
    // Send generic error to user
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
}
?>
