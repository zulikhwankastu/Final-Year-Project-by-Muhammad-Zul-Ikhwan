<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/main.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
        }
        #main-container {
            display: flex;
            max-width: 1400px;
            width: 100%;
            gap: 20px;
        }
        #sidebar {
            width: 300px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        #calendar-container {
            flex: 1;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .controls, .buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
        }
        .controls select, .buttons button {
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }
        .fc-daygrid-event {
            font-size: 1em;
            padding: 5px;
        }
        /* Event colors for different statuses */
        .status-arrived { background-color: #28a745 !important; color: white !important; }
        .status-checked-out { background-color: #6c757d !important; color: white !important; }
        .status-due-out { background-color: #dc3545 !important; color: white !important; }
        .status-confirmed { background-color: #17a2b8 !important; color: white !important; }
        .status-stayover { background-color: #ffc107 !important; color: white !important; }

        /* Legend styles */
        .legend {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .legend div {
            display: flex;
            align-items: center;
        }
        .legend div span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: 0 auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 900px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .modal-content h2 {
            text-align: center;
        }
        .modal-content label {
            display: block;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .modal-content input, .modal-content select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .modal-content button {
            padding: 15px 30px;
            font-size: 18px;
            margin-right: 10px;
            cursor: pointer;
        }
        .modal-content .cancel-button {
            background-color: #ccc;
        }
        .modal-content .ok-button {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>

    <div id="main-container">
        <!-- Sidebar for customer details -->
        <div id="sidebar">
            <h2>Customer Details</h2>
            <p id="customerInfo">Select a day to view booking details.</p>

            <!-- Change Status button -->
            <button id="changeStatusButton" style="display: none;">Change Status</button>
            <!-- Extend Stay button -->
            <button id="extendStayButton" style="display: none;">Extend Stay</button>
        </div>

        <!-- Calendar and Controls -->
        <div id="calendar-container">
            <h1>Room Booking Calendar</h1>
            
            <div class="controls">
                <select id="monthSelect">
                    <option value="0">January</option>
                    <option value="1">February</option>
                    <option value="2">March</option>
                    <option value="3">April</option>
                    <option value="4">May</option>
                    <option value="5">June</option>
                    <option value="6">July</option>
                    <option value="7">August</option>
                    <option value="8">September</option>
                    <option value="9">October</option>
                    <option value="10">November</option>
                    <option value="11">December</option>
                </select>

                <select id="yearSelect">
                    <!-- Populate years dynamically -->
                    <?php
                    $currentYear = date("Y");
                    for ($year = $currentYear; $year <= 2060; $year++) {
                        echo "<option value=\"$year\" " . ($year == $currentYear ? 'selected' : '') . ">$year</option>";
                    }
                    ?>
                </select>

                <button id="loadCalendar" title="Load Calendar">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <div id="calendar"></div>

            <!-- Legend for event statuses -->
            <div class="legend">
                <div><span style="background-color: #28a745;"></span> Arrived</div>
                <div><span style="background-color: #6c757d;"></span> Checked Out</div>
                <div><span style="background-color: #dc3545;"></span> Due Out</div>
                <div><span style="background-color: #17a2b8;"></span> Confirmed</div>
                <div><span style="background-color: #ffc107;"></span> Stayover</div>
            </div>
        </div>
    </div>

    <!-- Modal for editing booking details -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <h2>Edit Booking</h2>
            <form id="bookingForm">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="hp">HP:</label>
                <input type="text" id="hp" name="hp" required>

                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" name="startDate" required>

                <label for="checkOutDate">Check-out Date:</label>
                <input type="date" id="checkOutDate" name="checkOutDate" required>

                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="new">New</option>
                    <option value="confirm">Confirmed</option>
                    <option value="arrived">Arrived</option>
                    <option value="checkout">Checked Out</option>
                </select>

                <label for="paid">Paid:</label>
                <select id="paid" name="paid">
                    <option value="0">0%</option>
                    <option value="50">50%</option>
                    <option value="100">100%</option>
                </select>

                <div style="display: flex; justify-content: center;">
                    <button type="submit" class="ok-button">Ok</button>
                    <button type="button" class="cancel-button" id="cancelButton">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var customerInfoEl = document.getElementById('customerInfo');
    var changeStatusButton = document.getElementById('changeStatusButton');
    var extendStayButton = document.getElementById('extendStayButton');
    var selectedRoomNo = null;
    var selectedCheckIn = null;
    var modal = document.getElementById('myModal');
    var cancelButton = document.getElementById('cancelButton');

    // Initialize the calendar
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        events: {
            url: 'fetch_booking.php',
            failure: function() {
                alert('Error while fetching events!');
            }
        },
        eventClick: function(info) {
            var customerInfo = info.event.extendedProps.customerInfo;
            customerInfoEl.innerHTML = customerInfo.replace(/<br>/g, '\n');
            
            selectedRoomNo = info.event.extendedProps.roomNo;
            selectedCheckIn = info.event.startStr;
            changeStatusButton.style.display = 'block';
            extendStayButton.style.display = 'block';

            // Show modal with prefilled information
            document.getElementById('name').value = info.event.extendedProps.name;
            document.getElementById('hp').value = info.event.extendedProps.hp;
            document.getElementById('startDate').value = info.event.startStr.split('T')[0];
            document.getElementById('checkOutDate').value = info.event.endStr.split('T')[0];
            document.getElementById('status').value = info.event.extendedProps.status;
            document.getElementById('paid').value = info.event.extendedProps.paid;

            modal.style.display = "block";
        },
        dateClick: function(info) {
            customerInfoEl.textContent = 'No booking information for this day.';
            changeStatusButton.style.display = 'none';
            extendStayButton.style.display = 'none';
            selectedRoomNo = null;
            selectedCheckIn = null;
        }
    });

    calendar.render();

    cancelButton.addEventListener('click', function() {
        modal.style.display = "none";
    });

    document.getElementById('bookingForm').addEventListener('submit', function(event) {
        event.preventDefault();

        alert('Booking details updated');
        modal.style.display = "none";
    });
});
    </script>
</body>
</html>
