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
          $busno = $_POST["busnumber"];
          $buscap = $_POST["buscap"];

          $bus_exists = exist_buses($conn, $busno);
          $bus_added = false;

          if (!$bus_exists) {
            // Route is unique, proceed
            $sql = "INSERT INTO `buses` (`bus_no`, `bus_cap`, `bus_created`, `bus_assigned_d`) VALUES ('$busno', '$buscap', current_timestamp(),'0');";

            $result = mysqli_query($conn, $sql);

            if ($result)
              $bus_added = true;
          }

          if ($bus_added) {
            // Show success alert
            echo ' span class="text-success"><strong>Successful!</strong> Vehicle Information Added</span>
                 ';
            // Add the bus to seats table
            $seatSql = "INSERT INTO `seats` (`bus_no`) VALUES ('$busno');";
            $result = mysqli_query($conn, $seatSql);
          } else {

            // Show error alert
            echo '<span  class="text-wanring">
                  <strong>Error!</strong> Vehicle already exists
                  </span>';
          }
        }
        if (isset($_POST["edit"])) {
          // EDIT ROUTES
          $busno = strtoupper($_POST["busno"]);
          $id = $_POST["id"];
          $id_if_bus_exists = exist_buses($conn, $busno);
      
          if (!$id_if_bus_exists || $id == $id_if_bus_exists) {
              // Retrieve existing bus_cap value
              $existingBusCap = get_from_table($conn, "buses", "id", $id, "bus_cap");
      
              // Update the buses table
              $updateSql = "UPDATE `buses` SET `bus_no` = '$busno', `bus_cap` = '$existingBusCap' WHERE `buses`.`id` = $id;";
              $updateResult = mysqli_query($conn, $updateSql);
              $rowsAffected = mysqli_affected_rows($conn);
      
              $messageStatus = "danger";
              $messageInfo = "";
              $messageHeading = "Error!";
      
              if (!$rowsAffected) {
                  $messageInfo = "No Edits Administered!";
              } elseif ($updateResult) {
                  // Show success alert
                  $messageStatus = "success";
                  $messageHeading = "Successful!";
                  $messageInfo = "Vehicle details Edited";
              } else {
                  // Show error alert
                  $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
              }
      
              // MESSAGE
              echo '<span class="text-' . $messageStatus . '">
                      <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
                  </span>';
          } else {
              // If bus details already exist
              echo '<div class="text-warning">
                      <strong>Error!</strong> Vehicle details already exist
                  </div>';
          }
      }
      
        if (isset($_POST["delete"])) {
          // DELETE BUS
          $id = $_POST["id"];
          $bus_no = get_from_table($conn, "buses", "id", $id, "bus_no");
          // Delete the bus with id => id
          $deleteSql = "DELETE FROM `buses` WHERE `buses`.`id` = $id";

          $deleteResult = mysqli_query($conn, $deleteSql);
          $rowsAffected = mysqli_affected_rows($conn);
          $messageStatus = "danger";
          $messageInfo = "";
          $messageHeading = "Error!";

          if (!$rowsAffected) {
            $messageInfo = "Record Doesnt Exist";
          } elseif ($deleteResult) {
            // echo $num;
            // Show success alert
            $messageStatus = "success";
            $messageInfo = "Vehicle Details deleted";
            $messageHeading = "Successfull!";

            // Delete Bus from Seat table
            $sql = "DELETE from seats WHERE bus_no='$bus_no'";
            mysqli_query($conn, $sql);
          } else {
            // Show error alert
            $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
          }
          // Message
          echo '<span class="text-' . $messageStatus . '">
          <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
      </span>';
        }
      }
      ?>
      <?php
      $resultSql = "SELECT * FROM `buses` ORDER BY bus_created DESC";

      $resultSqlResult = mysqli_query($conn, $resultSql);

      if (!mysqli_num_rows($resultSqlResult)) { ?>
        <!-- Buses are not present -->
        <div class="container mt-4">
          <div id="noCustomers" class="alert alert-dark " role="alert">
            <h1 class="alert-heading">No Vehicles Found!!</h1>
            <p class="fw-light">Be the first person to add one!</p>
            <hr>
            <div id="addCustomerAlert" class="alert alert-success" role="alert">
              Click on <button id="add-button" class="button btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#addModal">ADD <i class="fas fa-plus"></i></button> to add a vehicle!
            </div>
          </div>
        </div>
      <?php } else { ?>
        </div>
        <?php require '../extensions/navbar.php'; ?>
        <!-- End Navbar -->
        <div class="content mt-4" >
          <div class="container-fluid">
            <div id="head">
            </div>
            <div>
              <button id="add-button" class="button btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#addModal">Add Vehicles Details <i class="fas fa-plus"></i></button>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header card-header-primary" style="background: #9A2A2A">
                    <h4 class="card-title ">Vehicle Status</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <th>#</th>
                          <th> Number plate</th>
                          <th> Capacity </th>
                          <th>Actions</th>

                        </thead>
                        <?php
                        $ser_no = 0;
                        while ($row = mysqli_fetch_assoc($resultSqlResult)) {
                          $ser_no++;
                          $id = $row["id"];
                          $busno = $row["bus_no"];
                          $buscap = $row["bus_cap"];
                          ?>
                          <tr>
                            <td>
                              <?php
                              echo $ser_no;
                              ?>
                            </td>
                            <td>
                              <?php
                              echo $busno;
                              ?>
                            </td>
                            <td>
                              <?php
                              echo $buscap;
                              ?>
                            </td>
                            <td>
                              <button class="button edit-button " data-link="<?php echo $_SERVER['REQUEST_URI']; ?>"
                                data-id="<?php
                                echo $id; ?>" data-busno="<?php
                                 echo $busno; ?>" data-buscap="<?php
                                  echo $buscap; ?>">Edit</button>
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
  <!-- Add Vehicle Modal -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add A Vehicle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addBusForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
            <div class="mb-3">
              <label for="busnumber" class="form-label"> Number plate</label>
              </span>
              <input type="text" class="form-control" id="busnumber" name="busnumber" required>
              <span id="error" class="error">
            </div>
            <div class="mb-3">

              <input type="number" class="form-control" id="buscap" value='14' name="buscap">
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
            Do you really want to delete this bus? <strong>This process cannot be undone.</strong>
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
  <script src="../assets/scripts/admin_bus.js"></script>
  <?php require '../extensions/footer.php' ?>
  <?php require '../extensions/script.php' ?>
</body>

</html>