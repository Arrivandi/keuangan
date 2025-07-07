<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $project_code = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM supplier_progress WHERE project_code = '$project_code'");
    $data = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit'])) {
    $project_owner = $_POST['project_owner'];
    $project_name = $_POST['project_name'];
    $supplier_name = $_POST['supplier_name'];
    $item = $_POST['item'];
    $local_import = $_POST['local_import'];
    $po_mju_no = $_POST['po_mju_no'];
    $po_mju_date = $_POST['po_mju_date'];
    $payment_mju_status = $_POST['payment_mju_status'];
    $cash_out_total = $_POST['cash_out_total'];
    $import_number = $_POST['import_number'];

    $query = "UPDATE supplier_progress SET 
        project_owner='$project_owner',
        project_name='$project_name',
        supplier_name='$supplier_name',
        item='$item',
        local_import='$local_import',
        po_mju_no='$po_mju_no',
        po_mju_date='$po_mju_date',
        payment_mju_status='$payment_mju_status',
        cash_out_total='$cash_out_total',
        import_number='$import_number'
        WHERE project_code='$project_code'";

    if (mysqli_query($conn, $query)) {
        header("Location: supplier.php");
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
<h2>Edit Supplier Progress</h2>
<form method="POST">
    <label>Project Code:</label>
    <input type="text" value="<?= $data['project_code'] ?>" disabled>

    <label>Project Owner:</label>
    <input type="text" name="project_owner" value="<?= $data['project_owner'] ?>" required>

    <label>Project Name:</label>
    <input type="text" name="project_name" value="<?= $data['project_name'] ?>" required>

    <label>Supplier Name:</label>
    <input type="text" name="supplier_name" value="<?= $data['supplier_name'] ?>" required>

    <label>Item:</label>
    <input type="text" name="item" value="<?= $data['item'] ?>" required>

    <label>Local/Import:</label>
    <select name="local_import">
        <option value="Local" <?= ($data['local_import'] == 'Local') ? 'selected' : '' ?>>Local</option>
        <option value="Import" <?= ($data['local_import'] == 'Import') ? 'selected' : '' ?>>Import</option>
    </select>

    <label>PO MJU No:</label>
    <input type="text" name="po_mju_no" value="<?= $data['po_mju_no'] ?>">

    <label>PO MJU Date:</label>
    <input type="date" name="po_mju_date" value="<?= $data['po_mju_date'] ?>">

    <label>Payment MJU Status:</label>
    <select name="payment_mju_status">
        <option value="Unpaid" <?= ($data['payment_mju_status'] == 'Unpaid') ? 'selected' : '' ?>>Unpaid</option>
        <option value="Supp PPN 1" <?= ($data['payment_mju_status'] == 'Supp PPN 1') ? 'selected' : '' ?>>Supp PPN 1</option>
        <option value="Supp PPN 2" <?= ($data['payment_mju_status'] == 'Supp PPN 2') ? 'selected' : '' ?>>Supp PPN 2</option>
        <option value="Finish" <?= ($data['payment_mju_status'] == 'Finish') ? 'selected' : '' ?>>Finish</option>
    </select>

    <label>Cash Out Total:</label>
    <input type="number" name="cash_out_total" value="<?= $data['cash_out_total'] ?>">

    <label>Import Number:</label>
    <input type="text" name="import_number" value="<?= $data['import_number'] ?>">

    <br><br>
    <button type="submit" name="submit" class="button">Update</button>
</form>
<a href="supplier.php" class="back">← Kembali</a>
</div>
