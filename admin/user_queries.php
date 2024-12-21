<?php
  // Includes essential configurations and functions
  require('inc/essentials.php');
  require('inc/db_config.php');
  
  // Admin login check
  adminLogin();

  // Handling "Mark as Read" functionality
  if(isset($_GET['seen']))
  {
    $frm_data = filteration($_GET); // Filtering the input data

    // If 'seen' is 'all', mark all queries as read
    if($frm_data['seen']=='all'){
      $q = "UPDATE `user_queries` SET `seen`=?";  // Update query to mark as seen
      $values = [1];
      if(update($q,$values,'i')){ // Execute update function
        alert('success','Marked all as read!');
      }
      else{
        alert('error','Operation Failed!');
      }
    }
    else{
      // Mark specific query as read
      $q = "UPDATE `user_queries` SET `seen`=? WHERE `sr_no`=?";
      $values = [1,$frm_data['seen']];
      if(update($q,$values,'ii')){  // Execute update function
        alert('success','Marked as read!');
      }
      else{
        alert('error','Operation Failed!');
      }
    }
  }

  // Handling "Delete" functionality
  if(isset($_GET['del']))
  {
    $frm_data = filteration($_GET); // Filtering the input data

    // If 'del' is 'all', delete all queries
    if($frm_data['del']=='all'){
      $q = "DELETE FROM `user_queries`";  // Delete query
      if(mysqli_query($con,$q)){  // Execute delete query
        alert('success','All data deleted!');
      }
      else{
        alert('error','Operation failed!');
      }
    }
    else{
      // Delete specific query
      $q = "DELETE FROM `user_queries` WHERE `sr_no`=?";
      $values = [$frm_data['del']];
      if(delete($q,$values,'i')){  // Execute delete function
        alert('success','Data deleted!');
      }
      else{
        alert('error','Operation failed!');
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - User Queries</title>
  <?php require('inc/links.php'); ?> <!-- Includes stylesheets and JS links -->
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>  <!-- Includes the header of the page -->

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">USER QUERIES</h3>

        <!-- Card for displaying user queries -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">

            <div class="text-end mb-4">
              <!-- Buttons to mark all as read or delete all queries -->
              <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                <i class="bi bi-check-all"></i> Mark all read
              </a>
              <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm">
                <i class="bi bi-trash"></i> Delete all
              </a>
            </div>

            <!-- Table for displaying user queries -->
            <div class="table-responsive-md" style="height: 450px; overflow-y: scroll;">
              <table class="table table-hover border">
                <thead class="sticky-top">
                  <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col" width="20%">Subject</th>
                    <th scope="col" width="30%">Message</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    // Query to fetch all user queries
                    $q = "SELECT * FROM `user_queries` ORDER BY `sr_no` DESC";
                    $data = mysqli_query($con,$q);  // Execute the query
                    $i=1;

                    // Loop through all fetched queries and display them
                    while($row = mysqli_fetch_assoc($data))
                    {
                      // Format date
                      $date = date('d-m-Y',strtotime($row['date']));
                      $seen='';
                      if($row['seen']!=1){
                        $seen = "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary'>Mark as read</a> <br>";  // Show 'Mark as read' button if not read
                      }
                      // Display 'Delete' button
                      $seen.="<a href='?del=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger mt-2'>Delete</a>";

                      // Display query data in the table
                      echo<<<query
                        <tr>
                          <td>$i</td>
                          <td>$row[name]</td>
                          <td>$row[email]</td>
                          <td>$row[subject]</td>
                          <td>$row[message]</td>
                          <td>$date</td>
                          <td>$seen</td>
                        </tr>
                      query;
                      $i++;  // Increment the index
                    }
                  ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
  

  <?php require('inc/scripts.php'); ?>  <!-- Includes JavaScript files -->

</body>
</html>
