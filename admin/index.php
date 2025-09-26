<?php
session_start();
// Koneksi ke database
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Buah</title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
    <style>
        /* Tambahan styling */
        body {
            background-color: #f8f9fa;
        }

        .navbar {
            margin-bottom: 20px;
        }

        .konten {
            padding: 20px 0;
        }

        .thumbnail {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .thumbnail:hover {
            transform: scale(1.05);
        }

        .caption h3 {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }

        .caption h5 {
            color: #28a745;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        
    </style>
</head>

<body>
<?php include 'menu.php'; ?>

    <!-- Content -->
    <section class="konten">
        <div class="container">
            <h1 class="text-center">Produk Terbaru</h1>
            <div class="row">
                <!-- Loop produk -->
                <?php $ambil = $koneksi->query("SELECT * FROM produk"); ?>
                <?php while ($perproduk = $ambil->fetch_assoc()) { ?>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="thumbnail">
                            <img src="foto_produk/<?php echo $perproduk['foto_produk']; ?>" alt="Produk"
                                class="img-responsive">
                            <div class="caption text-center">
                                <h3><?php echo $perproduk['nama_produk']; ?></h3>
                                <h5>Rp<?php echo number_format($perproduk['harga_produk']); ?></h5>
                                <a href="beli.php?id=<?php echo $perproduk['id_produk'];?>" class="btn btn-primary">Beli</a>
                                <a href="detail.php?id=<?php echo $perproduk["id_produk"]; ?>" class="btn btn-default">Detail</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <script src="bs-binary-admin/assets/js/jquery-1.10.2.js"></script>
    <script src="bs-binary-admin/assets/js/bootstrap.min.js"></script>
</body>

</html>