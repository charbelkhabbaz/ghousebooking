<?php
  // Including essential functions and database configuration files
  require('inc/essentials.php'); 
  require('inc/db_config.php'); 

  // Calling adminLogin() function to ensure the admin is logged in
  adminLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Defining character encoding and meta tags for responsive design -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Title of the page displayed in the browser tab -->
  <title>Admin Panel - Bookings Records</title>
  
  <!-- Including links for external CSS styles -->
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <!-- Including header file that contains the navigation or header elements -->
  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <!-- Main content area with padding and overflow handling -->
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">BOOKING RECORDS</h3>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">

            <!-- Search input field to filter bookings dynamically based on user input -->
            <div class="text-end mb-4">
              <input type="text" id="search_input" oninput="get_bookings(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Type to search...">
            </div>

            <div class="table-responsive">
              <!-- Table to display booking records with appropriate columns -->
              <table class="table table-hover border" style="min-width: 1200px;">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">User Details</th>
                    <th scope="col">Guesthouse Details</th>
                    <th scope="col">Bookings Details</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="table-data">
                  <!-- Booking records will be inserted here dynamically -->
                </tbody>
              </table>
            </div>

            <!-- Pagination controls for navigating through multiple pages of booking records -->
            <nav>
              <ul class="pagination mt-3" id="table-pagination">
                <!-- Pagination links will be inserted here dynamically -->
              </ul>
            </nav>

          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Including scripts necessary for the page functionality -->
  <?php require('inc/scripts.php'); ?>

  <!-- Linking to the external JavaScript file responsible for handling booking records -->
  <script src="scripts/booking_records.js"></script>

</body>
</html>
