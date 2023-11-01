<?php
// Connect to the database
$mysqli = mysqli_connect("mi-linux.wlv.ac.uk", "2102091", "91n08m", "db2102091");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Check if a search query has been submitted
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // Construct the SQL query to search for games based on the title
    $query = "SELECT Title, Genre, prices
        FROM GAMES WHERE Title LIKE '%$search%'";
} else {
    // If no search query is provided, retrieve all games
    $query = "SELECT Title, Genre, prices FROM GAMES";
}



// Run SQL query
$res = mysqli_query($mysqli, $query);

// Check if there are any errors in the SQL statement
if (!$res) {
    print("MySQL error: " . mysqli_error($mysqli));
    exit;
}

// Start or resume the PHP session
session_start();

// Check if the session variable for start time exists or is expired
if (!isset($_SESSION['start_time']) || (time() - $_SESSION['start_time'] > 1800)) { // Set a time limit (e.g., 1800 seconds = 30 minutes)
    $_SESSION['start_time'] = time();
}
?>

<!DOCTYPE html>
<html>

<head>
    <style>
      body {
    background: radial-gradient(circle at top, #ff69b4, #00bfff, #4b0082);
}

        table {
            border-collapse: collapse;
            font-size: 0.9em;
            width: 70%;
            border-radius: 20px 20px 0 0;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        th,
        td {
            border: 1px solid black;
            padding: 15px;
            text-align: center;
        }

        tr {
            background-color: #000000;
        }

        th {
            background-color: #000000;
            color: #ffff;
        }

        tr:nth-child(even) {
            background: #667afd;
            color: #ffff;
        }

        tr:nth-child(odd) {
            color: #ffff;
        }

        tr:last-of-type {
            border-bottom: 2px solid #3ba8fa;
        }

        h1 {
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        div.form-container {
            text-align: center;
            background-color: #3ba8fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        /* Style the label for the search input */
        label {
            font-size: 18px;
            color: #ffffff;
            /* Text color */
        }

        /* Style the search input field */
        input[type="text"] {
            width: 300px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin: 10px;

        }

        /* Style the search button */
        input[type="submit"] {
            background-color: #ffffff;
            color: #3ba8fa;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Style the search button on hover */
        input[type="submit"]:hover {
            background-color: #3ba8fa;
            color: #ffffff;
        }

        .container a {
            display: inline-block;
            padding: 5px 10px;
            background-color: #667afd;
            text-decoration: none;
            color: #fff;
        }

        .container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>List of Games</h1>

    <!-- This code block displays the time elapsed since the user's session started.
     It calculates the minutes and seconds and presents a message indicating how long the user has been on the page. -->
    <div style="margin-bottom: 20px; text-align: center; color: #fff;
            padding: 10px;
            text-align: center;">
        <?php
        $currentTime = time(); // Get the current time
        $timeElapsed = $currentTime - $_SESSION['start_time']; // Calculate the time elapsed
        $minutes = floor($timeElapsed / 60); // Calculate the minutes
        $seconds = $timeElapsed % 60; // Calculate the remaining seconds
        echo "You've been here for " . $minutes . " minute(s) and " . $seconds . " second(s)."; // Display the message
        ?>
    </div>

    <!-- This code block generates a table displaying game information. It checks if there are search results (using mysqli_num_rows), and if so, it iterates through each game row, populating the table with details for each game. If there are no results, it displays a message indicating no results found. -->
    <div style="margin-bottom: 30px;">
        <table>
            <tr>
                <th>Title</th>
                <th>Genre</th>
                <th>Price</th>
              
            </tr>
            <?php
            if (mysqli_num_rows($res) == 0) { // Check if there are no search results
                echo "<tr><td colspan='6'>No results found.</td></tr>"; // Display a message for no results
            } else {
                while ($row = mysqli_fetch_assoc($res)) { // Iterate through search results
                    echo "<tr>"; // Start a new table row
                    echo "<td><a href='game_details.php?title=" . urlencode($row['Title']) . "' style='text-decoration: none; color: #ffff;'>" . $row['Title'] . "</a></td>"; // Display the game title as a link
                    echo "<td>" . $row['Genre'] . "</td>"; // Display the game genre
                    echo "<td>$" . number_format($row['prices'], 2) . "</td>"; // Display the game price formatted as currency
                    echo "</tr>"; // End the table row
                }
            }
            ?>
        </table>
    </div>



    <!-- This code block creates a search form and a link to go back to the game list. The form allows users to input a game title to search for, and upon submission, it triggers a GET request. The input field is pre-populated with the value of the $search variable (if provided). There is also a "Back to Game List" link that takes users back to the main game list. -->
    <div style="text-align: center;">
        <form method="get"> <!-- Create a form for searching games -->
            <label for="search">Search for a game:</label> <!-- Display a label for the search input -->
            <input type="text" name="search" id="search" value="<?php echo $search; ?>">
            <!-- Create a text input field for the search query and populate it with the current search value -->
            <input type="submit" value="Search"> <!-- Create a submit button to trigger the search -->
        </form>
        <div class="container" style="text-align: center;">
            <a href="index.php">click here to go Back to Game List</a> <!-- Display a link to go back to the main game list -->
        </div>
    </div>

</body>

</html>