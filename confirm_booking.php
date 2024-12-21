<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - CONFIRM BOOKING</title>
</head>

<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <?php

  /*
      Check guesthouse id from url is present or not
      Shutdown mode is active or not
      User is logged in or not
    */

  if (!isset($_GET['id']) || $settings_r['shutdown'] == true) {
    redirect('guesthouses.php');
  } else if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('guesthouses.php');
  }

  // filter and get guesthouse and user data

  $data = filteration($_GET);

  $guesthouse_res = select("SELECT * FROM guesthouses WHERE id=? AND status=? AND removed=?", [$data['id'], 1, 0], 'iii');

  if (mysqli_num_rows($guesthouse_res) == 0) {
    redirect('guesthouses.php');
  }

  $guesthouse_data = mysqli_fetch_assoc($guesthouse_res);

  $_SESSION['guesthouse'] = [
    "id" => $guesthouse_data['id'],
    "name" => $guesthouse_data['name'],
    "price" => $guesthouse_data['price'],
    "payment" => null,
    "available" => false,
  ];


  $user_res = select("SELECT * FROM user_cred WHERE id=? LIMIT 1", [$_SESSION['uId']], "i");
  $user_data = mysqli_fetch_assoc($user_res);

  ?>



  <div class="container">
    <div class="row">

      <div class="col-12 my-5 mb-4 px-4">
        <h2 class="fw-bold">CONFIRM BOOKING</h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
          <span class="text-secondary"> > </span>
          <a href="guesthouses.php" class="text-secondary text-decoration-none">GUESTHOUSESS</a>
          <span class="text-secondary"> > </span>
          <a href="#" class="text-secondary text-decoration-none">CONFIRM</a>
        </div>
      </div>

      <div class="col-lg-7 col-md-12 px-4">
        <?php

        $guesthouse_thumb = GUESTHOUSES_IMG_PATH . "thumbnail.jpg";
        $thumb_q = mysqli_query($con, "SELECT * FROM guesthouse_images 
            WHERE guesthouse_id='$guesthouse_data[id]' 
            AND thumb='1'");

        if (mysqli_num_rows($thumb_q) > 0) {
          $thumb_res = mysqli_fetch_assoc($thumb_q);
          $guesthouse_thumb = GUESTHOUSES_IMG_PATH . $thumb_res['image'];
        }

        echo <<<data
            <div class="card p-3 shadow-sm rounded">
              <img src="$guesthouse_thumb" class="img-fluid rounded mb-3">
              <h5>$guesthouse_data[name]</h5>
              <h6>$$guesthouse_data[price] per night</h6>
            </div>
          data;

        ?>
      </div>
<!-- Booking Details Section -->
<div class="col-lg-5 col-md-12 px-4">
  <!-- Card for booking details -->
  <div class="card mb-4 border-0 shadow-sm rounded-3">
    <div class="card-body">
      <!-- Form for booking -->
      <form action="pay_now.php" method="POST" id="booking_form">
        <h6 class="mb-3">BOOKING DETAILS</h6>
        <div class="row">
          <!-- Name input field -->
          <div class="col-md-6 mb-3">
            <label class="form-label">Name</label>
            <input name="name" type="text" value="<?php echo $user_data['name'] ?>" class="form-control shadow-none" required>
          </div>
          <!-- Phone number input field -->
          <div class="col-md-6 mb-3">
            <label class="form-label">Phone Number</label>
            <input name="phonenum" type="number" value="<?php echo $user_data['phonenum'] ?>" class="form-control shadow-none" required>
          </div>
          <!-- Address input field -->
          <div class="col-md-12 mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $user_data['address'] ?></textarea>
          </div>
          <!-- Check-in date input field -->
          <div class="col-md-6 mb-3">
            <label class="form-label">Check-in</label>
            <input name="checkin" onchange="check_availability()" type="date" class="form-control shadow-none" required>
          </div>
          <!-- Check-out date input field -->
          <div class="col-md-6 mb-4">
            <label class="form-label">Check-out</label>
            <input name="checkout" onchange="check_availability()" type="date" class="form-control shadow-none" required>
          </div>

          <!-- Status and action buttons -->
          <div class="col-12">
            <!-- Loading spinner -->
            <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>

            <!-- Message to user if availability isn't provided -->
            <h6 class="mb-3 text-danger" id="pay_info">Provide check-in & check-out date !</h6>

            <!-- Pay now button (initially disabled) -->
            <button name="pay_now" class="btn w-100 text-white custom-bg shadow-none mb-1" disabled>Pay Now</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="col-12 text-center mt-4">
  <a href="rate_guesthouse.php?id=<?php echo $guesthouse_data['id']; ?>" class="btn text-white" style="background-color: #2ec1ac; border-color: #2ec1ac;">Rate Now!</a>

</div>


<!-- Footer inclusion -->
<?php require('inc/footer.php'); ?>

<!-- Script to handle availability check and update booking status -->
<script>
  let booking_form = document.getElementById('booking_form');
  let info_loader = document.getElementById('info_loader');
  let pay_info = document.getElementById('pay_info');

  function check_availability() {
    let checkin_val = booking_form.elements['checkin'].value;
    let checkout_val = booking_form.elements['checkout'].value;

    // Disable pay now button while checking availability
    booking_form.elements['pay_now'].setAttribute('disabled', true);

    // Check if both check-in and check-out dates are selected
    if (checkin_val != '' && checkout_val != '') {
      // Hide payment information initially and show loader
      pay_info.classList.add('d-none');
      pay_info.classList.replace('text-dark', 'text-danger');
      info_loader.classList.remove('d-none');

      let data = new FormData();

      // Append data to check availability
      data.append('check_availability', '');
      data.append('check_in', checkin_val);
      data.append('check_out', checkout_val);

      // Create a new AJAX request to confirm booking
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/confirm_booking.php", true);

      xhr.onload = function() {
        let data = JSON.parse(this.responseText);

        // Check the availability response and update the UI accordingly
        if (data.status == 'check_in_out_equal') {
          pay_info.innerText = "You cannot check-out on the same day!";
        } else if (data.status == 'check_out_earlier') {
          pay_info.innerText = "Check-out date is earlier than check-in date!";
        } else if (data.status == 'check_in_earlier') {
          pay_info.innerText = "Check-in date is earlier than today's date!";
        } else if (data.status == 'unavailable') {
          pay_info.innerText = "GuestHouse not available for this check-in date!";
        } else {
          pay_info.innerHTML = "No. of Days: " + data.days + "<br>Total Amount to Pay: $" + data.payment;
          pay_info.classList.replace('text-danger', 'text-dark');
          // Enable the Pay Now button
          booking_form.elements['pay_now'].removeAttribute('disabled');
        }

        // Display the final result and hide the loader
        pay_info.classList.remove('d-none');
        info_loader.classList.add('d-none');
      }

      // Send the request to the server
      xhr.send(data);
    }

  }
</script>

</body>
</html>