<?php
  // Including essential files for the application
  require('inc/essentials.php');
  // Including database configuration
  require('inc/db_config.php');

  // Starting the session
  session_start();
  // Checking if the admin is already logged in and redirecting to dashboard if true
  if((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)){
    redirect('dashboard.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Setting meta tags for character encoding, compatibility, and viewport scaling -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login Panel</title>
  <!-- Including external CSS links -->
  <?php require('inc/links.php'); ?>
  <style>
    /* Styling the login form to be centered on the page */
    div.login-form{
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%,-50%);
      width: 400px;
    }
  </style>
</head>
<body class="bg-light">
  
  <!-- Creating the login form -->
  <div class="login-form text-center rounded bg-white shadow overflow-hidden">
    <form method="POST">
      <!-- Header section for the login form -->
      <h4 class="bg-dark text-white py-3">ADMIN LOGIN PANEL</h4>
      <div class="p-4">
        <!-- Input field for admin name -->
        <div class="mb-3">
          <input name="admin_name" required type="text" class="form-control shadow-none text-center" placeholder="Admin Name">
        </div>
        <!-- Input field for admin password -->
        <div class="mb-4">
          <input name="admin_pass" required type="password" class="form-control shadow-none text-center" placeholder="Password">
        </div>
        <!-- Login button -->
        <button name="login" type="submit" class="btn text-white custom-bg shadow-none">LOGIN</button>
      </div>
    </form>
  </div>

  <?php 
    
    // Checking if the login form is submitted
    if(isset($_POST['login']))
    {
      // Filtering the form input data
      $frm_data = filteration($_POST);

      // Query to check the provided admin credentials
      $query = "SELECT * FROM  `admin_cred` WHERE `admin_name`=? AND `admin_pass`=?";
      // Values to be bound to the query
      $values = [$frm_data['admin_name'],$frm_data['admin_pass']];

      // Executing the query to fetch admin data
      $res = select($query,$values,"ss");
      // Checking if a matching admin record is found
      if($res->num_rows==1){
        $row = mysqli_fetch_assoc($res);
        // Storing session data for successful login
        $_SESSION['adminLogin'] = true;
        $_SESSION['adminId'] = $row['sr_no'];
        // Redirecting to the dashboard
        redirect('dashboard.php');
      }
      else{
        // Displaying an alert if login fails
        alert('error','Login failed - Invalid Credentials!');
      }
    }
  
  ?>

  <!-- Including external JavaScript files -->
  <?php require('inc/scripts.php') ?>
</body>
</html>
