<?php

// Import database configuration and essential functions
require('../inc/db_config.php');
require('../inc/essentials.php');

// Set the default timezone
date_default_timezone_set("Asia/Beirut");

// Ensure the admin is logged in before executing
adminLogin();

if (isset($_POST['get_bookings'])) {
  // Filter and sanitize incoming POST data
  $frm_data = filteration($_POST);

  // Pagination settings
  $limit = 2; // Number of results per page
  $page = $frm_data['page']; // Current page number
  $start = ($page - 1) * $limit; // Starting index for the query

  // SQL query to fetch booking and related details
  $query = "SELECT bo.*, bd.* FROM `booking_order` bo
      INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
      WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
      OR (bo.booking_status='cancelled' AND bo.refund=1)
      OR (bo.booking_status='payment failed')) 
      AND (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?) 
      ORDER BY bo.booking_id DESC";

  // Execute the query to get all matching results
  $res = select($query, ["%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%"], 'sss');

  // Modify query to add pagination
  $limit_query = $query . " LIMIT $start,$limit";
  $limit_res = select($limit_query, ["%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%"], 'sss');

  // Get total number of rows matching the criteria
  $total_rows = mysqli_num_rows($res);

  // Handle the case where no results are found
  if ($total_rows == 0) {
    $output = json_encode(["table_data" => "<b>No Data Found!</b>", "pagination" => '']);
    echo $output;
    exit;
  }

  // Initialize table row counter
  $i = $start + 1;
  $table_data = ""; // Stores table rows HTML

  // Loop through the paginated results to generate table rows
  while ($data = mysqli_fetch_assoc($limit_res)) {
    // Format dates
    $date = date("d-m-Y", strtotime($data['datentime']));
    $checkin = date("d-m-Y", strtotime($data['check_in']));
    $checkout = date("d-m-Y", strtotime($data['check_out']));

    // Determine booking status badge style
    if ($data['booking_status'] == 'booked') {
      $status_bg = 'bg-success';
    } else if ($data['booking_status'] == 'cancelled') {
      $status_bg = 'bg-danger';
    } else {
      $status_bg = 'bg-warning text-dark';
    }

    // Append row HTML to table_data
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
            <b>Price:</b> $$data[price]
          </td>
          <td>
            <b>Amount:</b> $$data[trans_amt]
            <br>
            <b>Date:</b> $date
          </td>
          <td>
            <span class='badge $status_bg'>$data[booking_status]</span>
          </td>
          <td>
            <button type='button' onclick='download($data[booking_id])' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
              <i class='bi bi-file-earmark-arrow-down-fill'></i>
            </button>
          </td>
        </tr>
      ";

    $i++; // Increment row counter
  }

  $pagination = ""; // Stores pagination HTML

  // Generate pagination controls if total rows exceed the limit
  if ($total_rows > $limit) {
    $total_pages = ceil($total_rows / $limit); // Total number of pages

    // Add "First" button if not on the first page
    if ($page != 1) {
      $pagination .= "<li class='page-item'>
          <button onclick='change_page(1)' class='page-link shadow-none'>First</button>
        </li>";
    }

    // Add "Prev" button
    $disabled = ($page == 1) ? "disabled" : "";
    $prev = $page - 1;
    $pagination .= "<li class='page-item $disabled'>
        <button onclick='change_page($prev)' class='page-link shadow-none'>Prev</button>
      </li>";

    // Add "Next" button
    $disabled = ($page == $total_pages) ? "disabled" : "";
    $next = $page + 1;
    $pagination .= "<li class='page-item $disabled'>
        <button onclick='change_page($next)' class='page-link shadow-none'>Next</button>
      </li>";

    // Add "Last" button if not on the last page
    if ($page != $total_pages) {
      $pagination .= "<li class='page-item'>
          <button onclick='change_page($total_pages)' class='page-link shadow-none'>Last</button>
        </li>";
    }
  }

  // Combine table data and pagination into a JSON response
  $output = json_encode(["table_data" => $table_data, "pagination" => $pagination]);

  echo $output; // Output the response
}
