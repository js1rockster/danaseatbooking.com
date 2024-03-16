<?php
require '../extensions/header.php';
?>

<body>
  <div class="wrapper">
    <div class="sidebar" data-color="purple" data-background-color="black" data-image="../asset/img/tranbus.png">
      <?php require '../extensions/sidebar.php'; ?>
    </div>

    <div class="main-panel">
      <?php
      require '../assets/partials/_getJSON.php';
      $routeData = json_decode($routeJson);
      $customerData = json_decode($customerJson);
      $seatData = json_decode($seatJson);
      $busData = json_decode($busJson);
      $adminData = json_decode($adminJson);
      $bookingData = json_decode($bookingJson);
      $driverData = json_decode($driverJson);
      require '../extensions/navbar.php';
      ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
              <div class="col-xl-4 col-lg-12">
                <div class="card card-chart">
                  <div class="card-header card-header-success">
                    <div class="ct-chart" id="dailySalesChart"></div>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">Total Bookings</h4>
                    <h3 class="card-category">
                      <span class="text-success"><i class="fa fa-long-arrow-up"></i> </span>
                      <?php
                      echo count($bookingData);
                      ?>
                    </h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats text-warning">
                    <a href="./booking.php">View More <i class="fas fa-arrow-right"></i></a>
                      <!-- <i class="material-icons">access_time</i> updated 4 minutes ago -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-12">
                <div class="card card-chart">
                  <div class="card-header card-header-warning">
                    <div class="ct-chart" id="websiteViewsChart"></div>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">Total Vehicles</h4>
                    <h3 class="card-category">
                      <?php
                      echo count($busData);
                      ?>
                    </h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                    <a href="./vehicle.php">View More <i class="fas fa-arrow-right"></i></a>
                      <!-- <i class="material-icons">access_time</i> campaign sent 2 days ago -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-12">
                <div class="card card-chart">
                  <div class="card-header card-header-danger">
                    <div class="ct-chart" id="completedTasksChart"></div>
                  </div>
                  <div class="card-body">
                    <h4 class="card-title">Total Routes</h4>
                    <h3 class="card-category">
                      <?php
                      echo count($routeData);
                      ?>
                    </h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                    <a href="./route.php">View More <i class="fas fa-arrow-right"></i></a>
                      <!-- <i class="material-icons">access_time</i> campaign sent 2 days ago -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-4 col-lg-12">
                <div class="card card-stats">
                  <div class="card-header card-header-warning card-header-icon">
                    <div class="card-icon">
                      <i class="material-icons">Total </i>Seats
                    </div>
                    <p class="card-category">"
                      
                    </p>
                    <h3 class="card-title"><?php
                      echo 38 * count($busData);
                      ?>
                     
                    </h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                      <i class="material-icons text-warning">warning</i>
                      <a href="./seat.php">View More <i class="fas fa-arrow-right"></i></a>
                      <!-- <a href="#pablo" class="warning-link">Get More Space...</a> -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-12">
                <div class="card card-stats">
                  <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                      <i class="material-icons">Total</i> Passengers
                    </div>
                    <p class="card-category">
                    Total Passengers
                    </p>
                    <h3 class="card-title"><?php
                      echo count($customerData) ?></h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                    <a href="./Passengers.php">View More <i class="fas fa-arrow-right"></i></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-12">
                <div class="card card-stats">
                  <div class="card-header card-header-danger card-header-icon">
                    <div class="card-icon">
                      <i class="material-icons">Total </i>Drivers
                    </div>
                    <p class="card-category">
                    Total Drivers
                    </p>
                    <h3 class="card-title"><?php
                      echo count($adminData);
                      ?></h3>
                  </div>
                  <div class="card-footer">
                    <div class="stats">
                    <a href="./drivers.php">View More <i class="fas fa-arrow-right"></i></a>
                      <!-- <i class="material-icons">local_offer</i> Tracked from Github -->
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

  <?php require '../extensions/footer.php'; ?>
  <?php require '../extensions/script.php'; ?>
</body>

</html>