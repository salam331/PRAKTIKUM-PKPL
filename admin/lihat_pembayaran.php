<?php
session_start();
include 'koneksi.php';

// cek login
if (!isset($_SESSION["pelanggan"])) {
    echo "<script>alert('Silakan login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    exit();
}

// validasi id di URL
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo "<script>alert('ID Pembelian tidak valid');</script>";
    echo "<script>location='riwayat.php';</script>";
    exit();
}

$id_pembelian = intval($_GET["id"]);

// ambil data pembayaran + pembelian
$ambil = $koneksi->query("
    SELECT pembayaran.*, pembelian.total_pembelian, pembelian.id_pelanggan 
    FROM pembayaran 
    LEFT JOIN pembelian 
    ON pembayaran.id_pembelian = pembelian.id_pembelian 
    WHERE pembelian.id_pembelian = '$id_pembelian'
");

$detbay = $ambil->fetch_assoc();

// jika belum ada data pembayaran
if (empty($detbay)) {
    echo "<script>alert('Belum ada data pembayaran untuk transaksi ini');</script>";
    echo "<script>location='riwayat.php';</script>";
    exit();
}

// validasi hak akses user
if ($_SESSION["pelanggan"]["id_pelanggan"] != $detbay["id_pelanggan"]) {
    echo "<script>alert('Anda tidak berhak melihat pembayaran orang lain');</script>";
    echo "<script>location='riwayat.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Pembayaran</title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
</head>

<body>

    <?php include 'menu.php'; ?>

    <div class="container mt-4">
        <h3>Detail Pembayaran</h3>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-striped">
                    <tr>
                        <th>Nama Penyetor</th>
                        <td><?php echo htmlspecialchars($detbay["nama"]); ?></td>
                    </tr>
                    <tr>
                        <th>Bank</th>
                        <td><?php echo htmlspecialchars($detbay["bank"]); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?php echo htmlspecialchars($detbay["tanggal"]); ?></td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>Rp. <?php echo number_format($detbay["total_pembelian"]); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Bukti Transfer:</h5>
                <img src="bukti_pembayaran/<?php echo $detbay["bukti"]; ?>" alt="Bukti Pembayaran"
                    class="img-fluid border p-2">
            </div>
        </div>
    </div>


</body>

</html>