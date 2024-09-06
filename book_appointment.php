<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $doctor_id = $_POST['doctor'];
    $appointment_date = $_POST['date'];
    $appointment_time = $_POST['time'];

    // Initialize and prepare statement for checking availability
    $stmt = $conn->prepare("SELECT COUNT(*) FROM booked_time_slots WHERE doctor_id = ? AND date = ? AND time = ?");
    if ($stmt) {
        $stmt->bind_param("iss", $doctor_id, $appointment_date, $appointment_time);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    } else {
        die("Prepare failed: " . $conn->error);
    }

    if ($count > 0) {
        echo "The selected time slot is already booked. Please choose a different time slot.";
    } else {
        // Initialize and prepare statement for inserting appointment
        $stmt = $conn->prepare("INSERT INTO appointments (name, email, phone, doctor_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssiss", $name, $email, $phone, $doctor_id, $appointment_date, $appointment_time);

            if ($stmt->execute()) {
                echo "Appointment booked successfully.";

                // Initialize and prepare statement for marking time slot as booked
                $stmt = $conn->prepare("INSERT INTO booked_time_slots (doctor_id, date, time) VALUES (?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("iss", $doctor_id, $appointment_date, $appointment_time);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    echo "Prepare failed: " . $conn->error;
                }
            } else {
                echo "Error: " . $stmt->error;
            }

            
        } else {
            die("Prepare failed: " . $conn->error);
        }
    }
}

// Close the database connection
$conn->close();
?>
