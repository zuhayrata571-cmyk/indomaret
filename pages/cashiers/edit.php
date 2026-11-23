<?php
include '../../config/config.php';

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$id = (int) $_GET['id'];

$query = mysqli_prepare($conn,
    "SELECT * FROM tabel_kasir WHERE id_kasir = ?");

mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data kasir tidak ditemukan!");
}

include '../../includes/header.php';
?>

<style>
    .page-title {
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 7px;
        font-weight: bold;
    }

    input, textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    textarea {
        resize: vertical;
    }

    .btn {
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        color: white;
        font-size: 14px;
    }

    .btn-save {
        background: #005baa;
    }

    .btn-back {
        background: #777;
    }
</style>

<h2 class="page-title">Edit Kasir</h2>

<form action="../../process/cashiers_process.php" method="POST">

    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id_kasir" value="<?= $data['id_kasir']; ?>">

    <div class="form-group">
        <label>Nama Kasir</label>
        <input type="text" name="nama_kasir"
        value="<?= htmlspecialchars($data['nama_kasir']); ?>"
        maxlength="100" required>
    </div>

    <div class="form-group">
        <label>Nomor Telepon</label>
        <input type="text" name="no_telp"
        value="<?= htmlspecialchars($data['no_telp']); ?>"
        maxlength="20" required>
    </div>

    <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" rows="4" required><?= htmlspecialchars($data['alamat']); ?></textarea>
    </div>

    <button type="submit" class="btn btn-save">Update</button>

    <a href="list.php" class="btn btn-back">Kembali</a>

</form>

<?php include '../../includes/footer.php'; ?>