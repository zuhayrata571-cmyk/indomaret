<?php
include '../config/config.php';

// ================= TAMBAH DETAIL TRANSAKSI =================
if (isset($_POST['action']) && $_POST['action'] == 'add') {

    $kode_transaksi = trim($_POST['kode_transaksi']);
    $id_produk = (int) $_POST['id_produk'];
    $id_kasir = (int) $_POST['id_kasir'];
    $kuantitas = (int) $_POST['kuantitas'];
    $diskon = (int) $_POST['diskon'];

    if ($kuantitas <= 0 || $diskon < 0) {
        die("Kuantitas atau diskon tidak valid.");
    }

    mysqli_begin_transaction($conn);

    // Ambil harga dan stok produk
    $query_produk = mysqli_prepare($conn,
        "SELECT harga, stok FROM tabel_produk WHERE id_produk = ? FOR UPDATE");

    mysqli_stmt_bind_param($query_produk, "i", $id_produk);
    mysqli_stmt_execute($query_produk);

    $result_produk = mysqli_stmt_get_result($query_produk);
    $produk = mysqli_fetch_assoc($result_produk);

    if (!$produk) {
        mysqli_rollback($conn);
        die("Produk tidak ditemukan.");
    }

    $harga = (int) $produk['harga'];
    $stok = (int) $produk['stok'];

    // Cek stok
    if ($kuantitas > $stok) {
        mysqli_rollback($conn);

        echo "<script>
            alert('Stok produk tidak mencukupi!');
            window.location='/indomaret098/pages/transactions/detail.php';
        </script>";
        exit;
    }

    // Hitung harga jual
    $harga_jual = ($harga * $kuantitas) - $diskon;

    if ($harga_jual < 0) {
        mysqli_rollback($conn);
        die("Diskon tidak boleh melebihi harga barang.");
    }

    // Simpan detail transaksi
    $query_detail = mysqli_prepare($conn,
        "INSERT INTO tabel_detail_transaksi
        (kode_transaksi, id_produk, id_kasir, harga, kuantitas, diskon, harga_jual)
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$query_detail) {
        mysqli_rollback($conn);
        die("Query error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($query_detail, "siiiiii",
        $kode_transaksi,
        $id_produk,
        $id_kasir,
        $harga,
        $kuantitas,
        $diskon,
        $harga_jual
    );

    if (!mysqli_stmt_execute($query_detail)) {
        mysqli_rollback($conn);
        die("Detail gagal disimpan: " . mysqli_stmt_error($query_detail));
    }

    // Kurangi stok produk
    $query_stok = mysqli_prepare($conn,
        "UPDATE tabel_produk SET stok = stok - ? WHERE id_produk = ?");

    mysqli_stmt_bind_param($query_stok, "ii", $kuantitas, $id_produk);

    if (!mysqli_stmt_execute($query_stok)) {
        mysqli_rollback($conn);
        die("Stok gagal diperbarui.");
    }

    // Hitung ulang total transaksi
    $query_total = mysqli_prepare($conn,
        "SELECT SUM(harga_jual) AS total
         FROM tabel_detail_transaksi
         WHERE kode_transaksi = ?");

    mysqli_stmt_bind_param($query_total, "s", $kode_transaksi);
    mysqli_stmt_execute($query_total);

    $result_total = mysqli_stmt_get_result($query_total);
    $data_total = mysqli_fetch_assoc($result_total);

    $total_transaksi = (int) $data_total['total'];

    // Update total transaksi
    $update_transaksi = mysqli_prepare($conn,
        "UPDATE tabel_transaksi SET total = ? WHERE kode_transaksi = ?");

    mysqli_stmt_bind_param($update_transaksi, "is",
        $total_transaksi, $kode_transaksi);

    if (!mysqli_stmt_execute($update_transaksi)) {
        mysqli_rollback($conn);
        die("Total transaksi gagal diperbarui.");
    }

    mysqli_commit($conn);

    echo "<script>
        alert('Detail transaksi berhasil ditambahkan!');
        window.location='/indomaret098/pages/transaction/detail.php';
    </script>";

    exit;
}


// ================= HAPUS DETAIL TRANSAKSI =================
if (isset($_GET['action']) && $_GET['action'] == 'delete') {

    $kode_transaksi = $_GET['kode'];
    $id_produk = (int) $_GET['produk'];

    mysqli_begin_transaction($conn);

    // Ambil kuantitas sebelum dihapus
    $query_detail = mysqli_prepare($conn,
        "SELECT kuantitas FROM tabel_detail_transaksi
         WHERE kode_transaksi = ? AND id_produk = ?");

    mysqli_stmt_bind_param($query_detail, "si",
        $kode_transaksi, $id_produk);

    mysqli_stmt_execute($query_detail);

    $result = mysqli_stmt_get_result($query_detail);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        mysqli_rollback($conn);
        die("Detail transaksi tidak ditemukan.");
    }

    $kuantitas = (int) $data['kuantitas'];

    // Kembalikan stok produk
    $query_stok = mysqli_prepare($conn,
        "UPDATE tabel_produk SET stok = stok + ? WHERE id_produk = ?");

    mysqli_stmt_bind_param($query_stok, "ii",
        $kuantitas, $id_produk);

    if (!mysqli_stmt_execute($query_stok)) {
        mysqli_rollback($conn);
        die("Stok gagal dikembalikan.");
    }

    // Hapus detail transaksi
    $query_hapus = mysqli_prepare($conn,
        "DELETE FROM tabel_detail_transaksi
         WHERE kode_transaksi = ? AND id_produk = ?");

    mysqli_stmt_bind_param($query_hapus, "si",
        $kode_transaksi, $id_produk);

    if (!mysqli_stmt_execute($query_hapus)) {
        mysqli_rollback($conn);
        die("Detail gagal dihapus.");
    }

    // Hitung ulang total transaksi
    $query_total = mysqli_prepare($conn,
        "SELECT SUM(harga_jual) AS total
         FROM tabel_detail_transaksi
         WHERE kode_transaksi = ?");

    mysqli_stmt_bind_param($query_total, "s", $kode_transaksi);
    mysqli_stmt_execute($query_total);

    $result_total = mysqli_stmt_get_result($query_total);
    $data_total = mysqli_fetch_assoc($result_total);

    $total_transaksi = (int) ($data_total['total'] ?? 0);

    // Update total transaksi
    $update_transaksi = mysqli_prepare($conn,
        "UPDATE tabel_transaksi SET total = ? WHERE kode_transaksi = ?");

    mysqli_stmt_bind_param($update_transaksi, "is",
        $total_transaksi, $kode_transaksi);

    if (!mysqli_stmt_execute($update_transaksi)) {
        mysqli_rollback($conn);
        die("Total transaksi gagal diperbarui.");
    }

    mysqli_commit($conn);

    echo "<script>
        alert('Detail berhasil dihapus dan stok dikembalikan!');
        window.location='/indomaret098/pages/transactions/detail.php';
    </script>";

    exit;
}
?>