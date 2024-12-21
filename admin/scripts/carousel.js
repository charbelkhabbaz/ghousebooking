let carousel_s_form = document.getElementById('carousel_s_form');
let carousel_picture_inp = document.getElementById('carousel_picture_inp');

// Event listener for form submission, prevents default form action and calls add_image function
carousel_s_form.addEventListener('submit', function(e){
  e.preventDefault();
  add_image(); // Calls the add_image function to handle image upload
});

function add_image()
{
  // Creates a new FormData object to hold the uploaded file
  let data = new FormData();
  data.append('picture', carousel_picture_inp.files[0]); // Appends the picture file from input
  data.append('add_image', ''); // Adds a flag to the form data to indicate image addition

  // Creates a new XMLHttpRequest to send the data to the server
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/carousel_crud.php", true);

  // Defines what happens when the request is complete
  xhr.onload = function(){
    var myModal = document.getElementById('carousel-s'); // Gets the modal element
    var modal = bootstrap.Modal.getInstance(myModal); // Initializes the modal using Bootstrap
    modal.hide(); // Closes the modal after the action is completed

    // Checks the response from the server and displays appropriate alert messages
    if(this.responseText == 'inv_img'){
      alert('error', 'Only JPG and PNG images are allowed!'); // Error message for invalid image format
    }
    else if(this.responseText == 'inv_size'){
      alert('error', 'Image should be less than 2MB!'); // Error message for image size
    }
    else if(this.responseText == 'upd_failed'){
      alert('error', 'Image upload failed. Server Down!'); // Error message for failed upload
    }
    else{
      alert('success', 'New image added!'); // Success message for successful image addition
      carousel_picture_inp.value = ''; // Clears the input field after successful upload
      get_carousel(); // Refreshes the carousel images
    }
  }

  // Sends the data to the server
  xhr.send(data);
}

function get_carousel()
{
  // Creates a new XMLHttpRequest to fetch the carousel images
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/carousel_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Defines what happens when the request is complete
  xhr.onload = function(){
    document.getElementById('carousel-data').innerHTML = this.responseText; // Updates the carousel with the new images
  }

  // Sends the request to get the carousel images
  xhr.send('get_carousel');
}

function rem_image(val)
{
  // Creates a new XMLHttpRequest to send a request to remove an image from the carousel
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/carousel_crud.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Defines what happens when the request is complete
  xhr.onload = function(){
    // Checks the server's response and shows an appropriate message
    if(this.responseText == 1){
      alert('success', 'Image removed!'); // Success message when image is removed
      get_carousel(); // Refreshes the carousel after removal
    }
    else{
      alert('error', 'Server down!'); // Error message if server fails
    }
  }

  // Sends the request to remove the image
  xhr.send('rem_image=' + val);
}

// Calls the get_carousel function when the page loads to display the carousel images
window.onload = function(){
  get_carousel();
}
