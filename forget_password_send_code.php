<?php
require('inc/links.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission
    $entered_code = filter_input(INPUT_POST, 'verification_code', FILTER_SANITIZE_NUMBER_INT);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $new_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

    // Default verification code
    $default_code = "8548";

    // Check if the entered code matches the default code
    if ($entered_code !== $default_code) {
        $error_message = "Invalid verification code.";
    } elseif (!$email) {
        $error_message = "Invalid email format.";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if the email exists in the database
        $query = "SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1";
        $user = select($query, [$email], "s");

        if (mysqli_num_rows($user) == 0) {
            $error_message = "Email not found.";
        } else {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update the password in the database
            $update_query = "UPDATE `user_cred` SET `password`=? WHERE `email`=?";
            if (update($update_query, [$hashed_password, $email], "ss")) {
                $success_message = "Password has been successfully reset.";
                header('Location: http://localhost/ghousebooking/index.php');
            } else {
                $error_message = "Failed to reset password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS (via CDN) -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>
    <?php require('inc/header.php'); ?> 

    <div class="container mt-5">
        <h2 class="text-center mb-4">Forgot Password</h2>

        <!-- Success or Error Messages -->
        <?php if (isset($success_message)) : ?>
            <div class="alert alert-success" role="alert">
                <?= $success_message ?>
            </div>
        <?php elseif (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <!-- Forgot Password Form -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="verification_code" class="form-label">Verification Code:</label>
                <input
                    type="text"
                    class="form-control"
                    id="verification_code"
                    name="verification_code"
                    required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password:</label>
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password:</label>
                <input
                    type="password"
                    class="form-control"
                    id="confirm_password"
                    name="confirm_password"
                    required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>

    <!-- Bootstrap JS (via CDN) + Popper JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>