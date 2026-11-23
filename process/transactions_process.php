
<?php
include '../config/config.php';

// ================= TAMBAH TRANSAKSI =================
if (isset($_POST['action']) && $_POST['action'] == 'add') {

    $kode_transaksi = trim($_POST['kode_transaksi'] ?? '');
    $tanggal_transaksi = $_POST['tanggal_transaksi'] ?? '';
    $no_order = trim($_POST['no_order'] ?? '');
    $id_kasir = (int) ($_POST['id_kasir'] ?? 0);

    $tunai = (float) ($_POST['tunai'] ?? 0);
    $qris = (float) ($_POST['qris'] ?? 0);

    $id_donasi = !empty($_POST['id_donasi'])
        ? (int) $_POST['id_donasi']
        : null;

    $items = $_POST['items'] ?? [];

    if (
        $kode_transaksi == '' ||
        $tanggal_transaksi == '' ||
        $no_order == '' ||
        $id_kasir <= 0 ||
        empty($items)
    ) {
        die("Data transaksi belum lengkap.");
    }

    mysqli_begin_transaction($conn);

    try {

        // ================= CEK DONASI =================
        $nominal_donasi = 0;

        if ($id_donasi !== null) {

            $stmtDonasi = mysqli_prepare($conn,
                "SELECT harga_donasi FROM tabel_donasi WHERE id_donasi = ?");

            mysqli_stmt_bind_param($stmtDonasi, "i", $id_donasi);
            mysqli_stmt_execute($stmtDonasi);

            $resultDonasi = mysqli_stmt_get_result($stmtDonasi);
            $dataDonasi = mysqli_fetch_assoc($resultDonasi);

            if (!$dataDonasi) {
                throw new Exception("Donasi tidak ditemukan.");
            }

            $nominal_donasi = (float) $dataDonasi['harga_donasi'];
        }

        // ================= VALIDASI KASIR =================
        $stmtKasir = mysqli_prepare($conn,
            "SELECT id_kasir FROM tabel_kasir WHERE id_kasir = ?");

        mysqli_stmt_bind_param($stmtKasir, "i", $id_kasir);
        mysqli_stmt_execute($stmtKasir);

        $resultKasir = mysqli_stmt_get_result($stmtKasir);

        if (mysqli_num_rows($resultKasir) == 0) {
            throw new Exception("Kasir tidak ditemukan.");
        }

        // ================= CEK PRODUK DAN STOK =================
        $detailItems = [];
        $subtotalProduk = 0;

        foreach ($items as $item) {

            $id_produk = (int) ($item['id_produk'] ?? 0);
            $qty = (int) ($item['kuantitas'] ?? 0);
            $diskon = (float) ($item['diskon'] ?? 0);

            if ($id_produk <= 0 || $qty <= 0 || $diskon < 0) {
                throw new Exception("Data produk tidak valid.");
            }

            $stmtProduk = mysqli_prepare($conn,
                "SELECT nama_produk, harga, stok
                 FROM tabel_produk
                 WHERE id_produk = ?
                 FOR UPDATE");

            mysqli_stmt_bind_param($stmtProduk, "i", $id_produk);
            mysqli_stmt_execute($stmtProduk);

            $resultProduk = mysqli_stmt_get_result($stmtProduk);
            $dataProduk = mysqli_fetch_assoc($resultProduk);

            if (!$dataProduk) {
                throw new Exception("Produk tidak ditemukan.");
            }

            $harga = (float) $dataProduk['harga'];
            $stok = (int) $dataProduk['stok'];

            if ($qty > $stok) {
                throw new Exception(
                    "Stok produk " . $dataProduk['nama_produk'] . " tidak mencukupi."
                );
            }

            $hargaTotal = $harga * $qty;

            if ($diskon > $hargaTotal) {
                throw new Exception("Diskon melebihi harga produk.");
            }

            $hargaJual = $hargaTotal - $diskon;

            $subtotalProduk += $hargaJual;

            $detailItems[] = [
                'id_produk' => $id_produk,
                'harga' => $harga,
                'qty' => $qty,
                'diskon' => $diskon,
                'harga_jual' => $hargaJual
            ];

            // Kurangi stok
            $stokBaru = $stok - $qty;

            $stmtStok = mysqli_prepare($conn,
                "UPDATE tabel_produk SET stok = ? WHERE id_produk = ?");

            mysqli_stmt_bind_param($stmtStok, "ii", $stokBaru, $id_produk);
            mysqli_stmt_execute($stmtStok);
        }

        // ================= HITUNG TOTAL =================
        $total = $subtotalProduk + $nominal_donasi;
        $kembalian = ($tunai + $qris) - $total;

        if ($kembalian < 0) {
            throw new Exception("Pembayaran masih kurang.");
        }

        // ================= SIMPAN TRANSAKSI =================
        $stmtTransaksi = mysqli_prepare($conn,
            "INSERT INTO tabel_transaksi
            (kode_transaksi, tanggal_transaksi, no_order, total, `QRIS`, tunai, kembalian, id_donasi)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        mysqli_stmt_bind_param(
            $stmtTransaksi,
            "sssddddi",
            $kode_transaksi,
            $tanggal_transaksi,
            $no_order,
            $total,
            $qris,
            $tunai,
            $kembalian,
            $id_donasi
        );

        if (!mysqli_stmt_execute($stmtTransaksi)) {
            throw new Exception(mysqli_stmt_error($stmtTransaksi));
        }

        // ================= SIMPAN DETAIL PRODUK =================
        foreach ($detailItems as $d) {

            $stmtDetail = mysqli_prepare($conn,
                "INSERT INTO tabel_detail_transaksi
                (kode_transaksi, id_produk, id_kasir, harga, kuantitas, diskon, harga_jual)
                VALUES (?, ?, ?, ?, ?, ?, ?)");

            mysqli_stmt_bind_param(
                $stmtDetail,
                "siididd",
                $kode_transaksi,
                $d['id_produk'],
                $id_kasir,
                $d['harga'],
                $d['qty'],
                $d['diskon'],
                $d['harga_jual']
            );

            if (!mysqli_stmt_execute($stmtDetail)) {
                throw new Exception(mysqli_stmt_error($stmtDetail));
            }
        }

        mysqli_commit($conn);

        echo "<script>
            alert('Transaksi berhasil ditambahkan!');
            window.location='../pages/transactions/list.php';
        </script>";

        exit;

    } catch (Exception $e) {

        mysqli_rollback($conn);

        echo "<script>
            alert(" . json_encode("Transaksi gagal: " . $e->getMessage()) . ");
            window.location='../pages/transactions/add.php';
        </script>";

        exit;
    }
}


// ================= HAPUS TRANSAKSI =================
if (isset($_GET['action']) && $_GET['action'] == 'delete') {

    $kode_transaksi = $_GET['kode'] ?? '';

    mysqli_begin_transaction($conn);

    try {

        // Ambil detail untuk mengembalikan stok
        $stmtDetail = mysqli_prepare($conn,
            "SELECT id_produk, kuantitas
             FROM tabel_detail_transaksi
             WHERE kode_transaksi = ?");

        mysqli_stmt_bind_param($stmtDetail, "s", $kode_transaksi);
        mysqli_stmt_execute($stmtDetail);

        $resultDetail = mysqli_stmt_get_result($stmtDetail);

        while ($d = mysqli_fetch_assoc($resultDetail)) {

            $id_produk = (int) $d['id_produk'];
            $qty = (int) $d['kuantitas'];

            $stmtStok = mysqli_prepare($conn,
                "UPDATE tabel_produk
                 SET stok = stok + ?
                 WHERE id_produk = ?");

            mysqli_stmt_bind_param($stmtStok, "ii", $qty, $id_produk);
            mysqli_stmt_execute($stmtStok);
        }

        // Hapus detail
        $stmtHapusDetail = mysqli_prepare($conn,
            "DELETE FROM tabel_detail_transaksi WHERE kode_transaksi = ?");

        mysqli_stmt_bind_param($stmtHapusDetail, "s", $kode_transaksi);
        mysqli_stmt_execute($stmtHapusDetail);

        // Hapus transaksi
        $stmtHapus = mysqli_prepare($conn,
            "DELETE FROM tabel_transaksi WHERE kode_transaksi = ?");

        mysqli_stmt_bind_param($stmtHapus, "s", $kode_transaksi);
        mysqli_stmt_execute($stmtHapus);

        mysqli_commit($conn);

        echo "<script>
            alert('Transaksi berhasil dihapus!');
            window.location='../pages/transactions/list.php';
        </script>";

    } catch (Exception $e) {

        mysqli_rollback($conn);

        echo "<script>
            alert('Gagal menghapus transaksi: " . addslashes($e->getMessage()) . "');
            window.location='../pages/transactions/list.php';
        </script>";
    }

    exit;
}
?>
