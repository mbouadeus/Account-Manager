<?php
    $page_title = 'Edit Account | Account Manager';
    include('includes/header.php');

    $errors = [];
    $alert_width = 'login_width';
    include('mysqli_connect.php');

    if (isset($_GET['id']) && is_numeric($_GET['id'])) { // For edit user page.
        $id = mysqli_real_escape_string($dbc, $_GET['id']);
    } else if (isset($_POST['id']) && is_numeric($_POST['id'])) { // For form submission.
        $id = mysqli_real_escape_string($dbc, $_POST['id']);
        $submit_flag = true;
    } else {
        $form_submit = 'edit_account.php';

        if (empty($_POST['email']) && empty($_POST['password'])) {
            include('login.php');
            include('includes/footer.php');
            exit();
        }

        $email = mysqli_real_escape_string($dbc, trim($_POST['email'])); // Safe to use.
        $pass = mysqli_real_escape_string($dbc, trim($_POST['password']));

        $q_login = "SELECT id, first_name FROM users WHERE email = '$email' && password = SHA2('$pass', 512);";
        $r_login = mysqli_query($dbc, $q_login);

        if (mysqli_num_rows($r_login) == 1) {
          $row = mysqli_fetch_array($r_login, MYSQLI_ASSOC);
          $id = $row['id'];
          $fn = $row['first_name'];
          mysqli_free_result($r_login);
        } else {
          $errors[] = '<strong>Email or password</strong> Incorrect. Please try again.';
          require('alert.php');
          require('login.php');
          include('includes/footer.php');
          mysqli_free_result($r_login);
          mysqli_close($dbc);
          exit();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($submit_flag)) { // For form submission.

        $fn = $_POST['first_name'];
        $ln = $_POST['last_name'];
        $email = $_POST['email'];
        $user = $_POST['username'];

        // Check all fields are populated.
        if (empty($fn)) {
            $errors[] = 'You need to enter a <strong>first name.</strong>';
        } else {
            $fn = mysqli_real_escape_string($dbc, trim($fn));
        }
        if (empty($ln)) {
            $errors[] = 'You need to enter a <strong>last name.</strong>';
        } else {
            $ln = mysqli_real_escape_string($dbc, trim($ln));
        }
        if (empty($email)) {
            $errors[] = 'You need to enter an <strong>email.</strong>';
        } else {
            $email = mysqli_real_escape_string($dbc, trim($email));
        }
        if (empty($user)) {
            $errors[] = 'You need to enter a <strong>username.</strong>';
        } else {
            $user = mysqli_real_escape_string($dbc, trim($user));
        }

        // If password changed

        if ((!empty($_POST['password-1'])) || (!empty($_POST['password-2']))) {
            $pass1 = $_POST['password-1'];
            $pass2 = $_POST['password-2'];

            if ($pass1 == $pass2) {
                $pass = mysqli_real_escape_string($dbc, trim($pass1));
            } else {
                $error[] = 'Your password must <strong>match.</strong>';
            }
        } else if (((!empty($_POST['password-1'])) && empty($_POST['password-2'])) || ((!empty($_POST['password-2'])) && empty($_POST['password-1']))) { // Submitted by empty password fields.
            $errors[] = 'You <strong>must</strong> enter a password.';
        }

        if (empty($errors)) { // No errors

            $q_check_email = "SELECT id FROM users WHERE email = '$email' && id != '$id';"; // Email not taken by anyone except you.
            $r_check_email = @mysqli_query($dbc, $q_check_email);
            $q_check_user = "SELECT id FROM users WHERE username = '$user' && id != '$id';";
            $r_check_user = @mysqli_query($dbc, $q_check_user);

            $row_check_email = mysqli_num_rows($r_check_email);
            $row_check_user = mysqli_num_rows($r_check_user);

            if ($row_check_email == 0 && $row_check_user == 0) { // Email and Username not taken by others.

                if (isset($pass)) {
                    $q_edit = "UPDATE users SET email = '$email', username = '$user', password = SHA2('$pass', 512), first_name = '$fn', last_name = '$ln' WHERE id = '$id';";
                } else {
                    $q_edit = "UPDATE users SET email = '$email', username = '$user', first_name = '$fn', last_name = '$ln' WHERE id = '$id';";
                }
                $r_edit = mysqli_query($dbc, $q_edit);

                $notify[] = 'Your account was <strong>successfully updated.</strong>';

//                if (mysqli_affected_rows($dbc) == 1) { // OK
//                    $notify[] = 'Your account was <strong>successfully updated.</strong>';
//                } else {
//                    $errors[] = '<strong>Whoops,</strong> something went wrong on our side. Please try again.';
//                }
                mysqli_free_result($r_edit);

            } else {
                if ($row_check_email == 1 && $row_check_user == 0) {
                    $errors[] = '<strong>Your email is already taken.</strong>';
                } else if ($row_check_email == 0 && $row_check_user == 1) {
                    $errors[] = '<strong>Your username is already taken.</strong>';
                } else {
                    $errors[] = '<strong>Your email and username are already taken.</strong>';
                }
            }
            mysqli_free_result($r_check_email);
            mysqli_free_result($r_check_user);
        }
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($submit_flag)) {
      $notify[] = "Welcome to your edt account panel <strong>$fn!</strong>";
    }

    $alert_width = 'form_width';
    require('alert.php');

    // Always show the form.

    $q_get = "SELECT email, username, first_name, last_name FROM users WHERE id = '$id';";
    $r_get = mysqli_query($dbc, $q_get);
    
    if (mysqli_num_rows($r_get) == 1) {
        $row = mysqli_fetch_array($r_get, MYSQLI_ASSOC);
        $fn = $row['first_name'];
        $ln = $row['last_name'];
        $email = $row['email'];
        $user = $row['username'];
?>
        <div class="main_content">
            <form method="POST" action="edit_account.php" class="form_width bg-dark pt-4 pb-2 px-5 my-5 rounded mx-auto" novalidate>
                <h1 class="text-uppercase font-weight-bold" align="center">Edit account</h1>
                <div class="mt-4">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#change-password-modal">Change Password</button>
                    <button type="button" class="btn btn-danger float-right clearfix" data-toggle="modal" data-target="#delete-account-modal">Delete Account</button>
                </div>
                <div class="form-row mt-3">
                    <div class="col-md-6 mb-3">
                        <label for="validationTooltip01" class="font-weight-bold">First name</label>
                        <input type="text" class="form-control" id="validationTooltip01" name="first_name" placeholder="First name" value="<?php echo $fn;?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationTooltip02" class="font-weight-bold">Last name</label>
                        <input type="text" class="form-control" id="validationTooltip02" name="last_name" placeholder="Last name" value="<?php echo $ln;?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationTooltip03" class="font-weight-bold">Email</label>
                        <input type="email" class="form-control" id="validationTooltip03" name="email" placeholder="Email" value="<?php echo $email;?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="validationTooltipUsername" class="font-weight-bold">Username</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="validationTooltipUsernamePrepend">@</span>
                            </div>
                            <input type="text" class="form-control" id="validationTooltipUsername" name="username" placeholder="Username" aria-describedby="validationTooltipUsernamePrepend"
                                   value="<?php echo $user;?>" required>
                        </div>
                    </div>
                </div>
                <p align="center"><button class="btn btn-primary mt-4" type="submit">Save Changes</button></p>
                <input type="hidden" name="id" value="<?php echo $id;?>">

                <form method="POST" action="edit_account.php">
                    <div class="modal fade" id="change-password-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="text-dark font-weight-bold">Change Password</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="new_password-1" class="font-weight-bold text-dark d-inline mb-1">New Password: </label>
                                        <input type="password" class="form-control d-inline" name="password-1" id="new_password-1" required>
                                    </div>
                                    <div>
                                        <label for="new_password-2" class="font-weight-bold text-dark d-inline mb-1">Confirm Password: </label>
                                        <input type="password" class="form-control" name="password-2" id="new_password-2" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-info">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form method="POST" action="delete_account.php">
                    <div class="modal fade" id="delete-account-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="text-danger font-weight-bold">Are you sure?</h3>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-dark">Once deleted, your account cannot be recovered.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete Permanently</button>
                                    <input type="hidden" name="id" value="<?php echo $id;?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
        <?php include('includes/footer.php'); ?>
<?php
    } else {
        $errors[] = '<strong>Whoops,</strong> something went wrong and the page couldn\'t be loaded.';
        include('alert.php');
        include('includes/footer.php');
    }
    mysqli_close($dbc);
?>
    
