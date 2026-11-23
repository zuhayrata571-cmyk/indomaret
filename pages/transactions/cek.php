<?php
echo "Lokasi folder: " . __DIR__ . "<br><br>";

echo "File yang ada di folder:<br>";

foreach (scandir(__DIR__) as $file) {
    echo $file . "<br>";
}

echo "<br>";

if (file_exists(__DIR__ . '/detail.php')) {
    echo "FILE DETAIL.PHP DITEMUKAN";
} else {
    echo "FILE DETAIL.PHP TIDAK DITEMUKAN";
}
?>