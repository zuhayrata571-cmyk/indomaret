<?php
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

<h2 class="page-title">Tambah Kasir</h2>

<form action="../../process/cashiers_process.php" method="POST">

    <input type="hidden" name="action" value="add">

    <div class="form-group">
        <label>Nama Kasir</label>
        <input type="text" name="nama_kasir" maxlength="100"
        placeholder="Masukkan nama kasir" required>
    </div>

    <div class="form-group">
        <label>Nomor Telepon</label>
        <input type="text" name="no_telp" maxlength="20"
        placeholder="Masukkan nomor telepon" required>
    </div>

    <div class="form-group">
        <label>Alamat</label>
        <textarea name="alamat" rows="4"
        placeholder="Masukkan alamat kasir" required></textarea>
    </div>

    <button type="submit" class="btn btn-save">Simpan</button>

    <a href="list.php" class="btn btn-back">Kembali</a>

</form>

<?php include '../../includes/footer.php'; ?>