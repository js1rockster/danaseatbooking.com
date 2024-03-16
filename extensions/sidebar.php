<?php
require '../assets/partials/_functions.php';
$conn = db_connect();

// Getting user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
  $user_fullname = $row["user_fullname"];
  $user_name = $row["user_name"];
}
?>

<div class="logo">
  <h4><img class="Logo" src="../assets/img/Untitled.png" height="60px" width="260px" ></h4>
</div>
<div class="sidebar-wrapper">
  <ul class="nav">
    <li class="nav-item option <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
      <a class="nav-link" href="./index.php" style="<?php echo ($page == 'dashboard') ? 'color: #9A2A2A !important;' : ''; ?>">
        <i class="material-icons">dashboard</i>
        <p>Dashboard</p>
      </a>
    </li>
    <!-- Repeat the pattern for other list items -->
    <li class="nav-item option <?php echo ($page == 'drivers') ? 'active' : ''; ?>">
      <a class="nav-link" href="./drivers.php" style="<?php echo ($page == 'drivers') ? 'color: #9A2A2A !important;' : ''; ?>">
        <i class="material-icons">Drivers</i>
        <p>Drivers</p>
      </a>
    </li>
    <!-- Repeat the pattern for other list items -->
    <li class="nav-item option <?php echo ($page == 'bus') ? 'active' : ''; ?>">
      <a class="nav-link" href="./vehicle.php" style="<?php echo ($page == 'bus') ? 'color: #9A2A2A !important;' : ''; ?>">
        <i class="material-icons">Vehicle</i>
        <p>Vehicle</p>
      </a>
    </li>
    <!-- Repeat the pattern for other list items -->
    <li class="nav-item option <?php echo ($page == 'route') ? 'active' : ''; ?>">
      <a class="nav-link" href="./route.php" style="<?php echo ($page == 'route') ? 'color: #9A2A2A !important;' : ''; ?>">
        <i class="material-icons">Route</i>
        <p>Route</p>
      </a>
    </li>
    <!-- Repeat the pattern for other list items -->
    <li class="nav-item option <?php echo ($page == 'Passengers') ? 'active' : ''; ?>">
      <a class="nav-link" href="./Passengers.php" style="<?php echo ($page == 'Passengers') ? 'color: #9A2A2A !important;' : ''; ?>">
        <i class="material-icons">Passengers</i>
        <p>Passengers</p>
      </a>
    </li>
    <!-- Repeat the pattern for other list items -->
    <li class="nav-item option <?php echo ($page == 'booking') ? 'active' : ''; ?>">
      <a class="nav-link" href="./booking.php" style="<?php echo ($page == 'booking') ? 'color: #9A2A2A !important;' : ''; ?>">
        <i class="material-icons">Bookings</i>
        <p>Bookings</p>
      </a>
    </li>
    <!-- Repeat the pattern for other list items -->
    <li class="nav-item option <?php echo ($page == 'seat') ? 'active' : ''; ?>">
      <a class="nav-link" href="./seat.php" style="<?php echo ($page == 'seat') ? 'color: #9A2A2A !important;' : ''; ?>">
        <i class="material-icons">Seats</i>
        <p>Seats</p>
      </a>
    </li>
    
    <li class="nav-item option <?php echo ($page == 'signup') ? 'active' : ''; ?>">
      <a class="nav-link" href="./createUserAccount.php" style="<?php echo ($page == 'signup') ? 'color: #9A2A2A !important;' : ''; ?>">
        <i class="material-icons">Create Account</i>
        <p>Create Account</p>
      </a>
    </li>
  </ul>
</div>
