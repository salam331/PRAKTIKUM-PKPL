<?php session_start(); ?>

<?php include 'koneksi.php' ?>

<?php 
// mendapatkan id_produk dari URL
$id_produk = $_GET['id'];

// query ambil data dari database
$ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk = '$id_produk'");
$detail = $ambil->fetch_assoc();

echo "<pre>";
print_r($detail);
echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
</head>

<body>

<?php include 'menu.php'; ?>

    <section class="kontent">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="foto_produk/<?php echo $detail ['foto_produk']; ?>" alt="" class="img-responsive">
                </div>
                <div class="col-md-6">
                    <h2><?php echo $detail ["nama_produk"];?></h2>
                    <h4>Rp. <?php echo number_format($detail ["harga_produk"]); ?></h4>
                    <h5>Stok: <?php echo $detail ["stok_produk"]; ?></h5>

                    <form method="post">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="number" min="1" class="form-control" name="jumlah" max="<?php echo $detail ["stok_produk"]; ?>">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary" name="beli">Beli</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php
                    // jika tombol beli di tekan maka akan menuju ke halaman keranjang
                    if(isset($_POST["beli"]))
                    {
                        // mendapatkan jumlah produk yang di inputkan
                        $jumlah = $_POST["jumlah"];
                        // masukkan ke dalam keranjang belabja
                        $_SESSION["keranjang"] [$id_produk] = $jumlah;

                        echo "<script>alert('Produk Telah Masuk ke Keranjang Belanja');</script>";
                        echo "<script>location='keranjang.php';</script>";
                    } 
                    ?>

                    <p><?php echo $detail ["deskripsi_produk"]; ?></p>
                </div>
            </div>
        </div>
    </section>


</body>

</html>