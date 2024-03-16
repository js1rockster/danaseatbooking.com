<?php
require '../extensions/header.php';
?>

<body>
  <div class="wrapper">
  <div class="sidebar" data-color="purple" data-background-color="black" data-image="./asset/img/sidebar-2.jpg">
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
    if ($loggedIn && $_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST["submit"])) {

        $cname = $_POST["cfirstname"] . " " . $_POST["clastname"];
        $cphone = $_POST["cphone"];

        $customer_exists = exist_customers($conn, $cname, $cphone);
        $customer_added = false;

        if (!$customer_exists) {
          // Route is unique, proceed
          $sql = "INSERT INTO `customers` (`customer_name`, `customer_phone`, `customer_created`) VALUES ('$cname', '$cphone', current_timestamp());";
          $result = mysqli_query($conn, $sql);
          // Gives back the Auto Increment id
          $autoInc_id = mysqli_insert_id($conn);
          // If the id exists then, 
          if ($autoInc_id) {
            $code = rand(1, 99999);
            // Generates the unique userid
            $customer_id = "CUST-" . $code . $autoInc_id;

            $query = "UPDATE `customers` SET `customer_id` = '$customer_id' WHERE `customers`.`id` = $autoInc_id;";
            $queryResult = mysqli_query($conn, $query);

            if (!$queryResult)
              echo "Not Working";
          }

          if ($result)
            $customer_added = true;
        }

        if ($customer_added) {
          // Show success alert
          echo '<span class="text-success"">
                    <strong>Successful!</strong> Passenger Added
                  
                    </span>';
        } else {
          // Show error alert
          echo '<span class="text-warning">
                    <strong>Error!</strong> Passenger already exists
                    </span>';
        }
      }
      if (isset($_POST["edit"])) {
        // EDIT ROUTES
        $cname = $_POST["cname"];
        $cphone = $_POST["cphone"];
        $id = $_POST["id"];
        $id_if_customer_exists = exist_customers($conn, $cname, $cphone);

        if (!$id_if_customer_exists || $id == $id_if_customer_exists) {
          $updateSql = "UPDATE `customers` SET
                    `customer_name` = '$cname',
                    `customer_phone` = '$cphone' WHERE `customers`.`customer_id` = '$id';";

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
            $messageHeading = "Successfull!";
            $messageInfo = "Passenger details Edited";
          } else {
            // Show error alert
            $messageInfo = "Your request could not be processed due to technical Issues from our part. We regret the inconvenience caused";
          }

          // MESSAGE
          echo '<span class="text-' . $messageStatus . '">
          <strong>' . $messageHeading . '</strong> ' . $messageInfo . '
      </span>';
        } else {
          // If customer details already exists
          echo '<span class="text-warning">
                    <strong>Error!</strong> Passenger already exists
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        }

      }
      if (isset($_POST["delete"])) {
        // DELETE ROUTES
        $id = $_POST["id"];
        // Delete the route with id => id
        $deleteSql = "DELETE FROM `customers` WHERE `customers`.`id` = $id";

        $deleteResult = mysqli_query($conn, $deleteSql);
        $rowsAffected = mysqli_affected_rows($conn);
        $messageStatus = "danger";
        $messageInfo = "";
        $messageHeading = "Error!";

        if (!$rowsAffected) {
          $messageInfo = "Record Doesnt Exist";
        } elseif ($deleteResult) {
          $messageStatus = "success";
          $messageInfo = "Passenger Details deleted";
          $messageHeading = "Successfull!";
        } else {

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
    $resultSql = "SELECT * FROM `customers` ORDER BY customer_created DESC";

    $resultSqlResult = mysqli_query($conn, $resultSql);

    if (!mysqli_num_rows($resultSqlResult)) { ?>
      <!-- Customers are not present -->
      <div class="container mt-4">
        <div id="noCustomers" class="alert alert-dark " role="alert">
          <h1 class="alert-heading">No Passenger Found!!</h1>
          <p class="fw-light">Be the first person to add one!</p>
          <hr>
          <div id="addCustomerAlert" class="alert alert-success" role="alert">
            Click on <button id="add-button" class="button btn-sm" type="button" data-bs-toggle="modal"
              data-bs-target="#addModal">ADD <i class="fas fa-plus"></i></button> to add a passenger!
          </div>
        </div>
      </div>
    <?php } else { ?>
    </div>
        <!-- End Navbar -->
        <div class="content mt-4">
          <div class="container-fluid">
            <div id="head">
            
            </div>
            <div>
              <button id="add-button" class="button btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#addModal">Add Passenger Details <i class="fas fa-plus"></i></button>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header card-header-primary" style="background: #9A2A2A">
                    <h4 class="card-title ">Passenger Status</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Contact</th>
                          <th>Actions</th>
                        </thead>
                        <?php
                        while ($row = mysqli_fetch_assoc($resultSqlResult)) {
                          // echo "<pre>";
                          // var_export($row);
                          // echo "</pre>";
                          $id = $row["id"];
                          $customer_id = $row["customer_id"];
                          $customer_name = $row["customer_name"];
                          $customer_phone = $row["customer_phone"];
                          ?>
                          <tr>
                            <td>
                              <?php
                              echo $customer_id;
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
                              <button class="button edit-button " data-link="<?php echo $_SERVER['REQUEST_URI']; ?>"
                                data-id="<?php
                                echo $customer_id; ?>" data-name="<?php
                                  echo $customer_name; ?>" data-phone="<?php
                                    echo $customer_phone; ?>">Edit</button>
                              <button class="button delete-button" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                data-id="<?php
                                echo $id; ?>">Delete</button>
                            </td>
                          </tr>
                        <?php }

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
  <!-- Add Route Modal -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add A Passenger</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addCustomerForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
            <div class="mb-3">
              <label for="cfirstname" class="form-label">Passenger Firstname</label>
              <input type="text" class="form-control" id="cfirstname" name="cfirstname">
            </div>
            <div class="mb-3">
              <label for="clastname" class="form-label">Passenger Lastname</label>
              <input type="text" class="form-control" id="clastname" name="clastname">
            </div>
            <div class="mb-3">
              <label for="cphone" class="form-label">Contact Number</label>
              <input type="tel" class="form-control" id="cphone" name="cphone">
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
            Do you really want to delete these driver details? <strong>This process cannot be undone.</strong>
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
  <script src="../assets/scripts/admin_customer.js"></script>
  <?php require '../extensions/footer.php' ?>
  <?php require '../extensions/script.php' ?>
</body>

</html>