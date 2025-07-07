<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM invoice WHERE id = '$id'");
    $data = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit'])) {
    $project_code = $_POST['project_code'];
    $project_owner = $_POST['project_owner'];
    $invoice_number = $_POST['invoice_number'];
    $invoice_amount = $_POST['invoice_amount'];
    $dpp = $_POST['dpp'];
    $invoice_date = $_POST['invoice_date'];
    $ppn = $_POST['ppn'];

    $query = "UPDATE invoice SET 
        project_code='$project_code',
        project_owner='$project_owner',
        invoice_number='$invoice_number',
        invoice_amount='$invoice_amount',
        dpp='$dpp',
        invoice_date='$invoice_date',
        ppn='$ppn'
        WHERE id='$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: invoice.php");
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
<h2>Edit Invoice</h2>
<form method="POST">
    <label>Project Code:</label>
    <input type="text" name="project_code" value="<?= $data['project_code'] ?>" required>

    <label>Project Owner:</label>
    <input type="text" name="project_owner" value="<?= $data['project_owner'] ?>" required>

    <label>Invoice Number:</label>
    <input type="text" name="invoice_number" value="<?= $data['invoice_number'] ?>" required>

    <label>Invoice Amount:</label>
    <input type="number" name="invoice_amount" value="<?= $data['invoice_amount'] ?>" required>

    <label>DPP:</label>
    <input type="number" name="dpp" value="<?= $data['dpp'] ?>">

    <label>Invoice Date:</label>
    <input type="date" name="invoice_date" value="<?= $data['invoice_date'] ?>">

    <label>PPN:</label>
    <input type="number" name="ppn" value="<?= $data['ppn'] ?>">

    <br><br>
    <button type="submit" name="submit" class="button">Update</button>
</form>
<a href="invoice.php" class="back">← Kembali</a>
</div>
