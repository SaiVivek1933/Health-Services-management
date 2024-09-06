<?php
session_start();

// Assuming user authentication is done and user ID is stored in session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not authenticated
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user information
$user_stmt = $conn->prepare("SELECT name, email, phone, address FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_stmt->bind_result($name, $email, $phone, $address);
$user_stmt->fetch();
$user_stmt->close();

// Fetch health records
$records_stmt = $conn->prepare("SELECT record_name, record_file FROM health_records WHERE user_id = ?");
$records_stmt->bind_param("i", $user_id);
$records_stmt->execute();
$records_stmt->bind_result($record_name, $record_file);

$records = [];
while ($records_stmt->fetch()) {
    $records[] = ['name' => $record_name, 'file' => $record_file];
}
$records_stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Health Records</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>User Profile - Health Records</h1>
        
        <div class="profile-info">
            <h2>User Information</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
        </div>

        <div class="health-records">
            <h2>Health Records</h2>
            <ul>
                <?php foreach ($records as $record): ?>
                    <li><?php echo htmlspecialchars($record['name']); ?> - <a href="<?php echo htmlspecialchars($record['file']); ?>" target="_blank">View</a></li>
                <?php endforeach; ?>
                <?php if (empty($records)): ?>
                    <li>No health records available.</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="actions">
            <h2>Actions</h2>
            <button onclick="editProfile()">Edit Profile</button>
            <button onclick="uploadRecords()">Upload New Records</button>
            <button onclick="shareRecords()">Share Records</button>
        </div>

        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <script>
        function editProfile() {
            window.location.href = 'edit_profile.php';
        }

        function uploadRecords() {
            window.location.href = 'upload_records.php';
        }

        function shareRecords() {
            window.location.href = 'share_records.php';
        }
    </script>
</body>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h1, h2 {
        text-align: center;
    }

    .profile-info, .health-records, .actions, .logout {
        margin-bottom: 30px;
    }

    .actions button {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        font-size: 16px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .actions button:hover {
        background-color: #0056b3;
    }

    .logout {
        text-align: center;
    }

    .logout a {
        color: #007bff;
        text-decoration: none;
    }

    .logout a:hover {
        text-decoration: underline;
    }
</style>
</html>
