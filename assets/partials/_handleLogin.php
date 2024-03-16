<?php
    require '_functions.php';
    
    // Start the session
    session_start();

    $conn = db_connect();

    if(!$conn) {
        die("Oh Shoot!! Connection Failed");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Use prepared statements to prevent SQL injection
        $sql = "SELECT * FROM `users` WHERE user_name=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if($row = mysqli_fetch_assoc($result)) {
            $hash = $row['user_password'];

            if(password_verify($password, $hash)) {
                // Login successful
                $_SESSION["loggedIn"] = true;
                $_SESSION["user_id"] = $row["user_id"];

                header("location: ../../interface/index.php");
                exit;
            }
        }

        // Login failure
        $error_message = "Invalid username or password";
        echo $error_message;
        header("location: ../../index.php?error_message=$error_message");
    }
?>
