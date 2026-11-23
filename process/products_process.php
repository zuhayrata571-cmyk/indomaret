
<?php
include '../config/config.php';

// ================= TAMBAH PRODUK =================
if (isset($_POST['action']) && $_POST['action'] == 'add') {

    $nama_produk = trim($_POST['nama_produk']);
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];

    $query = mysqli_prepare($conn,
        "INSERT INTO tabel_produk (nama_produk, harga, stok)
         VALUES (?, ?, ?)");

    if (!$query) {
        die("Query error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($query, "sii", $nama_produk, $harga, $stok);

    if (mysqli_stmt_execute($query)) {
        echo "<script>
            alert('Produk berhasil ditambahkan!');
            window.location='../pages/products/list.php';
        </script>";
    } else {
        die("Insert error: " . mysqli_stmt_error($query));
    }

    mysqli_stmt_close($query);
    exit;
}


// ================= EDIT PRODUK =================
if (isset($_POST['action']) && $_POST['action'] == 'edit') {

    $id = (int) $_POST['id_produk'];
    $nama_produk = trim($_POST['nama_produk']);
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];

    $query = mysqli_prepare($conn,
        "UPDATE tabel_produk
         SET nama_produk = ?, harga = ?, stok = ?
         WHERE id_produk = ?");

    if (!$query) {
        die("Query error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($query, "siii",
        $nama_produk, $harga, $stok, $id
    );

    if (mysqli_stmt_execute($query)) {
        echo "<script>
            alert('Produk berhasil diperbarui!');
            window.location='../pages/products/list.php';
        </script>";
    } else {
        die("Update error: " . mysqli_stmt_error($query));
    }

    mysqli_stmt_close($query);
    exit;
}


// ================= HAPUS PRODUK =================
if (isset($_GET['action']) && $_GET['action'] == 'delete') {

    $id = (int) $_GET['id'];

    $query = mysqli_prepare($conn,
        "DELETE FROM tabel_produk WHERE id_produk = ?");

    if (!$query) {
        die("Query error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($query, "i", $id);

    if (mysqli_stmt_execute($query)) {
        echo "<script>
            alert('Produk berhasil dihapus!');
            window.location='../pages/products/list.php';
        </script>";
    } else {
        echo "<script>
            alert('Produk gagal dihapus!');
            window.location='../pages/products/list.php';
        </script>";
    }

    mysqli_stmt_close($query);
    exit;
}

?>
