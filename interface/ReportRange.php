<?php

require('fpdf186/fpdf.php');
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a new PDF instance with landscape orientation
$pdf = new FPDF('L'); // 'L' stands for landscape
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
// Add a title with background color
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(270, 10, 'Booking Report', 1, 1, 'C', true);

// Define column widths and brighter colors
$colWidths = [40, 40, 40, 50, 55, 45];
$colColors = [135, 206, 235, 255]; // Brighter shades of colors

// Set the header row
$pdf->SetFont('Arial', 'B', 12);
foreach ($colWidths as $index => $colWidth) {
    $pdf->SetFillColor($colColors[0], $colColors[1], $colColors[2]);
    $pdf->Cell($colWidth, 10, getColumnHeader($index), 1, 0, 'C', 1);
}
$pdf->Ln();

// Connect to the database (you need to provide your own database connection details)
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'bus-ticket';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch data from the database
$fdate = $conn->real_escape_string($_POST['fdate']);
$tdate = $conn->real_escape_string($_POST['tdate']);

// Fetch data from the database including customer information
$sql = "
    SELECT
        b.booking_id,
        c.customer_name,
        c.customer_phone,
        c.customer_id,
        b.customer_route,
        b.travel_purpose,
        b.booking_created
    FROM
        bookings AS b
        JOIN customers AS c ON b.customer_id = c.customer_id
    WHERE
        DATE(b.booking_created) BETWEEN '$fdate' AND '$tdate'";

$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $rowColor = 255;

        while ($row = $result->fetch_assoc()) {
            $pdf->SetFont('Arial', '', 10);
            foreach ($colWidths as $index => $colWidth) {
                $value = getColumnValue($index, $row); // Corrected index
                $pdf->SetFillColor($colColors[0], $colColors[1], $colColors[2]);
                $pdf->Cell($colWidth, 10, $value, 1, 0, 'C', 1);
            }
            $pdf->Ln();
            $rowColor = $rowColor == 255 ? 200 : 255; // Alternate row colors
        }
    } else {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(array_sum($colWidths), 10, 'No records found.', 0, 1);
    }
    $result->free(); // Free the result set
} else {
    die('Query failed: ' . $conn->error);
}

// Close the database connection
$conn->close();

// Output the PDF
$pdf->Output();

// Function to get column header by index
function getColumnHeader($index) {
    $headers = [
         'Passenger ID', 'Passenger Name', 'Passenger Mobile', 'Route','Travel Motive', 'Date Booked'
    ];
    return $headers[$index];
}

// Function to get column value by index from the row
function getColumnValue($index, $row) {
    $values = [
         'customer_id', 'customer_name', 'customer_phone', 'customer_route', 'travel_purpose','booking_created'
    ];
    $value = $row[$values[$index]];
    return isset($value) ? $value : '';
}

?>
