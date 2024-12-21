<?php
// Including necessary files for functionality and database configuration
require('inc/essentials.php');
require('inc/db_config.php');

// Ensuring the admin is logged in before accessing the dashboard
adminLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Meta tags for character encoding, compatibility with older browsers, and responsiveness for mobile devices -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Title of the page, shown in the browser tab -->
  <title>Admin Panel - Dashboard</title>
  
  <!-- Including external CSS files (such as Bootstrap and custom styles) -->
  <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

  <?php
  // Including the header (likely contains the navigation bar or page header)
  require('inc/header.php');

  // Fetching system settings to check if the website is in shutdown mode
  $is_shutdown = mysqli_fetch_assoc(mysqli_query($con, "SELECT `shutdown` FROM `settings`"));

  // Fetching statistics for new bookings, cancelled bookings, and unread user queries/reviews
  $current_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT 
      COUNT(CASE WHEN booking_status='booked' AND arrival=0 THEN 1 END) AS `new_bookings`,
      COUNT(CASE WHEN booking_status='cancelled'  THEN 1 END) AS `refund_bookings`
      FROM `booking_order`"));

  // Fetching unread user queries and reviews
  $unread_queries = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(sr_no) AS `count`
      FROM `user_queries` WHERE `seen`=0"));

  $unread_reviews = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(sr_no) AS `count`
      FROM `rating_review` WHERE `seen`=0"));

  // Fetching user statistics (total users, active, inactive, and unverified)
  $current_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT 
      COUNT(id) AS `total`,
      COUNT(CASE WHEN `status`=1 THEN 1 END) AS `active`,
      COUNT(CASE WHEN `status`=0 THEN 1 END) AS `inactive`,
      COUNT(CASE WHEN `is_verified`=0 THEN 1 END) AS `unverified`
      FROM `user_cred`"));

  // Fetching total bookings and the total amount generated
  $total_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS `count` FROM `booking_order`"));
  $total_amount = mysqli_fetch_assoc(mysqli_query($con, "
      SELECT SUM(g.price) AS `total` 
      FROM `booking_order` b
      INNER JOIN `guesthouses` g ON b.guesthouse_id = g.id
  "));
  ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <!-- Main content area with padding and handling for overflow -->
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">

        <div class="d-flex align-items-center justify-content-between mb-4">
          <h3>DASHBOARD</h3>
          <?php
          // Displaying a message if the website is in shutdown mode
          if ($is_shutdown['shutdown']) {
            echo <<<data
                <h6 class="badge bg-danger py-2 px-3 rounded">Shutdown Mode is Active!</h6>
              data;
          }
          ?>
        </div>
<!-- Section to display various booking and query statistics in cards -->
<div class="row mb-4">
  <div class="col-md-3 mb-4">
    <a href="new_bookings.php" class="text-decoration-none">
      <div class="card text-center text-success p-3">
        <h6>New Bookings</h6>
        <h1 class="mt-2 mb-0"><?php echo $current_bookings['new_bookings'] ?></h1>
      </div>
    </a>
  </div>
  <div class="col-md-3 mb-4">
    <a href="refund_bookings.php" class="text-decoration-none">
      <div class="card text-center text-warning p-3">
        <h6>Refund Bookings</h6>
        <h1 class="mt-2 mb-0"><?php echo $current_bookings['refund_bookings'] ?></h1>
      </div>
    </a>
  </div>
  <div class="col-md-3 mb-4">
    <a href="user_queries.php" class="text-decoration-none">
      <div class="card text-center text-info p-3">
        <h6>User Queries</h6>
        <h1 class="mt-2 mb-0"><?php echo $unread_queries['count'] ?></h1>
      </div>
    </a>
  </div>
  <div class="col-md-3 mb-4">
    <a href="rate_review.php" class="text-decoration-none">
      <div class="card text-center text-info p-3">
        <h6>Rating & Review</h6>
        <h1 class="mt-2 mb-0"><?php echo $unread_reviews['count'] ?></h1>
      </div>
    </a>
  </div>
</div>

<!-- Section to display booking analytics with a dropdown to filter by time range -->
<div class="d-flex align-items-center justify-content-between mb-3">
  <h5>Booking Analytics</h5>
  <select class="form-select shadow-none bg-light w-auto" onchange="booking_analytics(this.value)">
    <option value="1">Past 30 Days</option>
    <option value="2">Past 90 Days</option>
    <option value="3">Past 1 Year</option>
    <option value="4">All time</option>
  </select>
</div>

<!-- Section to display total bookings and amounts, with separate cards for active and cancelled bookings -->
<div class="row mb-3">
  <div class="col-md-3 mb-4">
    <div class="card text-center text-primary p-3">
      <h6>Total Bookings</h6>
      <h1 class="mt-2 mb-0"><?php echo $total_bookings['count']; ?></h1>
      <h4 class="mt-2 mb-0" id="total_amt"><?php echo number_format($total_amount['total'], 2); ?></h4>
    </div>
  </div>
  <div class="col-md-3 mb-4">
    <div class="card text-center text-success p-3">
      <h6>Active Bookings</h6>
      <h1 class="mt-2 mb-0" id="active_bookings">0</h1>
      <h4 class="mt-2 mb-0" id="active_amt">$0</h4>
    </div>
  </div>
  <div class="col-md-3 mb-4">
    <div class="card text-center text-danger p-3">
      <h6>Cancelled Bookings</h6>
      <h1 class="mt-2 mb-0" id="cancelled_bookings">0</h1>
      <h4 class="mt-2 mb-0" id="cancelled_amt">$0</h4>
    </div>
  </div>
</div>

<!-- Section to display user, query, and review analytics -->
<div class="d-flex align-items-center justify-content-between mb-3">
  <h5>User, Queries, Reviews Analytics</h5>
  <select class="form-select shadow-none bg-light w-auto" onchange="user_analytics(this.value)">
    <option value="1">Past 30 Days</option>
    <option value="2">Past 90 Days</option>
    <option value="3">Past 1 Year</option>
    <option value="4">All time</option>
  </select>
</div>

<!-- Section to display user-related statistics -->
<div class="row mb-3">
  <div class="col-md-3 mb-4">
    <div class="card text-center text-success p-3">
      <h6>New Registration</h6>
      <h1 class="mt-2 mb-0" id="total_new_reg"><?php echo $current_users['total'] ?></h1>
    </div>
  </div>
  <div class="col-md-3 mb-4">
    <div class="card text-center text-primary p-3">
      <h6>Queries</h6>
      <h1 class="mt-2 mb-0" id="total_queries">0</h1>
    </div>
  </div>
  <div class="col-md-3 mb-4">
    <div class="card text-center text-primary p-3">
      <h6>Reviews</h6>
      <h1 class="mt-2 mb-0" id="total_reviews">0</h1>
    </div>
  </div>
</div>

<!-- Section to display user count and their status -->
<h5>Users</h5>
<div class="row mb-3">
  <div class="col-md-3 mb-4">
    <div class="card text-center text-info p-3">
      <h6>Total</h6>
      <h1 class="mt-2 mb-0"><?php echo $current_users['total'] ?></h1>
    </div>
  </div>
  <div class="col-md-3 mb-4">
    <div class="card text-center text-success p-3">
      <h6>Active</h6>
      <h1 class="mt-2 mb-0"><?php echo $current_users['active'] ?></h1>
    </div>
  </div>
  <div class="col-md-3 mb-4">
    <div class="card text-center text-warning p-3">
      <h6>Inactive</h6>
      <h1 class="mt-2 mb-0"><?php echo $current_users['inactive'] ?></h1>
    </div>
  </div>
</div>
