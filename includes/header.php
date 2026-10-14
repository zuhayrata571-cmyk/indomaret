<?php
// Base URL agar link tetap valid jika dibuka dari folder mana saja
$base_url = "/indomaret098";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indomaret</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
        }

        /* Navbar Sederhana */
        .navbar {
            background-color: #005baa; /* Biru khas */
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            color: #ffffff;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
        }

        .navbar-brand span {
            color: #fff100; /* Kuning */
        }

        .navbar-menu {
            list-style: none;
            display: flex;
            gap: 15px;
            margin: 0;
            padding: 0;
        }

        .navbar-menu li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            padding: 6px 10px;
            border-radius: 4px;
        }

        .navbar-menu li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Kontainer sederhana untuk isi konten halaman */
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="<?= $base_url; ?>/index.php" class="navbar-brand">INDO<span>MARET</span></a>
        <ul class="navbar-menu">
            <li><a href="<?= $base_url; ?>/pages/dashboard.php">Dashboard</a></li>
            <li><a href="<?= $base_url; ?>/pages/products/list.php">Produk</a></li>
            <li><a href="<?= $base_url; ?>/pages/cashiers/list.php">Kasir</a></li>
            <li><a href="<?= $base_url; ?>/pages/transactions/list.php">Transaksi</a></li>
            <li><a href="<?= $base_url; ?>/pages/donation/list.php">Donasi</a></li>
        </ul>
    </nav>

    <!-- Konten Utama -->
    <div class="container">