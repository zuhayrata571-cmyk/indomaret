
<?php
include '../../config/config.php';
include '../../includes/header.php';

$query = mysqli_query($conn, "
    SELECT 
        t.*,
        GROUP_CONCAT(
            DISTINCT p.nama_produk
            ORDER BY p.nama_produk
            SEPARATOR ', '
        ) AS barang
    FROM tabel_transaksi t
    LEFT JOIN tabel_detail_transaksi d 
        ON t.kode_transaksi = d.kode_transaksi
    LEFT JOIN tabel_produk p 
        ON d.id_produk = p.id_produk
    GROUP BY t.kode_transaksi
    ORDER BY t.tanggal_transaksi DESC
");

if (!$query) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<style>
    .page-title {
        margin-bottom: 20px;
    }

    .btn {
        display: inline-block;
        padding: 8px 12px;
        text-decoration: none;
        border-radius: 4px;
        color: white;
        font-size: 13px;
        border: none;
        cursor: pointer;
        margin: 2px;
    }

    .btn-add {
        background: #005baa;
        margin-bottom: 15px;
    }

    .btn-delete {
        background: #dc3545;
    }

    .search-box {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    th, td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background: #005baa;
        color: white;
    }

    tr:nth-child(even) {
        background: #f8f8f8;
    }
</style>

<h2 class="page-title">Data Transaksi</h2>

<a href="add.php" class="btn btn-add">Tambah Transaksi</a>

<input type="text" id="search" class="search-box"
placeholder="Cari transaksi atau barang...">

<table id="tabelTransaksi">
    <thead>
        <tr>
            <th>Kode Transaksi</th>
            <th>Tanggal</th>
            <th>No Order</th>
            <th>Barang</th>
            <th>Total</th>
            <th>QRIS</th>
            <th>Tunai</th>
            <th>Kembalian</th>
            <th>ID Donasi</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= htmlspecialchars($data['kode_transaksi']); ?></td>

            <td><?= htmlspecialchars($data['tanggal_transaksi']); ?></td>

            <td><?= htmlspecialchars($data['no_order']); ?></td>

            <td>
                <?= htmlspecialchars($data['barang'] ?? 'Belum ada barang'); ?>
            </td>

            <td>Rp <?= number_format($data['total'], 0, ',', '.'); ?></td>

            <td>Rp <?= number_format($data['QRIS'], 0, ',', '.'); ?></td>

            <td>Rp <?= number_format($data['tunai'], 0, ',', '.'); ?></td>

            <td>Rp <?= number_format($data['kembalian'], 0, ',', '.'); ?></td>

            <td><?= htmlspecialchars($data['id_donasi'] ?? '-'); ?></td>

            <td>
                <a href="../../process/transactions_process.php?action=delete&kode=<?= urlencode($data['kode_transaksi']); ?>"
                   class="btn btn-delete"
                   onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                   Hapus
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<script>
document.getElementById("search").addEventListener("keyup", function() {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll("#tabelTransaksi tbody tr");

    rows.forEach(function(row) {
        row.style.display = row.innerText.toLowerCase().includes(keyword)
            ? ""
            : "none";
    });
});
</script>

<?php include '../../includes/footer.php'; ?>
