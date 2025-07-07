<?php
include 'koneksi.php';

if (isset($_GET['import_number'])) {
    $import_number = $_GET['import_number'];
    $result = mysqli_query($conn, "SELECT * FROM import_invoice WHERE import_number = '$import_number'");
    $data = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit'])) {
    $import_date = $_POST['import_date'];
    $inbound_date = $_POST['inbound_date'];
    $supp_inv_1_no = $_POST['supp_inv_1_no'];
    $supp_inv_1_date = $_POST['supp_inv_1_date'];
    $supp_inv_1_amount = $_POST['supp_inv_1_amount'];
    $supp_dpp_1_amount = $_POST['supp_dpp_1_amount'];
    $supp_ppn_1_amount = $_POST['supp_ppn_1_amount'];

    $query = "UPDATE import_invoice SET 
        import_date='$import_date',
        inbound_date='$inbound_date',
        supp_inv_1_no='$supp_inv_1_no',
        supp_inv_1_date='$supp_inv_1_date',
        supp_inv_1_amount='$supp_inv_1_amount',
        supp_dpp_1_amount='$supp_dpp_1_amount',
        supp_ppn_1_amount='$supp_ppn_1_amount'
        WHERE import_number = '$import_number'";

    if (mysqli_query($conn, $query)) {
        header("Location: import_invoice.php");
    } else {
        echo "Gagal mengedit data: " . mysqli_error($conn);
    }
}
?>

<style>
body { font-family: Arial, sans-serif; background: #f2f2f2; padding: 20px; }
.container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px #ccc; }
h2 { text-align: center; }
form label { display: block; margin-top: 10px; }
form input { width: 100%; padding: 7px; margin-top: 5px; }
.button { padding: 7px 15px; background: #2196F3; color: white; border: none; border-radius: 3px; cursor: pointer; }
.button:hover { background: #1976D2; }
.back { display: inline-block; margin-top: 15px; color: purple; text-decoration: none; }
</style>

<div class="container">
<h2>Edit Import Invoice</h2>
<form method="POST">
    <label>Import Date:</label>
    <input type="date" name="import_date" value="<?= $data['import_date'] ?>">

    <label>Inbound Date:</label>
    <input type="date" name="inbound_date" value="<?= $data['inbound_date'] ?>">

    <label>Supp Inv 1 No:</label>
    <input type="text" name="supp_inv_1_no" value="<?= $data['supp_inv_1_no'] ?>">

    <label>Supp Inv 1 Date:</label>
    <input type="date" name="supp_inv_1_date" value="<?= $data['supp_inv_1_date'] ?>">

    <label>Supp Inv 1 Amount:</label>
    <input type="number" name="supp_inv_1_amount" value="<?= $data['supp_inv_1_amount'] ?>">

    <label>Supp DPP 1 Amount:</label>
    <input type="number" name="supp_dpp_1_amount" value="<?= $data['supp_dpp_1_amount'] ?>">

    <label>Supp PPN 1 Amount:</label>
    <input type="number" name="supp_ppn_1_amount" value="<?= $data['supp_ppn_1_amount'] ?>">

    <br><br>
    <button type="submit" name="submit" class="button">Update</button>
</form>
<a href="import_invoice.php" class="back">← Kembali</a>
</div>
