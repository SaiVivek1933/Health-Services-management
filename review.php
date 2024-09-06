<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "specialist";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    // Insert review into the database
    $sql = "INSERT INTO reviews (doctor_id, rating, review) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("iis", $doctor_id, $rating, $review);

    if ($stmt->execute()) {
        echo "Review submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
