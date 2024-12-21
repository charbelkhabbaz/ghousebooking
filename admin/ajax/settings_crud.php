<?php 

  // Include necessary configuration and functions
  require('../inc/db_config.php');
  require('../inc/essentials.php');
  adminLogin();

  // Fetch general settings from the 'settings' table
  if(isset($_POST['get_general']))
  {
    // Prepare and execute query to retrieve settings
    $q = "SELECT * FROM `settings` WHERE `sr_no`=?";
    $values = [1];
    $res = select($q,$values,"i");
    $data = mysqli_fetch_assoc($res);

    // Encode the data to JSON format and return it
    $json_data = json_encode($data);
    echo $json_data;
  }

  // Update general settings for site title and about
  if(isset($_POST['upd_general']))
  {
    // Sanitize form data
    $frm_data = filteration($_POST);

    // Prepare and execute query to update settings
    $q = "UPDATE `settings` SET `site_title`=?, `site_about`=? WHERE `sr_no`=?";
    $values = [$frm_data['site_title'],$frm_data['site_about'],1];
    $res = update($q,$values,'ssi');
    echo $res;
  }

  // Toggle shutdown status
  if(isset($_POST['upd_shutdown']))
  {
    // Determine new shutdown value (toggle between 0 and 1)
    $frm_data = ($_POST['upd_shutdown']==0) ? 1 : 0;

    // Update the shutdown status in the settings table
    $q = "UPDATE `settings` SET `shutdown`=? WHERE `sr_no`=?";
    $values = [$frm_data,1];
    $res = update($q,$values,'ii');
    echo $res;
  }

  // Fetch contact details from the 'contact_details' table
  if(isset($_POST['get_contacts']))
  {
    // Prepare and execute query to retrieve contact details
    $q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
    $values = [1];
    $res = select($q,$values,"i");
    $data = mysqli_fetch_assoc($res);

    // Encode the data to JSON format and return it
    $json_data = json_encode($data);
    echo $json_data;
  }

  // Update contact details such as address, phone numbers, etc.
  if(isset($_POST['upd_contacts']))
  {
    // Sanitize form data
    $frm_data = filteration($_POST);

    // Prepare and execute query to update contact details
    $q = "UPDATE `contact_details` SET `address`=?,`gmap`=?,`pn1`=?,`pn2`=?,`email`=?,`fb`=?,`insta`=?,`tw`=?,`iframe`=? WHERE `sr_no`=?";
    $values = [$frm_data['address'],$frm_data['gmap'],$frm_data['pn1'],$frm_data['pn2'],$frm_data['email'],$frm_data['fb'],$frm_data['insta'],$frm_data['tw'],$frm_data['iframe'],1];
    $res = update($q,$values,'sssssssssi');
    echo $res;
  }

  // Add a new team member
  if(isset($_POST['add_member']))
  {
    // Sanitize form data
    $frm_data = filteration($_POST);

    // Upload the image for the team member
    $img_r = uploadImage($_FILES['picture'],ABOUT_FOLDER);

    // Check for various image upload errors
    if($img_r == 'inv_img'){
      echo $img_r;
    }
    else if($img_r == 'inv_size'){
      echo $img_r;
    }
    else if($img_r == 'upd_failed'){
      echo $img_r;
    }
    else{
      // Insert the new member into the team_details table
      $q = "INSERT INTO `team_details`(`name`, `picture`) VALUES (?,?)";
      $values = [$frm_data['name'],$img_r];
      $res = insert($q,$values,'ss');
      echo $res;
    }
  }

  // Fetch and display all team members
  if(isset($_POST['get_members']))
  {
    // Retrieve all team members from the database
    $res = selectAll('team_details');

    // Iterate through and display each team member
    while($row = mysqli_fetch_assoc($res))
    {
      // Define the image path for the team member
      $path = ABOUT_IMG_PATH;
      
      // Output the team member's data in HTML format
      echo <<<data
        <div class="col-md-2 mb-3">
          <div class="card bg-dark text-white">
            <img src="$path$row[picture]" class="card-img">
            <div class="card-img-overlay text-end">
              <button type="button" onclick="rem_member($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                <i class="bi bi-trash"></i> Delete
              </button>
            </div>
            <p class="card-text text-center px-3 py-2">$row[name]</p>
          </div>
        </div>
      data;
    }
  }

  // Remove a team member
  if(isset($_POST['rem_member']))
  {
    // Sanitize form data
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_member']];

    // Retrieve the team member's data
    $pre_q = "SELECT * FROM `team_details` WHERE `sr_no`=?";
    $res = select($pre_q,$values,'i');
    $img = mysqli_fetch_assoc($res);

    // Attempt to delete the team member's image and record
    if(deleteImage($img['picture'],ABOUT_FOLDER)){
      $q = "DELETE FROM `team_details` WHERE `sr_no`=?";
      $res = delete($q,$values,'i');
      echo $res;
    }
    else{
      echo 0;
    }

  }

?>
