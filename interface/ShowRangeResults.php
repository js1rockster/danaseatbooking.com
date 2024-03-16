<?php
// Connect to the database (you need to provide your own database connection details)
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'bus-ticket';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}


if (isset($_POST['fromdate']) && isset($_POST['todate'])) {
    $fdate = $_POST['fromdate'];
    $tdate = $_POST['todate'];

    // Perform a database query to fetch the report data based on the date range
    $query =  "
    SELECT
        b.booking_id,
        c.customer_name,
        c.customer_phone,
        c.customer_id,
        b.route_id,
        b.customer_route,
        b.travel_purpose,
        b.booking_created
    FROM
        bookings AS b
        JOIN customers AS c ON b.customer_id = c.customer_id
    WHERE
        DATE(b.booking_created) BETWEEN '$fdate' AND '$tdate'";

    $result = mysqli_query($conn, $query);

    // Start building the HTML for the report
    $reportHtml = '
        <h5 align="center" style="color: green">Report from ' . $fdate . ' to ' . $tdate . '</h5>
        <hr />
        <table class="table table-borderless table-striped table-earning">
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Booking ID</th>
                    <th>Passenger ID</th>
                    <th>Passenger Name</th>
                    <th>Passenger Mobile</th>
                    <th>Route ID</th>
                    <th>Travel Motive</th>
                    <th>Booking Created</th>
                </tr>
            </thead>
            <tbody>
    ';

    $cnt = 1;
    while ($row = mysqli_fetch_array($result)) {
        $reportHtml .= '
            <tr>
                <td>' . $cnt . '</td>
                <td>' . $row['booking_id'] . '</td>
                <td>' . $row['customer_id'] . '</td>
                <td>' . $row['customer_name'] . '</td>
                <td>' . $row['customer_phone'] . '</td>
                <td>' . $row['route_id'] . '</td>
                <td>' . $row['travel_purpose'] . '</td>
                <td>' . $row['booking_created'] . '</td>
            </tr>
        ';
        $cnt++;
    }

    // Finish building the HTML
    $reportHtml .= '
            </tbody>
        </table>
    ';

    // Send the report HTML as the response
    echo $reportHtml;
} else {
    // Handle invalid or missing date values
    echo 'Invalid date range.';
}
?>