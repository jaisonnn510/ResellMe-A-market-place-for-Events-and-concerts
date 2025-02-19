<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        .form-group input {
            height: 50px;
            font-size: 1rem;
        }
        .btn-primary {
        background-color: #D91656;
        width: 100%;
        height: 50px;
        font-size: 1.2rem;
        font-weight: bold;
        }
        .register {
            text-align: center;
            margin-top: 15px;
        }
        .register a {
            text-decoration: none;
            font-weight: bold;
            color: #007bff;
        }
        .register a:hover {
            color: #D91656;
        }
        .form-heading {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-heading h1 {
            font-size: 2rem;
            font-weight: bold;
        }
        .alert {
            margin-bottom: 15px;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <?php 
            if (isset($_POST["submit"])) {
                $fullName = $_POST["fullname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $passwordRepeat = $_POST["repeat_password"];
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                $errors = array();

                if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
                    array_push($errors, "All fields are required.");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Email is not valid.");
                }
                if (strlen($password) < 8) {
                    array_push($errors, "Password must be at least 8 characters.");
                }
                if ($password !== $passwordRepeat) {
                    array_push($errors, "Passwords do not match.");
                }

                require_once "database.php";
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    array_push($errors, "Email already exists!");
                }

                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                } else {
                    $sql = "INSERT INTO users(full_name, email, password) VALUES (?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                        mysqli_stmt_execute($stmt);
                        echo "<div class='alert alert-success'>You are registered successfully!</div>";
                    } else {
                        die("Something went wrong.");
                    }
                }
            }
        ?>
        <div class="form-heading">
            <h1>Register</h1>
        </div>
        <form action="registration.php" method="post">
            <div class="form-group mb-3">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
            </div>
            <div class="form-group mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-group mb-3">
                <input type="password" name="repeat_password" class="form-control" placeholder="Repeat Password" required>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="submit" value="Register">
            </div>
            <div class="register mt-3">
                <p>Have an account? <a href="login.php">Sign In</a></p>
            </div>
        </form>
    </div>
</body>
</html>
