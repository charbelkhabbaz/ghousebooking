<?php
// Including essential files
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();

// Fetch random bookings
if (isset($_POST['get_random_bookings'])) {
    $query = "SELECT bookings.id, bookings.user_id, bookings.guesthouse_id, bookings.details, 
                     users.name AS user_name, users.email AS user_email, 
                     guesthouses.name AS guesthouse_name 
              FROM bookings 
              INNER JOIN users ON bookings.user_id = users.id 
              INNER JOIN guesthouses ON bookings.guesthouse_id = guesthouses.id 
              ORDER BY RAND() 
              LIMIT 10"; // Fetch up to 10 random records

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $output = '';
    if ($result->num_rows > 0) {
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            $output .= '<tr>';
            $output .= '<td>' . $count++ . '</td>';
            $output .= '<td>' . htmlspecialchars($row['user_name']) . '<br>' . htmlspecialchars($row['user_email']) . '</td>';
            $output .= '<td>' . htmlspecialchars($row['guesthouse_name']) . '</td>';
            $output .= '<td>' . htmlspecialchars($row['details']) . '</td>';
            $output .= '<td>
                            <button class="btn btn-sm btn-primary" onclick="assignGuesthouse(' . $row['id'] . ')">Assign</button>
                        </td>';
            $output .= '</tr>';
        }
    } else {
        $output .= '<tr><td colspan="5" class="text-center">No bookings found</td></tr>';
    }

    echo $output;
    exit;
}
?>
