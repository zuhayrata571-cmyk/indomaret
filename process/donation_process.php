
<?php
include '../config/config.php';

// TAMBAH DONASI
if (isset($_POST['action']) && $_POST['action'] == 'add') {

    $nama_donasi = trim($_POST['nama_donasi']);
    $harga_donasi = (int) $_POST['harga_donasi'];

    if ($nama_donasi == '' || $harga_donasi < 0) {
        die("Data donasi tidak valid.");
    }

    $query = mysqli_prepare($conn,
        "INSERT INTO tabel_donasi (nama_donasi, harga_donasi) VALUES (?, ?)"
    );

    mysqli_stmt_bind_param($query, "si", $nama_donasi, $harga_donasi);

    if (mysqli_stmt_execute($query)) {
        header("Location: /indomaret098/pages/donation/list.php");
        exit;
    } else {
        die("Gagal menambahkan donasi: " . mysqli_error($conn));
    }
}

// EDIT DONASI
if (isset($_POST['action']) && $_POST['action'] == 'edit') {

    $id_donasi = (int) $_POST['id_donasi'];
    $nama_donasi = trim($_POST['nama_donasi']);
    $harga_donasi = (int) $_POST['harga_donasi'];

    if ($nama_donasi == '' || $harga_donasi < 0) {
        die("Data donasi tidak valid.");
    }

    $query = mysqli_prepare($conn,
        "UPDATE tabel_donasi SET nama_donasi = ?, harga_donasi = ? WHERE id_donasi = ?"
    );

    mysqli_stmt_bind_param($query, "sii", $nama_donasi, $harga_donasi, $id_donasi);

    if (mysqli_stmt_execute($query)) {
        header("Location: /indomaret098/pages/donation/list.php");
        exit;
    } else {
        die("Gagal mengedit donasi: " . mysqli_error($conn));
    }
}

// HAPUS DONASI
if (isset($_GET['action']) && $_GET['action'] == 'delete') {

    $id_donasi = (int) $_GET['id'];

    $query = mysqli_prepare($conn,
        "DELETE FROM tabel_donasi WHERE id_donasi = ?"
    );

    mysqli_stmt_bind_param($query, "i", $id_donasi);

    if (mysqli_stmt_execute($query)) {
        header("Location: /indomaret098/pages/donation/list.php");
        exit;
    } else {
        die("Gagal menghapus donasi: " . mysqli_error($conn));
    }
}
?>
