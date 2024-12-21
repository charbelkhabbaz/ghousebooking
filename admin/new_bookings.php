<?php
  // Including essential files for application and database configuration
  require('inc/essentials.php');
  require('inc/db_config.php');
  // Checking if admin is logged in by calling the adminLogin function
  adminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Setting meta tags for character encoding, compatibility, and viewport scaling -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - New Bookings</title>
  <!-- Including external CSS links for styles -->
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <!-- Including header of the admin panel -->
  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">NEW BOOKINGS</h3>

        <!-- Card displaying booking information -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">

            <!-- Search bar to filter bookings based on input -->
            <div class="text-end mb-4">
              <input type="text" oninput="get_bookings(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Type to search...">
            </div>

            <!-- Table displaying booking details -->
            <div class="table-responsive">
              <table class="table table-hover border" style="min-width: 1200px;">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">User name</th>
                    <th scope="col">Guesthouse name</th>
                    
                    <th scope="col">Phone number</th>
                    <th scope="col">Price</th>
                  </tr>
                </thead>
                <tbody id="table-data">                 
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Modal for assigning guesthouse number to a booking -->
  <div class="modal fade" id="assign-guesthouse" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="assign_guesthouse_form">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Assign Guesthouse</h5>
          </div>
          <div class="modal-body">
            <!-- Input field to enter guesthouse number -->
            <div class="mb-3">
              <label class="form-label fw-bold">Guesthouse Number</label>
              <input type="text" name="guesthouse_no" class="form-control shadow-none" required>
            </div>
            <!-- Note about guesthouse assignment -->
            <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
              Note: Assign Guesthouse Number only when user has been arrived!
            </span>
            <!-- Hidden field to store booking ID -->
            <input type="hidden" name="booking_id">
          </div>
          <div class="modal-footer">
            <!-- Cancel button to close the modal -->
            <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
            <!-- Submit button to assign guesthouse -->
            <button type="submit" class="btn custom-bg text-white shadow-none">ASSIGN</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Including external JavaScript files -->
  <?php require('inc/scripts.php'); ?>

  <!-- Including custom script for handling new bookings actions -->
  <script src="scripts/new_bookings.js"></script>

</body>
</html>
