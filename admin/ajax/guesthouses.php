<?php

// Including external files for database configuration and essential functions
require('../inc/db_config.php');
require('../inc/essentials.php');

// Calling adminLogin function to ensure only authorized access
adminLogin();

// Check if the 'add_guesthouse' form is submitted
if (isset($_POST['add_guesthouse'])) {
  // Decoding and sanitizing input data for features and facilities
  $features = filteration(json_decode($_POST['features']));
  $facilities = filteration(json_decode($_POST['facilities']));

  // Sanitize the remaining form data
  $frm_data = filteration($_POST);
  $flag = 0; // Flag to track success or failure

  // Query to insert guesthouse data into the 'guesthouses' table
  $q1 = "INSERT INTO guesthouses (name, area, price, quantity, adult, children, description) VALUES (?,?,?,?,?,?,?)";
  $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc']];

  // Insert the data into the database
  if (insert($q1, $values, 'siiiiis')) {
    $flag = 1; // Set flag to 1 if insert was successful
  }

  // Get the last inserted guesthouse ID
  $guesthouse_id = mysqli_insert_id($con);

  // Query to insert guesthouse facilities into the 'guesthouse_facilities' table
  $q2 = "INSERT INTO guesthouse_facilities(guesthouse_id, facilities_id) VALUES (?,?)";
  if ($stmt = mysqli_prepare($con, $q2)) {
    foreach ($facilities as $f) {
      mysqli_stmt_bind_param($stmt, 'ii', $guesthouse_id, $f);
      mysqli_stmt_execute($stmt); // Execute each facility insert
    }
    mysqli_stmt_close($stmt);
  } else {
    $flag = 0;
    die('query cannot be prepared - insert'); // If query preparation fails, exit with an error
  }

  // Query to insert guesthouse features into the 'guesthouse_features' table
  $q3 = "INSERT INTO guesthouse_features(guesthouse_id, features_id) VALUES (?,?)";
  if ($stmt = mysqli_prepare($con, $q3)) {
    foreach ($features as $f) {
      mysqli_stmt_bind_param($stmt, 'ii', $guesthouse_id, $f);
      mysqli_stmt_execute($stmt); // Execute each feature insert
    }
    mysqli_stmt_close($stmt);
  } else {
    $flag = 0;
    die('query cannot be prepared - insert'); // If query preparation fails, exit with an error
  }

  // If all inserts were successful, return 1, else return 0
  if ($flag) {
    echo 1;
  } else {
    echo 0;
  }
}

// Check if the 'get_all_guesthouses' request is made
if (isset($_POST['get_all_guesthouses'])) {
  // Query to get all guesthouses that are not removed (removed = 0)
  $res = select("SELECT * FROM guesthouses WHERE removed=?", [0], 'i');
  $i = 1; // Counter for the row number

  $data = ""; // Variable to store the output HTML

  // Loop through all the guesthouses and build the HTML output
  while ($row = mysqli_fetch_assoc($res)) {
    // Check the guesthouse's status and create the appropriate button for status toggle
    if ($row['status'] == 1) {
      $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
    } else {
      $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
    }

    // Construct the table row for each guesthouse
    $data .= "
        <tr class='align-middle'>
          <td>$i</td>
          <td>$row[name]</td>
          <td>$row[area] sq. ft.</td>
          <td>
            <span class='badge rounded-pill bg-light text-dark'>
              Adult: $row[adult]
            </span><br>
            <span class='badge rounded-pill bg-light text-dark'>
              Children: $row[children]
            </span>
          </td>
          <td>$$row[price]</td>
          <td>$row[quantity]</td>
          <td>$status</td>
          <td>
            <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-guesthouse'>
              <i class='bi bi-pencil-square'></i> 
            </button>
            <button type='button' onclick=\"guesthouse_images($row[id],'$row[name]')\" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#guesthouse-images'>
              <i class='bi bi-images'></i> 
            </button>
            <button type='button' onclick='remove_guesthouse($row[id])' class='btn btn-danger shadow-none btn-sm'>
              <i class='bi bi-trash'></i> 
            </button>
          </td>
        </tr>
      ";
    $i++; // Increment row number
  }

  echo $data;
}

// Check if 'get_guesthouse' request is made
if (isset($_POST['get_guesthouse'])) {
  $frm_data = filteration($_POST);

  $res1 = select("SELECT * FROM guesthouses WHERE id=?", [$frm_data['get_guesthouse']], 'i');
  $res2 = select("SELECT * FROM guesthouse_features WHERE guesthouse_id=?", [$frm_data['get_guesthouse']], 'i');
  $res3 = select("SELECT * FROM guesthouse_facilities WHERE guesthouse_id=?", [$frm_data['get_guesthouse']], 'i');

  $guesthousedata = mysqli_fetch_assoc($res1);
  $features = [];
  $facilities = [];

  if (mysqli_num_rows($res2) > 0) {
    while ($row = mysqli_fetch_assoc($res2)) {
      array_push($features, $row['features_id']);
    }
  }

  if (mysqli_num_rows($res3) > 0) {
    while ($row = mysqli_fetch_assoc($res3)) {
      array_push($facilities, $row['facilities_id']);
    }
  }

  $data = ["guesthousedata" => $guesthousedata, "features" => $features, "facilities" => $facilities];

  $data = json_encode($data);

  echo $data;
}

// Check if 'edit_guesthouse' request is made
if (isset($_POST['edit_guesthouse'])) {
  $features = filteration(json_decode($_POST['features']));
  $facilities = filteration(json_decode($_POST['facilities']));

  $frm_data = filteration($_POST);
  $flag = 0;

  // Password-related code (example variable for password handling)
  $password = isset($_POST['password']) ? $_POST['password'] : null;

  $q1 = "UPDATE guesthouses SET name=?,area=?,price=?,quantity=?,
      adult=?,children=?,description=? WHERE id=?";
  $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc'], $frm_data['guesthouse_id']];

  if (update($q1, $values, 'siiiiisi')) {
    $flag = 1;
  }

  // Deleting old features and facilities for this guesthouse
  $del_features = delete("DELETE FROM guesthouse_features WHERE guesthouse_id=?", [$frm_data['guesthouse_id']], 'i');
  $del_facilities = delete("DELETE FROM guesthouse_facilities WHERE guesthouse_id=?", [$frm_data['guesthouse_id']], 'i');

  if (!($del_facilities && $del_features)) {
    $flag = 0;
  }

  // Inserting new facilities for this guesthouse
  $q2 = "INSERT INTO guesthouse_facilities(guesthouse_id, facilities_id) VALUES (?,?)";
  if ($stmt = mysqli_prepare($con, $q2)) {
    foreach ($facilities as $f) {
      mysqli_stmt_bind_param($stmt, 'ii', $frm_data['guesthouse_id'], $f);
      mysqli_stmt_execute($stmt);
    }
    $flag = 1;
    mysqli_stmt_close($stmt);
  } else {
    $flag = 0;
    die('query cannot be prepared - insert');
  }

  // Inserting new features for this guesthouse
  $q3 = "INSERT INTO guesthouse_features(guesthouse_id, features_id) VALUES (?,?)";
  if ($stmt = mysqli_prepare($con, $q3)) {
    foreach ($features as $f) {
      mysqli_stmt_bind_param($stmt, 'ii', $frm_data['guesthouse_id'], $f);
      mysqli_stmt_execute($stmt);
    }
    $flag = 1;
    mysqli_stmt_close($stmt);
  } else {
    $flag = 0;
    die('query cannot be prepared - insert');
  }

  if ($flag) {
    echo 1;
  } else {
    echo 0;
  }
}

// Check if 'toggle_status' request is made
if (isset($_POST['toggle_status'])) {
  $frm_data = filteration($_POST);

  // Update the status of the guesthouse
  $q = "UPDATE guesthouses SET status=? WHERE id=?";
  $v = [$frm_data['value'], $frm_data['toggle_status']];

  if (update($q, $v, 'ii')) {
    echo 1; // Status update success
  } else {
    echo 0; // Status update failure
  }
}

// Check if 'add_image' request is made
if (isset($_POST['add_image'])) {
  $frm_data = filteration($_POST);

  // Upload the image for the guesthouse
  $img_r = uploadImage($_FILES['image'], GUESTHOUSES_FOLDER);

  // Handle possible image upload errors
  if ($img_r == 'inv_img') {
    echo $img_r; // Invalid image type
  } else if ($img_r == 'inv_size') {
    echo $img_r; // Invalid image size
  } else if ($img_r == 'upd_failed') {
    echo $img_r; // Image upload failed
  } else {
    // Insert image into the database
    $q = "INSERT INTO guesthouse_images(guesthouse_id, image) VALUES (?,?)";
    $values = [$frm_data['guesthouse_id'], $img_r];
    $res = insert($q, $values, 'is');
    echo $res; // Output result of image insert
  }
}

// Check if 'get_guesthouse_images' request is made
if (isset($_POST['get_guesthouse_images'])) {
  $frm_data = filteration($_POST);
  $res = select("SELECT * FROM guesthouse_images WHERE guesthouse_id=?", [$frm_data['get_guesthouse_images']], 'i');

  $path = GUESTHOUSES_IMG_PATH;

  // Loop through each image of the guesthouse
  while ($row = mysqli_fetch_assoc($res)) {
    // Check if the image is marked as a thumbnail
    if ($row['thumb'] == 1) {
      $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>"; // Thumbnail already set
    } else {
      // Button to set the image as a thumbnail
      $thumb_btn = "<button onclick='thumb_image($row[sr_no],$row[guesthouse_id])' class='btn btn-secondary shadow-none'>
          <i class='bi bi-check-lg'></i>
        </button>";
    }

    // Output the image and the thumbnail button
    echo <<<data
        <tr class='align-middle'>
          <td><img src='$path$row[image]' class='img-fluid'></td>
          <td>$thumb_btn</td>
          <td>
            <button onclick='rem_image($row[sr_no],$row[guesthouse_id])' class='btn btn-danger shadow-none'>
              <i class='bi bi-trash'></i>
            </button>
          </td>
        </tr>
      data;
  }
}

// Check if 'rem_image' request is made
if (isset($_POST['rem_image'])) {
  $frm_data = filteration($_POST);

  $values = [$frm_data['image_id'], $frm_data['guesthouse_id']];

  // Retrieve the image data
  $pre_q = "SELECT * FROM guesthouse_images WHERE sr_no=? AND guesthouse_id=?";
  $res = select($pre_q, $values, 'ii');
  $img = mysqli_fetch_assoc($res);

  // Delete the image from the folder
  if (deleteImage($img['image'], GUESTHOUSES_FOLDER)) {
    // Delete the image from the database
    $q = "DELETE FROM guesthouse_images WHERE sr_no=? AND guesthouse_id=?";
    $res = delete($q, $values, 'ii');
    echo $res; // Output result of image deletion
  } else {
    echo 0; // Image deletion failed
  }
}

// Check if 'thumb_image' request is made
if (isset($_POST['thumb_image'])) {
  $frm_data = filteration($_POST);

  // Set all images to non-thumbnail first
  $pre_q = "UPDATE guesthouse_images SET thumb=? WHERE guesthouse_id=?";
  $pre_v = [0, $frm_data['guesthouse_id']];
  $pre_res = update($pre_q, $pre_v, 'ii');

  // Set the specified image as the thumbnail
  $q = "UPDATE guesthouse_images SET thumb=? WHERE sr_no=? AND guesthouse_id=?";
  $v = [1, $frm_data['image_id'], $frm_data['guesthouse_id']];
  $res = update($q, $v, 'iii');

  echo $res; // Output result of thumbnail update
}

// Check if 'remove_guesthouse' request is made
if (isset($_POST['remove_guesthouse'])) {
  $frm_data = filteration($_POST);

  // Retrieve all images for the guesthouse
  $res1 = select("SELECT * FROM guesthouse_images WHERE guesthouse_id=?", [$frm_data['guesthouse_id']], 'i');

  // Delete each image from the folder
  while ($row = mysqli_fetch_assoc($res1)) {
    deleteImage($row['image'], GUESTHOUSES_FOLDER);
  }

  // Delete the guesthouse images, features, and facilities
  $res2 = delete("DELETE FROM guesthouse_images WHERE guesthouse_id=?", [$frm_data['guesthouse_id']], 'i');
  $res3 = delete("DELETE FROM guesthouse_features WHERE guesthouse_id=?", [$frm_data['guesthouse_id']], 'i');
  $res4 = delete("DELETE FROM guesthouse_facilities WHERE guesthouse_id=?", [$frm_data['guesthouse_id']], 'i');
  
  // Mark the guesthouse as removed
  $res5 = update("UPDATE guesthouses SET removed=? WHERE id=?", [1, $frm_data['guesthouse_id']], 'ii');

  if ($res2 || $res3 || $res4 || $res5) {
    echo 1; // Success in removing guesthouse
  } else {
    echo 0; // Failure in removing guesthouse
  }
}