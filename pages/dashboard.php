
<?php include '../includes/header.php'; ?>

<style>
    .dashboard-title {
        margin-bottom: 8px;
        font-size: 26px;
        color: #005baa;
    }

    .dashboard-subtitle {
        color: #777;
        margin-bottom: 25px;
    }

    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }

    .dashboard-card {
        padding: 22px;
        border-radius: 8px;
        background: white;
        border-left: 5px solid #005baa;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .dashboard-card h3 {
        color: #777;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .dashboard-card p {
        font-size: 22px;
        font-weight: bold;
        color: #333;
    }

    .menu-section {
        margin-bottom: 25px;
    }

    .menu-section h2 {
        margin-bottom: 15px;
        font-size: 20px;
        color: #333;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .menu-item {
        text-decoration: none;
        color: #333;
        padding: 22px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fff;
        transition: 0.2s;
    }

    .menu-item h3 {
        color: #005baa;
        margin-bottom: 8px;
        font-size: 16px;
    }

    .menu-item p {
        color: #777;
        font-size: 13px;
    }

    .menu-item:hover {
        background: #f0f7ff;
        border-color: #005baa;
        transform: translateY(-3px);
    }

    .info-section {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .info-section h2 {
        margin-bottom: 12px;
        font-size: 20px;
    }

    .info-section p {
        line-height: 1.7;
        color: #666;
    }

    @media(max-width: 800px) {
        .dashboard-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 500px) {
        .dashboard-cards,
        .menu-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<h1 class="dashboard-title">Dashboard</h1>

<p class="dashboard-subtitle">
    Selamat datang di sistem manajemen Indomaret.
</p>

<div class="dashboard-cards">

    <div class="dashboard-card">
        <h3>Total Produk</h3>
        <p>Data Produk</p>
    </div>

    <div class="dashboard-card" style="border-color: #e53935;">
        <h3>Total Kasir</h3>
        <p>Data Kasir</p>
    </div>

    <div class="dashboard-card" style="border-color: #ffca28;">
        <h3>Total Transaksi</h3>
        <p>Data Transaksi</p>
    </div>

    <div class="dashboard-card" style="border-color: #43a047;">
        <h3>Total Donasi</h3>
        <p>Data Donasi</p>
    </div>

</div>

<div class="menu-section">

    <h2>Menu Utama</h2>

    <div class="menu-grid">

        <a href="products/list.php" class="menu-item">
            <h3>Produk</h3>
            <p>Kelola data produk</p>
        </a>

        <a href="cashiers/list.php" class="menu-item">
            <h3>Kasir</h3>
            <p>Kelola data kasir</p>
        </a>

        <a href="transactions/list.php" class="menu-item">
            <h3>Transaksi</h3>
            <p>Lihat data transaksi</p>
        </a>

        <a href="donation/list.php" class="menu-item">
            <h3>Donasi</h3>
            <p>Kelola data donasi</p>
        </a>

    </div>

</div>

<div class="info-section">

    <h2>Informasi Sistem</h2>

    <p>
        Sistem ini digunakan untuk mengelola data produk, kasir,
        transaksi penjualan, dan donasi. Dengan sistem ini,
        pengelolaan data toko menjadi lebih mudah dan terorganisir.
    </p>

</div>

<?php include '../includes/footer.php'; ?>
