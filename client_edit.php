<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM client_progress WHERE id = $id");
    $data = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit'])) {
    $project_code = $_POST['project_code'];
    $project_owner = $_POST['project_owner'];
    $project_name = $_POST['project_name'];
    $po_date = $_POST['po_date'];
    $end_po_date = $_POST['end_po_date'];
    $payment_status = $_POST['payment_status'];
    $po_value = $_POST['po_value'];
    $total_invoice = $_POST['total_invoice'];

    $query = "UPDATE client_progress SET 
                project_code='$project_code',
                project_owner='$project_owner',
                project_name='$project_name',
                po_date='$po_date',
                end_po_date='$end_po_date',
                payment_status='$payment_status',
                po_value='$po_value',
                total_invoice='$total_invoice'
              WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        header("Location: client.php");
    } else {
        echo "Gagal mengedit data: " . mysqli_error($conn);
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
    background: #2196F3;
    color: white;
    text-decoration: none;
    border-radius: 3px;
    border: none;
    cursor: pointer;
}
.button:hover {
    background: #1976D2;
}
.back {
    display: inline-block;
    margin-top: 15px;
    color: purple;
    text-decoration: none;
}
</style>

<div class="container">
<h2>Edit Client Progress</h2>
<form method="POST">
    <label>Project Code:</label>
    <input type="text" name="project_code" value="<?= $data['project_code'] ?>" required>

    <label>Project Owner:</label>
    <input type="text" name="project_owner" value="<?= $data['project_owner'] ?>" required>

    <label>Project Name:</label>
    <input type="text" name="project_name" value="<?= $data['project_name'] ?>" required>

    <label>PO Date:</label>
    <input type="date" name="po_date" value="<?= $data['po_date'] ?>">

    <label>End PO Date:</label>
    <input type="date" name="end_po_date" value="<?= $data['end_po_date'] ?>">

    <label>Payment Status:</label>
    <select name="payment_status">
        <option value="Not Yet Payment" <?= ($data['payment_status'] == 'Not Yet Payment') ? 'selected' : '' ?>>Not Yet Payment</option>
        <option value="Invoice 1" <?= ($data['payment_status'] == 'Invoice 1') ? 'selected' : '' ?>>Invoice 1</option>
        <option value="Invoice 2" <?= ($data['payment_status'] == 'Invoice 2') ? 'selected' : '' ?>>Invoice 2</option>
        <option value="Finish" <?= ($data['payment_status'] == 'Finish') ? 'selected' : '' ?>>Finish</option>
    </select>

    <label>PO Value:</label>
    <input type="number" name="po_value" value="<?= $data['po_value'] ?>">

    <label>Total Invoice:</label>
    <input type="number" name="total_invoice" value="<?= $data['total_invoice'] ?>">

    <br><br>
    <button type="submit" name="submit" class="button">Update</button>
</form>
<a href="client.php" class="back">← Kembali</a>
</div>
