<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM invoice WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: invoice.php");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    header("Location: invoice.php");
}
