<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Supplier Progress</title>
<style>
body { font-family: Arial, sans-serif; margin: 0; }
.navbar { background: #333; padding: 10px; position: sticky; top: 0; }
.navbar a { color: white; margin-right: 15px; text-decoration: none; }
.navbar a:hover { background: #555; padding: 5px; }
.content { padding: 20px; }
table { border-collapse: collapse; width: 100%; margin-top: 20px; font-size: 12px; }
th, td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 12px; }
th { background-color: #f2f2f2; }
tr:nth-child(even) { background-color: #f9f9f9; }
tr:hover { background-color: #e9e9e9; }
.pagination { text-align: center; margin-top: 15px; }
.pagination a { 
    display: inline-block; 
    margin: 0 5px; 
    text-decoration: none; 
    width: 25px; 
    height: 25px; 
    line-height: 25px; 
    text-align: center; 
    border: 1px solid #333; 
    border-radius: 50%; 
    color: #333; 
}
.pagination a.active { background: #333; color: white; }
.pagination a:hover { background: #555; color: white; }
.button { padding: 5px 10px; background: #4CAF50; color: white; text-decoration: none; border-radius: 3px; }
.button:hover { background: #45a049; }
td a.button {
    margin-right: 5px;
}

</style>
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="supplier.php">Supplier Progress</a>
    <a href="client.php">Client Progress</a>
    <a href="import_invoice.php">Import Invoice</a>
    <a href="invoice.php">Invoice</a>
</div>

<div class="content">
<h2>Supplier Progress</h2>

<form method="GET" style="margin-bottom: 15px;">
    <label>Jenis:</label>
    <select name="local_import">
        <option value="">-- Semua --</option>
        <option value="Local" <?= (isset($_GET['local_import']) && $_GET['local_import'] == 'Local') ? 'selected' : '' ?>>Local</option>
        <option value="Import" <?= (isset($_GET['local_import']) && $_GET['local_import'] == 'Import') ? 'selected' : '' ?>>Import</option>
    </select>

    <label>Status Payment:</label>
    <select name="payment_mju_status">
        <option value="">-- Semua --</option>
        <option value="Unpaid" <?= (isset($_GET['payment_mju_status']) && $_GET['payment_mju_status'] == 'Unpaid') ? 'selected' : '' ?>>Unpaid</option>
        <option value="Supp PPN 1" <?= (isset($_GET['payment_mju_status']) && $_GET['payment_mju_status'] == 'Supp PPN 1') ? 'selected' : '' ?>>Supp PPN 1</option>
        <option value="Supp PPN 2" <?= (isset($_GET['payment_mju_status']) && $_GET['payment_mju_status'] == 'Supp PPN 2') ? 'selected' : '' ?>>Supp PPN 2</option>
        <option value="Finish" <?= (isset($_GET['payment_mju_status']) && $_GET['payment_mju_status'] == 'Finish') ? 'selected' : '' ?>>Finish</option>
    </select>

    <button type="submit">Filter</button>
    <a href="supplier.php" class="button" style="background:#ddd; color:purple;">Reset</a>
    <a href="supplier_create.php" class="button">+ Tambah Data</a>
</form>

<table>
<tr>
    <th>Project Code</th>
    <th>Project Owner</th>
    <th>Project Name</th>
    <th>Supplier Name</th>
    <th>Item</th>
    <th>Local/Import</th>
    <th>PO MJU No</th>
    <th>PO MJU Date</th>
    <th>Payment MJU Status</th>
    <th>Cash Out Total</th>
    <th>Import Number</th>
    <th>Aksi</th>
</tr>
<?php
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

$whereArr = [];
if (!empty($_GET['local_import'])) {
    $local_import = mysqli_real_escape_string($conn, $_GET['local_import']);
    $whereArr[] = "local_import = '$local_import'";
}
if (!empty($_GET['payment_mju_status'])) {
    $payment_mju_status = mysqli_real_escape_string($conn, $_GET['payment_mju_status']);
    $whereArr[] = "payment_mju_status = '$payment_mju_status'";
}
$where = count($whereArr) ? "WHERE " . implode(" AND ", $whereArr) : "";

$result = mysqli_query($conn, "SELECT * FROM supplier_progress $where LIMIT $start, $per_page");
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['project_code']}</td>
        <td>{$row['project_owner']}</td>
        <td>{$row['project_name']}</td>
        <td>{$row['supplier_name']}</td>
        <td>{$row['item']}</td>
        <td>{$row['local_import']}</td>
        <td>{$row['po_mju_no']}</td>
        <td>{$row['po_mju_date']}</td>
        <td>{$row['payment_mju_status']}</td>
        <td>Rp " . number_format($row['cash_out_total'], 0, ',', '.') . "</td>
        <td>{$row['import_number']}</td>
        <td>
    <a href='supplier_edit.php?id={$row['project_code']}' class='button' style='background:#2196F3; margin-right:5px;'>Edit</a>
    <a href='supplier_delete.php?id={$row['project_code']}' class='button' style='background:#f44336;' onclick='return confirm(\"Yakin hapus data ini?\")'>Hapus</a>
</td>

    </tr>";
}
?>
</table>

<div class="pagination">
<?php
$total_rows = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM supplier_progress $where"));
$total_pages = ceil($total_rows / $per_page);

$params = [];
if (isset($_GET['local_import'])) $params[] = "local_import=" . $_GET['local_import'];
if (isset($_GET['payment_mju_status'])) $params[] = "payment_mju_status=" . $_GET['payment_mju_status'];
$paramStr = count($params) ? "&" . implode("&", $params) : "";

if ($page > 1) {
    echo "<a href='supplier.php?page=" . ($page - 1) . "$paramStr'>&laquo;</a> ";
}
for ($i = 1; $i <= $total_pages; $i++) {
    $active = $i == $page ? "active" : "";
    echo "<a class='$active' href='supplier.php?page=$i$paramStr'>$i</a> ";
}
if ($page < $total_pages) {
    echo "<a href='supplier.php?page=" . ($page + 1) . "$paramStr'>&raquo;</a> ";
}
?>
</div>

</div>
</body>
</html>
