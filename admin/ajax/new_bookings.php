<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

// Fetch bookings based on search criteria
if (isset($_POST['get_bookings'])) {
  $frm_data = filteration($_POST);

  // Fetch details from the booking_details table
  $query = "SELECT sr_no, user_name, guesthouse_name, price, phonenum
            FROM `booking_details`
            WHERE (user_name LIKE ? OR phonenum LIKE ?)
            ORDER BY sr_no ASC";

  // Execute the query with the provided search term
  $res = select($query, ["%$frm_data[search]%", "%$frm_data[search]%"], 'ss');

  $i = 1;
  $table_data = "";

  // If no results are found, show a message and exit
  if (mysqli_num_rows($res) == 0) {
    echo "<b>No Data Found!</b>";
    exit;
  }

  // Process and format the results into HTML table rows
  while ($data = mysqli_fetch_assoc($res)) {
   

    $table_data .= "
        <tr>
          <td>$i</td>
          <td>
            <span class='badge bg-primary'>
             $data[user_name]
            </span>
          </td>
          <td>
            <b></b> $data[guesthouse_name]
          </td>
          <td>
          <b></b> $data[phonenum]
        </td>
          <td>
            <b></b> $$data[price]
          </td>
         
        </tr>
      ";

    $i++;
  }

  echo $table_data;
}
?>
