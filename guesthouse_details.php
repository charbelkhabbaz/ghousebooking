<!DOCTYPE html> 
<html lang="en">

<head>
  <!-- Meta tags for character encoding, compatibility, and viewport settings -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Include external CSS and JS links -->
  <?php require('inc/links.php'); ?>

  <!-- Title of the page dynamically set from the settings -->
  <title><?php echo $settings_r['site_title'] ?> - GUESTHOUSE DETAILS</title>
</head>

<body class="bg-light">

  <!-- Include the header section of the page -->
  <?php require('inc/header.php'); ?>

  <?php
  // Check if the guesthouse ID is provided in the URL, if not, redirect to guesthouses listing page
  if (!isset($_GET['id'])) {
    redirect('guesthouses.php');
  }

  // Sanitize the GET parameters to prevent SQL injection
  $data = filteration($_GET);

  // Fetch guesthouse details based on the ID from the database
  $guesthouse_res = select("SELECT * FROM `guesthouses` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii');

  // If no matching guesthouse is found, redirect to guesthouses listing page
  if (mysqli_num_rows($guesthouse_res) == 0) {
    redirect('guesthouses.php');
  }

  // Fetch the guesthouse data
  $guesthouse_data = mysqli_fetch_assoc($guesthouse_res);
  ?>

  <div class="container">
    <div class="row">

      <!-- Guesthouse name and breadcrumb navigation -->
      <div class="col-12 my-5 mb-4 px-4">
        <h2 class="fw-bold"><?php echo $guesthouse_data['name'] ?></h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
          <span class="text-secondary"> > </span>
          <a href="guesthouses.php" class="text-secondary text-decoration-none">GUESTHOUSES</a>
        </div>
      </div>

      <!-- Image carousel for displaying guesthouse images -->
      <div class="col-lg-7 col-md-12 px-4">
        <div id="guesthouseCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php
            // Default thumbnail image for guesthouse
            $guesthouse_img = GUESTHOUSES_IMG_PATH . "thumbnail.jpg";

            // Query to fetch guesthouse images from the database
            $img_q = mysqli_query($con, "SELECT * FROM `guesthouse_images` 
                WHERE `guesthouse_id`='$guesthouse_data[id]'");

            // If images exist, display them in the carousel
            if (mysqli_num_rows($img_q) > 0) {
              $active_class = 'active';

              while ($img_res = mysqli_fetch_assoc($img_q)) {
                echo "
                    <div class='carousel-item $active_class'>
                      <img src='" . GUESTHOUSES_IMG_PATH . $img_res['image'] . "' class='d-block w-100 rounded'>
                    </div>
                  ";
                $active_class = ''; // Reset the active class after the first image
              }
            } else {
              // If no images, show default image
              echo "<div class='carousel-item active'>
                  <img src='$guesthouse_img' class='d-block w-100'>
                </div>";
            }
            ?>
          </div>
          <!-- Carousel navigation buttons -->
          <button class="carousel-control-prev" type="button" data-bs-target="#guesthouseCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#guesthouseCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>

      <!-- Card displaying guesthouse details -->
      <div class="col-lg-5 col-md-12 px-4">
        <div class="card mb-4 border-0 shadow-sm rounded-3">
          <div class="card-body">
            <?php
            // Display the guesthouse price per night
            echo <<<price
                <h4>$$guesthouse_data[price] per night</h4>
              price;

            // Query to calculate the average rating of the guesthouse
            $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review`
                WHERE `guesthouse_id`='$guesthouse_data[id]' ORDER BY `sr_no` DESC LIMIT 20";

            // Execute the query and fetch the result
            $rating_res = mysqli_query($con, $rating_q);
            $rating_fetch = mysqli_fetch_assoc($rating_res);

            $rating_data = "";

            // Display stars based on the average rating
            if ($rating_fetch['avg_rating'] != NULL) {
              for ($i = 0; $i < $rating_fetch['avg_rating']; $i++) {
                $rating_data .= "<i class='bi bi-star-fill text-warning'></i> ";
              }
            }

            // Display the stars for the rating
            echo <<<rating
                <div class="mb-3">
                  $rating_data
                </div>
              rating;

            // Query to fetch the features of the guesthouse
            $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f 
                INNER JOIN `guesthouse_features` rfea ON f.id = rfea.features_id 
                WHERE rfea.guesthouse_id = '$guesthouse_data[id]'");

            $features_data = "";
            // Display the features as badges
            while ($fea_row = mysqli_fetch_assoc($fea_q)) {
              $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                  $fea_row[name]
                </span>";
            }

            // Display the guesthouse features
            echo <<<features
                <div class="mb-3">
                  <h6 class="mb-1">Features</h6>
                  $features_data
                </div>
              features;

            // Query to fetch the facilities of the guesthouse
            $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f 
                INNER JOIN `guesthouse_facilities` rfac ON f.id = rfac.facilities_id 
                WHERE rfac.guesthouse_id = '$guesthouse_data[id]'");

            $facilities_data = "";
            // Display the facilities as badges
            while ($fac_row = mysqli_fetch_assoc($fac_q)) {
              $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                  $fac_row[name]
                </span>";
            }

            // Display the guesthouse facilities
            echo <<<facilities
                <div class="mb-3">
                  <h6 class="mb-1">Facilities</h6>
                  $facilities_data
                </div>
              facilities;

            // Display the number of adults and children the guesthouse can accommodate
            echo <<<guests
                <div class="mb-3">
                  <h6 class="mb-1">Guests</h6>
                  <span class="badge rounded-pill bg-light text-dark text-wrap">
                    $guesthouse_data[adult] Adults
                  </span>
                  <span class="badge rounded-pill bg-light text-dark text-wrap">
                    $guesthouse_data[children] Children
                  </span>
                </div>
              guests;

            // Display the area of the guesthouse in square feet
            echo <<<area
                <div class="mb-3">
                  <h6 class="mb-1">Area</h6>
                  <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                    $guesthouse_data[area] sq. ft.
                  </span>
                </div>
              area;

            // If the site is not shut down, display the booking button
            if (!$settings_r['shutdown']) {
              $login = 0;
              // Check if the user is logged in
              if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                $login = 1;
              }
              // Display the "Book Now" button
              echo <<<book
                  <button onclick='checkLoginToBook($login,$guesthouse_data[id])' class="btn w-100 text-white custom-bg shadow-none mb-1">Book Now</button>
                book;
            }

            ?>
          </div>
        </div>
      </div>
      <div class="col-12 mt-4 px-4">
  <!-- Description Section -->
  <div class="mb-5">
    <h5>Description</h5>
    <p>
      <!-- Displaying the description of the guesthouse -->
      <?php echo $guesthouse_data['description'] ?>
    </p>
  </div>

  <!-- Reviews & Ratings Section -->
  <div>
    <h5 class="mb-3">Reviews & Ratings</h5>

    <?php
    // SQL query to fetch the reviews and ratings for the guesthouse
    $review_q = "SELECT rr.*,uc.name AS uname, uc.profile, r.name AS rname FROM `rating_review` rr
        INNER JOIN `user_cred` uc ON rr.user_id = uc.id
        INNER JOIN `guesthouses` r ON rr.guesthouse_id = r.id
        WHERE rr.guesthouse_id = '$guesthouse_data[id]'
        ORDER BY `sr_no` DESC LIMIT 15";

    // Executing the query to get reviews
    $review_res = mysqli_query($con, $review_q);
    $img_path = USERS_IMG_PATH;

    // Checking if there are no reviews yet
    if (mysqli_num_rows($review_res) == 0) {
      echo 'No reviews yet!';
    } else {
      // Looping through each review and displaying them
      while ($row = mysqli_fetch_assoc($review_res)) {
        // Generating star icons based on rating
        $stars = "<i class='bi bi-star-fill text-warning'></i> ";
        for ($i = 1; $i < $row['rating']; $i++) {
          $stars .= " <i class='bi bi-star-fill text-warning'></i>";
        }

        // Displaying the review and associated information
        echo <<<reviews
            <div class="mb-4">
              <div class="d-flex align-items-center mb-2">
                <img src="$img_path$row[profile]" class="rounded-circle" loading="lazy" width="30px">
                <h6 class="m-0 ms-2">$row[uname]</h6>
              </div>
              <p class="mb-1">
                $row[review]
              </p>
              <div>
                $stars
              </div>
            </div>
          reviews;
      }
    }
    ?>

  </div>
</div>

</div>
</div>

<!-- Footer Section -->
<?php require('inc/footer.php'); ?>

</body>

</html>
