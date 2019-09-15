<?php
  $page_title = 'Edit Account';
  include('includes/header.php');

  $alert_width = "form_width";

  if ((!empty($_POST['id'])) || (!empty($_GET['id']))) {
    require('mysqli_connect.php');

    $id = (!empty($_POST['id'])) ? $_POST['id'] : $_GET['id'];


    $q_del = "DELETE FROM users WHERE id = '$id' LIMIT 1;";
    $r_del = mysqli_query($dbc, $q_del);

    if (mysqli_affected_rows($dbc) == 1) {
      $notify[] = "<h2><strong>".((!empty($_POST['id'])) ? 'Your' : 'This'). "account was successfully deleted!</strong></h2><h3>If this was an accident, you will unfortunately have to create a new account.</h3><hr><h3>Contact <i>mbouadeus@gmail.com</i> for further support.</h3>";
    } else {
      $error[] = "<strong>There was an error while deleting your account.</strong>Contact <strong><i>mbouadeus@gmail.com</i></strong> for further support.";
    }

    require('alert.php');
    mysqli_free_result($r_del);
    mysqli_close($dbc);
  }

  require('includes/footer.php');
