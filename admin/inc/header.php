<div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between sticky-top">
  <!-- Main header for the website, styled with dark background and light text -->
  <h3 class="mb-0 h-font">GUEST HOUSE WEBSITE</h3>
  <!-- Log out button that redirects to logout.php -->
  <a href="logout.php" class="btn btn-light btn-sm">LOG OUT</a>
</div>

<div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="dashboard-menu">
  <!-- Sidebar menu for admin panel -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid flex-lg-column align-items-stretch">
      <h4 class="mt-2 text-light">ADMIN PANEL</h4>
      <!-- Button for toggling the navigation menu -->
      <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Collapsible menu for admin options -->
      <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link text-white" href="dashboard.php">Dashboard</a> <!-- Link to dashboard -->
          </li>
          <li class="nav-item">
            
            <!-- Collapsible submenu for booking-related options -->
            
                <li class="nav-item">
                  <a class="nav-link text-white" href="new_bookings.php">New Bookings</a> <!-- Link to new bookings -->
                </li>
                
          <!-- Links for other admin panel features -->
          <li class="nav-item">
            <a class="nav-link text-white" href="users.php">Users</a> <!-- Link to manage users -->
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="user_queries.php">User Queries</a> <!-- Link to user queries -->
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="rate_review.php">Ratings & Reviews</a> <!-- Link to ratings and reviews -->
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="guesthouses.php">Guesthouses</a> <!-- Link to manage guesthouses -->
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="features_facilities.php">Features & Facilities</a> <!-- Link to manage features and facilities -->
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="carousel.php">Carousel</a> <!-- Link to manage carousel -->
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="settings.php">Settings</a> <!-- Link to settings -->
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>
