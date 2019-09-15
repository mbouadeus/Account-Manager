<?php
  $page_title = "Admin Panel | Account Manager";
  include('includes/header.php');

  $errors = [];
  $alert_width = 'login_width';
  include('mysqli_connect.php');


    $form_submit = 'admin_panel.php';

  if ((empty($_POST['email']) && empty($_POST['password'])) && (empty($_GET['id']))) {
    include('login.php');
    include('includes/footer.php');
    exit();
  }

  if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $q_login = "SELECT status FROM users WHERE id='$id'";
    $r_login = mysqli_query($dbc, $q_login);

  } else {
    $email = mysqli_real_escape_string($dbc, trim($_POST['email'])); // Safe to use.
    $pass = mysqli_real_escape_string($dbc, trim($_POST['password']));

    $q_login = "SELECT status, id FROM users WHERE email = '$email' && password = SHA2('$pass', 512);";
    $r_login = mysqli_query($dbc, $q_login);
  }

  if (mysqli_num_rows($r_login) < 1) {
    $errors[] = '<strong>Email or password</strong> Incorrect. Please try again.';
    require('alert.php');
    require('login.php');
    include('includes/footer.php');
    mysqli_free_result($r_login);
    mysqli_close($dbc);
    exit();
  }

  $row = mysqli_fetch_array($r_login, MYSQLI_ASSOC);
  $status = $row['status'];
  if (!isset($id)) { // if id not set
    $id = $row['id'];
  }
  mysqli_free_result($r_login);

  if ($status == 0) {
    $errors[] = '<strong>Your account does not have admin access.</strong><hr>Please contact me at <strong><i>mbouadeus@gmail.com</i></strong> if you wish to.';
    $errors[] = $status;
    require('alert.php');
    require('login.php');
    include('includes/footer.php');
    mysqli_close($dbc);
    exit();

  }

  // Display admin panel
  if (isset($_GET['s'])) {
    $start = $_GET['s'];
  } else {
    $start = 0;
  }

  if (isset($_GET['o'])) {
    switch ($_GET['o']) {
      case 'fn':
        $order = 'first_name';
        break;
      case 'ln':
        $order = 'last_name';
        break;
      case 'full':
        $order = 'CONCAT(first_name, last_name)';
        break;
      case 'user':
        $order = 'username';
        break;
      case 'email':
        $order = 'email';
        break;
      case 'date':
        $order = 'date_registered';
        break;
      default:
        $order = 'id';
    }
  } else {
    $order = 'id';
  }

  $display = 10;

  if (!isset($_POST['find'])) {
    $q_users = "SELECT id, first_name, last_name, email, username, DATE(date_registered) AS date_registered FROM users ORDER BY ".$order." ASC LIMIT ".$start.", ".$display;
    $q_all = "SELECT id FROM users";
  } else {
    $find = mysqli_real_escape_string($dbc, trim($_POST['find']));
    $find = "%$find%";
    $q_users = "SELECT id, first_name, last_name, email, username, DATE(date_registered) AS date_registered FROM users 
      WHERE first_name LIKE '$find' || last_name LIKE '$find' || username LIKE '$find' || email LIKE '$find' ORDER BY ".$order." ASC LIMIT ".$start.", ".$display;
    $q_all = "SELECT id FROM users WHERE first_name LIKE '$find' || last_name LIKE '$find' || username LIKE '$find' || email LIKE '$find'";
  }
  $r_users = mysqli_query($dbc, $q_users);

  $r_all = mysqli_query($dbc, $q_all);
  $records = mysqli_num_rows($r_all);
  if ($records > $display) {
    $pages = ceil($records/$display);
  } else {
    $pages = 1;
  }
?>

<nav class="nav nav-dark bg-dark panel_width mx-auto mt-5 p-2 rounded">
  <form class="form-inline my-2 my-lg-0" method="POST" action="<?php echo "admin_panel.php?id=$id&o=$order"; ?>">
    <h5 class="font-weight-bold pt-2 mr-2">Find</h5>
    <input class="form-control mr-sm-2" type="search" name="find">
    <button class="btn btn-secondary text-weight-bold my-2 my-sm-0 h-100" type="submit"><strong>Find</strong></button>
  </form>
  <h5 class="font-weight-bold pt-2 mr-2 ml-auto">Sort by</h5>
  <div class="btn-group float-right" role="group"><!--    Margin left auto is like float in bootstrap-->
    <a class="btn btn-secondary" href="<?php echo "admin_panel.php?id=$id&o=fn"; ?>">First Name</a>
    <a class="btn btn-secondary" href="<?php echo "admin_panel.php?id=$id&o=ln"; ?>">Last Name</a>
    <a class="btn btn-secondary" href="<?php echo "admin_panel.php?id=$id&o=full"; ?>">Full Name</a>
    <a class="btn btn-secondary" href="<?php echo "admin_panel.php?id=$id&o=user"; ?>">Username</a>
    <a class="btn btn-secondary" href="<?php echo "admin_panel.php?id=$id&o=email"; ?>">Email</a>
    <a class="btn btn-secondary" href="<?php echo "admin_panel.php?id=$id&o=date"; ?>">Date</a>
  </div>
</nav>

<div class="main_content">
  <table class="table table-striped table-dark panel_width my-3 mx-auto rounded">
    <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Edit</th>
      <th scope="col">Delete</th>
      <th scope="col">First Name</th>
      <th scope="col">Last Name</th>
      <th scope="col">Username</th>
      <th scope="col">Email</th>
      <th scope="col">Date Registered</th>
    </tr>
    </thead>
    <tbody>

<?php

  while ($row = mysqli_fetch_array($r_users)) {
?>

    <tr>
      <th scope="row"><?php echo $row['id']; ?></th>
      <td><a class="btn btn-primary p-0 m-0 px-2" href="edit_account.php?id=<?php echo $row['id']; ?>">edit</a></td>
      <td><a class="btn btn-danger p-0 m-0 px-2" href="delete_account.php?id=<?php echo $row['id']; ?>">delete</a></td>
      <td><?php echo $row['first_name']; ?></td>
      <td><?php echo $row['last_name']; ?></td>
      <td><?php echo '@' . $row['username']; ?></td>
      <td><?php echo $row['email']; ?></td>
      <td><?php echo $row['date_registered']; ?></td>
    </tr>

<?php
  }

  echo "
    </tbody>
  </table>
</div>
  ";

  if ($pages > 1) {
    $current_page = ($start/$display) + 1;

    echo "<nav class='clearfix'><ul class='pagination float-right page_list'>";

    if ($current_page != 1) {
      echo "<li class='page-item'><a class='page-link page_unselected' href='admin_panel.php?id=".$id."&s=".($start-$display)."&o=".$order."'>Previous</a></li>";
    }

    for ($i=1; $i<=$pages; $i++) {
      echo "<li class='page-item'><a class='page-link ".($current_page == $i ? "page_selected" : "page_unselected")."' href='admin_panel.php?id=".$id."&s=".(($i - 1) * $display)."&o=".$order."'>".$i."</a></li>";
    }

    if ($current_page != $pages) {
      echo "<li class='page-item'><a class='page-link page_unselected' href='admin_panel.php?id=".$id."&s=".($start+$display)."&o=".$order."'>Next</a></li>";
    }

    echo "</ul></nav>";
  }

  mysqli_free_result($r_users);
  mysqli_close($dbc);

include('includes/footer.php');
