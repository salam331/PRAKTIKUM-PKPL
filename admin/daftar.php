<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelanggan</title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
</head>

<body>

    <?php include 'menu.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Daftar Pelanggan</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" class="form-horizontal">
                            <div class="form-group"></div>
                            <label class="control-label col-md-3">Nama</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="nama" required>
                            </div>
                            <div class="form-group"></div>
                            <label class="control-label col-md-3">Email</label>
                            <div class="col-md-7">
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group"></div>
                            <label class="control-label col-md-3">Password</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="password" required>
                            </div>
                            <div class="form-group"></div>
                            <label class="control-label col-md-3">Alamat</label>
                            <div class="col-md-7">
                                <textarea class="form-control" name="alamat" required></textarea>
                            </div>
                            <div class="form-group"></div>
                            <label class="control-label col-md-3">NoTelepon</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="telepon" required pattern="\d{12}" maxlength="12" minlength="12" title="Masukkan tepat 12 digit angka"> 
                            </div>
                            <div class="form-group">
                                <div class="col-md-7 col-md-offset-3">
                                    <button class="btn btn-primary" name="daftar">Daftar</button>
                                </div>
                            </div>
                        </form>
                        <?php
                        //jika ada tombol dafta (ditekan)
                        if (isset($_POST['daftar'])) {
                            //mengambil isian form (nama, email, password, alamat, telepon)
                            $nama = $_POST['nama'];
                            $email = $_POST['email'];
                            $password = $_POST['password'];
                            $alamat = $_POST['alamat'];
                            $telepon = $_POST['telepon'];
                            // $password_hash = password_hash($password, PASSWORD_DEFAULT);

                            // kemudian cek apakah email sudah di gunakan sebelumnya atau belum
                            $ambil = $koneksi->query("SELECT * FROM pelanggan WHERE email_pelanggan = '$email'");
                            $yangcocok = $ambil->num_rows;

                            if ($yangcocok == 1) {
                                echo "<script>alert('Pendaftar Gagal, Email Sudah Terdaftar');</script>";
                                echo "<script>location='daftar.php';</script>";
                            } else {
                                //jika email belum terdaftar, maka simpan data ke database
                                // insert ke dalam tabel pelanggan menggunakan query INSERT
                                $koneksi->query("INSERT INTO pelanggan (email_pelanggan, password_pelanggan, nama_pelanggan, telepon_pelanggan, alamat_pelanggan) VALUES 
                                ('$email', '$password', '$nama', '$telepon', '$alamat')");

                                //tampilkan alertnya
                                echo "<script>alert('Pendaftaran Berhasil, Silahkan Login');</script>";
                                echo "<script>location='login.php';</script>";
                            }

                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>