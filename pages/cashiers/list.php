<?php
include '../../config/config.php';
include '../../includes/header.php';

$query = mysqli_query($conn, "SELECT * FROM tabel_kasir ORDER BY id_kasir DESC");

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
        padding: 9px 14px;
        text-decoration: none;
        border-radius: 4px;
        color: white;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }

    .btn-add {
        background: #005baa;
        margin-bottom: 15px;
    }

    .btn-edit {
        background: #f0ad4e;
        color: #222;
    }

    .btn-delete {
        background: #dc3545;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        padding: 12px;
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

    .search-box {
        padding: 9px;
        width: 100%;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

<h2 class="page-title">Data Kasir</h2>

<a href="add.php" class="btn btn-add">Tambah Kasir</a>

<input type="text" id="search" class="search-box"
placeholder="Cari nama kasir, nomor telepon, atau alamat...">

<table id="tabelKasir">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kasir</th>
            <th>No. Telepon</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($data['nama_kasir']); ?></td>
            <td><?= htmlspecialchars($data['no_telp']); ?></td>
            <td><?= htmlspecialchars($data['alamat']); ?></td>
            <td>
                <a href="edit.php?id=<?= $data['id_kasir']; ?>"
                   class="btn btn-edit">Edit</a>

                <a href="../../process/cashiers_process.php?action=delete&id=<?= $data['id_kasir']; ?>"
                   class="btn btn-delete"
                   onclick="return confirm('Yakin ingin menghapus kasir ini?')">
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
    let rows = document.querySelectorAll("#tabelKasir tbody tr");

    rows.forEach(function(row) {
        let text = row.innerText.toLowerCase();

        row.style.display = text.includes(keyword) ? "" : "none";
    });
});
</script>

<?php include '../../includes/footer.php'; ?>