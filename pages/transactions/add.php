
<?php
include '../../config/config.php';
include '../../includes/header.php';

$produk = mysqli_query($conn, "SELECT id_produk, nama_produk, harga, stok FROM tabel_produk ORDER BY nama_produk ASC");
$kasir = mysqli_query($conn, "SELECT id_kasir, nama_kasir FROM tabel_kasir ORDER BY nama_kasir ASC");
$donasi = mysqli_query($conn, "SELECT id_donasi, nama_donasi, harga_donasi FROM tabel_donasi ORDER BY nama_donasi ASC");

$kode_transaksi = "TRX" . date("Ymd") . mt_rand(1000, 9999);
?>

<style>
    .page-title {
        margin-bottom: 5px;
    }

    .subtitle {
        color: #777;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .transaction-container {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 20px;
    }

    .card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 17px;
        margin-bottom: 15px;
        color: #005baa;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 7px;
        font-weight: bold;
        font-size: 13px;
    }

    input, select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    input:focus, select:focus {
        outline: none;
        border-color: #005baa;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .product-inputs {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 10px;
        align-items: end;
    }

    .btn {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        color: white;
        font-size: 13px;
        display: inline-block;
    }

    .btn-add {
        background: #005baa;
        width: 100%;
    }

    .btn-save {
        background: #198754;
        width: 100%;
        font-weight: bold;
        padding: 13px;
    }

    .btn-back {
        background: #777;
        width: 100%;
        text-align: center;
        margin-top: 8px;
    }

    .btn-delete {
        background: #dc3545;
        padding: 5px 9px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-top: 15px;
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

    .empty-cart {
        text-align: center;
        color: #777;
        padding: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin: 12px 0;
        font-size: 14px;
    }

    .grand-total {
        border-top: 1px solid #ddd;
        padding-top: 15px;
        font-size: 20px;
        font-weight: bold;
        color: #005baa;
    }

    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .change-box {
        background: #f1f5f9;
        padding: 12px;
        border-radius: 5px;
        margin-top: 12px;
    }

    .change-amount {
        font-size: 20px;
        font-weight: bold;
        color: #005baa;
    }

    @media(max-width: 900px) {
        .transaction-container {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 600px) {
        .info-grid, .product-inputs, .payment-grid {
            grid-template-columns: 1fr;
        }

        .card {
            padding: 14px;
        }

        table {
            font-size: 11px;
        }
    }
</style>

<h2 class="page-title">Tambah Transaksi</h2>
<p class="subtitle">Tambahkan produk belanja, pilih donasi (jika ada), lalu proses pembayaran.</p>

<form action="../../process/transactions_process.php" method="POST" id="formTransaksi">

    <input type="hidden" name="action" value="add">
    <input type="hidden" name="total" id="inputTotal" value="0">
    <input type="hidden" name="kembalian" id="inputKembalian" value="0">

    <div class="transaction-container">

        <div>

            <!-- DATA TRANSAKSI -->
            <div class="card">
                <h3 class="card-title">Data Transaksi</h3>

                <div class="info-grid">
                    <div class="form-group">
                        <label>Kode Transaksi</label>
                        <input type="text" name="kode_transaksi"
                               value="<?= htmlspecialchars($kode_transaksi); ?>" readonly required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi"
                               value="<?= date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>No. Order</label>
                        <input type="text" name="no_order"
                               placeholder="Masukkan nomor order" required>
                    </div>

                    <div class="form-group">
                        <label>Kasir Bertugas</label>
                        <select name="id_kasir" required>
                            <option value="">-- Pilih Kasir --</option>
                            <?php while ($k = mysqli_fetch_assoc($kasir)) { ?>
                                <option value="<?= $k['id_kasir']; ?>">
                                    <?= htmlspecialchars($k['nama_kasir']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- PRODUK -->
            <div class="card">
                <h3 class="card-title">Daftar Produk Belanja</h3>

                <div class="product-inputs">
                    <div class="form-group">
                        <label>Pilih Produk</label>
                        <select id="pilihProduk">
                            <option value="">-- Pilih Produk --</option>

                            <?php while ($p = mysqli_fetch_assoc($produk)) { ?>
                                <option
                                    value="<?= $p['id_produk']; ?>"
                                    data-nama="<?= htmlspecialchars($p['nama_produk'], ENT_QUOTES); ?>"
                                    data-harga="<?= $p['harga']; ?>"
                                    data-stok="<?= $p['stok']; ?>">
                                    <?= htmlspecialchars($p['nama_produk']); ?>
                                    (Stok: <?= $p['stok']; ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Qty</label>
                        <input type="number" id="qtyProduk" value="1" min="1">
                    </div>

                    <div class="form-group">
                        <label>Diskon (Rp)</label>
                        <input type="number" id="diskonProduk" value="0" min="0">
                    </div>
                </div>

                <button type="button" class="btn btn-add" id="btnTambah">
                    + Tambah Produk
                </button>

                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Diskon</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="keranjang">
                            <tr>
                                <td colspan="6" class="empty-cart">
                                    Belum ada produk. Pilih produk lalu klik Tambah.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- DONASI DAN PEMBAYARAN -->
        <div>

            <div class="card">
                <h3 class="card-title">Donasi & Pembayaran</h3>

                <div class="form-group">
                    <label>Program Donasi (Opsional)</label>
                    <select name="id_donasi" id="pilihDonasi">
                        <option value="">-- Tidak Berdonasi --</option>

                        <?php while ($d = mysqli_fetch_assoc($donasi)) { ?>
                            <option value="<?= $d['id_donasi']; ?>"
                                    data-harga="<?= $d['harga_donasi']; ?>">
                                <?= htmlspecialchars($d['nama_donasi']); ?>
                                (Rp <?= number_format($d['harga_donasi'], 0, ',', '.'); ?>)
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="summary-row">
                    <span>Subtotal Produk</span>
                    <span id="subtotalProduk">Rp 0</span>
                </div>

                <div class="summary-row">
                    <span>Donasi</span>
                    <span id="totalDonasi">Rp 0</span>
                </div>

                <div class="summary-row grand-total">
                    <span>Total Bayar</span>
                    <span id="totalBayar">Rp 0</span>
                </div>

                <hr style="margin:20px 0;">

                <div class="payment-grid">
                    <div class="form-group">
                        <label>Bayar Tunai (Rp)</label>
                        <input type="number" name="tunai" id="tunai"
                               min="0" value="0" required>
                    </div>

                    <div class="form-group">
                        <label>Bayar QRIS (Rp)</label>
                        <input type="number" name="qris" id="qris"
                               min="0" value="0" required>
                    </div>
                </div>

                <div class="change-box">
                    <div style="margin-bottom:5px;">Kembalian</div>
                    <div class="change-amount" id="tampilanKembalian">Rp 0</div>
                </div>

                <br>

                <button type="submit" class="btn btn-save">
                    Simpan Transaksi
                </button>

                <a href="list.php" class="btn btn-back">Batal</a>
            </div>

        </div>

    </div>
</form>

<script>
const pilihProduk = document.getElementById("pilihProduk");
const qtyProduk = document.getElementById("qtyProduk");
const diskonProduk = document.getElementById("diskonProduk");
const keranjang = document.getElementById("keranjang");

const pilihDonasi = document.getElementById("pilihDonasi");
const tunai = document.getElementById("tunai");
const qris = document.getElementById("qris");

const subtotalProduk = document.getElementById("subtotalProduk");
const totalDonasi = document.getElementById("totalDonasi");
const totalBayar = document.getElementById("totalBayar");
const tampilanKembalian = document.getElementById("tampilanKembalian");

const inputTotal = document.getElementById("inputTotal");
const inputKembalian = document.getElementById("inputKembalian");

let cart = [];

function rupiah(angka) {
    return "Rp " + Number(angka).toLocaleString("id-ID");
}

function renderCart() {
    keranjang.innerHTML = "";

    if (cart.length === 0) {
        keranjang.innerHTML = `
            <tr>
                <td colspan="6" class="empty-cart">
                    Belum ada produk di keranjang.
                </td>
            </tr>
        `;
    }

    cart.forEach((item, index) => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>${item.nama}</td>
            <td>${rupiah(item.harga)}</td>
            <td>${rupiah(item.diskon)}</td>
            <td>${item.qty}</td>
            <td>${rupiah(item.subtotal)}</td>
            <td>
                <button type="button" class="btn btn-delete"
                    onclick="hapusProduk(${index})">Hapus</button>
            </td>
        `;

        keranjang.appendChild(tr);

        const hiddenId = document.createElement("input");
        hiddenId.type = "hidden";
        hiddenId.name = `items[${index}][id_produk]`;
        hiddenId.value = item.id;
        hiddenId.className = "cart-hidden";

        const hiddenQty = document.createElement("input");
        hiddenQty.type = "hidden";
        hiddenQty.name = `items[${index}][kuantitas]`;
        hiddenQty.value = item.qty;
        hiddenQty.className = "cart-hidden";

        const hiddenDiskon = document.createElement("input");
        hiddenDiskon.type = "hidden";
        hiddenDiskon.name = `items[${index}][diskon]`;
        hiddenDiskon.value = item.diskon;
        hiddenDiskon.className = "cart-hidden";

        document.getElementById("formTransaksi").appendChild(hiddenId);
        document.getElementById("formTransaksi").appendChild(hiddenQty);
        document.getElementById("formTransaksi").appendChild(hiddenDiskon);
    });

    hitungTotal();
}

function hapusProduk(index) {
    cart.splice(index, 1);
    renderCart();
}

document.getElementById("btnTambah").addEventListener("click", function() {
    const option = pilihProduk.options[pilihProduk.selectedIndex];

    if (!pilihProduk.value) {
        alert("Pilih produk terlebih dahulu.");
        return;
    }

    const id = option.value;
    const nama = option.dataset.nama;
    const harga = Number(option.dataset.harga);
    const stok = Number(option.dataset.stok);
    const qty = Number(qtyProduk.value);
    const diskon = Number(diskonProduk.value);

    if (qty < 1) {
        alert("Kuantitas minimal 1.");
        return;
    }

    if (qty > stok) {
        alert("Jumlah melebihi stok yang tersedia.");
        return;
    }

    if (diskon > (harga * qty)) {
        alert("Diskon tidak boleh melebihi total harga produk.");
        return;
    }

    const subtotal = (harga * qty) - diskon;

    const existing = cart.find(item => item.id === id);

    if (existing) {
        if (existing.qty + qty > stok) {
            alert("Jumlah produk melebihi stok.");
            return;
        }

        existing.qty += qty;
        existing.diskon += diskon;
        existing.subtotal = (existing.harga * existing.qty) - existing.diskon;
    } else {
        cart.push({
            id,
            nama,
            harga,
            qty,
            diskon,
            subtotal
        });
    }

    pilihProduk.value = "";
    qtyProduk.value = 1;
    diskonProduk.value = 0;

    renderCart();
});

pilihDonasi.addEventListener("change", hitungTotal);
tunai.addEventListener("input", hitungTotal);
qris.addEventListener("input", hitungTotal);

function hitungTotal() {
    let subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);

    let optionDonasi = pilihDonasi.options[pilihDonasi.selectedIndex];
    let nominalDonasi = Number(optionDonasi.dataset.harga || 0);

    let total = subtotal + nominalDonasi;

    let pembayaranTunai = Number(tunai.value || 0);
    let pembayaranQris = Number(qris.value || 0);

    let dibayar = pembayaranTunai + pembayaranQris;
    let kembalian = Math.max(dibayar - total, 0);

    subtotalProduk.textContent = rupiah(subtotal);
    totalDonasi.textContent = rupiah(nominalDonasi);
    totalBayar.textContent = rupiah(total);

    tampilanKembalian.textContent = rupiah(kembalian);

    inputTotal.value = total;
    inputKembalian.value = kembalian;
}

document.getElementById("formTransaksi").addEventListener("submit", function(e) {
    if (cart.length === 0) {
        e.preventDefault();
        alert("Tambahkan minimal satu produk ke keranjang.");
        return;
    }

    const total = Number(inputTotal.value);
    const dibayar = Number(tunai.value) + Number(qris.value);

    if (dibayar < total) {
        e.preventDefault();
        alert("Pembayaran masih kurang.");
        return;
    }
});
</script>

<?php include '../../includes/footer.php'; ?>
