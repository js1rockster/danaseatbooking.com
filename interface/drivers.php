<?php
require '../extensions/header.php';
?>

<body>
  <div class="wrapper">
    <div class="sidebar" data-color="purple" data-background-color="black" data-image="../asset/img/tranbus.png">
      <?php require '../extensions/sidebar.php'; ?>
    </div>
    <div class="main-panel">
    <div id="errorMessage" class="text-center" style="margin-top: 40px">
    <?php
    if (!isset($_SESSION["loggedIn"]) || !$_SESSION["loggedIn"]) {
      header("location: /index.php");
    }
    $loggedIn = true;
    if ($loggedIn && $_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST["submit"])) {
          // ADDING Drivers
          $dfirstname = $_POST["dfirstname"];
          $dlastname = $_POST["dlastname"];
          $dname = $dfirstname . " " . $dlastname;
          $dphone = $_POST["dphone"];
          $busno = isset($_POST["busno"]) ? $_POST["busno"] : null; // Check if 'busno' exists in the form
      
          // Perform any necessary client-side validation here
      
          $driver_exists = exist_customers($conn, $dname, $dphone);
          $driver_added = false;
      
          if (!$driver_exists && $busno) { // Check if $busno is set
              // Route is unique, proceed
              $sql = "INSERT INTO `drivers` (`d_name`, `d_contact`, `bus_no`, `d_created`) VALUES (?, ?, ?, current_timestamp())";
              $stmt = mysqli_prepare($conn, $sql);
              mysqli_stmt_bind_param($stmt, "sss", $dname, $dphone, $busno);
              $result = mysqli_stmt_execute($stmt);
      
              if ($result) {
                  $autoInc_id = mysqli_insert_id($conn);
      
                  if ($autoInc_id) {
                      $code = rand(1, 99999);
                      $d_id = "DRv-" . $code . $autoInc_id;
                      $query = "UPDATE `drivers` SET `d_id` = ? WHERE `drivers`.`id` = ?";
                      $stmtUpdateDriver = mysqli_prepare($conn, $query);
                      mysqli_stmt_bind_param($stmtUpdateDriver, "si", $d_id, $autoInc_id);
                      $queryResult = mysqli_stmt_execute($stmtUpdateDriver);
      
                      if (!$queryResult) {
                          echo "Error updating drivers table: " . $conn->error;
                      }
                  }
      
                  $driver_added = true;
      
                  // Update the buses table
                  $queryBus = "UPDATE `buses` SET `bus_assigned_d` = 1 WHERE `bus_no` = ?";
                  $stmtBus = mysqli_prepare($conn, $queryBus);
                  mysqli_stmt_bind_param($stmtBus, "s", $busno);
                  $resultBus = mysqli_stmt_execute($stmtBus);
      
                  if (!$resultBus) {
                      echo "Error updating buses table: " . $conn->error;
                  }
              }
          }
      
          if ($driver_added) {
              // Show success alert
              echo '<span class="text-success" >
                      <strong>Successful!</strong> Driver Added
                    
                    </span>';
          } else {
              // Show error alert
              echo '<span class="text-warning">
                      <strong>Error!</strong> Driver already exists or bus number is missing
                     
                    </span>';
          }
      
          // Close the statement
          mysqli_stmt_close($stmt);
          mysqli_stmt_close($stmtBus);
          mysqli_stmt_close($stmtUpdateDriver);
      }
   
      
      if (isset($_POST["edit"])) {
        // EDIT ROUTES
        $dname = $_POST["dname"];
        $dphone = $_POST["dphone"];
        $id = $_POST["id"];
        $busno = isset($_POST["busno"]) ? $_POST["busno"] : null; // Check if 'busno' exists in the form
        $oldBusNo = isset($_POST["old-busno"]) ? $_POST["old-busno"] : null; // Check if 'old-busno' exists in the form
        $id_if_driver_exists = exist_drivers($conn, $dname, $dphone);
        $driver_added = false;

        if (!$id_if_driver_exists || $id == $id_if_driver_exists) {
          if ($busno !== null) { // Check if $busno is not null
            $updateSql = "UPDATE `drivers` SET
                     `d_name` = ?,
                     `d_contact` = ?,
                     `bus_no` = ?
                     WHERE `drivers`.`d_id` = ?";

            $stmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($stmt, "ssss", $dname, $dphone, $busno, $id);
            $updateResult = mysqli_stmt_execute($stmt);
            $rowsAffected = mysqli_affected_rows($conn);

            $messageStatus = "danger";
            $messageInfo = "";
            $messageHeading = "Error!";

            if (!$rowsAffected) {
              $messageInfo = "No Edits Administered!";
            } elseif ($updateResult) {
              if ($oldBusNo != $busno) {
                bus_assign($conn, $busno);
                bus_free($conn, $oldBusNo);
              }
              // Show success alert
              $messageStatus = "success";
              $messageHeading = "Successful!";
              $messageInfo = "Driver details Edited";
            } else {
              // Show error alert
              $messageInfo = "Your request could not be processed due to technical issues. We regret the inconvenience caused";
            }

            // MESSAGE
            echo '<span class="text-' . $messageStatus . '">
                      <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
                  </span>';
          } else {
            // If 'busno' is missing in the form
            echo '<span class="text-warning" role="alert">
                     <strong>Error!</strong> Bus number is missing
                     
                     </span>';
          }
        } else {
          // If customer details already exist
          echo '<span class="text-warning">
                 <strong>Error!</strong> Driver already exists
                 
                 </span>';
        }
      }

      if (isset($_POST["delete"])) {
        // DELETE ROUTES
        $id = $_POST["id"];
        $busno_toFree = busno_from_routeid($conn, $id);
        $deleteSql = "DELETE FROM `drivers` WHERE `drivers`.`id` = ?";

        $stmt = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $deleteResult = mysqli_stmt_execute($stmt);
        $rowsAffected = mysqli_affected_rows($conn);

        $messageStatus = "danger";
        $messageInfo = "";
        $messageHeading = "Error!";

        if (!$rowsAffected) {
          $messageInfo = "Record Doesn't Exist";
        } elseif ($deleteResult) {
          $messageStatus = "success";
          $messageInfo = "Driver Details Deleted";
          $messageHeading = "Successful!";
          bus_free($conn, $busno_toFree);
        } else {
          $messageInfo = "Your request could not be processed due to technical issues. We regret the inconvenience caused";
        }

        echo '<span class="text-' . $messageStatus . '">
        <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
    </span>';
      }
    }
    ?>
    <?php
    $resultSql = "SELECT * FROM `drivers` ORDER BY d_created DESC";
    $resultSqlResult = mysqli_query($conn, $resultSql);

    if (!mysqli_num_rows($resultSqlResult)) { ?>
      <!-- Customers are not present -->
      <div class="container mt-4">
        <div id="noCustomers" class="alert alert-dark " role="alert">
          <h1 class="alert-heading">No Drivers Found!!</h1>
          <p class="fw-light">Be the first person to add one!</p>
          <hr>
          <div id="addCustomerAlert" class="alert alert-success" role="alert">
            Click on <button id="add-button" class="button btn-sm" type="button" data-bs-toggle="modal"
              data-bs-target="#addModal">ADD <i class="fas fa-plus"></i></button> to add a driver!
          </div>
        </div>
      </div>
    <?php } else { ?>
    </div>
        <?php require '../extensions/navbar.php'; ?>
        <!-- End Navbar -->
        <div class="content mt-4">
          <div class="container-fluid">
            <div id="head">
              <h4></h4>
            </div>
            <div>
              <button id="add-button" class="button btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#addModal">Add Driver Details <i class="fas fa-plus"></i></button>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header card-header-primary" style="background: #9A2A2A">
                    <h4 class="card-title ">Driver Status</h4>
                   
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Contact</th>
                          <th>Vehicles</th>
                          <th>Actions</th>
                        </thead>
                        <?php
                        while ($row = mysqli_fetch_assoc($resultSqlResult)) {
                          // echo "<pre>";
                          // var_export($row);
                          // echo "</pre>";
                          $id = $row["id"];
                          $d_id = $row["d_id"];
                          $d_name = $row["d_name"];
                          $d_contact = $row["d_contact"];

                          $bus_no = $row["bus_no"];
                          ?>
                          <tr>
                            <td>
                              <?php
                              echo $d_id;
                              ?>
                            </td>
                            <td>
                              <?php
                              echo $d_name;
                              ?>
                            </td>
                            <td>
                              <?php
                              echo $d_contact;
                              ?>
                            </td>
                            <td>
                              <?php
                              echo $bus_no;
                              ?>
                            </td>
                            <td>
                              <button class="button edit-button " data-link="<?php echo $_SERVER['REQUEST_URI']; ?>
                                " data-id="<?php echo $d_id; ?>
                                " data-name="<?php echo $d_name; ?>
                                " data-phone="<?php echo $d_contact; ?>" " data-busno=" <?php echo $bus_no; ?>">Edit</button>
                              <button class="button delete-button" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                data-id="<?php
                                echo $id; ?>">Delete</button>
                            </td>
                          </tr>
                          <?php
                        }

                        ?>
                      </table>
                    </div>
                  </div>
                </div>
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
  <?php
  $busSql = "Select * from buses where bus_assigned_d=0";
  $resultBusSql = mysqli_query($conn, $busSql);
  $arr = array();
  while ($row = mysqli_fetch_assoc($resultBusSql))
    $arr[] = $row;
  $busJson = json_encode($arr);
  ?>
  <!-- All Modals Here -->
  <!-- Add Driver and mapped them to a vehicle -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add A Driver</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addCustomerForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
            <div class="mb-3">
              <label for="dfirstname" class="form-label">Driver Firstname</label>
              <input type="text" class="form-control" id="dfirstname" name="dfirstname" autocomplete="off" required
                style="text-transform: capitalize">
            </div>
            <div class="mb-3">
              <label for="dlastname" class="form-label">Driver Lastname</label>
              <input type="text" class="form-control" id="dlastname" name="dlastname" autocomplete="off"
                style="text-transform: capitalize" required>
            </div>
            <div class="mb-3">
              <label for="dphone" class="form-label">Contact Number</label>
              <input type="tel" class="form-control" id="dphone" name="dphone" autocomplete="off" required>
            </div>

            <div class="mb-3">
              <label for="busno" class="form-label"> Number Plate </label>
              <!-- Search Functionality -->
              <div class="searchBus">
                <select type="text" class="form-control  busnoInput" id="busno" name="busno" required>
                  <option value="">Select a Number Plate</option>
                </select>
              </div>
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
            Do you really want to delete these customer details? <strong>This process cannot be
              undone.</strong>
          </p>
          <!-- Needed to pass id -->
          <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="delete-form" method="POST">
            <input id="delete-id" type="hidden" name="id">
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
  <script src="../assets/scripts/admin_driver.js"></script>
  <?php require '../extensions/footer.php' ?>
  <?php require '../extensions/script.php' ?>
</body>

</html>
<script>
  // Get the bus data from the JSON string
  const busData = <?php echo $busJson; ?>;

  // Select the dropdown element
  const busnoSelect = document.getElementById("busno");

  // Populate the dropdown options
  busData.forEach((bus) => {
    const option = document.createElement("option");
    option.value = bus.bus_no;
    option.textContent = bus.bus_no;
    busnoSelect.appendChild(option);
  });
</script>