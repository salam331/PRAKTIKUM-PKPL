<?php
session_start();
include 'koneksi.php';

// cek login
if (!isset($_SESSION["pelanggan"]) || empty($_SESSION["pelanggan"])) {
    echo "<script>alert('Silahkan login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    exit();
}

// ambil id pembelian dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Pembelian tidak ditemukan');</script>";
    echo "<script>location='riwayat.php';</script>";
    exit();
}

$idpem = intval($_GET['id']);
$ambil = $koneksi->query("SELECT * FROM pembelian WHERE id_pembelian='$idpem'");
$detpem = $ambil->fetch_assoc();

// validasi kepemilikan nota
$id_pelanggan_beli = $detpem["id_pelanggan"];
$id_pelanggan_login = $_SESSION["pelanggan"]["id_pelanggan"];

if ($id_pelanggan_login != $id_pelanggan_beli) {
    echo "<script>alert('Jangan Nakal');</script>";
    echo "<script>location='riwayat.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">
    <h2>Konfirmasi Pembayaran</h2>
    <p>Kirim bukti pembayaran anda disini</p>
    <div class="alert alert-info">
        Total Tagihan Anda <strong>Rp. <?php echo number_format($detpem["total_pembelian"]); ?></strong>
    </div>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Penyetor</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Bank</label>
            <input type="text" class="form-control" name="bank" required>
        </div>
        <div class="form-group">
            <label>Total</label>
            <!-- simpan nilai asli (tanpa format) -->
            <input type="text" class="form-control" name="jumlah" 
                   value="Rp.<?php echo number_format($detpem['total_pembelian']); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Foto Bukti</label>
            <input type="file" class="form-control" name="bukti" accept=".jpg,.jpeg,.png" required>
            <p class="text-danger">Foto bukti harus berupa JPG/PNG/JPEG dan max 5MB</p>
        </div>
        <button class="btn btn-primary" name="kirim">Kirim</button>
    </form>
</div>

<?php
if (isset($_POST["kirim"])) {
    $nama   = $koneksi->real_escape_string($_POST["nama"]);
    $bank   = $koneksi->real_escape_string($_POST["bank"]);
    $jumlah = intval($_POST["jumlah"]);
    $tanggal = date("Y-m-d");

    // cek file upload
    $namabukti = $_FILES["bukti"]["name"];
    $lokasibukti = $_FILES["bukti"]["tmp_name"];
    $size = $_FILES["bukti"]["size"];

    $ext = strtolower(pathinfo($namabukti, PATHINFO_EXTENSION));
    $allowed_ext = ["jpg","jpeg","png"];

    if (!in_array($ext, $allowed_ext)) {
        echo "<script>alert('Format file tidak valid, hanya JPG/PNG/JPEG.');</script>";
        exit();
    }
    if ($size > 5*1024*1024) { // max 5MB
        echo "<script>alert('Ukuran file terlalu besar, maksimal 5MB.');</script>";
        exit();
    }

    $namafiks = date("YmdHis") . "_" . uniqid() . "." . $ext;
    move_uploaded_file($lokasibukti, "bukti_pembayaran/$namafiks");

    // simpan pembayaran
    $koneksi->query("INSERT INTO pembayaran (id_pembelian, nama, bank, jumlah, tanggal, bukti) 
                     VALUES ('$idpem', '$nama', '$bank', '$jumlah', '$tanggal', '$namafiks')");

    // update status pembelian
    $koneksi->query("UPDATE pembelian 
                     SET status_pembelian='Sudah Kirim Pembayaran' 
                     WHERE id_pembelian = '$idpem'");

    echo "<script>alert('Terima Kasih, bukti pembayaran sudah terkirim');</script>";
    echo "<script>location='riwayat.php';</script>";
}
?>

</body>
</html>
