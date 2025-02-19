<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password
$dbname = "ticket_exchange";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get location filter if selected
$location_filter = isset($_GET['location']) ? $_GET['location'] : '';

// Base SQL query for approved tickets in Sports category
$sql = "SELECT ticket_id, event_name, location, event_date, price 
        FROM tickets 
        WHERE category = 'Sports' AND status = 'approved'";

// Append location filter if selected
if (!empty($location_filter)) {
    $sql .= " AND location = ?";
}

$stmt = $conn->prepare($sql);

if (!empty($location_filter)) {
    $stmt->bind_param("s", $location_filter);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Tickets</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .header-div {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ff0055, #ff7f7f);
            color: white;
            padding: 30px 20px;
            border-bottom: 2px solid #ff0055;
        }

        .logo {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .select-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
            width: 100%;
        }

        select {
            padding: 12px;
            border-radius: 5px;
            border: none;
            font-size: 14px;
            outline: none;
            background-color: #ffffff;
            color: #333;
            width: 200px;
        }

        .sell-tickets {
            background-color: #ffffff;
            color: #ff0055;
            border: none;
            border-radius: 5px;
            padding: 12px 25px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sell-tickets:hover {
            background-color: #ff7f7f;
            color: white;
        }

        .container {
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        .events {
            display: flex;
            flex-direction: column;
            gap: 20px;
            justify-content: center;
            align-items: center;
        }

        .event-card {
            background: white;
            border: 1px solid #dddddd;
            border-radius: 10px;
            padding: 20px;
            width: 350px; /* Increased width for a more spacious layout */
            height: 270px; /* Slightly increased height for better proportion */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-10px); /* Lift effect */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .event-name {
            font-size: 22px;
            color: #333333;
            margin-bottom: 10px;
            text-align: left;
            font-weight: bold; /* Makes the event name stand out */
        }

        .event-card p {
            color: #666666;
            margin: 5px 0;
        }

        .buy-now-btn {
            text-align: center;
            background-color: #ff0055;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            transition: background-color 0.3s;
            align-self: flex-end; /* Move to the right */
        }

        .buy-now-btn:hover {
            background-color: #e6004e;
        }

        hr {
            border: none;
            height: 1px;
            background-color: #dddddd;
            width: 100%;
            max-width: 300px;
            margin: 0;
        }

        @media screen and (max-width: 768px) {
            .header-div {
                padding: 20px 10px;
            }

            .select-container {
                flex-direction: column;
                gap: 10px;
            }

            select {
                width: 100%;
            }

            .sell-tickets {
                width: 100%;
                text-align: center;
            }

            .event-card {
                width: 90%;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-div">
            <div class="logo">Resell Me</div>
            <div class="select-container">
                <select name="locations" class="select-option" onchange="filterLocation(this.value)">
                    <option value="sports.php" <?php echo empty($location_filter) ? 'selected' : ''; ?>>All Locations</option>
                    <option value="sports.php?location=Bangalore" <?php echo ($location_filter == 'Bangalore') ? 'selected' : ''; ?>>Bangalore</option>
                    <option value="sports.php?location=Pune" <?php echo ($location_filter == 'Pune') ? 'selected' : ''; ?>>Pune</option>
                    <option value="sports.php?location=Mysore" <?php echo ($location_filter == 'Mysore') ? 'selected' : ''; ?>>Mysore</option>
                    <option value="sports.php?location=Mumbai" <?php echo ($location_filter == 'Mumbai') ? 'selected' : ''; ?>>Mumbai</option>
                    <option value="sports.php?location=Delhi" <?php echo ($location_filter == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
                </select>
                <button class="sell-tickets"><a href="sell-ticket-page.html" style="color: inherit; text-decoration: none;">Sell Tickets</a></button>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>Available Sports Tickets</h1>
        <div class="events">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div>";
                    echo "<div class='event-card'>";
                    echo "<div class='event-name'>" . htmlspecialchars($row['event_name']) . "</div>";
                    echo "<p>Location: " . htmlspecialchars($row['location']) . "</p>";
                    echo "<p>Date: " . htmlspecialchars($row['event_date']) . "</p>";
                    echo "<p>Price: ₹" . htmlspecialchars($row['price']) . "</p>";
                    echo "<a href='generate_qr.php?ticket_id=" . $row['ticket_id'] . "' class='buy-now-btn'>Buy Now</a>";
                    echo "</div>";
                    echo "<hr>";
                    echo "</div>";
                }
            } else {
                echo "<p>No sports tickets available for the selected location.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        function filterLocation(value) {
            window.location.href = value;
        }
    </script>
</body>
</html>



<?php
$stmt->close();
$conn->close();
?>
