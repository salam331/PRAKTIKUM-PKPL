<?php
session_start();
include 'koneksi.php';

// jika pelanggan belum login maka dia tidk bisa mengakses halaman ini
if (!isset($_SESSION["pelanggan"]) or empty($_SESSION["pelanggan"])) {
    echo "<script>alert('Silahkan Login Terlebih Dahulu');</script>";
    echo "<script>location='login.php';</script>";
    exit();
}

// mendapatkan id_pembelian dari URL
$idpem = $_GET['id'];
$ambil = $koneksi->query("SELECT * FROM pembelian WHERE id_pembelian='$idpem'");
$detpem = $ambil->fetch_assoc();

// echo "<pre>";
// print_r($detpem);
// echo "</pre>";

// mendapatkan id_pelanggan yang beli
$id_pelanggan_beli = $detpem["id_pelanggan"];

// mendapatkan id_pelanggan yang login
$id_pelanggan_login = $_SESSION["pelanggan"]["id_pelanggan"];

if ($id_pelanggan_login !== $id_pelanggan_beli) {
    echo "<script>alert('Jangan Nakal');</script>";
    echo "<script>location='riwayat.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
</head>

<body>

    <?php include 'menu.php'; ?>

    <div class="container">
        <h2>Konfirmasi Pembayaran</h2>
        <p>kirim bukti pembayaran anda disini</p>
        <div class="alert alert-info">Total Tagihan Anda <strong>Rp.
                <?php echo number_format($detpem["total_pembelian"]); ?></strong></div>

        <form method="post" enctype="multipart/form-data">
            <div class="form-gorup">
                <label>Nama Penyetor</label>
                <input type="text" name="nama" class="form-control">
            </div>
            <div class="form-gorup">
                <label>Bank</label>
                <input type="text" class="form-control" name="bank">
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="text" class="form-control" name="jumlah"
                    value="Rp. <?php echo number_format($detpem['total_pembelian']); ?>" readonly>
            </div>

            <div class="form-gorup">
                <label>Foto Bukti</label>
                <input type="file" class="form-control" name="bukti">
                <p class="texy-danger">Foto Bukti Harus berupa Jpg dan Max 5MB</p>
            </div>
            <button class="btn btn-primary" name="kirim">Kirim</button>
        </form>
    </div>

    <?php
    // jika tombol kirim di tekan
    if (isset($_POST["kirim"])) {
        // upload terlebih dahulu foto buktinya
        $namabukti = $_FILES["bukti"]["name"];
        $lokasibukti = $_FILES["bukti"]["tmp_name"];
        $namafiks = date("YmdHis") . $namabukti;
        move_uploaded_file($lokasibukti, "bukti_pembayaran/$namafiks");

        $nama = $_POST["nama"];
        $jumlah = $_POST["jumlah"];
        $bank = $_POST["bank"];
        $tanggal = date("Y-m-d");

        // simpan pembayaran
        $koneksi->query("INSERT INTO pembayaran (id_pembelian, nama, bank, jumlah, tanggal, bukti) VALUES ('$idpem', '$nama', '$bank', '$jumlah', '$tanggal', '$namafiks')");

        // update data pembelian dari pending menjadi sudah kirim pembayaran
        $koneksi->query("UPDATE pembelian SET status_pembelian='Sudah Kirim Pembayaran' WHERE id_pembelian = '$idpem'");

        echo "<script>alert('Terima Kasih Sudah Mengirimkan Bukti Pembayaran');</script>";
        echo "<script>location='riwayat.php';</script>";

    }

    ?>


</body>

</html>