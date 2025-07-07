<?php // delete.php
include 'koneksi.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM supplier_progress WHERE id = $id");
}
header("Location: supplier.php");
exit();
?>
