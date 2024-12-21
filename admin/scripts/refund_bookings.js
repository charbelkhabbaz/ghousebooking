// Function to retrieve bookings, optionally filtered by a search term
function get_bookings(search='')
{
  // Create a new XMLHttpRequest to fetch booking data for refunds
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/refund_bookings.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Define what happens when the request is completed
  xhr.onload = function(){
    // Update the 'table-data' element with the response text (booking data)
    document.getElementById('table-data').innerHTML = this.responseText;
  }

  // Send the request, passing the search parameter if provided
  xhr.send('get_bookings&search='+search);
}

// Function to process a refund for a booking, with confirmation prompt
function refund_booking(id) 
{
  // Ask the user for confirmation before proceeding with the refund
  if(confirm("Refund money for this booking?"))
  {
    // Prepare data to send in the refund request
    let data = new FormData();
    data.append('booking_id',id);
    data.append('refund_booking','');

    // Create a new XMLHttpRequest to send the refund request
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/refund_bookings.php",true);

    // Define what happens when the request is completed
    xhr.onload = function()
    {
      // Check if the refund request was successful
      if(this.responseText == 1){
        alert('success','Money Refunded!');
        // Refresh the bookings list after refund
        get_bookings();
      }
      else{
        alert('error','Server Down!');
      }
    }

    // Send the refund request with the form data
    xhr.send(data);
  }
}

// When the page loads, fetch all bookings for refund processing
window.onload = function(){
  get_bookings();
}
