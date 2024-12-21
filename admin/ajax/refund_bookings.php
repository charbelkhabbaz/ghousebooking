<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

// Fetch cancelled bookings for refund processing based on search criteria
if (isset($_POST['get_bookings'])) {
  $frm_data = filteration($_POST);

  // Query to fetch cancelled bookings that match the search criteria
  $query = "SELECT bo.*, bd.* FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?) 
      AND (bo.booking_status=? AND bo.refund=?) ORDER BY bo.booking_id ASC";

  // Execute the query and get results
  $res = select($query, ["%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%", "cancelled", 0], 'sssss');

  $i = 1;
  $table_data = "";

  // If no results are found, show a "No Data Found" message
  if (mysqli_num_rows($res) == 0) {
    echo "<b>No Data Found!</b>";
    exit;
  }

  // Process and display the results in a table format
  while ($data = mysqli_fetch_assoc($res)) {
    $date = date("d-m-Y", strtotime($data['datentime']));
    $checkin = date("d-m-Y", strtotime($data['check_in']));
    $checkout = date("d-m-Y", strtotime($data['check_out']));

    // Append data into the table rows
    $table_data .= "
        <tr>
          <td>$i</td>
          <td>
            <span class='badge bg-primary'>
              Order ID: $data[order_id]
            </span>
            <br>
            <b>Name:</b> $data[user_name]
            <br>
            <b>Phone No:</b> $data[phonenum]
          </td>
          <td>
            <b>Guesthouse:</b> $data[guesthouse_name]
            <br>
            <b>Check-in:</b> $checkin
            <br>
            <b>Check-out:</b> $checkout
            <br>
            <b>Date:</b> $date
          </td>
          <td>
            <b>$$data[trans_amt]</b> 
          </td>
          <td>
            <button type='button' onclick='refund_booking($data[booking_id])' class='btn btn-success btn-sm fw-bold shadow-none'>
              <i class='bi bi-cash-stack'></i> Refund
            </button>
          </td>
        </tr>
      ";

    $i++;
  }

  // Output the generated table data
  echo $table_data;
}

// Refund the booking based on the booking ID
if (isset($_POST['refund_booking'])) {
  $frm_data = filteration($_POST);

  // Update the booking's refund status
  $query = "UPDATE `booking_order` SET `refund`=? WHERE `booking_id`=?";
  $values = [1, $frm_data['booking_id']];
  $res = update($query, $values, 'ii');

  // Return the result of the update operation
  echo $res;
}
