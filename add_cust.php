<?php
//For inserting new customer to database
include('conn_db.php');
$pwd = $_POST["pwd"];
$cfpwd = $_POST["cfpwd"];
if ($pwd != $cfpwd) {
?>
    <script>
        alert('Your password is not match.\nPlease enter it again.');
        history.back();
    </script>
    <?php
    exit(1);
} else {
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $type = $_POST["type"];
    $income_group = $_POST["income_group"];

    if ($gender == "-" || $type == "-") {
    ?>
        <script>
            alert('You didn\'t select your gender or role yet.\nPlease select again!');
            history.back();
        </script>
    <?php
        exit(1);
    }

    //Check for duplicating username
    $query = "SELECT c_username FROM customer WHERE c_username = '$username';";
    $result = $mysqli->query($query);
    if ($result->num_rows >= 1) {
    ?>
        <script>
            alert('Your username is already taken!');
            history.back();
        </script>
    <?php
    }
    $result->free_result();

    //Check for duplicating email
    $query = "SELECT c_email FROM customer WHERE c_email = '$email';";
    $result = $mysqli->query($query);
    if ($result->num_rows >= 1) {
    ?>
        <script>
            alert('Your email is already in use!');
            history.back();
        </script>
<?php
    }
    $result->free_result();


    $query = "INSERT INTO customer (c_username,c_pwd,c_firstname,c_lastname,c_email,c_gender,c_type, income_group)
        VALUES ('$username','$pwd','$firstname','$lastname','$email','$gender','$type', '$income_group');";

    $result = $mysqli->query($query);
    $new_customer_id = $mysqli->insert_id; // Get the last inserted id

    if ($result) {
        // Check if the type is Parent, and if so, insert the relationship
        if ($type == 'PRT' && isset($_POST['student_id'])) {
            $student_id = $_POST['student_id']; // Make sure to sanitize this input as well
            // Insert the parent-student relationship
            $relation_query = "INSERT INTO parent_student (parent_id, student_id) VALUES ('$new_customer_id', '$student_id');";
            $relation_result = $mysqli->query($relation_query);

            // Check if relation insert was successful
            if ($relation_result) {
                // If everything was successful
                header("location: cust_regist_success.php");
            } else {
                // Handle error
                echo "Error: " . $mysqli->error;
                // Optionally delete the inserted customer or handle as per your requirements
            }
        } else {
            // If not parent or no student_id posted, just redirect to success
            header("location: cust_regist_success.php");
        }
    } else {
        header("location: cust_regist_fail.php?err={$mysqli->errno}");
    }
}
?>