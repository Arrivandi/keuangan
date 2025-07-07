<?php
include 'koneksi.php';

$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

// Ambil daftar tahun dari tabel client_progress
$yearQuery = mysqli_query($conn, "SELECT DISTINCT YEAR(po_date) AS year FROM client_progress ORDER BY year DESC");
$years = [];
while ($rowYear = mysqli_fetch_assoc($yearQuery)) {
    $years[] = $rowYear['year'];
}

$where = [];

if (!empty($_GET['year'])) {
    $year = (int)$_GET['year'];
    $where[] = "YEAR(c.po_date) = $year";
}

$whereSql = count($where) ? "WHERE " . implode(" AND ", $where) : "";

// Hitung total data setelah filter
$countQuery = "
SELECT COUNT(DISTINCT c.project_code) AS total 
FROM client_progress c
LEFT JOIN supplier_progress s ON c.project_code = s.project_code
LEFT JOIN invoice i ON c.project_code = i.project_code
$whereSql
";
$countResult = mysqli_query($conn, $countQuery);
$total_rows = mysqli_fetch_assoc($countResult)['total'];
$total_pages = ceil($total_rows / $per_page);

$query = "
SELECT
    c.date_entry,
    c.project_code,
    c.project_owner,
    c.project_name,
    YEAR(c.po_date) AS year,
    c.po_date,
    s.payment_mju_status AS status_project,
    c.payment_status AS status_payment,
    c.po_value AS po_total,
    IFNULL(SUM(i.dpp), 0) AS dpp_total,
    s.cash_out_total
FROM client_progress c
LEFT JOIN supplier_progress s ON c.project_code = s.project_code
LEFT JOIN invoice i ON c.project_code = i.project_code
$whereSql
GROUP BY c.project_code
ORDER BY c.date_entry DESC
LIMIT $start, $per_page
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<style>
body { font-family: Arial, sans-serif; margin: 0; }
.navbar { background: #333; padding: 10px; position: sticky; top: 0; }
.navbar a { color: white; margin-right: 15px; text-decoration: none; }
.navbar a:hover { background: #555; padding: 5px; }
.content { padding: 20px; }
table { border-collapse: collapse; width: 100%; margin-top: 20px; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
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
    margin: 0 15px; /* Tombol kiri dan kanan lebih jauh jaraknya */
}

.pagination a.active { background: #333; color: white; }
.pagination a:hover { background: #555; color: white; }

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
    <h2>Project Summary</h2>

    <form method="GET">
        <input type="hidden" name="page" value="1">

        <label>Tahun:</label>
        <select name="year">
            <option value="">-- Semua Tahun --</option>
            <?php foreach ($years as $y): ?>
                <option value="<?= $y ?>" <?= (isset($_GET['year']) && $_GET['year'] == $y) ? 'selected' : '' ?>><?= $y ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filter</button>
        <a href="index.php" style="padding:5px 10px; background:#ddd; border:1px solid #aaa; text-decoration:none; color:purple;">Reset</a>
    </form>

    <table>
        <tr>
            <th>Date Entry</th>
            <th>Project Code</th>
            <th>Project Owner</th>
            <th>Nama Project</th>
            <th>Year</th>
            <th>PO Date</th>
            <th>Status Project</th>
            <th>Status Payment</th>
            <th>PO Total</th>
            <th>DPP Total</th>
            <th>Cash Out Total</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['date_entry'] ?></td>
            <td><?= $row['project_code'] ?></td>
            <td><?= $row['project_owner'] ?></td>
            <td><?= $row['project_name'] ?></td>
            <td><?= $row['year'] ?></td>
            <td><?= $row['po_date'] ?></td>
            <td><?= $row['status_project'] ?></td>
            <td><?= $row['status_payment'] ?></td>
            <td><?= 'Rp ' . number_format($row['po_total'], 0, ',', '.') ?></td>
<td><?= 'Rp ' . number_format($row['dpp_total'], 0, ',', '.') ?></td>
<td><?= 'Rp ' . number_format($row['cash_out_total'], 0, ',', '.') ?></td>

        </tr>
        <?php endwhile; ?>
    </table>

    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php for ($p = 1; $p <= $total_pages; $p++): ?>
            <a href="?page=<?= $p ?><?= isset($_GET['year']) ? '&year=' . $_GET['year'] : '' ?>" class="<?= ($page == $p) ? 'active' : '' ?>"><?= $p ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
