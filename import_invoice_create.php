<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $import_number = $_POST['import_number'];
    $import_date = $_POST['import_date'];
    $inbound_date = $_POST['inbound_date'];
    $supp_inv_1_no = $_POST['supp_inv_1_no'];
    $supp_inv_1_date = $_POST['supp_inv_1_date'];
    $supp_inv_1_amount = $_POST['supp_inv_1_amount'];
    $supp_dpp_1_amount = $_POST['supp_dpp_1_amount'];
    $supp_ppn_1_amount = $_POST['supp_ppn_1_amount'];

    $query = "INSERT INTO import_invoice 
    (import_number, import_date, inbound_date, supp_inv_1_no, supp_inv_1_date, supp_inv_1_amount, supp_dpp_1_amount, supp_ppn_1_amount)
    VALUES 
    ('$import_number', '$import_date', '$inbound_date', '$supp_inv_1_no', '$supp_inv_1_date', '$supp_inv_1_amount', '$supp_dpp_1_amount', '$supp_ppn_1_amount')";

    if (mysqli_query($conn, $query)) {
        header("Location: import_invoice.php");
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($conn);
    }
}
?>

<style>
body { font-family: Arial, sans-serif; background: #f2f2f2; padding: 20px; }
.container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px #ccc; }
h2 { text-align: center; }
form label { display: block; margin-top: 10px; }
form input { width: 100%; padding: 7px; margin-top: 5px; }
.button { padding: 7px 15px; background: #4CAF50; color: white; border: none; border-radius: 3px; cursor: pointer; }
.button:hover { background: #45a049; }
.back { display: inline-block; margin-top: 15px; color: purple; text-decoration: none; }
</style>

<div class="container">
<h2>Tambah Import Invoice</h2>
<form method="POST">
    <label>Import Number:</label>
    <input type="text" name="import_number" required>

    <label>Import Date:</label>
    <input type="date" name="import_date">

    <label>Inbound Date:</label>
    <input type="date" name="inbound_date">

    <label>Supp Inv 1 No:</label>
    <input type="text" name="supp_inv_1_no">

    <label>Supp Inv 1 Date:</label>
    <input type="date" name="supp_inv_1_date">

    <label>Supp Inv 1 Amount:</label>
    <input type="number" name="supp_inv_1_amount">

    <label>Supp DPP 1 Amount:</label>
    <input type="number" name="supp_dpp_1_amount">

    <label>Supp PPN 1 Amount:</label>
    <input type="number" name="supp_ppn_1_amount">

    <br><br>
    <button type="submit" name="submit" class="button">Simpan</button>
</form>
<a href="import_invoice.php" class="back">← Kembali</a>
</div>
