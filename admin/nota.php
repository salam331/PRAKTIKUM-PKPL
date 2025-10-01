<?php
session_start();
include 'koneksi.php';

// Pastikan pelanggan sudah login
if (!isset($_SESSION["pelanggan"]) || empty($_SESSION["pelanggan"])) {
    echo "<script>alert('Silahkan login terlebih dahulu');</script>";
    echo "<script>location='login.php';</script>";
    exit();
}

// Validasi parameter id dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID pembelian tidak ditemukan');</script>";
    echo "<script>location='riwayat.php';</script>";
    exit();
}

$id_pembelian = intval($_GET['id']); // cast ke integer untuk keamanan

// Ambil data pembelian + data pelanggan (prepared statement)
$stmt = $koneksi->prepare("
    SELECT p.*, pel.nama_pelanggan, pel.telepon_pelanggan, pel.email_pelanggan
    FROM pembelian p
    JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
    WHERE p.id_pembelian = ?
");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($koneksi->error));
}
$stmt->bind_param("i", $id_pembelian);
$stmt->execute();
$result = $stmt->get_result();

// Jika tidak ada pembelian dengan id tersebut
if (!$result || $result->num_rows === 0) {
    echo "<script>alert('Data pembelian tidak ditemukan');</script>";
    echo "<script>location='riwayat.php';</script>";
    exit();
}

$detail = $result->fetch_assoc();
$stmt->close();

// Cek apakah pembelian milik pelanggan yang login
$id_pelanggan_pembeli = $detail['id_pelanggan'];
$id_pelanggan_login = $_SESSION["pelanggan"]["id_pelanggan"];

if ($id_pelanggan_pembeli != $id_pelanggan_login) {
    echo "<script>alert('Anda tidak berhak melihat nota ini');</script>";
    echo "<script>location='riwayat.php';</script>";
    exit();
}

// Ambil daftar produk pada pembelian (prepared)
$stmt2 = $koneksi->prepare("SELECT * FROM pembelian_produk WHERE id_pembelian = ?");
if ($stmt2 === false) {
    die('Prepare failed: ' . htmlspecialchars($koneksi->error));
}
$stmt2->bind_param("i", $id_pembelian);
$stmt2->execute();
$produk_result = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota Pembelian #<?php echo htmlspecialchars($detail['id_pembelian']); ?></title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
</head>
<body>

<?php include 'menu.php'; ?>

<section class="konten">
    <div class="container">
        <h2>Detail Pembelian</h2>

        <div class="row">
            <div class="col-md-4">
                <h3>Pembelian</h3>
                <strong>No. Pembelian: <?php echo htmlspecialchars($detail['id_pembelian']); ?></strong><br>
                Tanggal: <?php echo htmlspecialchars($detail['tanggal_pembelian']); ?><br>
                Total: Rp. <?php echo number_format((float)$detail['total_pembelian']); ?>
            </div>

            <div class="col-md-4">
                <h3>Pelanggan</h3>
                <strong><?php echo htmlspecialchars($detail['nama_pelanggan']); ?></strong>
                <p>
                    Telepon: <?php echo htmlspecialchars($detail['telepon_pelanggan']); ?> <br>
                    Email: <?php echo htmlspecialchars($detail['email_pelanggan']); ?>
                </p>
            </div>

            <div class="col-md-4">
                <h3>Pengiriman</h3>
                <strong><?php echo htmlspecialchars($detail['nama_kota']); ?></strong><br>
                Ongkos Kirim: Rp. <?php echo number_format((float)$detail['tarif']); ?> <br>
                Alamat: <?php echo nl2br(htmlspecialchars($detail['alamat_pengiriman'])); ?>
            </div>
        </div>

        <hr>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Berat</th>
                    <th>Jumlah</th>
                    <th>Subberat</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                if ($produk_result && $produk_result->num_rows > 0):
                    while ($pecah = $produk_result->fetch_assoc()):
                        // pastikan tipe numeric disajikan dengan benar
                        $harga = (float)$pecah['harga'];
                        $berat = (float)$pecah['berat'];
                        $jumlah = (int)$pecah['jumlah'];
                        $subberat = (float)$pecah['subberat'];
                        $subharga = (float)$pecah['subharga'];
                ?>
                    <tr>
                        <td><?php echo $nomor; ?></td>
                        <td><?php echo htmlspecialchars($pecah['nama']); ?></td>
                        <td>Rp. <?php echo number_format($harga); ?></td>
                        <td><?php echo $berat; ?> gr</td>
                        <td><?php echo $jumlah; ?></td>
                        <td><?php echo $subberat; ?> gr</td>
                        <td>Rp. <?php echo number_format($subharga); ?></td>
                    </tr>
                <?php
                        $nomor++;
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada produk pada pembelian ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-7">
                <div class="alert alert-info">
                    <p>
                        Silahkan melakukan pembayaran sejumlah:
                        <strong>Rp. <?php echo number_format((float)$detail['total_pembelian']); ?></strong>
                        <br>
                        <strong>BANK BNI : 1650839083 AN ABDUL SALAM</strong>
                    </p>
                </div>
            </div>
            <div class="col-md-5">
                <div class="alert alert-success text-justify">
                    <p>
                        SILAHKAN PERGI KE MENU RIWAYAT BELANJA
                        dan melakukan pembayaran ke rekening di samping.
                        Pembayaran akan otomatis terverifikasi jika Anda melakukan pembayaran sesuai dengan jumlah total pembelian.
                    </p>
                </div>
        </div>

    </div>
</section>

</body>
</html>

<?php
// bersihkan prepared statement
$stmt2->close();
$koneksi->close();
?>
    