// Function to fetch and display the list of users
function get_users() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/users.php", true); // Open a POST request to the server
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Set the content type header for URL-encoded data

  xhr.onload = function() {
    // Update the 'users-data' element with the response from the server (the list of users)
    document.getElementById('users-data').innerHTML = this.responseText;
  }

  xhr.send('get_users'); // Send the request to the server to get the users
}

// Function to toggle the status of a user
function toggle_status(id, val) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/users.php", true); // Open a POST request to the server
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Set the content type header

  xhr.onload = function() {
    // Check the server response and show an alert based on the result
    if (this.responseText == 1) {
      alert('success', 'Status toggled!'); // Show success message if status was toggled
      get_users(); // Reload the list of users
    } else {
      alert('success', 'Server Down!'); // Show error if the server is down
    }
  }

  // Send the user ID and status value to the server to toggle the user's status
  xhr.send('toggle_status=' + id + '&value=' + val);
}

// Function to remove a user after confirming the action
function remove_user(user_id) {
  // Ask for confirmation before proceeding with the user removal
  if (confirm("Are you sure, you want to remove this user?")) {
    let data = new FormData();
    data.append('user_id', user_id); // Append the user ID to the request data
    data.append('remove_user', ''); // Append the action type to the request data

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users.php", true); // Open a POST request to the server

    xhr.onload = function() {
      // Check the server response and show the appropriate alert message
      if (this.responseText == 1) {
        alert('success', 'User Removed!'); // Show success message if user was removed
        get_users(); // Reload the list of users
      } else {
        alert('error', 'User removal failed!'); // Show error if user removal failed
      }
    }
    xhr.send(data); // Send the request to the server to remove the user
  }
}

// Function to search for a user by username
function search_user(username) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/users.php", true); // Open a POST request to the server
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Set the content type header

  xhr.onload = function() {
    // Update the 'users-data' element with the response from the server (the search results)
    document.getElementById('users-data').innerHTML = this.responseText;
  }

  // Send the username to the server to search for the user
  xhr.send('search_user&name=' + username);
}

// Window onload function to fetch the list of users when the page is loaded
window.onload = function() {
  get_users(); // Fetch and display the list of users
}
