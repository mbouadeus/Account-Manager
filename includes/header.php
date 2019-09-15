<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" type="image/icon" href="img/favicon.ico"/>
    <title><?php echo $page_title;?></title>
  </head>
  <body>
    <div class="content">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand text-white font-weight-bold text-uppercase" href="#">Account Manager</a>
        <div class="collapse navbar-collapse ml-3" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link btn btn-outline-secondary font-weight-bold <?php echo $page_title == 'Register | Account Manager' ? 'active' : '' ?>" href="register.php">Register</a>
            </li>
            <li class="nav-item mx-2">
              <a class="nav-link btn btn-outline-secondary font-weight-bold <?php echo $page_title == 'Edit Account | Account Manager' ? 'active' : '' ?>" href="edit_account.php">Edit Account</a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-outline-secondary font-weight-bold <?php echo $page_title == 'Admin Panel | Account Manager' ? 'active' : '' ?>" href="admin_panel.php">Admin Panel</a>
            </li>
          </ul>
        </div>
      </nav>
