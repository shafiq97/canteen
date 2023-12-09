<?php
// Include database connection
include("conn_db.php");

// Query to get students
$query = "SELECT * FROM customer WHERE c_type = 'STD'";
$result = mysqli_query($mysqli, $query);

$students = array();
while($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

// Return results as JSON
echo json_encode($students);
