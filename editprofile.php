<?php
session_start();
require 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    $_SESSION['error_message'] = 'You must be logged in to edit your profile.';
    header('Location: profile.php');
    exit();
}

// Get user ID from session
$user_id = $_SESSION['id'];

// Fetch current user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    $_SESSION['error_message'] = 'Database prepare error: ' . htmlspecialchars($conn->error);
    header('Location: profile.php');
    exit();
}
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    $_SESSION['error_message'] = 'Database execute error: ' . htmlspecialchars($stmt->error);
    header('Location: profile.php');
    exit();
}
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($address)) {
        // Update user profile in the database
        $update_query = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        if ($update_stmt === false) {
            $_SESSION['error_message'] = 'Database prepare error: ' . htmlspecialchars($conn->error);
            header('Location: profile.php');
            exit();
        }
        $update_stmt->bind_param('ssssi', $name, $email, $phone, $address, $user_id);

        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = 'Profile updated successfully.';
            header('Location: profile.php'); // Redirect to profile page after successful update
            exit();
        } else {
            $_SESSION['error_message'] = 'Failed to update profile. Please try again.';
            header('Location: profile.php'); // Redirect to profile page on failure
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'All fields are required.';
        header('Location: profile.php'); // Redirect to profile page if fields are empty
        exit();
    }
}
?>
