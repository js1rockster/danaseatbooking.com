<?php
require '../extensions/header.php';
?>

<body>
    <div class="wrapper">
        <div class="sidebar" data-color="purple" data-background-color="black" data-image="../asset/img/tranbus.png">
            <?php require '../extensions/sidebar.php'; ?>
        </div>
        <div class="main-panel">
        <?php require '../extensions/navbar.php'; ?>
        <div id="errorMessage" class="text-center" style="margin-top: 40px">
        <?php
           if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]) {
            header("location: /index.php");
          }
          $loggedIn = true;
if($loggedIn && $_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST["submit"]))
    {
      
        $customer_id = $_POST["cid"];
        $customer_name = $_POST["cname"];
        $customer_phone = $_POST["cphone"];
        $route_id = $_POST["route_id"];
        $route_source = $_POST["sourceSearch"];
        $route_destination = $_POST["destinationSearch"];
        $route_bus = $_POST["busSearch"]; 
        $route = $route_source . " &rarr; " . $route_destination;
        $booked_seat = $_POST["seatInput"];
        $travel_purpose = mysqli_real_escape_string($conn, $_POST["purpose"]);
        // $dep_timing = $_POST["dep_timing"];

        $booking_exists = exist_booking($conn,$customer_id,$route_id);
        $booking_added = false;

        if(!$booking_exists)
        {
            // Route is unique, proceed
            $sql = "INSERT INTO `bookings` (`customer_id`, `route_id`, `customer_route`,  `booked_seat`,`travel_purpose`, `booking_created`) VALUES ('$customer_id', '$route_id','$route', '$booked_seat','$travel_purpose', current_timestamp());";
            $result = mysqli_query($conn, $sql);
            // Gives back the Auto Increment id
            $autoInc_id = mysqli_insert_id($conn);
            // If the id exists then, 
            if($autoInc_id)
            {
                $key = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $code = "";
                for($i = 0; $i < 5; ++$i)
                    $code .= $key[rand(0,strlen($key) - 1)];
                
                // Generates the unique bookingid
                $booking_id = $code.$autoInc_id;
                
                $query = "UPDATE `bookings` SET `booking_id` = '$booking_id' WHERE `bookings`.`id` = $autoInc_id;";
                $queryResult = mysqli_query($conn, $query);

                if(!$queryResult)
                    echo "Not Working";
            }

            if($result)
                $booking_added = true;
        }

        if($booking_added)
        {
            // Show success alert
            echo '<span class="text-success">
            <strong>Successful!</strong> Booking Added
            
            </span>';

            // Update the Seats table
            $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");
            $seats = get_from_table($conn, "seats", "bus_no", $bus_no, "seat_booked");
            if($seats)
            {
                $seats .= "," . $booked_seat;
            }
            else 
                $seats = $booked_seat;

            $updateSeatSql = "UPDATE `seats` SET `seat_booked` = '$seats' WHERE `seats`.`bus_no` = '$bus_no';";
            mysqli_query($conn, $updateSeatSql);
        }
        else{
            // Show error alert
            echo '<span class="text-warning" >
            <strong>Error!</strong> Booking already exists
            
            </span>';
        }
    }
    if(isset($_POST["edit"]))
    {
        $cname = $_POST["cname"];
        $cphone = $_POST["cphone"];
        $id = $_POST["id"];
        $customer_id = $_POST["customer_id"];
        $id_if_customer_exists = exist_customers($conn,$cname,$cphone);

        if(!$id_if_customer_exists || $customer_id == $id_if_customer_exists)
        {
            $updateSql = "UPDATE `customers` SET
            `customer_name` = '$cname',
            `customer_phone` = '$cphone' WHERE `customers`.`customer_id` = '$customer_id';";

            $updateResult = mysqli_query($conn, $updateSql);
            $rowsAffected = mysqli_affected_rows($conn);

            $messageStatus = "danger";
            $messageInfo = "";
            $messageHeading = "Error!";

            if(!$rowsAffected)
            {
                $messageInfo = "No Edits Administered!";
            }

            elseif($updateResult)
            {
                // Show success alert
                $messageStatus = "success";
                $messageHeading = "Successfull!";
                $messageInfo = "Passenger details Edited";
            }
            else{
                // Show error alert
                $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
            }

            echo '<span class="text-' . $messageStatus . '">
            <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
        </span>';
        }
        else{
            // If customer details already exists
            echo '<span class="text-warning">
            <strong>Error!</strong> Passenger already exists
            </span>';
        }

    }
    if(isset($_POST["delete"]))
    {
        // DELETE BOOKING
        $id = $_POST["id"];
        $route_id = $_POST["route_id"];
        // Delete the booking with id => id
        $deleteSql = "DELETE FROM `bookings` WHERE `bookings`.`id` = $id";

        $deleteResult = mysqli_query($conn, $deleteSql);
        $rowsAffected = mysqli_affected_rows($conn);
        $messageStatus = "danger";
        $messageInfo = "";
        $messageHeading = "Error!";

        if(!$rowsAffected)
        {
            $messageInfo = "Record Doesn't Exist";
        }

        elseif($deleteResult)
        {   
            $messageStatus = "success";
            $messageInfo = "Booking Details deleted";
            $messageHeading = "Successfull!";

            // Update the Seats table
            $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");
            $seats = get_from_table($conn, "seats", "bus_no", $bus_no, "seat_booked");

            // Extract the seat no. that needs to be deleted
            $booked_seat = $_POST["booked_seat"];

            $seats = explode(",", $seats);
            $idx = array_search($booked_seat, $seats);
            array_splice($seats,$idx,1);
            $seats = implode(",", $seats);

            $updateSeatSql = "UPDATE `seats` SET `seat_booked` = '$seats' WHERE `seats`.`bus_no` = '$bus_no';";
            mysqli_query($conn, $updateSeatSql);
        }
        else{

            $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
        }

        echo '<span class="text-' . $messageStatus . '">
        <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
    </span>';
    }
}
?>
        </div>
<?php
    $resultSql = "SELECT * FROM `bookings` ORDER BY booking_created DESC";
                    
    $resultSqlResult = mysqli_query($conn, $resultSql);

    if(!mysqli_num_rows($resultSqlResult)){ ?>
        <!-- Bookings are not present -->
        <div class="container mt-4">
            <div id="noCustomers" class="alert alert-dark " role="alert">
                <h1 class="alert-heading">No Bookings Found!!</h1>
                <p class="fw-light">Be the first person to add one!</p>
                <hr>
                <div id="addCustomerAlert" class="alert alert-success" role="alert">
                        Click on <button id="add-button" class="button btn-sm"type="button"data-bs-toggle="modal" data-bs-target="#addModal">ADD <i class="fas fa-plus"></i></button> to add a booking!
                </div>
            </div>
        </div>
    <?php }
    
    else { ?>
    
            <!-- End Navbar -->
            <div class="content mt-4">
                <div class="container-fluid">
             
                    <div>
                        <button id="add-button" class="button btn-sm" type="button" data-bs-toggle="modal"
                            data-bs-target="#addModal">Add Bookings<i class="fas fa-plus"></i></button>
                        <a href="report.php"><button type="button" class="btn btn-success mb-2">Get
                                Report</button> </a>
                    </div>
                    <div id="head" class="text-center mt-7">
                        <h4></h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        
                            <div class="card">
                                <div class="card-header card-header-primary" style="background: #9A2A2A">
                                    <h4 class="card-title ">Booking Status</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <th>PNR</th>
                                                <th>Name</th>
                                                <th>Contact</th>
                                                <th>Vehicle</th>
                                                <th>Route</th>
                                                <th>Seat</th>
                                                <th>Travel Motive</thv>
                                                <th>Departure</th>
                                                <th>Booked</th>
                                                <th>Actions</th>
                                            </thead>
                                            <?php
                        while($row = mysqli_fetch_assoc($resultSqlResult))
                        {
                                // echo "<pre>";
                                // var_export($row);
                                // echo "</pre>";
                            $id = $row["id"];
                            $customer_id = $row["customer_id"];
                            $route_id = $row["route_id"];

                            $pnr = $row["booking_id"];

                            $customer_name = get_from_table($conn, "customers","customer_id", $customer_id, "customer_name");
                            
                            $customer_phone = get_from_table($conn,"customers","customer_id", $customer_id, "customer_phone");

                            $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");

                            $route = $row["customer_route"];

                            $booked_seat = $row["booked_seat"];

                            $purpose = $row["travel_purpose"];
                            

                            $dep_date = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_date");

                            $dep_time = get_from_table($conn, "routes", "route_id", $route_id, "route_dep_time");

                            $booked_timing = $row["booking_created"];
                    ?>
                    <tr>
                        <td>
                            <?php 
                                echo $pnr;
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo $customer_name;
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo $customer_phone;
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo $bus_no;
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo $route;
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo $booked_seat;
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo $purpose;
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo $dep_date . " , ". $dep_time;
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo $booked_timing;
                            ?>
                        </td>
                        <td>
                        <button class="button btn-sm edit-button" data-link="<?php echo $_SERVER['REQUEST_URI']; ?>" data-customerid="<?php 
                                            echo $customer_id;?>" data-id="<?php 
                                            echo $id;?>" data-name="<?php 
                                            echo $customer_name;?>" data-phone="<?php 
                                            echo $customer_phone;?>" >Edit</button>
                            <button class="button delete-button btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                            data-id="<?php 
                                            echo $id;?>" data-bookedseat="<?php 
                                            echo $booked_seat;
                                        ?>" data-routeid="<?php 
                                        echo $route_id;
                                    ?>"> Delete</button>
                        </td>
                    </tr>
                    <?php 
                    }
                ?>
                </table>
                                    </div>
                                </div>
                            </div>
                         
                                    <div class="row">
                                        <!-- generate report per range of dates -->
                                            <div class="col ">
                                                <label for="fromdate" class="form-control-label">From Date</label>
                                            </div>
                                            <div class="col ">
                                                <input type="date" id="fromdate" name="fromdate" class="form-control"
                                                    required>
                                            </div>
                                            <div class="col ">
                                                <label for="todate" class="form-control-label">To Date</label>
                                            </div>
                                            <div class="col">
                                                <input type="date" id="todate" name="todate" class="form-control"
                                                    required onchange="generateReport()">
                                            </div>
                                  
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-sm report">Generate Range
                                            Report</button>
                            <!-- Render Report -->
                            <div id='reportContainer'></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require '../extensions/footer1.php'; ?>
            <script>
            const x = new Date().getFullYear();
            let date = document.getElementById('date');
            date.innerHTML = '&copy; ' + x + date.innerHTML;
            </script>
        </div>
    </div>

    <?php } ?>
    <?php require '../assets/partials/_getJSON.php';?>
    <!-- Add Booking Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Make Bookings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBookingForm" action=" <?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                        <!-- Passing Route JSON -->
                        <input type="hidden" id="routeJson" name="routeJson" value='<?php echo $routeJson; ?>'>
                        <!-- Passing Customer JSON -->
                        <input type="hidden" id="customerJson" name="customerJson" value='<?php echo $customerJson; ?>'>
                        <!-- Passing Seat JSON -->
                        <input type="hidden" id="seatJson" name="seatJson" value='<?php echo $seatJson; ?>'>

                        <div class="mb-3">
                            <label for="cid" class="form-label">Passenger ID</label>
                            <!-- Search Functionality -->
                            <div class="searchQuery">
                                <input type="text" class="form-control searchInput" id="cid" name="cid" autocomplete="off">
                                <div class="sugg">

                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cname" class="form-label">Passenger Name</label>
                            <input type="text" class="form-control" id="cname" name="cname" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="cphone" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="cphone" name="cphone" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Motive</label>
                            <textarea type="text" class="form-control" id="purpose" name="purpose" placeholder="The Purpose for Travel"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="routeSearch" class="form-label">Route</label>
                            <!-- Search Functionality -->
                            <div class="searchQuery">
                                <input type="text" class="form-control searchInput" id="routeSearch" name="routeSearch" autocomplete="off">
                                <div class="sugg">
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" class="form-control searchInput" id="busSearch" name="busSearch"
                            data-route-id="">

                        <input type="hidden" name="route_id" id="route_id">
                        <!-- Send the departure timing too -->
                        <input type="hidden" name="dep_timing" id="dep_timing">

                        <div class="mb-3">
                            <label for="sourceSearch" class="form-label">Source</label>
                            <!-- Search Functionality -->
                            <div class="searchQuery">
                                <input type="text" class="form-control searchInput" autocomplete="off" id="sourceSearch"
                                    name="sourceSearch">
                                <div class="sugg">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="destinationSearch" class="form-label">Destination</label>
                            <!-- Search Functionality -->
                            <div class="searchQuery">
                                <input type="text" class="form-control searchInput" id="destinationSearch"
                                    name="destinationSearch">
                                <div class="sugg">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <table id="seatsDiagram">
                                <tr>
                                    <td id="seat-1" data-name="1">1</td>
                                    <td id="seat-2" data-name="2">2</td>
                                    <td id="seat-3" data-name="3">3</td>
                                    <td id="seat-4" data-name="4">4</td>
                                    <td id="seat-5" data-name="5">5</td>
                                    <td id="seat-6" data-name="6">6</td>
                                    <td id="seat-7" data-name="7">7</td>
                                </tr>
                                <tr>
                                    <td id="seat-8" data-name="8">8</td>
                                    <td id="seat-9" data-name="9">9</td>
                                    <td id="seat-10" data-name="10">10</td>
                                    <td id="seat-11" data-name="11">11</td>
                                    <td id="seat-12" data-name="12">12</td>
                                    <td id="seat-131" data-name="13">13</td>
                                    <td id="seat-14" data-name="14">14</td>
                                </tr>
                            </table>
                        </div>
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-auto">
                                <label for="seatInput" class="col-form-label">Seat Number</label>
                            </div>
                            <div class="col-auto">
                                <input type="text" id="seatInput" class="form-control" name="seatInput" readonly>
                            </div>
                            <div class="col-auto">
                                <span id="seatInfo" class="form-text">
                                    Select from the above figure, Maximum 1 seat.
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" name="submit">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <!-- Add Anything -->
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-circle"></i></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h2 class="text-center pb-4">
                        Are you sure?
                    </h2>
                    <p>
                        Do you really want to delete this booking? <strong>This process cannot be undone.</strong>
                    </p>
                    <!-- Needed to pass id -->
                    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="delete-form" method="POST">
                        <input id="delete-id" type="hidden" name="id">
                        <input id="delete-booked-seat" type="hidden" name="booked_seat">
                        <input id="delete-route-id" type="hidden" name="route_id">
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="delete-form" name="delete" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- External JS -->
    <script src="../assets/scripts/admin_booking.js"></script>
    <?php require '../extensions/footer.php' ?>
    <?php require '../extensions/script.php' ?>
</body>

</html>
<script>
var fdate, tdate; // Declare these variables in a higher scope
function generateReport() {
    // Fetch the values of fdate and tdate from the input fields
    fdate = $('#fromdate').val();
    tdate = $('#todate').val();
    // Send a POST request to fetch the report
    $.ajax({
        type: 'POST',
        url: 'ShowRangeResults.php',
        data: {
            fromdate: fdate,
            todate: tdate
        },
        success: function(data) {
            // Append the data to the report container
            $('#reportContainer').html(data);
        },
        error: function() {
            alert('Error while fetching the report.');
        }
    });
}


$(".report").click(function() {
    // Create a form to submit fdate and tdate
    var form = $("<form>")
        .attr("action", "ReportRange.php") // Replace with your PHP script URL
        .attr("method", "post")
        .css("display", "none"); // Hide the form

    // Create input fields for fdate and tdate
    var fdateInput = $("<input>")
        .attr("type", "text")
        .attr("name", "fdate")
        .val(fdate);
    var tdateInput = $("<input>")
        .attr("type", "text")
        .attr("name", "tdate")
        .val(tdate);

    // Append the input fields to the form
    form.append(fdateInput);
    form.append(tdateInput);

    // Append the form to the document body
    $("body").append(form);

    // Submit the form
    form.submit();

    // Remove the form from the DOM
    form.remove();
});
</script>
<script>
    // Add JavaScript code to fade out the error message after 3 seconds
    setTimeout(function() {
        var errorMessage = document.getElementById('errorMessage');
        if (errorMessage) {
            errorMessage.style.transition = 'opacity 1s';
            errorMessage.style.opacity = 0;

            // Remove the element from the DOM after the fade out
            setTimeout(function() {
                errorMessage.parentNode.removeChild(errorMessage);
            }, 1000); // 1000 milliseconds = 1 second
        }
    }, 3000); // 3000 milliseconds = 3 seconds
</script>