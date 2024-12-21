<?php

// Including essential files for the application
require('inc/essentials.php');
// Including database configuration
require('inc/db_config.php');
// Including the MPDF library for generating PDF documents
require('inc/mpdf/vendor/autoload.php');

// Admin login check
adminLogin();

// Check if the 'gen_pdf' and 'id' parameters are set in the GET request
if (isset($_GET['gen_pdf']) && isset($_GET['id'])) {
  // Filtering the input data
  $frm_data = filteration($_GET);

  // SQL query to fetch the booking details based on the booking ID and status conditions
  $query = "SELECT bo.*, bd.*, uc.email FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      INNER JOIN `user_cred` uc ON bo.user_id = uc.id
      WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
      OR (bo.booking_status='cancelled' AND bo.refund=1)
      OR (bo.booking_status='payment failed')) 
      AND bo.booking_id = '$frm_data[id]'";

  // Executing the query and storing the result
  $res = mysqli_query($con, $query);
  // Checking if any rows are returned from the query
  $total_rows = mysqli_num_rows($res);

  // If no rows are returned, redirect to the dashboard
  if ($total_rows == 0) {
    header('location: dashboard.php');
    exit;
  }

  // Fetching the booking data
  $data = mysqli_fetch_assoc($res);

  // Formatting the date and time for booking and check-in/check-out dates
  $date = date("h:ia | d-m-Y", strtotime($data['datentime']));
  $checkin = date("d-m-Y", strtotime($data['check_in']));
  $checkout = date("d-m-Y", strtotime($data['check_out']));

  // Creating the HTML structure for the booking receipt
  $table_data = "
    <h2>BOOKING RECIEPT</h2>
    <table border='1'>
      <tr>
        <td>Order ID: $data[order_id]</td>
        <td>Booking Date: $date</td>
      </tr>
      <tr>
        <td colspan='2'>Status: $data[booking_status]</td>
      </tr>
      <tr>
        <td>Name: $data[user_name]</td>
        <td>Email: $data[email]</td>
      </tr>
      <tr>
        <td>Phone Number: $data[phonenum]</td>
        <td>Address: $data[address]</td>
      </tr>
      <tr>
        <td>Guesthouse Name: $data[guesthouse_name]</td>
        <td>Cost: $$data[price] per night</td>
      </tr>
      <tr>
        <td>Check-in: $checkin</td>
        <td>Check-out: $checkout</td>
      </tr>
    ";

  // Adding specific details based on the booking status (cancelled or payment failed)
  if ($data['booking_status'] == 'cancelled') {
    $refund = ($data['refund']) ? "Amount Refunded" : "Not Yet Refunded";

    $table_data .= "<tr>
        <td>Amount Paid: $$data[trans_amt]</td>
        <td>Refund: $refund</td>
      </tr>";
  } else if ($data['booking_status'] == 'payment failed') {
    $table_data .= "<tr>
        <td>Transaction Amount: $$data[trans_amt]</td>
        <td>Failure Response: $data[trans_resp_msg]</td>
      </tr>";
  } else {
    $table_data .= "<tr>
        <td>Guesthouse Number: $data[guesthouse_no]</td>
        <td>Amount Paid: $$data[trans_amt]</td>
      </tr>";
  }

  // Closing the HTML table
  $table_data .= "</table>";

  // Creating a new instance of MPDF for PDF generation
  $mpdf = new \Mpdf\Mpdf();
  // Writing the generated HTML data to the PDF
  $mpdf->WriteHTML($table_data);
  // Outputting the PDF with the order ID as the filename, and forcing download
  $mpdf->Output($data['order_id'] . '.pdf', 'D');
} else {
  // If 'gen_pdf' or 'id' is not set, redirect to the dashboard
  header('location: dashboard.php');
}
