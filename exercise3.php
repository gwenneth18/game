<?php

// Connect to server/database
$mysqli = mysqli_connect("mi-linux.wlv.ac.uk", "2102091", "Kiddominat1-", "db2102091");
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
} else {
echo "Connected to the database successfully.";
}


// Run SQL query
$res = mysqli_query($mysqli, "SELECT Title, Genres, Prices FROM Games");

// Are there any errors in my SQL statement?
if(!$res) {
  print("MySQL error: " . mysqli_error($mysqli));
  exit;
}

// How many rows were returned?
echo("<p>" . mysqli_num_rows($res) . " record(s) were returned...</p>");

// Loop through resultset and display each field's value
while($row = mysqli_fetch_assoc($res)) {
  echo $row['Title']. " - ". $row['Genres'] ."<br>";
}

?>
