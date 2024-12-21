<?php
session_start(); // Start the session
require('admin/inc/db_config.php');

// Define the redirect function if it doesn't exist
if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: $url");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['uId'])) {
        die('User not logged in. Please log in to submit a rating.');
    }

    $user_id = $_SESSION['uId'];
    $data = filteration($_POST);

    $query = "INSERT INTO rating_review (guesthouse_id, user_id, rating, review, seen, datentime) 
              VALUES (?, ?, ?, ?, 0, NOW())";

    $params = [$data['guesthouse_id'], $user_id, $data['rating'], $data['review']];
    
    try {
        if (insert($query, $params, 'iiis')) {
            redirect('guesthouses.php?success=Thank you for your feedback!');
        } else {
            redirect('rate_guesthouse.php?id=' . $data['guesthouse_id'] . '&error=Failed to submit feedback!');
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    redirect('guesthouses.php');
}
?>