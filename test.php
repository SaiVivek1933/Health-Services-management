<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $testType = $_POST['testType'];
    $date = $_POST['date'];

    $sql = "INSERT INTO tests (name, email, phone, address, testType, date)
            VALUES ('$name', '$email', '$phone', '$address', '$testType', '$date')";

    if ($conn->query($sql) === TRUE) {
        echo "Lab Test booked successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>