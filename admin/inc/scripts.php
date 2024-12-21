<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>

  // Function to show alert messages based on the type ('success' or 'danger')
  function alert(type, msg, position='body')
  {
    // Determines the Bootstrap class based on the alert type
    let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
    let element = document.createElement('div');
    
    // Creates the HTML structure for the alert
    element.innerHTML = `
      <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
        <strong class="me-3">${msg}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `;

    // Appends the alert to the body or a specified position
    if(position=='body'){
      document.body.append(element);
      element.classList.add('custom-alert');
    }
    else{
      document.getElementById(position).appendChild(element);
    }
    
    // Removes the alert after 2 seconds
    setTimeout(remAlert, 2000);
  }

  // Function to remove the alert
  function remAlert(){
    document.getElementsByClassName('alert')[0].remove();
  }

  // Function to set the active class on the navbar based on the current page
  function setActive()
  {
    let navbar = document.getElementById('dashboard-menu');
    let a_tags = navbar.getElementsByTagName('a');

    // Loops through each navbar link and checks if the current URL matches the link's filename
    for(i=0; i<a_tags.length; i++)
    {
      let file = a_tags[i].href.split('/').pop();
      let file_name = file.split('.')[0];

      // Adds the 'active' class to the matching link
      if(document.location.href.indexOf(file_name) >= 0){
        a_tags[i].classList.add('active');
      }

    }
  }
  // Calls the setActive function to highlight the active page
  setActive();
</script>
