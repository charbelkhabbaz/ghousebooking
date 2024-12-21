<?php 

  // Include database configuration and essential utility functions.
  require('../inc/db_config.php');
  require('../inc/essentials.php');
  
  // Ensure the admin is logged in before processing analytics.
  adminLogin();

  // Check if the booking analytics request is made.
  if (isset($_POST['booking_analytics'])) {
    // Sanitize the incoming data from the request.
    $frm_data = filteration($_POST);

    $condition = ""; // Initialize the condition for SQL queries based on the period.

    // Define conditions based on the selected period.
    if ($frm_data['period'] == 1) {
      // Last 30 days.
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    } else if ($frm_data['period'] == 2) {
      // Last 90 days.
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    } else if ($frm_data['period'] == 3) {
      // Last 1 year.
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
    }

    // Query to fetch booking statistics based on the defined condition.
    $result = mysqli_fetch_assoc(mysqli_query($con, "SELECT 
      COUNT(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN 1 END) AS `total_bookings`, -- Count of all valid bookings.
      SUM(CASE WHEN booking_status!='pending' AND booking_status!='payment failed' THEN `trans_amt` END) AS `total_amt`, -- Total transaction amount for valid bookings.

      COUNT(CASE WHEN booking_status='booked' AND arrival=1 THEN 1 END) AS `active_bookings`, -- Count of active bookings where arrival has occurred.
      SUM(CASE WHEN booking_status='booked' AND arrival=1 THEN `trans_amt` END) AS `active_amt`, -- Total transaction amount for active bookings.

      COUNT(CASE WHEN booking_status='cancelled' AND refund=1 THEN 1 END) AS `cancelled_bookings`, -- Count of cancelled bookings with refunds processed.
      SUM(CASE WHEN booking_status='cancelled' AND refund=1 THEN `trans_amt` END) AS `cancelled_amt` -- Total transaction amount for refunded bookings.

      FROM `booking_order` $condition")); // Apply the condition based on the period.

    // Encode the query result as a JSON response.
    $output = json_encode($result);

    // Output the result to the client.
    echo $output;
  }

  // Check if the user analytics request is made.
  if (isset($_POST['user_analytics'])) {
    // Sanitize the incoming data from the request.
    $frm_data = filteration($_POST);

    $condition = ""; // Initialize the condition for SQL queries based on the period.

    // Define conditions based on the selected period.
    if ($frm_data['period'] == 1) {
      // Last 30 days.
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    } else if ($frm_data['period'] == 2) {
      // Last 90 days.
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    } else if ($frm_data['period'] == 3) {
      // Last 1 year.
      $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
    }

    // Query to fetch total reviews within the specified period.
    $total_reviews = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(sr_no) AS `count`
      FROM `rating_review` $condition"));

    // Query to fetch total user queries within the specified period.
    $total_queries = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(sr_no) AS `count`
      FROM `user_queries` $condition"));

    // Query to fetch total new registrations within the specified period.
    $total_new_reg = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(id) AS `count`
    FROM `user_cred` $condition"));

    // Prepare the output as an associative array.
    $output = [
      'total_queries' => $total_queries['count'], // Total number of user queries.
      'total_reviews' => $total_reviews['count'], // Total number of reviews.
      'total_new_reg' => $total_new_reg['count']  // Total number of new user registrations.
    ];

    // Encode the output as a JSON response.
    $output = json_encode($output);

    // Output the result to the client.
    echo $output;
  }

?>
