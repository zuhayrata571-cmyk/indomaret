
<?php
include '../../config/config.php';
include '../../includes/header.php';

$kode_selected = $_GET['kode'] ?? '';

// Dropdown transaksi
$transaksi = mysqli_query($conn, "
    SELECT kode_transaksi, tanggal_transaksi, no_order, total, `QRIS`, tunai, kembalian, id_donasi
    FROM tabel_transaksi
    ORDER BY tanggal_transaksi DESC
");

// Dropdown produk
$produk = mysqli_query($conn, "
    SELECT id_produk, nama_produk, harga, stok
    FROM tabel_produk
    ORDER BY nama_produk ASC
");

// Dropdown kasir
$kasir = mysqli_query($conn, "
    SELECT id_kasir, nama_kasir
    FROM tabel_kasir
    ORDER BY nama_kasir ASC
");

// Informasi transaksi yang dipilih
$infoTransaksi = null;

if ($kode_selected != '') {
    $stmtInfo = mysqli_prepare($conn, "
        SELECT *
        FROM tabel_transaksi
        WHERE kode_transaksi = ?
    ");

    mysqli_stmt_bind_param($stmtInfo, "s", $kode_selected);
    mysqli_stmt_execute($stmtInfo);

    $resultInfo = mysqli_stmt_get_result($stmtInfo);
    $infoTransaksi = mysqli_fetch_assoc($resultInfo);
}

// Ambil detail transaksi
if ($kode_selected != '') {

    $stmtDetail = mysqli_prepare($conn, "
        SELECT d.*, p.nama_produk, k.nama_kasir
        FROM tabel_detail_transaksi d
        JOIN tabel_produk p ON d.id_produk = p.id_produk
        JOIN tabel_kasir k ON d.id_kasir = k.id_kasir
        WHERE d.kode_transaksi = ?
        ORDER BY d.id_produk ASC
    ");

    mysqli_stmt_bind_param($stmtDetail, "s", $kode_selected);
    mysqli_stmt_execute($stmtDetail);

    $detail = mysqli_stmt_get_result($stmtDetail);

} else {

    $detail = mysqli_query($conn, "
        SELECT d.*, p.nama_produk, k.nama_kasir
        FROM tabel_detail_transaksi d
        JOIN tabel_produk p ON d.id_produk = p.id_produk
        JOIN tabel_kasir k ON d.id_kasir = k.id_kasir
        ORDER BY d.kode_transaksi DESC
    ");
}

// Simpan hasil detail ke array
$dataDetail = [];
$subtotalDetail = 0;

if ($detail) {
    while ($d = mysqli_fetch_assoc($detail)) {
        $dataDetail[] = $d;
        $subtotalDetail += (float) $d['harga_jual'];
    }
}
?>

<style>
    .page-title {
        margin-bottom: 20px;
    }

    .section-title {
        margin: 20px 0 15px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 7px;
        font-weight: bold;
    }

    input, select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #005baa;
    }

    .btn {
        display: inline-block;
        padding: 9px 14px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        color: white;
        cursor: pointer;
        font-size: 13px;
        margin: 2px;
    }

    .btn-save {
        background: #005baa;
    }

    .btn-delete {
        background: #dc3545;
    }

    .btn-back {
        background: #777;
    }

    .btn-all {
        background: #198754;
    }

    .btn-save:hover,
    .btn-all:hover {
        opacity: 0.9;
    }

    .transaction-info {
        background: #f8f8f8;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .info-item {
        padding: 10px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .info-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }

    .info-value {
        font-weight: bold;
        word-break: break-word;
    }

    .form-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .form-card,
    .summary-card {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 18px;
        background: white;
    }

    .summary-card {
        background: #f8f8f8;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }

    .summary-total {
        font-size: 18px;
        font-weight: bold;
        color: #005baa;
    }

    .total-preview {
        margin: 15px 0;
        padding: 12px;
        background: #f0f5fa;
        border-radius: 4px;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
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

    .table-total {
        background: #f0f0f0;
        font-weight: bold;
    }

    .filter-info {
        margin-bottom: 15px;
    }

    @media(max-width: 800px) {
        .form-layout {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>

<h2 class="page-title">Detail Transaksi</h2>

<?php if ($kode_selected != '' && $infoTransaksi) { ?>

    <div class="transaction-info">
        <h3>Informasi Transaksi</h3>

        <div class="info-grid">

            <div class="info-item">
                <div class="info-label">Kode Transaksi</div>
                <div class="info-value">
                    <?= htmlspecialchars($infoTransaksi['kode_transaksi']); ?>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Tanggal Transaksi</div>
                <div class="info-value">
                    <?= htmlspecialchars($infoTransaksi['tanggal_transaksi']); ?>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">No Order</div>
                <div class="info-value">
                    <?= htmlspecialchars($infoTransaksi['no_order']); ?>
                </div>
            </div>

        </div>

        <br>

        <a href="transaction_details.php" class="btn btn-all">
            Lihat Semua Detail
        </a>
    </div>

<?php } ?>

<h3 class="section-title">Tambah Barang Transaksi</h3>

<div class="form-layout">

    <div class="form-card">

        <form action="../../process/transaction_details_process.php"
              method="POST">

            <input type="hidden" name="action" value="add">

            <div class="form-group">
                <label>Kode Transaksi</label>

                <select name="kode_transaksi" required>
                    <option value="">-- Pilih Transaksi --</option>

                    <?php while ($t = mysqli_fetch_assoc($transaksi)) { ?>
                        <option
                            value="<?= htmlspecialchars($t['kode_transaksi']); ?>"
                            <?= ($kode_selected == $t['kode_transaksi']) ? 'selected' : ''; ?>>

                            <?= htmlspecialchars($t['kode_transaksi']); ?>

                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Produk</label>

                <select name="id_produk" id="produk" required>
                    <option value="">-- Pilih Produk --</option>

                    <?php while ($p = mysqli_fetch_assoc($produk)) { ?>
                        <option
                            value="<?= $p['id_produk']; ?>"
                            data-harga="<?= $p['harga']; ?>"
                            data-stok="<?= $p['stok']; ?>">

                            <?= htmlspecialchars($p['nama_produk']); ?>
                            (Stok: <?= $p['stok']; ?>)

                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Kasir</label>

                <select name="id_kasir" required>
                    <option value="">-- Pilih Kasir --</option>

                    <?php while ($k = mysqli_fetch_assoc($kasir)) { ?>
                        <option value="<?= $k['id_kasir']; ?>">
                            <?= htmlspecialchars($k['nama_kasir']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Harga Produk</label>
                <input type="number" id="harga" readonly>
            </div>

            <div class="form-group">
                <label>Kuantitas</label>

                <input type="number"
                       name="kuantitas"
                       id="kuantitas"
                       value="1"
                       min="1"
                       required>
            </div>

            <div class="form-group">
                <label>Diskon (Rp)</label>

                <input type="number"
                       name="diskon"
                       id="diskon"
                       value="0"
                       min="0"
                       required>
            </div>

            <div class="total-preview">
                Harga Jual:
                Rp <span id="total">0</span>
            </div>

            <button type="submit" class="btn btn-save">
                Simpan Detail
            </button>

        </form>

    </div>

    <div class="summary-card">

        <h3>Ringkasan Transaksi</h3>

        <div class="summary-row">
            <span>Jumlah Produk</span>
            <strong><?= count($dataDetail); ?> item</strong>
        </div>

        <div class="summary-row">
            <span>Subtotal Detail</span>
            <strong>
                Rp <?= number_format($subtotalDetail, 0, ',', '.'); ?>
            </strong>
        </div>

        <?php if ($infoTransaksi) { ?>

            <div class="summary-row">
                <span>Total Transaksi</span>
                <strong>
                    Rp <?= number_format($infoTransaksi['total'], 0, ',', '.'); ?>
                </strong>
            </div>

            <div class="summary-row">
                <span>QRIS</span>
                <strong>
                    Rp <?= number_format($infoTransaksi['QRIS'], 0, ',', '.'); ?>
                </strong>
            </div>

            <div class="summary-row">
                <span>Tunai</span>
                <strong>
                    Rp <?= number_format($infoTransaksi['tunai'], 0, ',', '.'); ?>
                </strong>
            </div>

            <div class="summary-row">
                <span>Kembalian</span>
                <strong>
                    Rp <?= number_format($infoTransaksi['kembalian'], 0, ',', '.'); ?>
                </strong>
            </div>

            <div class="summary-row">
                <span>ID Donasi</span>
                <strong>
                    <?= htmlspecialchars($infoTransaksi['id_donasi'] ?? '-'); ?>
                </strong>
            </div>

        <?php } ?>

    </div>

</div>

<hr style="margin:25px 0;">

<h3 class="section-title">Daftar Detail Transaksi</h3>

<?php if ($kode_selected != '') { ?>
    <div class="filter-info">
        Menampilkan transaksi:
        <strong><?= htmlspecialchars($kode_selected); ?></strong>
    </div>
<?php } ?>

<table>
    <thead>
        <tr>
            <th>Kode Transaksi</th>
            <th>Produk</th>
            <th>Kasir</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Diskon</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

        <?php if (count($dataDetail) > 0) { ?>

            <?php foreach ($dataDetail as $d) { ?>

                <tr>
                    <td><?= htmlspecialchars($d['kode_transaksi']); ?></td>

                    <td><?= htmlspecialchars($d['nama_produk']); ?></td>

                    <td><?= htmlspecialchars($d['nama_kasir']); ?></td>

                    <td>
                        Rp <?= number_format($d['harga'], 0, ',', '.'); ?>
                    </td>

                    <td><?= (int) $d['kuantitas']; ?></td>

                    <td>
                        Rp <?= number_format($d['diskon'], 0, ',', '.'); ?>
                    </td>

                    <td>
                        Rp <?= number_format($d['harga_jual'], 0, ',', '.'); ?>
                    </td>

                    <td>
                        <a class="btn btn-delete"
                           href="../../process/transaction_details_process.php?action=delete&kode=<?= urlencode($d['kode_transaksi']); ?>&produk=<?= (int) $d['id_produk']; ?>"
                           onclick="return confirm('Yakin ingin menghapus detail ini? Stok produk akan dikembalikan.')">

                            Hapus
                        </a>
                    </td>
                </tr>

            <?php } ?>

            <tr class="table-total">
                <td colspan="6">Total Subtotal Produk</td>
                <td colspan="2">
                    Rp <?= number_format($subtotalDetail, 0, ',', '.'); ?>
                </td>
            </tr>

        <?php } else { ?>

            <tr>
                <td colspan="8">
                    Belum ada detail barang pada transaksi ini.
                </td>
            </tr>

        <?php } ?>

    </tbody>
</table>

<script>
const produk = document.getElementById("produk");
const harga = document.getElementById("harga");
const qty = document.getElementById("kuantitas");
const diskon = document.getElementById("diskon");
const total = document.getElementById("total");

function hitung() {
    let selected = produk.options[produk.selectedIndex];

    let price = Number(selected.dataset.harga || 0);
    let jumlah = Number(qty.value || 0);
    let potongan = Number(diskon.value || 0);

    harga.value = price;

    let hasil = (price * jumlah) - potongan;

    total.textContent = Math.max(hasil, 0).toLocaleString("id-ID");
}

produk.addEventListener("change", hitung);
qty.addEventListener("input", hitung);
diskon.addEventListener("input", hitung);
</script>

<?php include '../../includes/footer.php'; ?>
