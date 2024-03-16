<?php
session_start();
function db_connect()
    {
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'bus-ticket';

        $conn = mysqli_connect($servername, $username, $password, $database);
        return $conn;
    }
?>