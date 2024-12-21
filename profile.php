<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta tags for character set, compatibility, and viewport settings -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Includes external PHP file for links (e.g., CSS, JS files) -->
  <?php require('inc/links.php'); ?>

  <!-- Page title, dynamically fetching site title from settings -->
  <title><?php echo $settings_r['site_title'] ?> - PROFILE</title>
</head>
<body class="bg-light">

  <!-- Includes header file (e.g., navigation bar) -->
  <?php 
    require('inc/header.php'); 

    // Check if the user is logged in, if not redirect to index page
    if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
      redirect('index.php');
    }

    // SQL query to check if the user exists
    $u_exist = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],'s');

    // If the user doesn't exist, redirect to index page
    if(mysqli_num_rows($u_exist)==0){
      redirect('index.php');
    }

    // Fetch the user's data
    $u_fetch = mysqli_fetch_assoc($u_exist);
  ?>


  <!-- Main content container -->
  <div class="container">
    <div class="row">

      <!-- Section displaying the page title and breadcrumb -->
      <div class="col-12 my-5 px-4">
        <h2 class="fw-bold">PROFILE</h2>
        <div style="font-size: 14px;">
          <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
          <span class="text-secondary"> > </span>
          <a href="#" class="text-secondary text-decoration-none">PROFILE</a>
        </div>
      </div>

      <!-- Section for updating basic user information -->
      <div class="col-12 mb-5 px-4">
        <div class="bg-white p-3 p-md-4 rounded shadow-sm">
          <form id="info-form">
            <h5 class="mb-3 fw-bold">Basic Information</h5>
            <div class="row">
              <!-- Form fields for name, phone number, date of birth, pincode, and address -->
              <div class="col-md-4 mb-3">
                <label class="form-label">Name</label>
                <input name="name" type="text" value="<?php echo $u_fetch['name'] ?>" class="form-control shadow-none" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Phone Number</label>
                <input name="phonenum" type="number" value="<?php echo $u_fetch['phonenum'] ?>" class="form-control shadow-none" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Date of birth</label>
                <input name="dob" type="date" value="<?php echo $u_fetch['dob'] ?>" class="form-control shadow-none" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Pincode</label>
                <input name="pincode" type="number" value="<?php echo $u_fetch['pincode'] ?>" class="form-control shadow-none" required>
              </div>
              <div class="col-md-8 mb-4">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $u_fetch['address'] ?></textarea>
              </div>
            </div>
            <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
          </form>
        </div>
      </div>

      <!-- Section for updating the user profile picture -->
      <div class="col-md-4 mb-5 px-4">
        <div class="bg-white p-3 p-md-4 rounded shadow-sm">
          <form id="profile-form">
            <h5 class="mb-3 fw-bold">Picture</h5>
            <!-- Display current profile picture -->
            <img src="<?php echo USERS_IMG_PATH.$u_fetch['profile'] ?>" class="rounded-circle img-fluid mb-3">

            <!-- Form field for uploading a new profile picture -->
            <label class="form-label">New Picture</label>
            <input name="profile" type="file" accept=".jpg, .jpeg, .png, .webp" class="mb-4 form-control shadow-none" required>

            <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
          </form>
        </div>
      </div>

      <!-- Section for changing the user's password -->
      <div class="col-md-8 mb-5 px-4">
        <div class="bg-white p-3 p-md-4 rounded shadow-sm">
          <form id="pass-form">
            <h5 class="mb-3 fw-bold">Change Password</h5>
            <div class="row">
              <!-- Form fields for new password and confirming the password -->
              <div class="col-md-6 mb-3">
                <label class="form-label">New Password</label>
                <input name="new_pass" type="password" class="form-control shadow-none" required>
              </div>
              <div class="col-md-6 mb-4">
                <label class="form-label">Confirm Password</label>
                <input name="confirm_pass" type="password" class="form-control shadow-none" required>
              </div>
            </div>
            <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
          </form>
        </div>
      </div>


    </div>
  </div>


  <?php require('inc/footer.php'); ?> <!-- Include footer.php file to render the footer section -->

<script>

    // Event listener for the information form submission
    let info_form = document.getElementById('info-form');

    info_form.addEventListener('submit',function(e){
      e.preventDefault(); // Prevent default form submission behavior

      let data = new FormData(); // Create a new FormData object to hold form data
      data.append('info_form',''); // Append a custom identifier to the data
      data.append('name',info_form.elements['name'].value); // Append form field values to the data
      data.append('phonenum',info_form.elements['phonenum'].value);
      data.append('address',info_form.elements['address'].value);
      data.append('pincode',info_form.elements['pincode'].value);
      data.append('dob',info_form.elements['dob'].value);

      let xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
      xhr.open("POST","ajax/profile.php",true); // Send a POST request to 'profile.php'

      // Callback function to handle server response
      xhr.onload = function(){
        if(this.responseText == 'phone_already'){ // If phone number already registered
          alert('error',"Phone number is already registered!"); // Show error alert
        }
        else if(this.responseText == 0){ // If no changes were made
          alert('error',"No Changes Made!"); // Show alert indicating no changes
        }
        else{
          alert('success','Changes saved!'); // Show success alert for saved changes
        }
      }

      xhr.send(data); // Send the form data to the server
    });

    
    // Event listener for the profile form submission
    let profile_form = document.getElementById('profile-form');

    profile_form.addEventListener('submit',function(e){
      e.preventDefault(); // Prevent default form submission behavior

      let data = new FormData(); // Create a new FormData object to hold form data
      data.append('profile_form',''); // Append a custom identifier to the data
      data.append('profile',profile_form.elements['profile'].files[0]); // Append the selected file

      let xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
      xhr.open("POST","ajax/profile.php",true); // Send a POST request to 'profile.php'

      // Callback function to handle server response
      xhr.onload = function()
      {
        if(this.responseText == 'inv_img'){ // If the uploaded image type is invalid
          alert('error',"Only JPG, WEBP & PNG images are allowed!"); // Show error alert
        }
        else if(this.responseText == 'upd_failed'){ // If image upload failed
          alert('error',"Image upload failed!"); // Show error alert
        }
        else if(this.responseText == 0){ // If update failed
          alert('error',"Updation failed!"); // Show error alert
        }
        else{
          window.location.href=window.location.pathname; // Reload the page after successful update
        }
      }

      xhr.send(data); // Send the form data to the server
    });


    // Event listener for the password change form submission
    let pass_form = document.getElementById('pass-form');

    pass_form.addEventListener('submit',function(e){
      e.preventDefault(); // Prevent default form submission behavior

      let new_pass = pass_form.elements['new_pass'].value; // Get the new password value
      let confirm_pass = pass_form.elements['confirm_pass'].value; // Get the confirm password value

      // Check if the new password and confirm password match
      if(new_pass!=confirm_pass){
        alert('error','Password do not match!'); // Show error alert if passwords don't match
        return false; // Prevent further execution if passwords don't match
      }

      let data = new FormData(); // Create a new FormData object to hold form data
      data.append('pass_form',''); // Append a custom identifier to the data
      data.append('new_pass',new_pass); // Append new password value to the data
      data.append('confirm_pass',confirm_pass); // Append confirm password value to the data

      let xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
      xhr.open("POST","ajax/profile.php",true); // Send a POST request to 'profile.php'

      // Callback function to handle server response
      xhr.onload = function()
      {
        if(this.responseText == 'mismatch'){ // If the passwords don't match
          alert('error',"Password do not match!"); // Show error alert
        }
        else if(this.responseText == 0){ // If the update failed
          alert('error',"Updation failed!"); // Show error alert
        }
        else{
          alert('success','Changes saved!'); // Show success alert if changes are saved
          pass_form.reset(); // Reset the password form after successful update
        }
      }

      xhr.send(data); // Send the form data to the server
    });

</script> <!-- End of JavaScript code for handling form submissions -->

</body> <!-- Closing the body tag -->
</html> <!-- Closing the HTML tag -->
