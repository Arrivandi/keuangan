<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM client_progress WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: client.php");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    header("Location: client.php");
}
