<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password (blank)
$dbname = "ticket_exchange"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $event_name = $conn->real_escape_string($_POST['event_name']);
    $location = $conn->real_escape_string($_POST['location']);
    $event_date = $conn->real_escape_string($_POST['event_date']);
    $category = $conn->real_escape_string($_POST['category']);
    $price = floatval($_POST['price']); // Ensure price is treated as a float

    // Handle file upload
    $upload_dir = 'uploads/pdfs/';  // Folder where PDF will be uploaded
    $pdf_file = $_FILES['ticket_pdf']['name'];
    $pdf_tmp = $_FILES['ticket_pdf']['tmp_name'];
    $pdf_path = $upload_dir . basename($pdf_file);

    // Move the uploaded file to the server's directory
    if (move_uploaded_file($pdf_tmp, $pdf_path)) {
        // Insert data into the database, including the PDF path
        $sql = "INSERT INTO tickets (event_name, location, event_date, category, price, pdf_path, status) 
                VALUES ('$event_name', '$location', '$event_date', '$category', $price, '$pdf_path', 'pending')";

        if ($conn->query($sql) === TRUE) {
            echo "New ticket added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading the file.";
    }
}

$conn->close();
?>
