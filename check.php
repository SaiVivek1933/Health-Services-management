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

// Check if the ID parameter is set
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $doctor_id = $_GET['id'];

    // Prepare and execute the SQL query with JOIN
    $sql = "SELECT d.doctor_id, d.name, d.specialization, d.experience, d.fee, d.location, r.rating, r.review
            FROM Cardiologist d
            LEFT JOIN reviews r ON d.doctor_id = r.doctor_id
            WHERE d.doctor_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Output results
    if ($result->num_rows > 0) {
        echo "<div class='container'>";
        echo "<h2>Reviews for Doctor ID: " . htmlspecialchars($doctor_id) . "</h2>";

        // Fetch and display doctor details
        $doctorDetails = $result->fetch_assoc();
        echo "<h3>Doctor Details</h3>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($doctorDetails['name']) . "</p>";
        echo "<p><strong>Specialization:</strong> " . htmlspecialchars($doctorDetails['specialization']) . "</p>";
        echo "<p><strong>Experience:</strong> " . htmlspecialchars($doctorDetails['experience']) . " years</p>";
        echo "<p><strong>Fee:</strong> $" . htmlspecialchars($doctorDetails['fee']) . "</p>";
        echo "<p><strong>Location:</strong> " . htmlspecialchars($doctorDetails['location']) . "</p>";

        // Display reviews in a table
        echo "<h3>Reviews</h3>";
        echo "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>";
        echo "<tr><th>Rating</th><th>Review</th></tr>";

        // Reset pointer to fetch reviews again
        $result->data_seek(0); 

        $reviewsFound = false;
        while ($row = $result->fetch_assoc()) {
            if ($row['rating'] !== null && $row['review'] !== null) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['rating']) . "/5</td>";
                echo "<td>" . htmlspecialchars($row['review']) . "</td>";
                echo "</tr>";
                $reviewsFound = true;
            }
        }

        if (!$reviewsFound) {
            echo "<tr><td colspan='2'>No reviews found for this doctor.</td></tr>";
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='container'><h2>No doctor found with ID: " . htmlspecialchars($doctor_id) . "</h2></div>";
    }

    $stmt->close();
} else {
    echo "<div class='container'><h2>Please enter a Doctor ID to check reviews.</h2></div>";
}

$conn->close();
?>
