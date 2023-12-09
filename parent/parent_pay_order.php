<?php
// parent_pay_order.php

session_start();
include("../conn_db.php");

// Check if the user is logged in and is a parent
if ($_SESSION["utype"] != "PARENT") {
  header("location: ../restricted.php");
  exit;
}

// Assume $_POST['orh_id'], $_POST['order_total'], $_POST['customer_name'], and $_POST['customer_email'] are set and validated
$orh_id = $_POST['orh_id'];
$order_total = $_POST['order_total'];
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];

// Payment gateway data
$harga = $order_total; // Assuming the total price is already in the correct format
$nama = $customer_name;
$email = $customer_email;

// Convert RM1=100 for the payment gateway
$rmx100 = ($harga * 100);

// Prepare the data for the payment gateway
$some_data = array(
  'userSecretKey' => 'um8gei8j-h8t3-f5yy-vtma-zted3wtaq3dj',
  'categoryCode' => '7pa122f1',
  'billName' => 'Order Purchase',
  'billDescription' => 'Order payment RM' . $harga,
  'billPriceSetting' => 1,
  'billPayorInfo' => 1,
  'billAmount' => $rmx100,
  'billReturnUrl' => 'http://www.youtube.com',
  'billCallbackUrl' => '',
  'billExternalReferenceNo' => '',
  'billTo' => $nama,
  'billEmail' => $email,
  'billPhone' => "01119950594",
  'billSplitPayment' => 0,
  'billSplitPaymentArgs' => '',
  'billPaymentChannel' => 0,
);
$curl = curl_init();
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
$result = curl_exec($curl);
$info = curl_getinfo($curl);
curl_close($curl);
// Decode JSON response into PHP array
$obj = json_decode($result, true);

// Check if the response is as expected
if (json_last_error() === JSON_ERROR_NONE && is_array($obj) && isset($obj[0]['BillCode'])) {
  // Retrieve the BillCode
  $billcode = $obj[0]['BillCode'];

  // Now you can use the BillCode for further processing or redirection
  // For example, redirect to the payment page:
  header("Location: https://toyyibpay.com/{$billcode}");
  exit;
} else {
  // Handle error, the response might not be as expected
  // Log error or notify the user/administrator
  echo "Error retrieving the BillCode.";
}
