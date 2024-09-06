<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $insuranceType = $_POST['insuranceType'];

    // Perform validation (example: basic checks)
    if (empty($fullName) || empty($email) || empty($dob) || empty($phone) || empty($insuranceType)) {
        echo "Please fill out all required fields.";
        exit;
    }

    // Process the registration (example: save to database)
    // Replace with your actual database connection and insert query
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "test";

    // Create connection (example using MySQLi)
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO registrations (full_name, email, dob, phone, insurance_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullName, $email, $dob, $phone, $insuranceType);

    // Execute SQL statement
    if ($stmt->execute()) {
        echo "Registration successful. Thank you for choosing our insurance!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to the registration page if accessed directly without form submission
    header("Location: get_insured_now.html");
    exit();
}
?>
