<?php 

  // Including necessary database configuration and utility functions
  require('../inc/db_config.php');
  require('../inc/essentials.php');
  adminLogin(); // Ensure the admin is logged in

  // Fetch users data
  if(isset($_POST['get_users']))
  {
    // Select all users from the user_cred table
    $res = selectAll('user_cred');    
    $i=1;
    $path = USERS_IMG_PATH;

    $data = "";

    // Loop through users and prepare the data
    while($row = mysqli_fetch_assoc($res))
    {
      // Delete button HTML
      $del_btn = "<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
        <i class='bi bi-trash'></i> 
      </button>";

      // Set user verification badge
      $verified = "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";

      // If the user is verified, update the badge and remove delete button
      if($row['is_verified']){
        $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
        $del_btn = ""; 
      }

      // Status button for active or inactive users
      $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>
        active
      </button>";

      // If the user is inactive, change the status button
      if(!$row['status']){
        $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>
          inactive
        </button>";
      }

      // Format the date of registration
      $date = date("d-m-Y",strtotime($row['datentime']));

      // Concatenate user data into a table row
      $data.="
        <tr>
          <td>$i</td>
          <td>
            <img src='$path$row[profile]' width='55px'>
            <br>
            $row[name]
          </td>
          <td>$row[email]</td>
          <td>$row[phonenum]</td>
          <td>$row[address] | $row[pincode]</td>
          <td>$row[dob]</td>
          <td>$status</td>
          <td>$date</td>
          <td>$del_btn</td>
        </tr>
      ";
      $i++;
    }

    // Output the concatenated table rows
    echo $data;
  }

  // Toggle user status (active/inactive)
  if(isset($_POST['toggle_status']))
  {
    $frm_data = filteration($_POST);

    // Update the user's status in the database
    $q = "UPDATE `user_cred` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'],$frm_data['toggle_status']];

    if(update($q,$v,'ii')){
      echo 1; // Success
    }
    else{
      echo 0; // Failure
    }
  }

  // Remove a user from the system
  if(isset($_POST['remove_user']))
  {
    $frm_data = filteration($_POST);

    // Delete the user from the database if they are not verified
    $res = delete("DELETE FROM `user_cred` WHERE `id`=? AND `is_verified`=?",[$frm_data['user_id'],0],'ii');

    if($res){
      echo 1; // Success
    }
    else{
      echo 0; // Failure
    }
  }

  // Search for users by name
  if(isset($_POST['search_user']))
  {
    $frm_data = filteration($_POST);

    // Search users based on name using LIKE query
    $query = "SELECT * FROM `user_cred` WHERE `name` LIKE ?";

    $res = select($query,["%$frm_data[name]%"],'s');    
    $i=1;
    $path = USERS_IMG_PATH;

    $data = "";

    // Loop through search results and format as table rows
    while($row = mysqli_fetch_assoc($res))
    {
      // Delete button HTML
      $del_btn = "<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
        <i class='bi bi-trash'></i> 
      </button>";

      // Set user verification badge
      $verified = "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";

      // If the user is verified, update the badge and remove delete button
      if($row['is_verified']){
        $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
        $del_btn = ""; 
      }

      // Status button for active or inactive users
      $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>
        active
      </button>";

      // If the user is inactive, change the status button
      if(!$row['status']){
        $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>
          inactive
        </button>";
      }

      // Format the date of registration
      $date = date("d-m-Y",strtotime($row['datentime']));

      // Concatenate user data into a table row
      $data.="
        <tr>
          <td>$i</td>
          <td>
            <img src='$path$row[profile]' width='55px'>
            <br>
            $row[name]
          </td>
          <td>$row[email]</td>
          <td>$row[phonenum]</td>
          <td>$row[address] | $row[pincode]</td>
          <td>$row[dob]</td>
          <td>$verified</td>
          <td>$status</td>
          <td>$date</td>
          <td>$del_btn</td>
        </tr>
      ";
      $i++;
    }

    // Output the concatenated table rows
    echo $data;
  }

?>
