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

// Base SQL query for approved tickets in Concerts category
$sql = "SELECT ticket_id, event_name, location, event_date, price 
        FROM tickets 
        WHERE category = 'Concerts' AND status = 'approved'";

// Append location filter if selected
if (!empty($location_filter)) {
    $sql .= " AND location = '" . $conn->real_escape_string($location_filter) . "'";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concert Tickets</title>
    <link rel="stylesheet" href="style.css">
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .header-div {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ff0055;
            color: white;
            padding: 15px 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        select {
            padding: 8px;
            border-radius: 5px;
            border: none;
            font-size: 14px;
            outline: none;
        }

        .sell-tickets {
            background-color: white;
            color: #ff0055;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .sell-tickets a {
            color: #ff0055;
            text-decoration: none;
        }

        .container {
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }

        .events {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center the tickets */
            gap: 20px;
        }

        .event-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 350px; /* Wider cards */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-10px); /* Hover effect */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .event-card h3 {
            font-size: 20px;
            color: #343a40;
            margin: 0;
        }

        .event-card p {
            font-size: 14px;
            color: #6c757d;
            margin: 5px 0;
        }

        .buy-now-btn {
            background-color: #ff0055;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            font-weight: bold;
            align-self: flex-end;
            transition: background-color 0.3s ease-in-out;
        }

        .buy-now-btn:hover {
            background-color: #e6004e;
        }

        hr {
            border: none;
            height: 1px;
            background-color: #dee2e6;
            margin: 20px 0;
        }

        @media screen and (max-width: 768px) {
            .header-div {
                flex-direction: column;
                gap: 10px;
            }

            .events {
                gap: 15px;
                flex-direction: column;
                align-items: center;
            }

            .event-card {
                padding: 15px;
                width: 90%;
            }

            .buy-now-btn {
                padding: 8px 10px;
                font-size: 12px;
            }
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
    </style>
</head>
<body>
<header>
    <div class="header-div">
        <div class="logo">Resell Me</div>
        <div class="select-container">
            <select name="locations" class="select-option" onchange="filterLocation(this.value)">
                <option value="concerts.php" <?php echo empty($location_filter) ? 'selected' : ''; ?>>All Locations</option>
                <option value="concerts.php?location=Bangalore" <?php echo ($location_filter == 'Bangalore') ? 'selected' : ''; ?>>Bangalore</option>
                <option value="concerts.php?location=Pune" <?php echo ($location_filter == 'Pune') ? 'selected' : ''; ?>>Pune</option>
                <option value="concerts.php?location=Mysore" <?php echo ($location_filter == 'Mysore') ? 'selected' : ''; ?>>Mysore</option>
                <option value="concerts.php?location=Mumbai" <?php echo ($location_filter == 'Mumbai') ? 'selected' : ''; ?>>Mumbai</option>
                <option value="concerts.php?location=Delhi" <?php echo ($location_filter == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
            </select>
            <button class="sell-tickets"><a href="sell-ticket-page.html" style="color: inherit; text-decoration: none;">Sell Tickets</a></button>
        </div>
    </div>
</header>

<div class="container">
    <h1>Available Concert Tickets</h1>
    <div class="events">
        <?php
        if ($result->num_rows > 0) {
            $first = true;
            while ($row = $result->fetch_assoc()) {
                if (!$first) echo "<hr>";
                $first = false;
                echo "<div class='event-card'>";
                echo "<h3>" . htmlspecialchars($row['event_name']) . "</h3>";
                echo "<p>Location: " . htmlspecialchars($row['location']) . "</p>";
                echo "<p>Date: " . htmlspecialchars($row['event_date']) . "</p>";
                echo "<p>Price: ₹" . htmlspecialchars($row['price']) . "</p>";
                echo "<a href='generate_qr.php?ticket_id=" . $row['ticket_id'] . "' class='buy-now-btn'>Buy Now</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No concert tickets available for the selected location.</p>";
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
$conn->close();
?>
