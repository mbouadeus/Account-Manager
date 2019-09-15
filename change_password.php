<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Change Account</title>
</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require('mysqli_connect.php');

    $username = $_POST['username'];
    $old_password = $_POST['old_password'];
    $new_password_1 = $_POST['new_password-1'];
    $new_password_2 = $_POST['new_password-2'];

    $errors = [];

    if (empty($username)) {
        $errors[] = 'You need to enter your current username.';
    } else {
        $username = mysqli_real_escape_string($dbc, trim($username)); // Safe to interact with database.
    }

    if (empty($old_password)) {
        $errors[] = 'You need to enter your current password.';
    } else {
        if (empty($new_password_1) || empty($new_password_2)) {
            $errors[] = 'You need to enter a new password';
        } else {
            if ($new_password_1 != $new_password_2) {
                $errors[] = 'Your new password doesn\'t match.';
            } else {
                if ($old_password == $new_password_1) {
                    $errors[] = 'Your new password cannot be the same as your old one.';
                } else {
                    $new_password = mysqli_real_escape_string($dbc, trim($new_password_1));
                }
            }
        }
    }

    if (empty($errors)) {

        $q_id = "SELECT id FROM login WHERE username = '$username' && password = SHA2('$old_password', 512);";
        $r_id = @mysqli_query($dbc, $q_id);
        echo mysqli_error($dbc); // Debug: remove @ first

        if (mysqli_num_rows($r_id) == 1) {
            $id = mysqli_fetch_array($r_id, MYSQLI_NUM);
            $q_change_pass = "UPDATE login SET password = '$new_password' WHERE id = '$id[0]';";
            $r_change_pass = @mysqli_query($dbc, $q_change_pass);

            echo '<h3># of Rows Affected: '.mysqli_affected_rows($dbc).'</h3>';
            echo mysqli_error($dbc); // Debug: remove @ first

            echo '<h2>Congratulations, your password has been SUCCESSFULLY reset!</h2>';

            mysqli_free_result($r_change_pass);
            mysqli_free_result($r_id);
            mysqli_close($dbc);
            exit(); //Stop form from being rendered again.
        } else {
            echo "<h3>Username not registered or wrong information.</h3>";
        }
    } else {
        echo "<h3>Error!</h3>
                        <p class='error'>The following errors occured:<br></p>";
        foreach($errors as $error) {
            echo "<p><strong>$error</strong></p><br>";
        }
        echo 'Please try again.';
    }
    mysqli_close($dbc);
}
?>
<h2>Reset Password</h2>
<form method="POST" action="change_password.php">
    <label>Username:</label>
    <input type="text" name="username" placeholder="username" maxlength="40" value="<?php if (isset($_POST['username'])) echo $_POST['username'];?>"><br>
    <label>Password:</label>
    <input type="password" name="old_password" placeholder="Old password" maxlength="40" value="<?php if (isset($_POST['old_password'])) echo $_POST['old_password'];?>"><br>
    <label>New Password: </label>
    <input type="password" name="new_password-1" placeholder="New password" maxlength="40" value="<?php if (isset($_POST['new_password-1'])) echo $_POST['new_password-1'];?>"><br>
    <label>Repeat New Password:</label>
    <input type="password" name="new_password-2" placeholder="Repeat new password" maxlength="40" value="<?php if (isset($_POST['new_password-2'])) echo $_POST['new_password-2'];?>"><br>
    <button type="submit">Change</button>
</form>
</body>
</html>