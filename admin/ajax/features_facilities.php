<?php 

  // Include database configuration and essential utility functions.
  require('../inc/db_config.php');
  require('../inc/essentials.php');

  // Ensure the admin is logged in.
  adminLogin();

  // Check if a request to add a feature is made.
  if (isset($_POST['add_feature'])) {
    // Sanitize the incoming data from the request.
    $frm_data = filteration($_POST);

    // Insert the feature name into the database.
    $q = "INSERT INTO `features`(`name`) VALUES (?)";
    $values = [$frm_data['name']];
    $res = insert($q, $values, 's');
    echo $res; // Output the result of the insertion.
  }

  // Check if a request to fetch all features is made.
  if (isset($_POST['get_features'])) {
    // Fetch all records from the features table.
    $res = selectAll('features');
    $i = 1; // Counter for row numbers.

    // Iterate through each feature and display it in a table row.
    while ($row = mysqli_fetch_assoc($res)) {
      echo <<<data
        <tr>
          <td>$i</td>
          <td>$row[name]</td>
          <td>
            <button type="button" onclick="rem_feature($row[id])" class="btn btn-danger btn-sm shadow-none">
              <i class="bi bi-trash"></i> Delete
            </button>
          </td>
        </tr>
      data;
      $i++; // Increment the counter.
    }
  }

  // Check if a request to remove a feature is made.
  if (isset($_POST['rem_feature'])) {
    // Sanitize the incoming data from the request.
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_feature']];

    // Check if the feature is associated with any guesthouse.
    $check_q = select('SELECT * FROM `guesthouse_features` WHERE `features_id`=?', [$frm_data['rem_feature']], 'i');

    if (mysqli_num_rows($check_q) == 0) {
      // If not associated, delete the feature from the database.
      $q = "DELETE FROM `features` WHERE `id`=?";
      $res = delete($q, $values, 'i');
      echo $res; // Output the result of the deletion.
    } else {
      echo 'guesthouse_added'; // Output message if the feature is linked to a guesthouse.
    }
  }

  // Check if a request to add a facility is made.
  if (isset($_POST['add_facility'])) {
    // Sanitize the incoming data from the request.
    $frm_data = filteration($_POST);

    // Upload the icon image for the facility.
    $img_r = uploadSVGImage($_FILES['icon'], FACILITIES_FOLDER);

    // Check for upload errors.
    if ($img_r == 'inv_img') {
      echo $img_r;
    } else if ($img_r == 'inv_size') {
      echo $img_r;
    } else if ($img_r == 'upd_failed') {
      echo $img_r;
    } else {
      // Insert the facility details into the database if the upload succeeds.
      $q = "INSERT INTO `facilities`(`icon`,`name`, `description`) VALUES (?,?,?)";
      $values = [$img_r, $frm_data['name'], $frm_data['desc']];
      $res = insert($q, $values, 'sss');
      echo $res; // Output the result of the insertion.
    }
  }

  // Check if a request to fetch all facilities is made.
  if (isset($_POST['get_facilities'])) {
    // Fetch all records from the facilities table.
    $res = selectAll('facilities');
    $i = 1; // Counter for row numbers.
    $path = FACILITIES_IMG_PATH; // Path to the facilities images.

    // Iterate through each facility and display it in a table row.
    while ($row = mysqli_fetch_assoc($res)) {
      echo <<<data
        <tr class='align-middle'>
          <td>$i</td>
          <td><img src="$path$row[icon]" width="100px"></td>
          <td>$row[name]</td>
          <td>$row[description]</td>
          <td>
            <button type="button" onclick="rem_facility($row[id])" class="btn btn-danger btn-sm shadow-none">
              <i class="bi bi-trash"></i> Delete
            </button>
          </td>
        </tr>
      data;
      $i++; // Increment the counter.
    }
  }

  // Check if a request to remove a facility is made.
  if (isset($_POST['rem_facility'])) {
    // Sanitize the incoming data from the request.
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_facility']];

    // Check if the facility is associated with any guesthouse.
    $check_q = select('SELECT * FROM `guesthouse_facilities` WHERE `facilities_id`=?', [$frm_data['rem_facility']], 'i');

    if (mysqli_num_rows($check_q) == 0) {
      // If not associated, fetch the facility details.
      $pre_q = "SELECT * FROM `facilities` WHERE `id`=?";
      $res = select($pre_q, $values, 'i');
      $img = mysqli_fetch_assoc($res);

      // Delete the facility image from the folder and then delete the record.
      if (deleteImage($img['icon'], FACILITIES_FOLDER)) {
        $q = "DELETE FROM `facilities` WHERE `id`=?";
        $res = delete($q, $values, 'i');
        echo $res; // Output the result of the deletion.
      } else {
        echo 0; // Output failure message if the image deletion fails.
      }
    } else {
      echo 'guesthouse_added'; // Output message if the facility is linked to a guesthouse.
    }
  }

?>
