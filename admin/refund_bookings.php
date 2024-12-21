<?php
require('inc/essentials.php'); // Includes essential files for admin authentication and settings
require('inc/db_config.php');  // Includes database configuration file for database connection
adminLogin(); // Calls a function to ensure the user is logged in as an admin
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8"> <!-- Character set for the page -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Ensures the page works well on modern browsers -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Makes the page responsive -->
  <title>Admin Panel - Refund Bookings</title> <!-- Title of the page -->
  <?php require('inc/links.php'); ?> <!-- Includes external CSS and other links -->
</head>

<body class="bg-light"> <!-- Light background color for the page -->

  <?php require('inc/header.php'); ?> <!-- Includes the header (usually includes navigation) -->

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">REFUND BOOKINGS</h3> <!-- Title of the page -->

        <div class="card border-0 shadow-sm mb-4"> <!-- Card container -->
          <div class="card-body"> <!-- Card body where content goes -->

            <div class="text-end mb-4"> <!-- Search box for searching refund bookings -->
              <input type="text" oninput="get_bookings(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Type to search...">
            </div>

            <div class="table-responsive"> <!-- Table to display refund bookings -->
              <table class="table table-hover border" style="min-width: 1200px;"> <!-- Table structure -->
                <thead>
                  <tr class="bg-dark text-light"> <!-- Table headers -->
                    <th scope="col">#</th>
                    <th scope="col">User Details</th>
                    <th scope="col">Guesthouse Details</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="table-data"> <!-- Table body where data is inserted -->
                  <?php
                  // Query to fetch bookings that are marked for deletion (is_deleted = 1)
                  $query = "SELECT b.booking_id, b.user_id, g.name AS guesthouse_name, g.price, u.name AS user_name, u.email 
                            FROM booking_order b 
                            INNER JOIN guesthouses g ON b.guesthouse_id = g.id 
                            INNER JOIN user_cred u ON b.user_id = u.id 
                            WHERE b.is_deleted = 1"; // SQL query to fetch data
                  $result = mysqli_query($con, $query); // Execute query

                  // Check if any records found
                  if (mysqli_num_rows($result) > 0) {
                    $counter = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                      // Display each booking
                      echo "<tr>
                                  <td>{$counter}</td>
                                  <td>
                                      <strong>Name:</strong> {$row['user_name']}<br>
                                      <strong>Email:</strong> {$row['email']}
                                  </td>
                                  <td>
                                      <strong>Guesthouse:</strong> {$row['guesthouse_name']}<br>
                                      <strong>Price:</strong> \${$row['price']}
                                  </td>
                                  <td>
                                      <button class='btn btn-sm btn-danger' onclick='deleteBooking({$row['booking_id']})'>Delete</button>
                                  </td>
                                </tr>";
                      $counter++; // Increment counter for each booking
                    }
                  } else {
                    // If no bookings found, display a message
                    echo "<tr><td colspan='5' class='text-center'>No refund bookings found</td></tr>";
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

  <?php require('inc/scripts.php'); ?> <!-- Includes external JavaScript files -->

  <script>
    // JavaScript function to handle booking deletion
    function deleteBooking(bookingId) {
      // Confirm before deleting
      if (confirm('Are you sure you want to delete this booking?')) {
        // Sending request to delete booking using fetch
        fetch('delete_booking.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json' // JSON data type
            },
            body: JSON.stringify({
              booking_id: bookingId // Sending booking ID to be deleted
            })
          })
          .then(response => response.text()) // Handling response
          .then(data => {
            alert(data); // Display success or error message
            location.reload(); // Reload the page to update the table
          })
          .catch(error => console.error('Error:', error)); // Log errors
      }
    }
  </script>

</body>

</html>
