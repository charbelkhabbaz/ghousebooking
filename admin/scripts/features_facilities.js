let feature_s_form = document.getElementById('feature_s_form'); // Selects the form element for adding a feature
let facility_s_form = document.getElementById('facility_s_form'); // Selects the form element for adding a facility

// Event listener to handle the form submission for adding a feature
feature_s_form.addEventListener('submit', function(e){
  e.preventDefault(); // Prevents the default form submission behavior
  add_feature(); // Calls the function to add the feature
});

// Function to add a new feature by sending data via XMLHttpRequest
function add_feature() {
  let data = new FormData(); // Creates a new FormData object to hold the data
  data.append('name', feature_s_form.elements['feature_name'].value); // Adds the feature name to the FormData
  data.append('add_feature', ''); // Indicates that this request is for adding a feature

  let xhr = new XMLHttpRequest(); // Creates a new XMLHttpRequest object
  xhr.open("POST", "ajax/features_facilities.php", true); // Opens a POST request to the server

  // Defines the behavior when the request is loaded (completed)
  xhr.onload = function(){
    var myModal = document.getElementById('feature-s'); // Gets the modal element
    var modal = bootstrap.Modal.getInstance(myModal); // Gets the instance of the modal
    modal.hide(); // Hides the modal once the request is complete

    // Checks the response from the server and displays appropriate alerts
    if(this.responseText == 1) {
      alert('success', 'New feature added!'); // Alerts success if the feature was added
      feature_s_form.elements['feature_name'].value = ''; // Clears the feature name input
      get_features(); // Refreshes the list of features
    }
    else {
      alert('error', 'Server Down!'); // Alerts error if the server failed to add the feature
    }
  }

  xhr.send(data); // Sends the request with the form data
}

// Function to get the list of features from the server
function get_features() {
  let xhr = new XMLHttpRequest(); // Creates a new XMLHttpRequest object
  xhr.open("POST", "ajax/features_facilities.php", true); // Opens a POST request to the server
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Sets the content type header

  // Defines the behavior when the request is loaded (completed)
  xhr.onload = function(){
    document.getElementById('features-data').innerHTML = this.responseText; // Updates the DOM with the list of features
  }

  xhr.send('get_features'); // Sends the request to get the list of features
}

// Function to remove a feature by sending a request to the server
function rem_feature(val) {
  let xhr = new XMLHttpRequest(); // Creates a new XMLHttpRequest object
  xhr.open("POST", "ajax/features_facilities.php", true); // Opens a POST request to the server
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Sets the content type header

  // Defines the behavior when the request is loaded (completed)
  xhr.onload = function(){
    if(this.responseText == 1) {
      alert('success', 'Feature removed!'); // Alerts success if the feature was removed
      get_features(); // Refreshes the list of features
    }
    else if(this.responseText == 'guesthouse_added') {
      alert('error', 'Feature is added in guesthouse!'); // Alerts error if the feature is in use in a guesthouse
    }
    else {
      alert('error', 'Server down!'); // Alerts error if there was an issue with the server
    }
  }

  xhr.send('rem_feature=' + val); // Sends the request to remove the feature
}

// Event listener to handle the form submission for adding a facility
facility_s_form.addEventListener('submit', function(e){
  e.preventDefault(); // Prevents the default form submission behavior
  add_facility(); // Calls the function to add the facility
});

// Function to add a new facility by sending data via XMLHttpRequest
function add_facility() {
  let data = new FormData(); // Creates a new FormData object to hold the data
  data.append('name', facility_s_form.elements['facility_name'].value); // Adds the facility name to the FormData
  data.append('icon', facility_s_form.elements['facility_icon'].files[0]); // Adds the facility icon to the FormData
  data.append('desc', facility_s_form.elements['facility_desc'].value); // Adds the facility description to the FormData
  data.append('add_facility', ''); // Indicates that this request is for adding a facility

  let xhr = new XMLHttpRequest(); // Creates a new XMLHttpRequest object
  xhr.open("POST", "ajax/features_facilities.php", true); // Opens a POST request to the server

  // Defines the behavior when the request is loaded (completed)
  xhr.onload = function(){
    var myModal = document.getElementById('facility-s'); // Gets the modal element
    var modal = bootstrap.Modal.getInstance(myModal); // Gets the instance of the modal
    modal.hide(); // Hides the modal once the request is complete

    // Checks the response from the server and displays appropriate alerts
    if(this.responseText == 'inv_img') {
      alert('error', 'Only SVG images are allowed!'); // Alerts error if the image is invalid
    }
    else if(this.responseText == 'inv_size') {
      alert('error', 'Image should be less than 1MB!'); // Alerts error if the image is too large
    }
    else if(this.responseText == 'upd_failed') {
      alert('error', 'Image upload failed. Server Down!'); // Alerts error if the upload failed
    }
    else {
      alert('success', 'New facility added!'); // Alerts success if the facility was added
      facility_s_form.reset(); // Resets the form after adding the facility
      get_facilities(); // Refreshes the list of facilities
    }
  }

  xhr.send(data); // Sends the request with the form data
}

// Function to get the list of facilities from the server
function get_facilities() {
  let xhr = new XMLHttpRequest(); // Creates a new XMLHttpRequest object
  xhr.open("POST", "ajax/features_facilities.php", true); // Opens a POST request to the server
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Sets the content type header

  // Defines the behavior when the request is loaded (completed)
  xhr.onload = function(){
    document.getElementById('facilities-data').innerHTML = this.responseText; // Updates the DOM with the list of facilities
  }

  xhr.send('get_facilities'); // Sends the request to get the list of facilities
}

// Function to remove a facility by sending a request to the server
function rem_facility(val) {
  let xhr = new XMLHttpRequest(); // Creates a new XMLHttpRequest object
  xhr.open("POST", "ajax/features_facilities.php", true); // Opens a POST request to the server
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Sets the content type header

  // Defines the behavior when the request is loaded (completed)
  xhr.onload = function(){
    if(this.responseText == 1) {
      alert('success', 'Facility removed!'); // Alerts success if the facility was removed
      get_facilities(); // Refreshes the list of facilities
    }
    else if(this.responseText == 'guesthouse_added') {
      alert('error', 'Facility is added in guesthouse!'); // Alerts error if the facility is in use in a guesthouse
    }
    else {
      alert('error', 'Server down!'); // Alerts error if there was an issue with the server
    }
  }

  xhr.send('rem_facility=' + val); // Sends the request to remove the facility
}

// Calls the functions to get the list of features and facilities when the page loads
window.onload = function(){
  get_features(); // Fetches the list of features
  get_facilities(); // Fetches the list of facilities
}
