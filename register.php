<?php
require "config/db.php";

$name     = $_POST['name'];
$email    = $_POST['email'];
$phone    = $_POST['phone'];
$password = password_hash($_POST['password'], password_default);

$sql = "insert into users (name, email, phone, password)
        values ('$name', '$email', '$phone', '$password')";

if (mysqli_query($conn, $sql)) {
    header("location: index.php");
    exit();
} else {
    echo "error: " . mysqli_error($conn);
}
