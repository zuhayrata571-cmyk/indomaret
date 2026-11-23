
<?php
include '../../config/config.php';
include '../../includes/header.php';

$query = mysqli_query($conn, "SELECT * FROM tabel_donasi ORDER BY id_donasi DESC");

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

    .btn-edit {
        background: #ffc107;
        color: #222;
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

<h2 class="page-title">Data Donasi</h2>

<a href="add.php" class="btn btn-add">Tambah Donasi</a>

<input type="text" id="search" class="search-box"
       placeholder="Cari donasi...">

<table id="tabelDonasi">
    <thead>
        <tr>
            <th>ID Donasi</th>
            <th>Nama Donasi</th>
            <th>Harga Donasi</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
        <tr>
            <td><?= htmlspecialchars($data['id_donasi']); ?></td>

            <td><?= htmlspecialchars($data['nama_donasi']); ?></td>

            <td>Rp <?= number_format($data['harga_donasi'], 0, ',', '.'); ?></td>

            <td>
                <a href="edit.php?id=<?= urlencode($data['id_donasi']); ?>"
                   class="btn btn-edit">
                   Edit
                </a>

                <a href="../../process/donation_process.php?action=delete&id=<?= urlencode($data['id_donasi']); ?>"
                   class="btn btn-delete"
                   onclick="return confirm('Yakin ingin menghapus donasi ini?')">
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
    let rows = document.querySelectorAll("#tabelDonasi tbody tr");

    rows.forEach(function(row) {
        row.style.display = row.innerText.toLowerCase().includes(keyword)
            ? ""
            : "none";
    });
});
</script>

<?php include '../../includes/footer.php'; ?>
