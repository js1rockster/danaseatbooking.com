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
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = array();

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userFullname = $_POST['userFullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['Confirmpassword'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $response['status'] = 'error';
        $response['message'] = 'Passwords do not match';
        echo json_encode($response);
        exit();
    }

    // Check if the password meets the minimum length requirement
    if (strlen($password) < 8) {
        $response['status'] = 'error';
        $response['message'] = 'Password should be at least 8 characters long';
        echo json_encode($response);
        exit();
    }

    // Check if the provided username and full name exist in the database
    $checkUserQuery = "SELECT * FROM users WHERE LOWER(user_fullname) = LOWER('$userFullname') AND user_name = '$username'";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows > 0) {
        // Hash the password (for better security)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the password in the database
        $updatePasswordQuery = "UPDATE users SET user_password = '$hashedPassword' WHERE LOWER(user_fullname) = LOWER('$userFullname') AND user_name = '$username'";

        if ($conn->query($updatePasswordQuery) === TRUE) {
            $response['status'] = 'success';
            $response['message'] = 'Password reset successfully';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error updating password: ' . $conn->error;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'User not found in the database';
    }

    // Close the database connection
    $conn->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

// Send JSON response
echo json_encode($response);
?>
