<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Invoice</title>
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
.pagination a.prev,
.pagination a.next {
    margin: 0 15px;
}
.pagination a.active { background: #333; color: white; }
.pagination a:hover { background: #555; color: white; }
.button {
    padding: 5px 10px;
    background: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 3px;
    font-size: 12px;
}
.button:hover {
    background: #45a049;
}
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
<h2>Invoice</h2>

<form method="GET" style="margin-bottom: 15px;">
    <label>Invoice Number:</label>
    <select name="invoice_number">
        <option value="">-- All --</option>
        <?php
        $invoiceList = mysqli_query($conn, "SELECT DISTINCT invoice_number FROM invoice ORDER BY invoice_number ASC");
        while ($inv = mysqli_fetch_assoc($invoiceList)) {
            $selected = (isset($_GET['invoice_number']) && $_GET['invoice_number'] == $inv['invoice_number']) ? "selected" : "";
            echo "<option value='{$inv['invoice_number']}' $selected>{$inv['invoice_number']}</option>";
        }
        ?>
    </select>
    <button type="submit">Filter</button>
    <a href="invoice.php" style="padding:5px 10px; background:#ddd; border:1px solid #aaa; text-decoration:none; color:purple;">Reset</a>
    <a href="invoice_create.php" class="button">+ Tambah Data</a>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Project Code</th>
    <th>Project Owner</th>
    <th>Invoice Number</th>
    <th>Invoice Amount</th>
    <th>DPP</th>
    <th>Invoice Date</th>
    <th>PPN</th>
    <th>Aksi</th>

</tr>
<?php
$where = "";
if (!empty($_GET['invoice_number'])) {
    $invoice_number = mysqli_real_escape_string($conn, $_GET['invoice_number']);
    $where = "WHERE invoice_number = '$invoice_number'";
}

$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

$result = mysqli_query($conn, "SELECT * FROM invoice $where ORDER BY id DESC LIMIT $start, $per_page");
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['project_code']}</td>
        <td>{$row['project_owner']}</td>
        <td>{$row['invoice_number']}</td>
        <td>Rp " . number_format($row['invoice_amount'], 0, ',', '.') . "</td>
        <td>Rp " . number_format($row['dpp'], 0, ',', '.') . "</td>
        <td>{$row['invoice_date']}</td>
        <td>Rp " . number_format($row['ppn'], 0, ',', '.') . "</td>
        <td>
            <a href='invoice_edit.php?id={$row['id']}' class='button' style='background:#2196F3;'>Edit</a>
            <a href='invoice_delete.php?id={$row['id']}' class='button' style='background:#f44336;' onclick='return confirm(\"Yakin hapus data ini?\")'>Hapus</a>
        </td>
        </tr>";
}
?>
</table>

<div class="pagination">
<?php
$count = mysqli_query($conn, "SELECT COUNT(*) AS total FROM invoice $where");
$total_rows = mysqli_fetch_assoc($count)['total'];
$total_pages = ceil($total_rows / $per_page);

$param = "";
if (isset($_GET['invoice_number'])) $param .= "&invoice_number=" . urlencode($_GET['invoice_number']);

if ($page > 1) {
    echo "<a class='prev' href='invoice.php?page=" . ($page - 1) . "$param'>&laquo;</a> ";
}

if ($total_pages <= 3) {
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? "active" : "";
        echo "<a class='$active' href='invoice.php?page=$i$param'>$i</a> ";
    }
} else {
    if ($page <= 2) {
        for ($i = 1; $i <= 3; $i++) {
            $active = $i == $page ? "active" : "";
            echo "<a class='$active' href='invoice.php?page=$i$param'>$i</a> ";
        }
        echo "...";
    } elseif ($page >= $total_pages - 1) {
        echo "... ";
        for ($i = $total_pages - 2; $i <= $total_pages; $i++) {
            $active = $i == $page ? "active" : "";
            echo "<a class='$active' href='invoice.php?page=$i$param'>$i</a> ";
        }
    } else {
        echo "... ";
        for ($i = $page - 1; $i <= $page + 1; $i++) {
            $active = $i == $page ? "active" : "";
            echo "<a class='$active' href='invoice.php?page=$i$param'>$i</a> ";
        }
        echo "...";
    }
}

if ($page < $total_pages) {
    echo "<a class='next' href='invoice.php?page=" . ($page + 1) . "$param'>&raquo;</a> ";
}
?>
</div>
</div>
</body>
</html>
