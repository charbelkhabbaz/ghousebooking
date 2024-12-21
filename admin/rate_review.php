<?php
  // Including essential files for application and database configuration
  require('inc/essentials.php');
  require('inc/db_config.php');
  // Checking if admin is logged in by calling the adminLogin function
  adminLogin();

  // Handling the "mark as seen" operation
  if(isset($_GET['seen']))
  {
    // Filtering the form data
    $frm_data = filteration($_GET);

    // If "all" is selected, mark all reviews as seen
    if($frm_data['seen']=='all'){
      $q = "UPDATE `rating_review` SET `seen`=?";
      $values = [1];
      // Updating the database
      if(update($q,$values,'i')){
        alert('success','Marked all as read!');
      }
      else{
        alert('error','Operation Failed!');
      }
    }
    else{
      // Mark a specific review as seen based on its ID
      $q = "UPDATE `rating_review` SET `seen`=? WHERE `sr_no`=?";
      $values = [1,$frm_data['seen']];
      // Updating the database
      if(update($q,$values,'ii')){
        alert('success','Marked as read!');
      }
      else{
        alert('error','Operation Failed!');
      }
    }
  }

  // Handling the "delete" operation
  if(isset($_GET['del']))
  {
    // Filtering the form data
    $frm_data = filteration($_GET);

    // If "all" is selected, delete all reviews
    if($frm_data['del']=='all'){
      $q = "DELETE FROM `rating_review`";
      // Deleting all data from the database
      if(mysqli_query($con,$q)){
        alert('success','All data deleted!');
      }
      else{
        alert('error','Operation failed!');
      }
    }
    else{
      // Delete a specific review based on its ID
      $q = "DELETE FROM `rating_review` WHERE `sr_no`=?";
      $values = [$frm_data['del']];
      // Deleting the review from the database
      if(delete($q,$values,'i')){
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
  <title>Admin Panel - Ratings & Reviews</title>
  <!-- Including external CSS links for styles -->
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <!-- Including header of the admin panel -->
  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">RATINGS & REVIEWS</h3>

        <!-- Card displaying ratings and reviews information -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">

            <!-- Action buttons to mark all as read or delete all reviews -->
            <div class="text-end mb-4">
              <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                <i class="bi bi-check-all"></i> Mark all read
              </a>
              <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm">
                <i class="bi bi-trash"></i> Delete all
              </a>
            </div>

            <!-- Table displaying ratings and reviews data -->
            <div class="table-responsive-md">
              <table class="table table-hover border">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">Guesthouse Name</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Rating</th>
                    <th scope="col" width="30%">Review</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    // Query to fetch rating and review data from the database
                    $q = "SELECT rr.*,uc.name AS uname, r.name AS rname FROM `rating_review` rr
                      INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                      INNER JOIN `guesthouses` r ON rr.guesthouse_id = r.id
                      ORDER BY `sr_no` DESC";

                    // Executing the query
                    $data = mysqli_query($con,$q);
                    $i=1;

                    // Looping through the result set and displaying data in table rows
                    while($row = mysqli_fetch_assoc($data))
                    {
                      $date = date('d-m-Y',strtotime($row['datentime']));

                      $seen='';
                      // Check if review is marked as seen or not
                      if($row['seen']!=1){
                        $seen = "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary mb-2'>Mark as read</a> <br>";
                      }
                      // Adding delete action for each review
                      $seen.="<a href='?del=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger'>Delete</a>";

                      // Displaying data in table rows
                      echo<<<query
                        <tr>
                          <td>$i</td>
                          <td>$row[rname]</td>
                          <td>$row[uname]</td>
                          <td>$row[rating]</td>
                          <td>$row[review]</td>
                          <td>$date</td>
                          <td>$seen</td>
                        </tr>
                      query;
                      $i++;
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
  

  <!-- Including external JavaScript files -->
  <?php require('inc/scripts.php'); ?>

</body>
</html>
