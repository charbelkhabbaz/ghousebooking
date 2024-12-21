// Function to retrieve bookings, optionally filtered by a search term
function get_bookings(search='')
{
  // Create a new XMLHttpRequest to fetch bookings data
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/new_bookings.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Define what happens when the request is completed
  xhr.onload = function(){
    // Update the 'table-data' element with the response text (booking data)
    document.getElementById('table-data').innerHTML = this.responseText;
  }

  // Send the request, passing the search parameter if provided
  xhr.send('get_bookings&search='+search);
}

// Get the form for assigning a guesthouse to a booking
let assign_guesthouse_form = document.getElementById('assign_guesthouse_form');

// Function to set the booking ID in the form when a booking is selected for guesthouse assignment
function assign_guesthouse(id){
  assign_guesthouse_form.elements['booking_id'].value=id;
}

// Add event listener for form submission to handle assigning a guesthouse
assign_guesthouse_form.addEventListener('submit',function(e){
  e.preventDefault();
  
  // Prepare data to send in the request
  let data = new FormData();
  data.append('guesthouse_no',assign_guesthouse_form.elements['guesthouse_no'].value);
  data.append('booking_id',assign_guesthouse_form.elements['booking_id'].value);
  data.append('assign_guesthouse','');

  // Create a new XMLHttpRequest to send the assignment request
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/new_bookings.php",true);

  // Define what happens when the request is completed
  xhr.onload = function(){
    // Hide the modal once the request is completed
    var myModal = document.getElementById('assign-guesthouse');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    // Check if the assignment was successful and alert the user
    if(this.responseText==1){
      alert('success','Guesthouse Number Alloted! Booking Finalized!');
      // Reset the form and refresh the bookings
      assign_guesthouse_form.reset();
      get_bookings();
    }
    else{
      alert('error','Server Down!');
    }
  }

  // Send the request with the form data
  xhr.send(data);
});

// Function to cancel a booking, with confirmation prompt
function cancel_booking(id) 
{
  if(confirm("Are you sure, you want to cancel this booking?"))
  {
    // Prepare data for the cancel booking request
    let data = new FormData();
    data.append('booking_id',id);
    data.append('cancel_booking','');

    // Create a new XMLHttpRequest to send the cancel request
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/new_bookings.php",true);

    // Define what happens when the request is completed
    xhr.onload = function()
    {
      // Check if the cancel request was successful
      if(this.responseText == 1){
        alert('success','Booking Cancelled!');
        // Refresh the bookings list after cancellation
        get_bookings();
      }
      else{
        alert('error','Server Down!');
      }
    }

    // Send the cancel request
    xhr.send(data);
  }
}

// When the page loads, fetch all bookings
window.onload = function(){
  get_bookings();
}
