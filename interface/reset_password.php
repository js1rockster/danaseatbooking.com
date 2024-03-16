<?php
session_start();

// Connect to the database (you need to provide your own database connection details)
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'bus-ticket';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$response = array();

// Check if the request is a POST request and if the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id']; // Retrieve user ID from the session

    // Retrieve form data
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $response['status'] = 'error';
        $response['message'] = 'Passwords do not match';
        echo json_encode($response);
        exit();
    }

    // Hash the password (for better security)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL query to update the password
    $updatePasswordQuery = "UPDATE users SET user_password = '$hashedPassword' WHERE user_id = $userId";

    if ($conn->query($updatePasswordQuery) === TRUE) {
        $response['status'] = 'success';
        $response['message'] = 'Password reset successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $updatePasswordQuery . '<br>' . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request or user not logged in';
}

// Send JSON response
echo json_encode($response);
?>
