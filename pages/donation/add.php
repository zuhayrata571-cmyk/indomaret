
<?php
include '../../config/config.php';
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

    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .btn {
        display: inline-block;
        padding: 9px 14px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        cursor: pointer;
    }

    .btn-save {
        background: #005baa;
    }

    .btn-back {
        background: #777;
    }
</style>

<h2 class="page-title">Tambah Donasi</h2>

<form action="../../process/donation_process.php" method="POST">

    <input type="hidden" name="action" value="add">

    <div class="form-group">
        <label>Nama Donasi</label>
        <input type="text" name="nama_donasi"
               placeholder="Masukkan nama donasi"
               required>
    </div>

    <div class="form-group">
        <label>Harga Donasi (Rp)</label>
        <input type="number" name="harga_donasi"
               min="0"
               placeholder="Masukkan nominal donasi"
               required>
    </div>

    <button type="submit" class="btn btn-save">Simpan</button>

    <a href="list.php" class="btn btn-back">Kembali</a>

</form>

<?php include '../../includes/footer.php'; ?>
