<?php
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembelian</title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
</head>

<body>

    <!-- Navbar -->
    <?php include 'menu.php'; ?>

    <section class="konten">
        <div class="container">

            <!-- nota disini copas aja dari nota yang ada di admin -->
            <h2>Detail Pembelian</h2>

            <?php
            $ambil = $koneksi->query("SELECT * FROM pembelian JOIN pelanggan ON pembelian.id_pelanggan = pelanggan.id_pelanggan WHERE pembelian.id_pembelian = '$_GET[id]'");
            $detail = $ambil->fetch_assoc();
            ?>
            <!-- <pre><?php //print_r($detail); ?></pre> -->

            <!-- jika pelanggan yang membeli tidak sama dengan pelanggan yang login, maka akan di larikan ke halaman riwayat.php kerana dia tidak berhak melihat nota orang lain -->
            <?php
            // mendapatkan id_pelanggan yang membeli
            $idpelangganyangbeli = $detail["id_pelanggan"];

            // mendapatkan id_pelanggan yang login
            $idpelangganyanglogin = $_SESSION["pelanggan"]["id_pelanggan"];

            if($idpelangganyangbeli !== $idpelangganyanglogin)
            {
                echo "<script>alert('Anda tidak berhak melihat nota ini');</script>";
                echo "<script>location='riwayat.php';</script>";
                exit();
            }
            ?>


            <div class="row">
                <div class="col-md-4">
                    <h3>Pembelian</h3>
                    <strong>No. Pembelian: <?php echo $detail['id_pembelian']; ?></strong> <br>
                    Tanggal: <?php echo $detail['tanggal_pembelian']; ?> <br>
                    Total: <?php echo number_format($detail['total_pembelian']); ?>
                </div>
                <div class="col-md-4">
                    <h3>Pelanggan</h3>
                    <strong><?php echo $detail['nama_pelanggan']; ?></strong>
                    <p>
                        Telepon: <?php echo $detail['telepon_pelanggan']; ?> <br>
                        Email: <?php echo $detail['email_pelanggan']; ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <h3>Pengiriman</h3>
                    <strong><?php echo $detail['nama_kota']; ?></strong> <br>
                    Ongkos Kirim: Rp. <?php echo number_format($detail['tarif']); ?> <br>
                    Alamat: <?php echo $detail['alamat_pengiriman']; ?>
                </div>
            </div>

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
                    <?php $nomor = 1; ?>
                    <?php $ambil = $koneksi->query("SELECT * FROM pembelian_produk WHERE id_pembelian='$_GET[id]'"); ?>
                    <?php while ($pecah = $ambil->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $nomor; ?></td>
                            <td><?php echo $pecah['nama']; ?></td>
                            <td>Rp. <?php echo number_format($pecah['harga']); ?></td>
                            <td><?php echo $pecah['berat']; ?> gr</td>
                            <td><?php echo $pecah['jumlah']; ?></td>
                            <td><?php echo $pecah['subberat']; ?> gr</td>
                            <td><?php echo number_format($pecah['subharga']); ?></td>
                        </tr>
                        <?php $nomor++; ?>
                    <?php } ?>
                </tbody>
            </table>
            <div class="row">
                <div claas="col-md-7">
                    <div class="alert alert-info">
                        <p>
                            Silahkan Melakukkan Pembayaran Sejumlah Rp.
                            <?php echo number_format($detail['total_pembelian']); ?> ke <br>
                            <strong>BANK BNI : 1650839083 AN ABDUL SALAM</strong>
                        </p>
                    </div>
                </div>
            </div>


        </div>
    </section>



</body>

</html>