<?php
session_start();
if(!isset($_SESSION["user"])){
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resell Me</title>
    <link rel="stylesheet" href="indexstyle.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');

        body {
            margin: 0;
            background-color: #ffffff;
            font-family: "Roboto", sans-serif;
            padding: 0;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .header-div {
            display: flex;
            align-items: center;
            justify-content: space-evenly; /* Evenly distribute items */
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            position: fixed;
            background-color: white;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        h4 a{
            color:black;
            font-size: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff0055;
            margin-left: 0; /* Remove any margin on the left */
        }

        .sell-tickets {
            background-color:#ff0055; /* Matches the header background */
            border: none; /* Removes border */
            outline: none;
            color:white; /* Black text color */
            cursor: pointer; /* Pointer cursor for clickable feel */
            font-size: 16px;
            padding: 0; /* Removes padding */
            text-align: center;
            width: 120px;
            height: 40px;
            border-radius: 5px;
        }

        button a {
            text-decoration: none;
            color: black;
        }

        button{
            width: 120px;
            height: 40px;
            padding: 8px;
            font-size: 20px;
            color: black;
            border-radius: 6px;
            background: transparent;
            outline: none;
        }

        .sell-tickets a {
            text-decoration: none; /* Removes underline */
            color: inherit; /* Inherits color from parent button */
            font-weight: normal; /* Matches header text weight */
        }

        .about-us a{
            text-decoration:none;
        }

        h4 .about-us{
            color: black;
        }

        /* Profile Icon */
        .profile-icon {
            font-size: 24px;
            color: black;
            cursor: pointer;
            margin-left: 20px;
        }

        .profile-icon:hover {
            color: #ff0055;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-div {
                flex-wrap: wrap;
                justify-content: space-around;
            }

            .second-section h1 {
                font-size: 24px;
            }


            .fourth-section .events {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .logo {
                font-size: 20px;
                margin-left: 10px;
            }

            .header-div > * {
                margin: 5px;
            }

            .second-section h1 {
                font-size: 20px;
            }

            .second-section {
                padding: 60px 10px 10px;
            }

            .fourth-section .event-card {
                width: 90%;
            }
        }
        .profile-icon {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-icon img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .profile-icon span {
        font-size: 14px;
        color: black;
    }

    @media (max-width: 768px) {
        .profile-icon {
            display: none; /* Hide the profile icon on small screens if needed */
        }
    }
    .second-section{
        margin:0px;
        padding:90px;
    }
    li .flex{
        outline:none;
        height:50px;
        text-align:center;
        border-radius:10px;
        background-color:#FF407D;
        font-size:18px;
    }

    </style>
</head>
<body>
<div class="main-container">
<header class="p-4 dark:bg-gray-100 dark:text-gray-800"style="background-color: white;">
        <div class="container flex justify-between h-16 mx-auto">
            <a rel="noopener noreferrer" href="index.php" aria-label="Back to homepage" class="flex items-center p-2">
                <div class="logo">RESELL ME</div>
            </a>
            <ul class="items-stretch hidden space-x-3 md:flex">
                <li class="flex"    >
                    <a rel="noopener noreferrer" href="sell-ticket-page.html" class="flex items-center px-4 -mb-1 border-b-2 dark:border-"style="color: white;">Sell tickets</a>
                </li>
                <li class="flex" >
                    <a rel="noopener noreferrer" href="aboutus.html" class="flex items-center px-4 -mb-1 border-b-2 dark:border-"style="color: white;">About us</a>
                </li>
                <li class="flex" >
                    <a rel="noopener noreferrer" href="logout.php" class="flex items-center px-4 -mb-1 border-b-2 dark:border-"style="color: white;">Logout</a>
                </li>
            </ul>
            
        </div>
    </header>

    <div class="second-section">
        <h1>Exchange your tickets <span>Hassle Free<br/></span> with <span>Resell Me</span></h1>
    </div>
</div>
    <div class="third-section">
        <h2>Looking for Tickets?</h2>
        <p>Available re-sale tickets of Sports and Entertainment are listed below !!</p>
        <div class="fourth-section">
            <div class="events flex justify-around mt-10">
                <!-- Sports Card -->
                <a href="sports.php" class="event-card group relative rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 ease-in-out w-64 h-64">
                    <img src="images/sports.jpg" alt="Sports" class="w-full h-full object-cover transition-transform duration-300 transform group-hover:scale-110">
                    <div class="absolute inset-0 bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                        <h3 class="text-white text-xl font-semibold">Sports</h3>
                    </div>
                </a>
                <!-- Concerts Card -->
                <a href="concerts.php" class="event-card group relative rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 ease-in-out w-64 h-64">
                    <img src="images/events.jpg" alt="Concerts" class="w-full h-full object-cover transition-transform duration-300 transform group-hover:scale-110">
                    <div class="absolute inset-0 bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                        <h3 class="text-white text-xl font-semibold">Concerts</h3>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

    <!-- Other sections of your HTML code -->
    <footer class="dark:bg-gray-100 dark:text-gray-900">
        <div class="container flex flex-col p-4 mx-auto md:p-8 lg:flex-row dark:divide-gray-600">
            <ul class="self-center py-6 space-y-4 text-center sm:flex sm:space-y-0 sm:justify-around sm:space-x-4 lg:flex-1 lg:justify-start">
                <li>Shop</li>
                <li>About</li>
                <li>Blog</li>
                <li>Pricing</li>
                <li>Contact</li>
            </ul>
            <div class="flex flex-col justify-center pt-6 lg:pt-0">
                <div class="flex justify-center space-x-4">
                    <!-- Instagram -->
                    <div class="profile-icon" >
                            <img src="images/1161954_instagram_icon.png" alt="Profile Icon" class="w-8 h-8 rounded-full">
                    </div>
                    <!-- Twitter -->
                    <div class="profile-icon" >
                        <img src="images/104501_twitter_bird_icon.png" alt="Profile Icon" class="w-8 h-8 rounded-full">
                    </div>
                    <!-- Additional Icons (Facebook, Pinterest, etc.) -->
                </div>
            </div>
        </div>
    </footer>

    
</body>


</html>
