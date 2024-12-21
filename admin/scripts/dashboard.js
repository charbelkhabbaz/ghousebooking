function booking_analytics(period=1)
{
  // Creates a new XMLHttpRequest to fetch booking analytics data
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/dashboard.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Defines the behavior when the request is loaded (completed)
  xhr.onload = function(){
    let data = JSON.parse(this.responseText); // Parses the response into a JSON object
    
    // Updates the DOM elements with the retrieved data for bookings and amounts
    document.getElementById('total_bookings').textContent = data.total_bookings; // Total bookings
    document.getElementById('total_amt').textContent = '$' + data.total_amt; // Total amount

    document.getElementById('active_bookings').textContent = data.active_bookings; // Active bookings
    document.getElementById('active_amt').textContent = '$' + data.active_amt; // Active amount
    
    document.getElementById('cancelled_bookings').textContent = data.cancelled_bookings; // Cancelled bookings
    document.getElementById('cancelled_amt').textContent = '$' + data.cancelled_amt; // Cancelled amount
  }

  // Sends the request with the period as a parameter
  xhr.send('booking_analytics&period=' + period);
}

function user_analytics(period=1)
{
  // Creates a new XMLHttpRequest to fetch user analytics data
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/dashboard.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Defines the behavior when the request is loaded (completed)
  xhr.onload = function(){
    let data = JSON.parse(this.responseText); // Parses the response into a JSON object

    // Updates the DOM elements with the retrieved data for user analytics
    document.getElementById('total_new_reg').textContent = data.total_new_reg; // Total new registrations
    document.getElementById('total_queries').textContent = data.total_queries; // Total queries
    document.getElementById('total_reviews').textContent = data.total_reviews; // Total reviews
  }

  // Sends the request with the period as a parameter
  xhr.send('user_analytics&period=' + period);
}

// Calls both analytics functions when the page loads to fetch and display data
window.onload = function(){
  booking_analytics(); // Fetches booking analytics
  user_analytics(); // Fetches user analytics
}
