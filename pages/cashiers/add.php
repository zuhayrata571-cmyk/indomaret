<?php
// Gunakan __DIR__ agar path selalu tepat dan tidak bergantung pada konfigurasi DOCUMENT_ROOT server
include_once __DIR__ . '/../../includes/header.php';
?>

<h2>Tambah Data Kasir</h2>
<br>

<form action="/indomaret098/process/cashiers_process.php" method="post">
    <div style="margin-bottom: 12px;">
        <label for="nama_kasir" style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Kasir:</label>
        <input type="text" name="nama_kasir" id="nama_kasir" placeholder="Masukkan Nama Kasir" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>

    <div style="margin-bottom: 12px;">
        <label for="no_telp" style="display: block; margin-bottom: 5px; font-weight: bold;">No. Telepon:</label>
        <input type="text" name="no_telp" id="no_telp" placeholder="Masukkan No. Telp" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="alamat" style="display: block; margin-bottom: 5px; font-weight: bold;">Alamat:</label>
        <textarea name="alamat" id="alamat" placeholder="Masukkan Alamat" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; resize: vertical;" rows="3"></textarea>
    </div>

    <button type="submit" name="submit" style="background-color: #005baa; color: white; padding: 10px 18px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">Simpan Kasir</button>
    <a href="<?= $base_url; ?>/pages/cashiers/list.php" style="margin-left: 10px; color: #666; text-decoration: none;">Kembali</a>
</form>

<?php
include_once __DIR__ . '/../../includes/footer.php';
?>
