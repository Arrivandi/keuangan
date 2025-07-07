<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Client Progress</title>
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
<h2>Client Progress</h2>

<?php
// Ambil daftar tahun dari po_date
$yearQuery = mysqli_query($conn, "SELECT DISTINCT YEAR(po_date) AS year FROM client_progress WHERE po_date IS NOT NULL ORDER BY year ASC");
$years = [];
while ($rowYear = mysqli_fetch_assoc($yearQuery)) {
    $years[] = $rowYear['year'];
}

$where = [];
if (!empty($_GET['year'])) {
    $year = (int)$_GET['year'];
    $where[] = "YEAR(po_date) = $year";
}
if (!empty($_GET['payment_status'])) {
    $status = mysqli_real_escape_string($conn, $_GET['payment_status']);
    $where[] = "payment_status = '$status'";
}
$whereSql = count($where) ? "WHERE " . implode(" AND ", $where) : "";
?>

<form method="GET">
    <label>Tahun:</label>
    <select name="year">
        <option value="">-- Semua Tahun --</option>
        <?php foreach ($years as $y): ?>
            <option value="<?= $y ?>" <?= (isset($_GET['year']) && $_GET['year'] == $y) ? 'selected' : '' ?>><?= $y ?></option>
        <?php endforeach; ?>
    </select>

    <label>Status Payment:</label>
    <select name="payment_status">
        <option value="">-- Semua Status --</option>
        <option value="Not Yet Payment" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == "Not Yet Payment") ? 'selected' : '' ?>>Not Yet Payment</option>
        <option value="Invoice 1" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == "Invoice 1") ? 'selected' : '' ?>>Invoice 1</option>
        <option value="Invoice 2" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == "Invoice 2") ? 'selected' : '' ?>>Invoice 2</option>
        <option value="Finish" <?= (isset($_GET['payment_status']) && $_GET['payment_status'] == "Finish") ? 'selected' : '' ?>>Finish</option>
    </select>

    <button type="submit">Filter</button>
    <a href="client.php" style="padding:5px 10px; background:#ddd; border:1px solid #aaa; text-decoration:none; color:purple;">Reset</a>
    <a href="client_create.php" class="button">+ Tambah Data</a>

</form>

<table>
<tr>
    <th>ID</th>
    <th>Project Code</th>
    <th>Project Owner</th>
    <th>Project Name</th>
    <th>PO Date</th>
    <th>End PO Date</th>
    <th>Payment Status</th>
    <th>PO Value</th>
    <th>Total Invoice</th>
    <th>Aksi</th>

</tr>
<?php
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

$result = mysqli_query($conn, "SELECT * FROM client_progress $whereSql ORDER BY id ASC LIMIT $start, $per_page");
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['project_code']}</td>
        <td>{$row['project_owner']}</td>
        <td>{$row['project_name']}</td>
        <td>{$row['po_date']}</td>
        <td>{$row['end_po_date']}</td>
        <td>{$row['payment_status']}</td>
        <td>Rp " . number_format($row['po_value'], 0, ',', '.') . "</td>
        <td>Rp " . number_format($row['total_invoice'], 0, ',', '.') . "</td>
        <td>
            <a href='client_edit.php?id={$row['id']}' class='button' style='background:#2196F3;'>Edit</a>
            <a href='client_delete.php?id={$row['id']}' class='button' style='background:#f44336;' onclick='return confirm(\"Yakin hapus data ini?\")'>Hapus</a>
        </td>
    </tr>";
}

?>
</table>

<div class="pagination">
<?php
$countQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM client_progress $whereSql");
$total_rows = mysqli_fetch_assoc($countQuery)['total'];
$total_pages = ceil($total_rows / $per_page);

$param = "";
if (isset($_GET['year'])) $param .= "&year=" . $_GET['year'];
if (isset($_GET['payment_status'])) $param .= "&payment_status=" . urlencode($_GET['payment_status']);

if ($page > 1) {
    echo "<a class='prev' href='client.php?page=" . ($page - 1) . "$param'>&laquo;</a> ";
}

if ($total_pages <= 3) {
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $page ? "active" : "";
        echo "<a class='$active' href='client.php?page=$i$param'>$i</a> ";
    }
} else {
    if ($page <= 2) {
        for ($i = 1; $i <= 3; $i++) {
            $active = $i == $page ? "active" : "";
            echo "<a class='$active' href='client.php?page=$i$param'>$i</a> ";
        }
        echo "...";
    } elseif ($page >= $total_pages - 1) {
        echo "... ";
        for ($i = $total_pages - 2; $i <= $total_pages; $i++) {
            $active = $i == $page ? "active" : "";
            echo "<a class='$active' href='client.php?page=$i$param'>$i</a> ";
        }
    } else {
        echo "... ";
        for ($i = $page - 1; $i <= $page + 1; $i++) {
            $active = $i == $page ? "active" : "";
            echo "<a class='$active' href='client.php?page=$i$param'>$i</a> ";
        }
        echo "...";
    }
}

if ($page < $total_pages) {
    echo "<a class='next' href='client.php?page=" . ($page + 1) . "$param'>&raquo;</a> ";
}
?>
</div>
</div>
</body>
</html>
