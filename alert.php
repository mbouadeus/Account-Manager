<?php

if (!empty($errors)) {
    $count = 0;
    foreach($errors as $error) {

    echo "<div class='alert alert-danger mx-auto mt-2 ".$alert_width."' role='alert'>$error</div>";
  }
    $errors = array();
}

if (!empty($notify)) {
    foreach ($notify as $notice) {
        echo "<div class='alert alert-success alert-dismissible fade show mx-auto mt-2 ".$alert_width."' role='alert'>
              $notice
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
          </div>";
    }
    $notify = array();
}
