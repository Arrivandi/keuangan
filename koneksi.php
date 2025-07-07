<?php
$host = "localhost";
$user = "root"; // default di XAMPP
$pass = "";
$db   = "keuangan";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
