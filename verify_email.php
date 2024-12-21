<?php
require('inc/links.php');

// If session variables not set, create them (only once)
if (!isset($_SESSION['verify_code'])) {
    $_SESSION['verify_code'] = random_int(1000, 9999);
}

// If POST request is made (Ajax), handle verification logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check session variables
    if (!isset($_SESSION['verify_code']) || !isset($_SESSION['email'])) {
        echo 'session_expired'; // Session expired or not set
        exit;
    }

    // Validate the entered verification code
    $entered_code = filter_input(INPUT_POST, 'verification_code', FILTER_SANITIZE_NUMBER_INT);

    if ($entered_code != $_SESSION['verify_code']) {
        echo 'invalid_code'; // Verification code mismatch
    } else {
        // Update the database to mark the user as verified

        $query = "UPDATE user_cred SET is_verified=1 WHERE email=?";
        if (update($query, [$_SESSION['email']], "s")) {
            //unset($_SESSION['verify_code']); // Clear verification code from session
            header("Location:index.php");
        } else {
            echo 'update_failed'; // Failed to update the database
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>

    <!-- Bootstrap CSS -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php require('inc/header.php'); ?>

    <div class="container vh-100 d-flex align-items-center justify-content-center">
        <div class="row justify-content-center w-100">
            <div class="col-md-6">

                <!-- Hidden alert; will show on success or if redirected with "showpopup=1" -->
                <div
                    class="alert alert-info alert-dismissible fade show"
                    role="alert"
                    id="redirectAlert"
                    style="display: none;">
                    Registration successful. Please verify your email.
                </div>

                <div
                    class="alert alert-info alert-dismissible fade show"
                    role="alert"
                    id="redirectAlert2"
                    style="display: none;">
                    Please verify your email.
                </div>

                <!-- Hidden alert; will show on successful verification -->
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                    id="verificationAlert"
                    style="display: none;">
                    Verification successful!
                </div>

                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Verify Your Email</h4>

                        <!-- Verification form -->
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="verification_code">Enter Verification Code</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="verification_code"
                                    name="verification_code"
                                    required
                                    placeholder="send the verification code first">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                Verify
                            </button>
                        </form>

                        <hr class="my-4">

                        <!-- Resend email form -->
                        <form action="https://youbee.click/send_email.php" method="POST" class="text-center">

                            <!-- Optional hidden inputs for session data (if needed): -->
                            <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
                            <input type="hidden" name="random" value="<?php echo $_SESSION['verify_code']; ?>">

                            <button type="submit" class="btn btn-secondary">
                               Send Verification Email
                            </button>
                        </form>

                    </div> <!-- end card-body -->
                </div> <!-- end card -->

            </div> <!-- end col-md-6 -->
        </div> <!-- end row -->
    </div> <!-- end container -->

    <!-- Full jQuery (with fadeIn/fadeOut support) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // 1) Check if URL param showpopup=1 is present
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('showpopup') === '1') {
                // Show the "redirectAlert" popup
                $('#redirectAlert').fadeIn();
                // Optionally hide it after 4 seconds
                setTimeout(function() {
                    $('#redirectAlert').fadeOut();
                }, 4000);
            } else {
                $('#redirectAlert2').fadeIn();
                // Optionally hide it after 4 seconds
                setTimeout(function() {
                    $('#redirectAlert2').fadeOut();
                }, 4000);
            }
        });
    </script>

</body>

</html>