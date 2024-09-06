<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the doctor_id and date from the request
$doctor_id = $_GET['doctor_id'];
$date = $_GET['date'];

// Define available time slots for August 29, 30, and 31, 2024
$availableTimeSlots = [
    1 => [
        '2024-08-29' => ['09:00', '10:00', '11:00', '13:00', '14:00'],
        '2024-08-30' => ['09:00', '10:00', '11:00', '13:00', '14:00'],
        '2024-08-31' => ['09:00', '10:00', '11:00', '13:00', '14:00']
    ],
    2 => [
        '2024-08-29' => ['10:00', '11:00', '12:00', '14:00', '15:00'],
        '2024-08-30' => ['10:00', '11:00', '12:00', '14:00', '15:00'],
        '2024-08-31' => ['10:00', '11:00', '12:00', '14:00', '15:00']
    ],
    3 => [
        '2024-08-29' => ['11:00', '12:00', '13:00', '15:00'],
        '2024-08-30' => ['11:00', '12:00', '13:00', '15:00'],
        '2024-08-31' => ['11:00', '12:00', '13:00', '15:00']
    ],
];

// Retrieve booked time slots from the database
$bookedTimeSlots = [];
$sql = "SELECT time FROM booked_time_slots WHERE doctor_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $doctor_id, $date);
$stmt->execute();
$stmt->bind_result($time);

while ($stmt->fetch()) {
    $bookedTimeSlots[] = $time;
}

$stmt->close();

// Get available slots for the given doctor and date
$doctorSlots = $availableTimeSlots[$doctor_id][$date] ?? [];
$availableSlots = array_diff($doctorSlots, $bookedTimeSlots);

// Return available slots as JSON
echo json_encode($availableSlots);

// Close the database connection
$conn->close();
?>
