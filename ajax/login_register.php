<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
// Removed SendGrid dependency
session_start();

date_default_timezone_set("Asia/Kolkata");

// Register user (no email confirmation)
if (isset($_POST['register'])) {

  $data = filteration($_POST);

  // Match password and confirm password fields
  if ($data['pass'] != $data['cpass']) {
    echo 'pass_mismatch';
    exit;
  }

  // Check if the user already exists (email or phone)
  $u_exist = select(
    "SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1",
    [$data['email'], $data['phonenum']],
    "ss"
  );

  if (mysqli_num_rows($u_exist) != 0) {
    $u_exist_fetch = mysqli_fetch_assoc($u_exist);
    echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
    exit;
  }

  // Upload user image to server
  $img = uploadUserImage($_FILES['profile']);

  if ($img == 'inv_img') {
    echo 'inv_img';
    exit;
  } else if ($img == 'upd_failed') {
    echo 'upd_failed';
    exit;
  }

  // Immediately register the user without email confirmation
  $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

  // Prepare SQL query
  $query = "INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `pincode`, `dob`,
          `profile`, `password`, `is_verified`) VALUES (?,?,?,?,?,?,?,?,?)";

  // Prepare the values for insertion
  $values = [
    $data['name'],
    $data['email'],
    $data['address'],
    $data['phonenum'],
    $data['pincode'],
    $data['dob'],
    $img,
    $enc_pass,
    0
  ]; // Set `is_verified` to 1 directly (skip email confirmation)


  // Attempt to insert the user into the database
  if (insert($query, $values, 'sssssssss')) {

    $_SESSION['email'] = $data['email'];
    echo "Done";
  } else {
    // If insertion fails, print error
    echo 'Failed to insert: ' . mysqli_error($con); // Output the error message from MySQL
  }
}

// User login
if (isset($_POST['login'])) {
  $data = filteration($_POST);

  $u_exist = select(
    "SELECT * FROM `user_cred` WHERE `email`=? OR `phonenum`=? LIMIT 1",
    [$data['email_mob'], $data['email_mob']],
    "ss"
  );

  if (mysqli_num_rows($u_exist) == 0) {
    echo 'inv_email_mob'; // Invalid email or phone number
  } else {
    $u_fetch = mysqli_fetch_assoc($u_exist);

    // Check if the user is verified
    if ($u_fetch['is_verified'] == 0) {
      echo 'not_verified'; // Not verified
    } else if ($u_fetch['status'] == 0) {
      echo 'inactive'; // Inactive account
    } else {
      // Verify password
      if (!password_verify($data['pass'], $u_fetch['password'])) {
        echo 'invalid_pass'; // Invalid password
      } else {
        // Successful login
        session_start();
        $_SESSION['login'] = true;
        $_SESSION['uId'] = $u_fetch['id'];
        $_SESSION['uName'] = $u_fetch['name'];
        $_SESSION['uPic'] = $u_fetch['profile'];
        $_SESSION['uPhone'] = $u_fetch['phonenum'];
        $_SESSION['email'] = $u_fetch['email'];

        // Store user's email in the session
        echo 1;
      }
    }
  }
}

// Forgot Password (no email confirmation)
if (isset($_POST['forgot_pass'])) {
  $data = filteration($_POST);

  $u_exist = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1", [$data['email']], "s");

  if (mysqli_num_rows($u_exist) == 0) {
    echo 'inv_email'; // Invalid email
  } else {
    $u_fetch = mysqli_fetch_assoc($u_exist);

    // Skip email verification (account is verified)
    if ($u_fetch['is_verified'] == 0) {
      echo 'not_verified';
    } else if ($u_fetch['status'] == 0) {
      echo 'inactive';
    } else {
      // Generate reset token and update database (no email sending)
      $token = bin2hex(random_bytes(16));
      $date = date("Y-m-d");

      $query = mysqli_query($con, "UPDATE `user_cred` SET `token`='$token', `t_expire`='$date' 
            WHERE `id`='$u_fetch[id]'");

      if ($query) {
        echo 1; // Reset token successfully updated
      } else {
        echo 'upd_failed'; // Failed to update token
      }
    }
  }
}

// Recover user (password reset)
if (isset($_POST['recover_user'])) {
  $data = filteration($_POST);

  $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

  $query = "UPDATE `user_cred` SET `password`=?, `token`=?, `t_expire`=? 
              WHERE `email`=? AND `token`=?";

  $values = [$enc_pass, null, null, $data['email'], $data['token']];

  if (update($query, $values, 'sssss')) {
    echo 1; // Password updated successfully
  } else {
    echo 'failed'; // Failed to update password
  }
}
