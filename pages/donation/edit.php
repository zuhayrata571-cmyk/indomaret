
<?php
include '../../config/config.php';
include '../../includes/header.php';

$id_donasi = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_prepare($conn, "SELECT * FROM tabel_donasi WHERE id_donasi = ?");
mysqli_stmt_bind_param($query, "i", $id_donasi);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data donasi tidak ditemukan.");
}
?>

<style>
    .page-title { margin-bottom: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display:block; margin-bottom:7px; font-weight:bold; }
    input {
        width:100%;
        padding:10px;
        border:1px solid #ccc;
        border-radius:4px;
        box-sizing:border-box;
    }
    .btn {
        display:inline-block;
        padding:9px 14px;
        border:none;
        border-radius:4px;
        text-decoration:none;
        color:white;
        cursor:pointer;
    }
    .btn-save { background:#005baa; }
    .btn-back { background:#777; }
</style>

<h2 class="page-title">Edit Donasi</h2>

<form action="../../process/donation_process.php" method="POST">

    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id_donasi" value="<?= $data['id_donasi']; ?>">

    <div class="form-group">
        <label>Nama Donasi</label>
        <input type="text" name="nama_donasi"
               value="<?= htmlspecialchars($data['nama_donasi']); ?>"
               required>
    </div>

    <div class="form-group">
        <label>Harga Donasi (Rp)</label>
        <input type="number" name="harga_donasi"
               value="<?= $data['harga_donasi']; ?>"
               min="0" required>
    </div>

    <button type="submit" class="btn btn-save">Simpan Perubahan</button>

    <a href="list.php" class="btn btn-back">Kembali</a>

</form>

<?php include '../../includes/footer.php'; ?>
