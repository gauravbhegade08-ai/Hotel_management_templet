<?php
// Set header to return JSON response
header('Content-Type: application/json');

// Get the raw POST data from the JS fetch request
$json_data = file_get_contents('php://input');
$booking = json_decode($json_data, true);

if ($booking) {
    $file = 'bookings.json';
    
    // Add a timestamp to the booking
    $booking['timestamp'] = date('Y-m-d H:i:s');
    
    // Read existing data if the file exists
    $current_data = [];
    if (file_exists($file)) {
        $file_content = file_get_contents($file);
        $current_data = json_decode($file_content, true) ?? [];
    }
    
    // Append the new booking
    $current_data[] = $booking;
    
    // Save it back to the file with nice formatting
    if (file_put_contents($file, json_encode($current_data, JSON_PRETTY_PRINT))) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to write to file']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
?>