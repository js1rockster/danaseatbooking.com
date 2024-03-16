<?php
require('fpdf186/fpdf.php');

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add a title
$pdf->Cell(0, 10, 'Booking Report', 0, 1, 'C');

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
$sql = 'SELECT booking_id, customer_id, route_id,booking_created FROM bookings';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data from each row
    while ($row = $result->fetch_assoc()) {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 10, 'PNR: ' . $row['booking_id']);
        $pdf->Cell(40, 10, 'Passenger: ' . $row['customer_id']);
        $pdf->Cell(35, 10, 'Route: ' . $row['route_id']);
        $pdf->Cell(35, 10, 'Booked: ' . $row['booking_created']);
        $pdf->Ln(); // Move to the next line
    }
} else {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'No records found.', 0, 1);
}

// Close the database connection
$conn->close();

// Output the PDF
$pdf->Output();
?>