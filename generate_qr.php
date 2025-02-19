<?php
// Start session to verify the user (if applicable)
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Include the PHP QR Code library
require_once 'phpqrcode/qrlib.php'; // Adjust the path if necessary

// Check if a ticket ID is passed in the URL
$ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;
if ($ticket_id <= 0) {
    die("Invalid ticket ID.");
}

// Simulate fetching ticket details from the database (replace with actual DB query)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticket_exchange";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch ticket details
$sql = "SELECT event_name, location, event_date, price, status, category FROM tickets WHERE ticket_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Ticket not found.");
}

$ticket = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Check if the ticket is already sold
if ($ticket['status'] === 'sold') {
    die("This ticket has already been sold.");
}

// Create a payment URL (replace with actual payment gateway link)
$payment_url = "upi://pay?pa=jaison2004dsouza@oksbi&pn=Jaison%20Dsouza&aid=uGICAgMDU1ov5FA" . $ticket['price'];

// Path to save the QR code image
$qr_file = "qrcodes/ticket_" . $ticket_id . ".png";

// Generate the QR code
QRcode::png($payment_url, $qr_file, 'L', 10, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment QR Code</title>
    <style>
        #success-message {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background-color: #d1fae5;
            border-radius: 10px;
            border: 1px solid #34d399;
            color: #16a34a;
            font-size: 18px;
            font-weight: 600;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .container h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }

        .container p {
            font-size: 16px;
            line-height: 1.5;
            color: #6b7280;
            margin-bottom: 25px;
        }

        .event-details {
            text-align: left;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .event-details p {
            font-size: 14px;
            margin: 5px 0;
            color: #374151;
        }

        .event-details span {
            font-weight: 600;
            color: #3b82f6;
        }

        .qr-code {
            margin: 20px 0;
        }

        .qr-code img {
            width: 200px;
            height: 200px;
            border-radius: 10px;
            border: 2px solid #3b82f6;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .action-buttons .button {
            display: inline-block;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            text-decoration: none;
        }

        .download-btn {
            background-color: #3b82f6;
            color: white;
            border: none;
        }

        .download-btn:hover {
            background-color: #2563eb;
        }

        .payment-btn {
            background-color: transparent;
            color: #3b82f6;
            border: 2px solid #3b82f6;
        }

        .payment-btn:hover {
            background-color: #3b82f6;
            color: white;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #6b7280;
        }

        /* Success message styling */
        #success-message {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background-color: #d1fae5;
            border-radius: 10px;
            border: 1px solid #34d399;
            color: #16a34a;
            font-size: 18px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Make Your Payment</h1>
        <p>Pay securely by scanning the QR code below. Ensure all details are correct before proceeding.</p>
        <div class="event-details">
            <p><span>Event:</span> <?php echo htmlspecialchars($ticket['event_name']); ?></p>
            <p><span>Location:</span> <?php echo htmlspecialchars($ticket['location']); ?></p>
            <p><span>Date:</span> <?php echo htmlspecialchars($ticket['event_date']); ?></p>
            <p><span>Price:</span> ₹<?php echo htmlspecialchars($ticket['price']); ?></p>
        </div>
        <div class="qr-code">
            <img src="<?php echo $qr_file; ?>" alt="QR Code">
        </div>
        <div class="action-buttons">
            <a href="<?php echo $qr_file; ?>" download class="button download-btn">Download QR Code</a>
            <form method="POST" action="" style="margin: 0;">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($ticket['category']); ?>">
                <button type="submit" name="payment_done" class="button payment-btn">Payment Done</button>
            </form>
        </div>
        <div class="footer">
            Need help? <a href="support.php" style="color: #3b82f6;">Contact Support</a>
        </div>
    </div>

    <?php
    if (isset($_POST['payment_done'])) {
        // Update ticket status to 'sold'
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE tickets SET status = 'sold' WHERE ticket_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ticket_id);

        if ($stmt->execute()) {
            // Determine the redirection based on the category
            $redirect_url = "";
            switch ($_POST['category']) {
                case 'sports':
                    $redirect_url = "sports_tickets.php"; // Adjust to the actual URL of sports tickets
                    break;
                case 'concerts':
                    $redirect_url = "concerts_tickets.php"; // Adjust to the actual URL of concerts tickets
                    break;
                default:
                    $redirect_url = "index.php"; // Default fallback page
                    break;
            }

            // Display success message and redirect
            echo "<div id='success-message'>
                    <p>Payment confirmed! The ticket has been marked as sold.</p>
                    <p>Redirecting to the available tickets section...</p>
                  </div>
                  <script>
                      setTimeout(function() {
                          window.location.href = '$redirect_url';
                      }, 3000);
                  </script>";
        } else {
            echo "<script>alert('Error confirming payment. Please try again.');</script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
