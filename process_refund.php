<?php
session_start();
require('inc/db_config.php');

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $booking_id = filter_var(trim($_POST['booking_id']), FILTER_VALIDATE_INT);
    $booking_price = filter_var(trim($_POST['booking_price']), FILTER_VALIDATE_FLOAT);
    $user_id = $_SESSION['uId'];

    if ($booking_id === false || $booking_price === false) {
        die('Invalid input data.');
    }

    // Insert refund details into refunds table
    // Delete the booking
    $update_booking = mysqli_query($con, "UPDATE booking_order SET is_deleted = 1 WHERE booking_id = '$booking_id' AND user_id = '$user_id'");

    if ($update_booking) {
        $_SESSION['message'] = "Refund requested successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete the booking.";
    }
} else {
    $_SESSION['message'] = "Failed to request refund.";
}

header('Location: bookings.php');
