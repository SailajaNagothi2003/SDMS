<?php
session_start();
include("includes/dbconnection.php");
date_default_timezone_set('Africa/Kampala');
$ldate = date('d-m-Y h:i:s A', time());
$email = $_SESSION['email'];

$sql = "UPDATE userlog SET logout = :ldate WHERE userEmail = :email ORDER BY id DESC LIMIT 1";
$query = $dbh->prepare($sql);
$query->bindParam(':ldate', $ldate, PDO::PARAM_STR);
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: LoginPage.php");
exit();
?>
