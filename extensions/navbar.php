<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top " id="navigation-example">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand" href="javascript:void(0)">
 
      </a>
    </div>
 
    <div class="collapse navbar-collapse justify-content-end">

      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link" href="javscript:void(0)" id="navbarDropdownMenuLink" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">person</i>
            <p class="d-lg-none d-md-block">
              Account
            </p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="javascript:void(0)"> <i  class="fas fa-user" > <?php
        echo $user_fullname;
        ?> </i></a>
            <a class="dropdown-item" href="../assets/partials/_logout.php"><i  class="fas fa-sign-out-alt" > Logout</i></a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>