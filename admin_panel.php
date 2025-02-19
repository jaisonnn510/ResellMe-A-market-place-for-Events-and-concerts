<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php"); // Redirect to login if admin is not logged in
}

$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password
$dbname = "ticket_exchange";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get tickets that are pending approval
$sql = "SELECT ticket_id, event_name, location, event_date, price, status, pdf_path FROM tickets WHERE status = 'Pending'";
$result = $conn->query($sql);
if ($result === false) {
    echo "Error executing query: " . $conn->error;
}

if (isset($_POST['action']) && isset($_POST['ticket_id'])) {
    $action = $_POST['action'];
    $ticket_id = $_POST['ticket_id'];

    if ($action === 'approve') {
        $update_sql = "UPDATE tickets SET status = 'Approved' WHERE ticket_id = $ticket_id";
    } elseif ($action === 'reject') {
        $update_sql = "UPDATE tickets SET status = 'Rejected' WHERE ticket_id = $ticket_id";
    }

    if ($conn->query($update_sql) === TRUE) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Ticket Verification</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script> <!-- Adjust to your CSS file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
    <style>
        /* Body and page container styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #8B5DFF;
            margin: 0;
            padding: 0;
        }

        /* New header design */
        .header-div {
            background-image: url('https://source.unsplash.com/random/640x480');
            background-position: center center;
            background-blend-mode: multiply;
            background-size: cover;
            padding: 20px 0;
            text-align: center;
        }

        .header-div h1 {
            font-size: 30px;
            color: #ffffff;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .header-div p {
            font-size: 18px;
            color: #ffffff;
            margin-bottom: 20px;
        }

        .header-div .logout-button a {
            color: #fff;
            background-color: #F96E2A; /* Red color for logout */
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            outline:none;
        }

        .logout-button a:hover {
            background-color: #1A1A1D; /* Darker red on hover */
        }

        .container {
            width: 80%;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .ticket-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .ticket-card:hover {
            transform: scale(1.02);
        }

        .ticket-card h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .ticket-card p {
            color: #666;
            margin: 5px 0;
        }

        .ticket-card a {
            color: black; /* Purple color for links */
            text-decoration: none;
            font-weight: bold;
            margin-right: 15px;
        }

        .ticket-card a:hover {
            text-decoration: none;
        }

        .approve-btn, .reject-btn {
            padding: 8px 16px;
            margin-top: 10px;
            border-radius: 5px;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .approve-btn {
            background-color: #4CAF50; /* Green for approve */
        }

        .approve-btn:hover {
            background-color: #45a049;
        }

        .reject-btn {
            background-color: #E11D48; /* Red for reject */
        }

        .reject-btn:hover {
            background-color: #9B1C35;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            .header-div {
                flex-direction: column;
                align-items: flex-start;
            }

            .logout-button a {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="header-div">
        <h1>Admin - Ticket Verification</h1>
        <p> Verify tickets awaiting approval!</p>
        <div class="logout-button">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</header>

<div class="container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='ticket-card' id='ticket-" . $row['ticket_id'] . "'>";
            echo "<h2>" . htmlspecialchars($row['event_name']) . "</h2>";
            echo "<p>Location: " . htmlspecialchars($row['location']) . "</p>";
            echo "<p>Date: " . htmlspecialchars($row['event_date']) . "</p>";
            echo "<p>Price: ₹" . htmlspecialchars($row['price']) . "</p>";

            // Provide options for admin to approve or reject
            echo "<a href='view_ticket_pdf.php?ticket_id=" . $row['ticket_id'] . "' target='_blank'>View PDF</a>";
            echo "<br>";

            // Actions to approve or reject
            echo "<button class='approve-btn' onclick='performAction(" . $row['ticket_id'] . ", \"approve\")'>Approve</button>";
            echo "<button class='reject-btn' onclick='performAction(" . $row['ticket_id'] . ", \"reject\")'>Reject</button>";

            echo "</div>";
        }
    } else {
        echo "<p>No tickets pending for approval.</p>";
    }
    ?>
</div>

<script>
    function performAction(ticketId, action) {
        $.ajax({
            url: '', // The same page will handle the request
            type: 'POST',
            data: {
                ticket_id: ticketId,
                action: action
            },
            success: function(response) {
                if (response === 'Success') {
                    // Hide the ticket card once the action is completed
                    $('#ticket-' + ticketId).fadeOut();
                } else {
                    alert('Error: ' + response);
                }
            }
        });
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
