
<?php include '../../includes/header.php'; ?>

<style>
    .form-title {
        color: #005baa;
        margin-bottom: 8px;
    }

    .form-subtitle {
        color: #777;
        margin-bottom: 25px;
    }

    .product-form {
        max-width: 600px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 11px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form-group input:focus {
        border-color: #005baa;
        outline: none;
    }

    .form-buttons {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-save {
        background: #005baa;
        color: white;
        border: none;
        padding: 11px 18px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-back {
        background: #777;
        color: white;
        text-decoration: none;
        padding: 11px 18px;
        border-radius: 5px;
    }
</style>

<h1 class="form-title">Tambah Produk</h1>
<p class="form-subtitle">Masukkan informasi produk baru.</p>

<form action="../../process/products_process.php"
      method="POST"
      class="product-form">

    <input type="hidden" name="action" value="add">

    <div class="form-group">
        <label>Nama Produk</label>
        <input type="text" name="nama_produk" maxlength="20" required>
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" min="0" required>
    </div>

    <div class="form-group">
        <label>Stok</label>
        <input type="number" name="stok" min="0" required>
    </div>

    <div class="form-buttons">
        <button type="submit" class="btn-save">Simpan Produk</button>
        <a href="list.php" class="btn-back">Kembali</a>
    </div>

</form>

<?php include '../../includes/footer.php'; ?>
