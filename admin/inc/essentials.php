<?php 

  // Frontend purpose data: Define image paths for various sections of the website
  define('SITE_URL','http://127.0.0.1/ghousebooking/'); // Base URL of the site
  define('ABOUT_IMG_PATH',SITE_URL.'images/about/'); // Path for about section images
  define('CAROUSEL_IMG_PATH',SITE_URL.'images/carousel/'); // Path for carousel images
  define('FACILITIES_IMG_PATH',SITE_URL.'images/facilities/'); // Path for facilities images
  define('GUESTHOUSES_IMG_PATH',SITE_URL.'images/guesthouses/'); // Path for guesthouses images
  define('USERS_IMG_PATH',SITE_URL.'images/users/'); // Path for users images


  // Backend upload process: Define file paths for backend image upload
  define('UPLOAD_IMAGE_PATH',$_SERVER['DOCUMENT_ROOT'].'/ghousebooking/images/'); // Upload path for images on server
  define('ABOUT_FOLDER','about/'); // Folder for about section images
  define('CAROUSEL_FOLDER','carousel/'); // Folder for carousel images
  define('FACILITIES_FOLDER','facilities/'); // Folder for facilities images
  define('GUESTHOUSES_FOLDER','guesthouses/'); // Folder for guesthouses images
  define('USERS_FOLDER','users/'); // Folder for user images

  
  // Function to check if the admin is logged in
  function adminLogin()
  {
    session_start(); // Start session to check if admin is logged in
    if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)){
      echo"<script>
        window.location.href='index.php'; // Redirect to login page if not logged in
      </script>";
      exit;
    }
  }

  // Function to redirect to a specified URL
  function redirect($url){
    echo"<script>
      window.location.href='$url'; // Redirect to the given URL
    </script>";
    exit;
  }

  // Function to display an alert message based on success or failure
  function alert($type,$msg){    
    $bs_class = ($type == "success") ? "alert-success" : "alert-danger"; // Determine alert type (success or danger)

    echo <<<alert
      <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
        <strong class="me-3">$msg</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    alert;
  }

  // Function to upload an image with validation checks for mime type and size
  function uploadImage($image,$folder)
  {
    $valid_mime = ['image/jpeg','image/png','image/webp']; // Allowed mime types
    $img_mime = $image['type']; // Get mime type of uploaded image

    if(!in_array($img_mime,$valid_mime)){
      return 'inv_img'; // Return error if mime type is not valid
    }
    else if(($image['size']/(1024*1024))>2){
      return 'inv_size'; // Return error if image size is greater than 2MB
    }
    else{
      $ext = pathinfo($image['name'],PATHINFO_EXTENSION); // Get file extension
      $rname = 'IMG_'.random_int(11111,99999).".$ext"; // Generate a random filename

      $img_path = UPLOAD_IMAGE_PATH.$folder.$rname; // Set the path where the image will be uploaded
      if(move_uploaded_file($image['tmp_name'],$img_path)){ // Upload the image
        return $rname; // Return the name of the uploaded image
      }
      else{
        return 'upd_failed'; // Return error if upload failed
      }
    }
  }

  // Function to delete an image from the server
  function deleteImage($image, $folder)
  {
    if(unlink(UPLOAD_IMAGE_PATH.$folder.$image)){ // Try to delete the file
      return true; // Return true if deletion was successful
    }
    else{
      return false; // Return false if deletion failed
    }
  }

  // Function to upload an SVG image with validation checks
  function uploadSVGImage($image,$folder)
  {
    $valid_mime = ['image/svg+xml']; // Allowed mime type for SVG
    $img_mime = $image['type']; // Get mime type of uploaded image

    if(!in_array($img_mime,$valid_mime)){
      return 'inv_img'; // Return error if mime type is not valid
    }
    else if(($image['size']/(1024*1024))>1){
      return 'inv_size'; // Return error if image size is greater than 1MB
    }
    else{
      $ext = pathinfo($image['name'],PATHINFO_EXTENSION); // Get file extension
      $rname = 'IMG_'.random_int(11111,99999).".$ext"; // Generate a random filename

      $img_path = UPLOAD_IMAGE_PATH.$folder.$rname; // Set the path where the image will be uploaded
      if(move_uploaded_file($image['tmp_name'],$img_path)){ // Upload the image
        return $rname; // Return the name of the uploaded image
      }
      else{
        return 'upd_failed'; // Return error if upload failed
      }
    }
  }

  // Function to upload a user image with additional checks for file format
  function uploadUserImage($image)
  {
    $valid_mime = ['image/jpeg','image/png','image/webp']; // Allowed mime types
    $img_mime = $image['type']; // Get mime type of uploaded image

    if(!in_array($img_mime,$valid_mime)){
      return 'inv_img'; // Return error if mime type is not valid
    }
    else
    {
      $ext = pathinfo($image['name'],PATHINFO_EXTENSION); // Get file extension
      $rname = 'IMG_'.random_int(11111,99999).".jpeg"; // Generate a random filename

      $img_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.$rname; // Set the path where the image will be uploaded

      // Handle different image formats (PNG, WEBP, JPEG)
      if($ext == 'png' || $ext == 'PNG') {
        $img = imagecreatefrompng($image['tmp_name']);
      }
      else if($ext == 'webp' || $ext == 'WEBP') {
        $img = imagecreatefromwebp($image['tmp_name']);
      }
      else{
        $img = imagecreatefromjpeg($image['tmp_name']);
      }

      if(imagejpeg($img,$img_path,75)){ // Save the image as JPEG
        return $rname; // Return the name of the uploaded image
      }
      else{
        return 'upd_failed'; // Return error if upload failed
      }
    }
  }

?>
