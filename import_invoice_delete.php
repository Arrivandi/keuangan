<?php
include 'koneksi.php';

if (isset($_GET['import_number'])) {
    $import_number = $_GET['import_number'];
    $query = "DELETE FROM import_invoice WHERE import_number = '$import_number'";

    if (mysqli_query($conn, $query)) {
        header("Location: import_invoice.php");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    header("Location: import_invoice.php");
}
