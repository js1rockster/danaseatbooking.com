<?php
require '../extensions/header.php';
?>

<body>
  <div class="wrapper">
    <div class="sidebar" data-color="purple" data-background-color="black" data-image="../asset/img/tranbus.png">
      <?php require '../extensions/sidebar.php'; ?>
    </div>
    <?php
    $busSql = "Select * from buses";
    $resultBusSql = mysqli_query($conn, $busSql);
    $arr = array();
    while ($row = mysqli_fetch_assoc($resultBusSql))
      $arr[] = $row;
    $busJson = json_encode($arr);
    ?>
    <div class="main-panel">
      <?php require '../extensions/navbar.php'; ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <div class="searchBus">
              <input type="text" id="bus-no" autocomplete ="off" name="bus-no" placeholder="Number Plate" class="busnoInput form-control">
              <div class="sugg">
              </div>
            </div>

            <!-- Sending busJson -->
            <input type="hidden" id="busJson" name="busJson" value='<?php echo $busJson; ?>'>
            <button type="submit" name="submit">Search</button>
          </form>
          <div id="seat-results">
            <?php
            if (isset($_GET["submit"])) {
              $busno = $_GET["bus-no"];
              // ... (Database connection code here)
            
              // Fetch bus_cap from the bus table
              $bus_cap_query = "SELECT bus_cap FROM buses WHERE bus_no = '$busno'";
              $bus_cap_result = mysqli_query($conn, $bus_cap_query);

              if ($bus_cap_result && mysqli_num_rows($bus_cap_result) > 0) {
                $bus_cap_row = mysqli_fetch_assoc($bus_cap_result);
                $bus_cap = $bus_cap_row["bus_cap"];

                // Check if the bus_cap is 10, 12, or 14
                if ($bus_cap == 10 || $bus_cap == 12 || $bus_cap == 14) {
                  $booked_seats_query = "SELECT * FROM seats WHERE bus_no='$busno'";
                  $booked_seats_result = mysqli_query($conn, $booked_seats_query);

                  if (mysqli_num_rows($booked_seats_result) > 0) {
                    $row = mysqli_fetch_assoc($booked_seats_result);
                    $booked_seats = $row["seat_booked"];

                    if ($booked_seats) {
                      echo '<table id="displaySeats" data-seats="' . $booked_seats . '">';
                      for ($i = 1; $i <= $bus_cap; $i++) {
                        echo '<td id="seat-' . $i . '" data-name="' . $i . '">' . $i . '</td>';
                        // if (($bus_cap == 10 && $i % 5 == 0) || ($bus_cap == 12 && $i % 4 == 0) || ($bus_cap == 14 && $i % 5 == 0)) {
                        if (($bus_cap == 14 && $i % 5 == 0)) {
                          echo '</tr><tr>';
                        }
                      }
                      echo '</table>';

                      echo '<div style="text-align: center; color: green; font-weight: bold;">' . $busno . '</div>';
                    } else {
                      echo '<p>No seat Booked</p>';
                    }
                  } else {
                    echo '<p>No seat Booked</p>';
                  }
                } else {
                  echo '<p>Invalid bus capacity</p>';
                }
              } else {
                echo '<p>Bus not found</p>';
              }

            }
            ?>
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
  <!-- External JS -->
  <script src="../assets/scripts/admin_seat.js"></script>
  <?php require '../extensions/footer.php' ?>
  <?php require '../extensions/script.php' ?>
</body>

</html>