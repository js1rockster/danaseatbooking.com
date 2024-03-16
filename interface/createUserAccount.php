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
            <?php
            if (isset($_GET['signup'])) {
                if ($_GET['signup']) {
                    // Show success alert
                    echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Successful!</strong> Account created successfully
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                } elseif ($_GET['user_exists']) {
                    // Show error alert
                    echo '<div class="my-0 alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Username already exists
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                }
            }
            ?>
            <div class="content">
            <div class="container-fluid">
         
            
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header card-header-primary" style="background: #9A2A2A">
                                <h4 class="card-title">Add User</h4>
                                <p class="card-category"></p>
                            </div>
                            <div class="card-body">
                            <div id="message2" class="text-success pull-right"></div>
                            <form id="signup-form" action="javascript:void(0)" method="POST">
                                    <div class="row">
                                        <div class="col-md-5">

                                            <div class="form-group">
                                                <label class="bmd-label-floating">Fist Name</label>
                                                <input type="text" class="form-control" autocomplete="off"
                                                    name="firstName" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">Last Name</label>
                                                <input type="text" class="form-control" autocomplete="off"
                                                    name="lastName" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">Username</label>
                                                <input type="text" class="form-control" autocomplete="off"
                                                    name="username" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">Password</label>
                                                <input tid="password" type="password" name="password"
                                                    placeholder="Password*" required class="form-control">
                                                <span id="passwordErr" class="error"></span>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="bmd-label-floating">Confirm Password</label>
                                                <input id="password" type="password" name="confirmPassword"
                                                    placeholder="Password*" required class="form-control">
                                                <span id="passwordErr" class="error"></span>
                                            </div>

                                        </div>
                                    </div>
                                    <button id="resetPassword" 
                                        class="btn btn-warning pull-left">RESET PASSWORD</button>
                                    <button id="signup-btn" type="submit" name="signup"
                                        class="btn btn-primary pull-right">ADD USER</button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-profile">
                            <div class="card-avatar">
                                <a href="#pablo">
                                    <img class="img" src="../asset/img/Joel a.jpg" />
                                </a>
                            </div>
                            <div class="card-body">
                                <h6 class="card-category">Developer</h6>
                                <h4 class="card-title">
                                    <?php
                                    echo $user_fullname;
                                    ?>
                                </h4>
                                <p class="card-description">
                                    Don't be scared of the truth because we need to restart the human foundation in
                                    truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but
                                    the back is...
                                </p>
                                <a href="#pablo" class="btn btn-round" style="background: #9A2A2A">Follow</a>
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

    <!-- External JS -->
    <script src="../assets/scripts/admin_signup.js"></script>
    <?php require '../extensions/footer.php'; ?>
    <?php require '../extensions/script.php'; ?>
</body>

</html>


<script>
$(document).ready(function () {
    // Event listener for form submission
    $("#resetUserPassword").submit(function (event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = $(this).serialize();

        // AJAX request
        $.ajax({
            type: "POST",
            url: "reset_password.php", // Replace with the actual path to _resetPassword.php
            data: formData,
            dataType: "json", // Expect JSON response from the server
            success: function (response) {
                // Handle the response from the server
               $("#message").text(response.message);
                // You can perform additional actions based on the response
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle the error response
                $("#message").text("Error: " + jqXHR.responseText);
            }
        });
    });
});

</script>


<script>
  $(document).ready(function () {
    // Event listener for form submission
    $("#signup-btn").click(function () {
        var formData = $("#signup-form").serialize();

        // AJAX request
        $.ajax({
            type: "POST",
            url: "../assets/partials/_handleSignup.php",
            data: formData,
            dataType: "json", // Expect JSON response from the server
            success: function (response) {
                // Handle the response from the server
                $("#message2").text(response.message);
               
                // You can perform additional actions based on the response
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle the error response
                $("#message2").text("Error: " + jqXHR.responseText);
            }
        });
    });
});

    // Wait for the document to be ready
    $(document).ready(function () {
        // Add an event listener to your button or any element that should trigger the modal
        $("#resetPassword").click(function () {
            // Use the modal's ID to trigger it
            $("#addModal").modal('show');
        });
    });


</script>

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="message" class="text-success pull-right"></div>
          <form id="resetUserPassword" action="javascript:void(0)" method="POST">
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
            </div>
            <button type="submit" class="btn btn-success" name="submit">Reset Password</button>
          </form>
        </div>
        <div class="modal-footer">
          <!-- Add Anything -->
        </div>
      </div>
    </div>
  </div>