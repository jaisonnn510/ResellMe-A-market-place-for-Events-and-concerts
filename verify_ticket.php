<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticket_exchange";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Approve or Reject ticket
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ticket_id = $_POST['ticket_id'];
    $action = $_POST['action']; // 'approve' or 'reject'

    $status = ($action === 'approve') ? 'approved' : 'rejected';

    $sql = "UPDATE tickets SET status = ? WHERE ticket_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $ticket_id);
    $stmt->execute();

    echo "<p>Ticket ID $ticket_id has been $status.</p>";
}

// Fetch pending tickets
$sql = "SELECT ticket_id, event_name, location, event_date, price, category FROM tickets WHERE status = 'pending'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Ticket Verification</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Pending Ticket Verification</h1>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Ticket ID</th>
                <th>Event Name</th>
                <th>Location</th>
                <th>Date</th>
                <th>Price</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['ticket_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                    <td>₹<?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="ticket_id" value="<?php echo $row['ticket_id']; ?>">
                            <button type="submit" name="action" value="approve">Approve</button>
                            <button type="submit" name="action" value="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No pending tickets at the moment.</p>
    <?php endif; ?>
</body>
</html>

<?php
$conn->close();
?>
