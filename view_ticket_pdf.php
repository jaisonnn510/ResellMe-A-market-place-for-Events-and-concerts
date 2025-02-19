<?php
if (isset($_GET['ticket_id'])) {
    $ticket_id = $_GET['ticket_id'];

    // Validate ticket_id is a valid integer
    if (!is_numeric($ticket_id)) {
        echo "Invalid ticket ID.";
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = ""; // Default XAMPP password
    $dbname = "ticket_exchange"; // Your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch the PDF path for the ticket
    $sql = "SELECT pdf_path FROM tickets WHERE ticket_id = ?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Check if preparation was successful
    if (!$stmt) {
        echo "SQL prepare failed: " . $conn->error;
        exit();
    }

    // Bind the ticket_id parameter and execute the statement
    $stmt->bind_param("i", $ticket_id); // 'i' means integer type
    $stmt->execute();

    $result = $stmt->get_result();

    // Check if the ticket is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pdf_path = $row['pdf_path'];

        // Check if the file exists and display it
        if (file_exists($pdf_path)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($pdf_path) . '"');
            readfile($pdf_path);
        } else {
            echo "PDF file not found.";
        }
    } else {
        echo "Ticket not found.";
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Ticket ID is required.";
}
?>
