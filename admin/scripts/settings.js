// Declaring variables to store general and contacts data globally
let general_data, contacts_data;

// Getting form elements for general settings
let general_s_form = document.getElementById('general_s_form');
let site_title_inp = document.getElementById('site_title_inp');
let site_about_inp = document.getElementById('site_about_inp');

// Getting form elements for contacts settings
let contacts_s_form = document.getElementById('contacts_s_form');

// Getting form elements for team settings
let team_s_form = document.getElementById('team_s_form');
let member_name_inp = document.getElementById('member_name_inp');
let member_picture_inp = document.getElementById('member_picture_inp');

// Function to fetch and display general site settings
function get_general() {
  // Elements for displaying site title and about
  let site_title = document.getElementById('site_title');
  let site_about = document.getElementById('site_about');

  // Toggle for shutdown status
  let shutdown_toggle = document.getElementById('shutdown-toggle');

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/settings_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    // Parse and store the response JSON data
    general_data = JSON.parse(this.responseText);

    // Update the page with fetched data
    site_title.innerText = general_data.site_title;
    site_about.innerText = general_data.site_about;

    site_title_inp.value = general_data.site_title;
    site_about_inp.value = general_data.site_about;

    // Set the shutdown toggle state based on the fetched value
    if (general_data.shutdown == 0) {
      shutdown_toggle.checked = false;
      shutdown_toggle.value = 0;
    } else {
      shutdown_toggle.checked = true;
      shutdown_toggle.value = 1;
    }
  };

  xhr.send('get_general'); // Sending the request
}

// Event listener to handle form submission for general settings
general_s_form.addEventListener('submit', function(e) {
  e.preventDefault(); // Preventing the default form submission behavior
  upd_general(site_title_inp.value, site_about_inp.value); // Updating general settings
});

// Function to update general settings
function upd_general(site_title_val, site_about_val) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/settings_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    var myModal = document.getElementById('general-s');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    // Show appropriate alert based on the response
    if (this.responseText == 1) {
      alert('success', 'Changes saved!');
      get_general(); // Reload the general settings after successful update
    } else {
      alert('error', 'No changes made!');
    }
  };

  xhr.send('site_title=' + site_title_val + '&site_about=' + site_about_val + '&upd_general');
}

// Function to update site shutdown status
function upd_shutdown(val) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/settings_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    // Display success message based on the shutdown status update
    if (this.responseText == 1 && general_data.shutdown == 0) {
      alert('success', 'Site has been shutdown!');
    } else {
      alert('success', 'Shutdown mode off!');
    }
    get_general(); // Reload the general settings after the update
  };

  xhr.send('upd_shutdown=' + val); // Sending the shutdown status
}

// Function to fetch and display contact information
function get_contacts() {
  let contacts_p_id = ['address', 'gmap', 'pn1', 'pn2', 'email', 'fb', 'insta', 'tw'];
  let iframe = document.getElementById('iframe');

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/settings_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function() {
    // Parse and store the contacts data
    contacts_data = JSON.parse(this.responseText);
    contacts_data = Object.values(contacts_data);

    // Updating the contact information displayed on the page
    for (i = 0; i < contacts_p_id.length; i++) {
      document.getElementById(contacts_p_id[i]).innerText = contacts_data[i + 1];
    }
    iframe.src = contacts_data[9]; // Setting the iframe source for the map
    contacts_inp(contacts_data); // Update the contact inputs
  };

  xhr.send('get_contacts'); // Sending the request
}
// Function to populate the contact input fields with the provided data
function contacts_inp(data) {
  // Array containing the IDs of the input elements
  let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'tw_inp', 'iframe_inp'];

  // Loop through the input elements and set their value with the corresponding data
  for (i = 0; i < contacts_inp_id.length; i++) {
    document.getElementById(contacts_inp_id[i]).value = data[i + 1];
  }
}

// Event listener for the contacts form submission, preventing default action and updating the contacts
contacts_s_form.addEventListener('submit', function (e) {
  e.preventDefault(); // Preventing form submission
  upd_contacts(); // Updating the contacts information
});

// Function to update contact details based on input field values
function upd_contacts() {
  let index = ['address', 'gmap', 'pn1', 'pn2', 'email', 'fb', 'insta', 'tw', 'iframe']; // Array of data keys
  let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'tw_inp', 'iframe_inp']; // Array of input element IDs

  let data_str = ""; // Initialize the data string for the request

  // Loop through the input fields and append their values to the data string
  for (i = 0; i < index.length; i++) {
    data_str += index[i] + "=" + document.getElementById(contacts_inp_id[i]).value + '&';
  }
  data_str += "upd_contacts"; // Append action type to the data string

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/settings_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    var myModal = document.getElementById('contacts-s');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide(); // Hide the modal after processing the request

    // Check the response and show the appropriate alert message
    if (this.responseText == 1) {
      alert('success', 'Changes saved!');
      get_contacts(); // Reload the contact information after successful update
    } else {
      alert('error', 'No changes made!');
    }
  }

  xhr.send(data_str); // Send the request to the server
}

// Event listener for the team form submission, preventing default action and adding a new team member
team_s_form.addEventListener('submit', function (e) {
  e.preventDefault(); // Preventing form submission
  add_member(); // Adding a new team member
});

// Function to add a new team member
function add_member() {
  let data = new FormData(); // FormData object to handle file and form data
  data.append('name', member_name_inp.value); // Append the member's name
  data.append('picture', member_picture_inp.files[0]); // Append the member's picture (file input)
  data.append('add_member', ''); // Append action type

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/settings_crud.php", true);

  xhr.onload = function () {
    var myModal = document.getElementById('team-s');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide(); // Hide the modal after processing the request

    // Check the response and show the appropriate alert message for different scenarios
    if (this.responseText == 'inv_img') {
      alert('error', 'Only JPG and PNG images are allowed!');
    } else if (this.responseText == 'inv_size') {
      alert('error', 'Image should be less than 2MB!');
    } else if (this.responseText == 'upd_failed') {
      alert('error', 'Image upload failed. Server Down!');
    } else {
      alert('success', 'New member added!');
      member_name_inp.value = ''; // Clear the name input field
      member_picture_inp.value = ''; // Clear the picture input field
      get_members(); // Reload the team members list
    }
  }

  xhr.send(data); // Send the data (including the file) to the server
}

// Function to fetch and display the list of team members
function get_members() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/settings_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    document.getElementById('team-data').innerHTML = this.responseText; // Update the team data section with the response
  }

  xhr.send('get_members'); // Send the request to fetch team members
}

// Function to remove a team member based on their ID
function rem_member(val) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/settings_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    // Check the response and show the appropriate alert message
    if (this.responseText == 1) {
      alert('success', 'Member removed!');
      get_members(); // Reload the team members list after removal
    } else {
      alert('error', 'Server down!');
    }
  }

  xhr.send('rem_member=' + val); // Send the member ID to remove the member
}

// Window onload function to initialize by fetching general settings, contacts, and team members
window.onload = function () {
  get_general(); // Fetch and display general settings
  get_contacts(); // Fetch and display contact information
  get_members(); // Fetch and display team members
}

