<?php
  $page_title = 'Register | Account Manager';
  include('includes/header.php');

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $fn = $_POST['first_name'];
          $ln = $_POST['last_name'];
          $email = $_POST['email'];
          $user = $_POST['username'];
          $pass1 = $_POST['password-1'];
          $pass2 = $_POST['password-2'];

          $errors = [];

          // Check all fields are populated.
          if (empty($fn)) {
              $errors[] = 'You need to enter a <strong>first name.</strong>';
          } else {
              $fn = trim($fn);
          }
          if (empty($ln)) {
              $errors[] = 'You need to enter a <strong>last name.</strong>';
          } else {
              $ln = trim($ln);
          }
          if (empty($email)) {
              $errors[] = 'You need to enter an <strong>email.</strong>';
          } else {
              $email = trim($email);
          }
          if (empty($user)) {
              $errors[] = 'You need to enter a <strong>username.</strong>';
          } else {
              $user = trim($user);
          }
          if (empty($pass1) || empty($pass2)) {
              $errors[] = 'You need to enter a <strong>password.</strong>';
          } else {
              if ($pass1 != $pass2) {
                $error[] = 'Your password must <strong>match.</strong>';
              } else {
                  $pass = trim($pass1);
              }
          }

          $alert_width = 'form_width';

          if (empty($errors)) { // No errors
              require('mysqli_connect.php');

              $fn = mysqli_real_escape_string($dbc, $fn); // Safe to interact with database.
              $ln = mysqli_real_escape_string($dbc, $ln);
              $email = mysqli_real_escape_string($dbc, $email);
              $user = mysqli_real_escape_string($dbc, $user);
              $pass = mysqli_real_escape_string($dbc, $pass);

              $q_check_email = "SELECT id FROM users WHERE email = '$email';";
              $r_check_email = @mysqli_query($dbc, $q_check_email);
              $q_check_user = "SELECT id FROM users WHERE username = '$user';";
              $r_check_user = @mysqli_query($dbc, $q_check_user);

              $row_check_email = mysqli_num_rows($r_check_email);
              $row_check_user = mysqli_num_rows($r_check_user);

              if ($row_check_email == 0 && $row_check_user == 0) {

                $q_register = "INSERT INTO users VALUES (NULL, '$fn', '$ln', '$email', '$user', SHA2('$pass', 512), NOW(), 0);";
                $r_register = mysqli_query($dbc, $q_register);

                if ($r_register) {

                  $notify[] = "<h4><strong>Congratulation,</strong> your account was successfully created.<br>
                    <p>Now that you have an account, you can utilize the other tabs and do really cool stuff. if you have any questions or wish
                     to have admin access, please contact me.
                    <hr>You can reach me at <strong><i>mbouadeus@gmail.com</i></strong></p>";

                  mysqli_free_result($r_register);
                  mysqli_free_result($r_check_email);
                  mysqli_free_result($r_check_user);
                  mysqli_close($dbc);
                  require('alert.php');
                  require('includes/footer.php');
                  exit();

                } else {
                  $errors[] = '<strong>Whoops, Something went wrong on our side.</strong> Sorry for the inconvenience.';
                  mysqli_free_result($r_register);
                }

              } else {
                if ($row_check_email == 1 && $row_check_user == 0) {
                    $errors[] = '<strong>Your email is already taken.</strong>';
                } else if ($row_check_email == 0 && $row_check_user == 1) {
                    $errors[] = '<strong>Your username is already taken.</strong>';
                } else {
                    $errors[] = '<strong>Your email and username are already taken.</strong>';
                }

                mysqli_free_result($r_check_email);
                mysqli_free_result($r_check_user);
                mysqli_close($dbc);
              }
          }
          require('alert.php');
      }
?>
  <div class="main_content">
    <form method="POST" action="register.php" class="form_width bg-dark pt-4 pb-2 px-5 my-5 rounded mx-auto" novalidate>
      <h1 class="text-uppercase font-weight-bold" align="center">Create an account</h1>
      <div class="form-row mt-3">
        <div class="col-md-6 mb-3">
          <label for="validationTooltip01" class="font-weight-bold">First name</label>
          <input type="text" class="form-control" id="validationTooltip01" name="first_name" placeholder="First name" value="<?php if (isset($fn)) echo $fn;?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="validationTooltip02" class="font-weight-bold">Last name</label>
          <input type="text" class="form-control" id="validationTooltip02" name="last_name" placeholder="Last name" value="<?php if (isset($ln)) echo $ln;?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="validationTooltip03" class="font-weight-bold">Email</label>
          <input type="email" class="form-control" id="validationTooltip03" name="email" placeholder="Email" value="<?php if (isset($email)) echo $email;?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="validationTooltipUsername" class="font-weight-bold">Username</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id="validationTooltipUsernamePrepend">@</span>
            </div>
            <input type="text" class="form-control" id="validationTooltipUsername" name="username" placeholder="Username" aria-describedby="validationTooltipUsernamePrepend"
                   value="<?php if (isset($user)) echo $user;?>" required>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6 mb-3">
          <label for="validationTooltip03" class="font-weight-bold">Password</label>
          <input type="password" class="form-control" id="validationTooltip03" name="password-1" placeholder="Password" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="validationTooltip04" class="font-weight-bold">Confirm Password</label>
          <input type="password" class="form-control" id="validationTooltip04" name="password-2" placeholder="Confirm Password" required>
        </div>
      </div>
      <p align="center"><button class="btn btn-primary mt-4" type="submit">Sign up</button></p>
    </form>
  </div>
  <?php include('includes/footer.php'); ?>
