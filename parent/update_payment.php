<?php
// payment_callback.php

session_start();
include("../conn_db.php");

// Check for the expected POST/GET data from the payment gateway
if (isset($_GET['status_id']) && $_GET['status_id'] == '1' && isset($_GET['payment_id']) && isset($_GET['transaction_id'])) {
  $p_id = $mysqli->real_escape_string($_GET['payment_id']);
  $transaction_id = $mysqli->real_escape_string($_GET['transaction_id']);

  // Update the payment status in your database
  $update_query = "UPDATE payment SET p_type = 'PAID', p_detail = ? WHERE p_id = ?";

  if ($stmt = $mysqli->prepare($update_query)) {
    $stmt->bind_param('si', $transaction_id, $p_id);

    if ($stmt->execute()) {
      // Redirect or inform the user of successful payment
      header("Location: parent_order_list.php");
      exit;
    } else {
      // Handle the error during update
      echo "Error updating payment: " . $stmt->error;
    }

    $stmt->close();
  } else {
    echo "Error preparing SQL statement.";
  }
}

$mysqli->close();
?>
