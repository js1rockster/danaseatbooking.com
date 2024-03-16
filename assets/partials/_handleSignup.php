<?php
function db_connect() {
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'bus-ticket';

    $conn = mysqli_connect($servername, $username, $password, $database);
    return $conn;
}

$response = array();

// Call the connection function to get the connection object
$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check connection
    if ($conn->connect_error) {
        $response['status'] = 'error';
        $response['message'] = 'Connection failed: ' . $conn->connect_error;
        echo json_encode($response);
        exit();
    }

    // Retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if any field is empty
    if (empty($firstName) || empty($lastName) || empty($username) || empty($password) || empty($confirmPassword)) {
        $response['status'] = 'error';
        $response['message'] = 'All fields must be filled out';
        echo json_encode($response);
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $response['status'] = 'error';
        $response['message'] = 'Passwords do not match';
        echo json_encode($response);
        exit();
    }

    // Check if the user already exists
    $checkUserQuery = "SELECT * FROM users WHERE user_name = '$username'";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Username already exists';
        echo json_encode($response);
        exit();
    }

    // Hash the password (for better security)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL query to insert data into the database
    $insertUserQuery = "INSERT INTO users (user_name, user_fullname, user_password, user_created) VALUES ('$username', '$firstName $lastName', '$hashedPassword', NOW())";

    if ($conn->query($insertUserQuery) === TRUE) {
        $response['status'] = 'success';
        $response['message'] = 'Record inserted successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $insertUserQuery . '<br>' . $conn->error;
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
