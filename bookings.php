<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - BOOKINGS</title>
  <style>
    .container {
      display: flex;
      flex-wrap: wrap;
    }

    .card {
      margin: 10px;
    }
  </style>
</head>

<body class="bg-light">

  <?php
  require('inc/header.php');

  echo '<div class="col-12 my-5 px-4">
     <h2 class="fw-bold">MY BOOKINGS</h2>
     <div style="font-size: 14px;">
         <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
         <span class="text-secondary"> > </span>
         <a href="#" class="text-secondary text-decoration-none">MY BOOKINGS</a>
     </div>
    </div>';

  if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
  }

  $uid = $_SESSION['uId']; // User ID from session

  // Fetch bookings for the logged-in user
  $booking_res = select(
    "SELECT b.*, g.name, g.price, g.adult, g.children 
        FROM booking_order b 
        INNER JOIN guesthouses g ON b.guesthouse_id = g.id 
        WHERE b.user_id = ? AND b.is_deleted = 0",
    [$uid],
    'i'
  );


  echo '<div class="container">';

  if (mysqli_num_rows($booking_res) > 0) {
    while ($booking_data = mysqli_fetch_assoc($booking_res)) {

      // Get thumbnail of the guesthouse
      $guesthouse_thumb = GUESTHOUSES_IMG_PATH . "thumbnail.jpg";
      $thumb_q = mysqli_query($con, "SELECT * FROM guesthouse_images WHERE guesthouse_id = '{$booking_data['guesthouse_id']}' AND thumb = '1'");

      if (mysqli_num_rows($thumb_q) > 0) {
        $thumb_res = mysqli_fetch_assoc($thumb_q);
        $guesthouse_thumb = GUESTHOUSES_IMG_PATH . $thumb_res['image'];
      }

      // Print guesthouse card
      echo <<<data
              <div class="col-lg-4 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                  <img src="$guesthouse_thumb" class="card-img-top">
                  <div class="card-body">
                    <h5>{$booking_data['name']}</h5>
                    <h6 class="mb-4">\${$booking_data['price']} per night</h6>
                    <div class="guests mb-4">
                      <h6 class="mb-1">Guests</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                        {$booking_data['adult']} Adults
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                        {$booking_data['children']} Children
                      </span>
                    </div>
                    <div class="dates mb-4">
                      <h6 class="mb-1">Booking Dates</h6>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Check-in: {$booking_data['check_in']}
                      </span>
                      <span class="badge rounded-pill bg-light text-dark text-wrap">
                        Check-out: {$booking_data['check_out']}
                      </span>
                    </div>
                    <form method="post" action="process_refund.php">
                      <input type="hidden" name="booking_id" value="{$booking_data['booking_id']}">
                      <input type="hidden" name="booking_price" value="{$booking_data['price']}">
                     
                    </form>
                  </div>
                </div>
              </div>
            data;
    }
  } else {
    echo '<p class="text-center">No bookings found.</p>';
  }

  echo '</div>';
  ?>

  <?php require('inc/footer.php'); ?>

</body>

</html>
