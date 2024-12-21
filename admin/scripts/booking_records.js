function get_bookings(search='', page=1)
{
  // Creates a new XMLHttpRequest object to send the request
  let xhr = new XMLHttpRequest();
  
  // Opens a POST request to the booking_records.php file with the search and page parameters
  xhr.open("POST", "ajax/booking_records.php", true);
  
  // Sets the content type of the request to be URL encoded
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Defines the callback function to handle the response when the request is loaded
  xhr.onload = function(){
    // Parses the JSON response from the server
    let data = JSON.parse(this.responseText);

    // Updates the HTML content of the 'table-data' element with the received table data
    document.getElementById('table-data').innerHTML = data.table_data;

    // Updates the HTML content of the 'table-pagination' element with the pagination data
    document.getElementById('table-pagination').innerHTML = data.pagination;
  }

  // Sends the request to the server with the search query and page number
  xhr.send('get_bookings&search=' + search + '&page=' + page);
}

function change_page(page){
  // Calls the get_bookings function with the current search input and the new page number
  get_bookings(document.getElementById('search_input').value, page);
}

function download(id){
  // Redirects the browser to the 'generate_pdf.php' script with the provided booking id to generate a PDF
  window.location.href = 'generate_pdf.php?gen_pdf&id=' + id;
}

// Runs the get_bookings function to load the initial data when the page is loaded
window.onload = function(){
  get_bookings();
}
