<?php
  // Including the essential functions and ensuring the admin is logged in
  require('inc/essentials.php'); 
  adminLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Setting character encoding, compatibility for older browsers, and responsiveness for mobile devices -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Title of the page shown in the browser tab -->
  <title>Admin Panel - Carousel</title>
  
  <!-- Including external CSS links (e.g., Bootstrap and custom styles) -->
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <!-- Including the header, typically includes the navigation bar or page header -->
  <?php require('inc/header.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <!-- Main content area with padding and overflow handling -->
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">CAROUSEL</h3>

        <!-- Carousel section card to display the images and add new images -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <!-- Section to manage images, with a button to trigger the modal to add new images -->
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="card-title m-0">Images</h5>
              <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#carousel-s">
                <i class="bi bi-plus-square"></i> Add
              </button>
            </div>

            <!-- Section where the carousel images will be dynamically loaded -->
            <div class="row" id="carousel-data">
              <!-- Carousel images will be inserted here dynamically -->
            </div>

          </div>
        </div>

        <!-- Modal to add a new image to the carousel -->
        <div class="modal fade" id="carousel-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <!-- Form for adding a new carousel image -->
            <form id="carousel_s_form">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Add Image</h5>
                </div>
                <div class="modal-body">
                  <!-- File input for selecting the image -->
                  <div class="mb-3">
                    <label class="form-label fw-bold">Picture</label>
                    <input type="file" name="carousel_picture" id="carousel_picture_inp" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <!-- Cancel button that resets the form and closes the modal -->
                  <button type="button" onclick="carousel_picture.value=''" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                  <!-- Submit button to submit the form and add the image -->
                  <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
  

  <!-- Including external scripts necessary for page functionality -->
  <?php require('inc/scripts.php'); ?>
  <!-- Linking the JavaScript file for managing the carousel -->
  <script src="scripts/carousel.js"></script>

</body>
</html>
