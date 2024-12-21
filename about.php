<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta tags for character encoding, compatibility, and viewport settings -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Link to Swiper CSS for image carousel functionality -->
  <link  rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">

  <!-- Including external PHP file for additional links (e.g., CSS or JS files) -->
  <?php require('inc/links.php'); ?>

  <!-- Dynamic title based on the site settings -->
  <title><?php echo $settings_r['site_title'] ?> - ABOUT</title>

  <style>
    /* Custom style to change the border top color of elements with class "box" */
    .box{
      border-top-color: var(--teal) !important;
    }
  </style>
</head>
<body class="bg-light">

  <!-- Including external PHP file for the header section -->
  <?php require('inc/header.php'); ?>

  <div class="my-5 px-4">
    <!-- Main heading for About Us section -->
    <h2 class="fw-bold h-font text-center">ABOUT US</h2>
    <!-- Horizontal line under the heading -->
    <div class="h-line bg-dark"></div>
    <!-- Short description for About Us section -->
    <p class="text-center mt-3">
      Lorem ipsum dolor sit amet consectetur adipisicing elit. 
      Temporibus incidunt odio quos <br> dolore commodi repudiandae 
      tenetur consequuntur et similique asperiores.
    </p>
  </div>

  <div class="container">
    <div class="row justify-content-between align-items-center">
      <!-- Text content with heading and paragraph about the company -->
      <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
        <h3 class="mb-3">Lorem ipsum dolor sit</h3>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. 
          Omnis minima sapiente aliquam sed officia nostrum fuga?
          Lorem ipsum dolor sit amet consectetur adipisicing elit. 
          Omnis minima sapiente aliquam sed officia nostrum fuga?
        </p>
      </div>
      <!-- Image showing related content -->
      <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
        <img src="images/about/about.jpg" class="w-100">
      </div>
    </div>
  </div>

  <!-- Stats section showing guesthouses, customers, reviews, and staff -->
  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/hotel.svg" width="70px">
          <h4 class="mt-3">100+ GUESTHOUSES</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/customers.svg" width="70px">
          <h4 class="mt-3">200+ CUSTOMERS</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/rating.svg" width="70px">
          <h4 class="mt-3">150+ REVIEWS</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/staff.svg" width="70px">
          <h4 class="mt-3">200+ STAFFS</h4>
        </div>
      </div>
    </div>
  </div>

  <!-- Management Team heading -->
  <h3 class="my-5 fw-bold h-font text-center">MANAGEMENT TEAM</h3>

  <!-- Swiper carousel displaying team members -->
  <div class="container px-4">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper mb-5">
        <?php 
          // Fetching team details from the database and displaying them in the carousel
          $about_r = selectAll('team_details');
          $path=ABOUT_IMG_PATH;
          while($row = mysqli_fetch_assoc($about_r)){
            echo<<<data
              <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                <img src="$path$row[picture]" class="w-100">
                <h5 class="mt-2">$row[name]</h5>
              </div>
            data;
          }
        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>

  <!-- Including external PHP file for the footer section -->
  <?php require('inc/footer.php'); ?>

  <!-- Swiper JS script to initialize the carousel with settings -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".mySwiper", {
      spaceBetween: 40,
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });
  </script>

</body>
</html>
