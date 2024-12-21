<?php 

  // Include database configuration and essential functions. Ensure these files are secure and not publicly accessible.
  require('../inc/db_config.php');
  require('../inc/essentials.php');
  
  // Function to check if admin is logged in. Verify this method is robust against session hijacking.
  adminLogin();

  if (isset($_POST['add_image'])) {
    // Attempt to upload the image and handle the result.
    $img_r = uploadImage($_FILES['picture'], CAROUSEL_FOLDER);

    // Check for different failure scenarios from the upload function.
    if ($img_r == 'inv_img') { 
      echo $img_r; 
    } else if ($img_r == 'inv_size') {
      echo $img_r;
    } else if ($img_r == 'upd_failed') {
      echo $img_r;
    } else {
      // Insert image into the carousel database table.
      $q = "INSERT INTO `carousel`(`image`) VALUES (?)";
      $values = [$img_r];
      $res = insert($q, $values, 's'); // Ensure the insert function handles SQL injection protection.
      echo $res;
    }
  }

  if (isset($_POST['get_carousel'])) {
    // Retrieve all images in the carousel and display them.
    $res = selectAll('carousel');

    while ($row = mysqli_fetch_assoc($res)) {
      $path = CAROUSEL_IMG_PATH;
      // Sanitize dynamic variables used in HTML to prevent XSS vulnerabilities.
      echo <<<data
        <div class="col-md-4 mb-3">
          <div class="card bg-dark text-white">
            <img src="$path$row[image]" class="card-img">
            <div class="card-img-overlay text-end">
              <button type="button" onclick="rem_image($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                <i class="bi bi-trash"></i> Delete
              </button>
            </div>
          </div>
        </div>
      data;
    }
  }

  if (isset($_POST['rem_image'])) {
    // Sanitize the input data before processing.
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_image']];

    // Fetch the image to be deleted from the database.
    $pre_q = "SELECT * FROM `carousel` WHERE `sr_no`=?";
    $res = select($pre_q, $values, 'i');
    $img = mysqli_fetch_assoc($res);

    // Delete the image file and the database entry if the file deletion is successful.
    if (deleteImage($img['image'], CAROUSEL_FOLDER)) {
      $q = "DELETE FROM `carousel` WHERE `sr_no`=?";
      $res = delete($q, $values, 'i'); // Verify the delete function handles SQL injection protection.
      echo $res;
    } else {
      echo 0; // Inform the user if the deletion fails.
    }
  }

?>
