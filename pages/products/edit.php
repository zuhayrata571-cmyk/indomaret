
<?php
include '../../config/config.php';
include '../../includes/header.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($conn, "SELECT * FROM tabel_produk WHERE id_produk = $id");

if (!$query || mysqli_num_rows($query) == 0) {
    die("Produk tidak ditemukan.");
}

$produk = mysqli_fetch_assoc($query);
?>

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

<h1 class="form-title">Edit Produk</h1>
<p class="form-subtitle">Ubah informasi produk.</p>

<form action="../../process/products_process.php"
      method="POST"
      class="product-form">

    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id_produk" value="<?= $produk['id_produk']; ?>">

    <div class="form-group">
        <label>Nama Produk</label>
        <input type="text"
               name="nama_produk"
               maxlength="20"
               value="<?= htmlspecialchars($produk['nama_produk']); ?>"
               required>
    </div>

    <div class="form-group">
        <label>Harga</label>
        <input type="number"
               name="harga"
               min="0"
               value="<?= $produk['harga']; ?>"
               required>
    </div>

    <div class="form-group">
        <label>Stok</label>
        <input type="number"
               name="stok"
               min="0"
               value="<?= $produk['stok']; ?>"
               required>
    </div>

    <div class="form-buttons">
        <button type="submit" class="btn-save">Simpan Perubahan</button>
        <a href="list.php" class="btn-back">Kembali</a>
    </div>

</form>

<?php include '../../includes/footer.php'; ?>
