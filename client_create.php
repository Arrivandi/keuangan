<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $project_code = $_POST['project_code'];
    $project_owner = $_POST['project_owner'];
    $project_name = $_POST['project_name'];
    $po_date = $_POST['po_date'];
    $end_po_date = $_POST['end_po_date'];
    $payment_status = $_POST['payment_status'];
    $po_value = $_POST['po_value'];
    $total_invoice = $_POST['total_invoice'];

    $query = "INSERT INTO client_progress (project_code, project_owner, project_name, po_date, end_po_date, payment_status, po_value, total_invoice) 
              VALUES ('$project_code', '$project_owner', '$project_name', '$po_date', '$end_po_date', '$payment_status', '$po_value', '$total_invoice')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: client.php");
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($conn);
    }
}
?>

<style>
body { font-family: Arial, sans-serif; background: #f2f2f2; padding: 20px; }
.container { max-width: 500px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px #ccc; }
h2 { text-align: center; }
form label { display: block; margin-top: 10px; }
form input, form select { width: 100%; padding: 7px; margin-top: 5px; }
.button {
    padding: 7px 15px;
    background: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 3px;
    border: none;
    cursor: pointer;
}
.button:hover {
    background: #45a049;
}
.back {
    display: inline-block;
    margin-top: 15px;
    color: purple;
    text-decoration: none;
}
</style>

<div class="container">
<h2>Tambah Client Progress</h2>
<form method="POST">
    <label>Project Code:</label>
    <input type="text" name="project_code" required>

    <label>Project Owner:</label>
    <input type="text" name="project_owner" required>

    <label>Project Name:</label>
    <input type="text" name="project_name" required>

    <label>PO Date:</label>
    <input type="date" name="po_date">

    <label>End PO Date:</label>
    <input type="date" name="end_po_date">

    <label>Payment Status:</label>
    <select name="payment_status">
        <option value="Not Yet Payment">Not Yet Payment</option>
        <option value="Invoice 1">Invoice 1</option>
        <option value="Invoice 2">Invoice 2</option>
        <option value="Finish">Finish</option>
    </select>

    <label>PO Value:</label>
    <input type="number" name="po_value">

    <label>Total Invoice:</label>
    <input type="number" name="total_invoice">

    <br><br>
    <button type="submit" name="submit" class="button">Simpan</button>
</form>
<a href="client.php" class="back">← Kembali</a>
</div>
