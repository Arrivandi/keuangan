<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Import Invoice</title>
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
<h2>Import Invoice</h2>

<?php
// Ambil daftar tahun dari import_date
$yearQuery = mysqli_query($conn, "SELECT DISTINCT YEAR(import_date) AS year FROM import_invoice WHERE import_date IS NOT NULL ORDER BY year DESC");
$years = [];
while ($rowYear = mysqli_fetch_assoc($yearQuery)) {
    $years[] = $rowYear['year'];
}

$where = [];
if (!empty($_GET['year'])) {
    $year = (int)$_GET['year'];
    $where[] = "YEAR(import_date) = $year";
}
$whereSql = count($where) ? "WHERE " . implode(" AND ", $where) : "";
?>

<form method="GET">
    <label>Tahun Import Date:</label>
    <select name="year">
        <option value="">-- Semua Tahun --</option>
        <?php foreach ($years as $y): ?>
            <option value="<?= $y ?>" <?= (isset($_GET['year']) && $_GET['year'] == $y) ? 'selected' : '' ?>><?= $y ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
    <a href="import_invoice.php" style="padding:5px 10px; background:#ddd; border:1px solid #aaa; text-decoration:none; color:purple;">Reset</a>
    <a href="import_invoice_create.php" class="button">+ Tambah Data</a></form>

<table>
<tr>
    <th>Import Number</th>
    <th>Import Date</th>
    <th>Inbound Date</th>
    <th>Supp Inv 1 No</th>
    <th>Supp Inv 1 Date</th>
    <th>Supp Inv 1 Amount</th>
    <th>Supp DPP 1 Amount</th>
    <th>Supp PPN 1 Amount</th>
    <th>Supp Inv 2 No</th>
    <th>Supp Inv 2 Date</th>
    <th>Supp Inv 2 Amount</th>
    <th>Supp DPP 2 Amount</th>
    <th>Supp PPN 2 Amount</th>
    <th>Supp Inv 3 No</th>
    <th>Supp Inv 3 Date</th>
    <th>Supp Inv 3 Amount</th>
    <th>Supp DPP 3 Amount</th>
    <th>Supp PPN 3 Amount</th>
    <th>Aksi</th>

</tr>
<?php
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

$result = mysqli_query($conn, "SELECT * FROM import_invoice $whereSql ORDER BY import_date DESC LIMIT $start, $per_page");
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['import_number']}</td>
        <td>{$row['import_date']}</td>
        <td>{$row['inbound_date']}</td>
        <td>{$row['supp_inv_1_no']}</td>
        <td>{$row['supp_inv_1_date']}</td>
        <td>Rp " . number_format($row['supp_inv_1_amount'], 0, ',', '.') . "</td>
        <td>Rp " . number_format($row['supp_dpp_1_amount'], 0, ',', '.') . "</td>
        <td>Rp " . number_format($row['supp_ppn_1_amount'], 0, ',', '.') . "</td>
        <td>{$row['supp_inv_2_no']}</td>
        <td>{$row['supp_inv_2_date']}</td>
        <td>Rp " . number_format($row['supp_inv_2_amount'], 0, ',', '.') . "</td>
        <td>Rp " . number_format($row['supp_dpp_2_amount'], 0, ',', '.') . "</td>
        <td>Rp " . number_format($row['supp_ppn_2_amount'], 0, ',', '.') . "</td>
        <td>{$row['supp_inv_3_no']}</td>
        <td>{$row['supp_inv_3_date']}</td>
        <td>Rp " . number_format($row['supp_inv_3_amount'], 0, ',', '.') . "</td>
        <td>Rp " . number_format($row['supp_dpp_3_amount'], 0, ',', '.') . "</td>
        <td>Rp " . number_format($row['supp_ppn_3_amount'], 0, ',', '.') . "</td>
        <td>
            <a href='import_invoice_edit.php?id={$row['import_number']}' class='button' style='background:#2196F3;'>Edit</a>
            <a href='import_invoice_delete.php?id={$row['import_number']}' class='button' style='background:#f44336;' onclick='return confirm(\"Yakin hapus data ini?\")'>Hapus</a>
        </td>
        </tr>";
}
?>
</table>

<div class="pagination">
<?php
$countQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM import_invoice $whereSql");
$total_rows = mysqli_fetch_assoc($countQuery)['total'];
$total_pages = ceil($total_rows / $per_page);

$param = "";
if (isset($_GET['year'])) $param .= "&year=" . $_GET['year'];

if ($page > 1) {
    echo "<a class='prev' href='import_invoice.php?page=" . ($page - 1) . "$param'>&laquo;</a> ";
}

if ($total_pages <= 3) {
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? "active" : "";
        echo "<a class='$active' href='import_invoice.php?page=$i$param'>$i</a> ";
    }
} else {
    if ($page <= 2) {
        for ($i = 1; $i <= 3; $i++) {
            $active = $i == $page ? "active" : "";
            echo "<a class='$active' href='import_invoice.php?page=$i$param'>$i</a> ";
        }
        echo "...";
    } elseif ($page >= $total_pages - 1) {
        echo "... ";
        for ($i = $total_pages - 2; $i <= $total_pages; $i++) {
            $active = $i == $page ? "active" : "";
            echo "<a class='$active' href='import_invoice.php?page=$i$param'>$i</a> ";
        }
    } else {
        echo "... ";
        for ($i = $page - 1; $i <= $page + 1; $i++) {
            $active = $i == $page ? "active" : "";
            echo "<a class='$active' href='import_invoice.php?page=$i$param'>$i</a> ";
        }
        echo "...";
    }
}

if ($page < $total_pages) {
    echo "<a class='next' href='import_invoice.php?page=" . ($page + 1) . "$param'>&raquo;</a> ";
}
?>
</div>
</div>
</body>
</html>
