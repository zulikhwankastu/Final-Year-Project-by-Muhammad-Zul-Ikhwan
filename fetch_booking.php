<?php
// fetch_events.php

header('Content-Type: application/json');

// Include the database connection
include '_db.php';

// Define the SQL query to fetch booking data
$query = "SELECT roomNo, Name, CheckIn, CheckOut, Telephone, Status FROM deluxesupersingles ORDER BY CheckIn ASC";
$result = mysqli_query($dbc, $query);

$events = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Map the Status to corresponding CSS class
        $statusClass = '';
        switch (strtolower($row['Status'])) {
            case 'arrived':
                $statusClass = 'status-arrived';
                break;
            case 'checked out':
                $statusClass = 'status-checked-out';
                break;
            case 'due out':
                $statusClass = 'status-due-out';
                break;
            case 'confirmed':
                $statusClass = 'status-confirmed';
                break;
            case 'stayover':
                $statusClass = 'status-stayover';
                break;
            default:
                $statusClass = '';
        }

        // Prepare each booking as a FullCalendar event
        $events[] = [
            'title' => "Room " . $row['roomNo'] . " - " . $row['Name'],
            'start' => $row['CheckIn'],
            'end' => $row['CheckOut'],
            'className' => $statusClass,
            'extendedProps' => [
                'customerInfo' => "Room No: " . $row['roomNo'] . "<br>" .
                                  "Name: " . $row['Name'] . "<br>" .
                                  "Telephone: " . $row['Telephone'] . "<br>" .
                                  "Status: " . $row['Status']
                
            ]
        ];
    }
}

// Return the events as JSON
echo json_encode($events);

// Close the database connection
mysqli_close($dbc);
?>
