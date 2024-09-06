<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "specialist";

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch pulmonologist data
$sql = "SELECT * FROM dentist";
$result = mysqli_query($conn, $sql); // Corrected line

if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    echo "<table border='1'>";
    echo "<tr><th>Name</th><th>Specialization</th><th>experience</th><th>fee</th><th>location</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . (isset($row["Name"]) ? $row["Name"] : 'N/A') . "</td><td>" . (isset($row["specialization"]) ? $row["specialization"] : 'N/A') . "</td><td>" . (isset($row["experience"]) ? $row["experience"] : 'N/A') . "</td><td>" . (isset($row["fee"]) ? $row["fee"] : 'N/A') . "</td><td>" . (isset($row["location"]) ? $row["location"] : 'N/A'). "</td></tr>.";
    }
    echo "</table>";
} else {
    echo "0 results";
}

// Close connection
mysqli_close($conn);
?>
