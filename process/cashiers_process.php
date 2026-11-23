// ================= HAPUS KASIR =================
if (isset($_GET['action']) && $_GET['action'] == 'delete') {

    $id = (int) $_GET['id'];

    $query = mysqli_prepare($conn,
        "DELETE FROM tabel_kasir WHERE id_kasir = ?");

    mysqli_stmt_bind_param($query, "i", $id);
    mysqli_stmt_execute($query);

    if (mysqli_stmt_affected_rows($query) > 0) {
        echo "<script>
            alert('Kasir berhasil dihapus!');
            window.location='../pages/cashiers/list.php';
        </script>";
    } else {
        echo "<script>
            alert('Data kasir tidak terhapus! ID: $id');
            window.location='../pages/cashiers/list.php';
        </script>";
    }

    mysqli_stmt_close($query);
    exit;
}