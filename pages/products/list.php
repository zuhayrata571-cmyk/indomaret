
<?php
include '../../config/config.php';
include '../../includes/header.php';

$query = mysqli_query($conn, "SELECT * FROM tabel_produk ORDER BY id_produk DESC");

if (!$query) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header h1 {
        color: #005baa;
        font-size: 26px;
    }

    .page-header p {
        color: #777;
        margin-top: 5px;
    }

    .btn-add {
        background: #005baa;
        color: white;
        padding: 10px 16px;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-add:hover {
        background: #004580;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .product-table th,
    .product-table td {
        padding: 13px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }

    .product-table th {
        background: #f1f5f9;
        color: #444;
    }

    .product-table tr:hover {
        background: #f9fcff;
    }

    .action-btn {
        padding: 6px 10px;
        text-decoration: none;
        border-radius: 4px;
        font-size: 13px;
        border: none;
        cursor: pointer;
    }

    .edit-btn {
        background: #ffc107;
        color: #333;
    }

    .delete-btn {
        background: #dc3545;
        color: white;
    }

    .search-box {
        padding: 10px;
        width: 250px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .empty-data {
        text-align: center;
        padding: 30px;
        color: #777;
    }
</style>

<div class="page-header">
    <div>
        <h1>Data Produk</h1>
        <p>Kelola seluruh produk yang tersedia.</p>
    </div>

    <a href="add.php" class="btn-add">+ Tambah Produk</a>
</div>

<input type="text" id="searchProduct"
       class="search-box"
       placeholder="Cari nama produk...">

<div class="table-wrapper">

<table class="product-table" id="productTable">

    <thead>
        <tr>
            <th>No</th>
            <th>ID Produk</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>stok</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

    <?php if (mysqli_num_rows($query) > 0): ?>

        <?php $no = 1; ?>

        <?php while ($produk = mysqli_fetch_assoc($query)): ?>

            <tr>
                <td><?= $no++; ?></td>

                <td><?= $produk['id_produk']; ?></td>

                <td><?= htmlspecialchars($produk['nama_produk']); ?></td>

                <td>
                    Rp <?= number_format($produk['harga'], 0, ',', '.'); ?>
                </td>

                <td>
                    <?= $produk['stok']; ?>
                </td>

                <td>
                    <a href="edit.php?id=<?= $produk['id_produk']; ?>"
                       class="action-btn edit-btn">
                        Edit
                    </a>

                    <a href="../../process/products_process.php?action=delete&id=<?= $produk['id_produk']; ?>"
                       class="action-btn delete-btn"
                       onclick="return confirm('Yakin ingin menghapus produk ini?')">
                        Hapus
                    </a>
                </td>
            </tr>

        <?php endwhile; ?>

    <?php else: ?>

        <tr>
            <td colspan="6" class="empty-data">
                Belum ada data produk.
            </td>
        </tr>

    <?php endif; ?>

    </tbody>

</table>

</div>

<script>
document.getElementById('searchProduct').addEventListener('keyup', function() {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll('#productTable tbody tr');

    rows.forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(keyword)
            ? ''
            : 'none';
    });
});
</script>

<?php include '../../includes/footer.php'; ?>
