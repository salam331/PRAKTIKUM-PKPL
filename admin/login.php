<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan</title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
</head>

<body>

    <!-- Navbar -->
    <?php include 'menu.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Login Pelanggan</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <button class="btn btn-primary" name="login">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php
    if (isset($_POST["login"])) {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $ambil = $koneksi->query("SELECT * FROM pelanggan WHERE email_pelanggan = '$email' AND password_pelanggan = '$password'");

        // ngitung akun yang ter ambil
        $akunyangcocok = $ambil->num_rows;

        // jika ada satu akun yang cocok maka login kan
        if ($akunyangcocok == 1) {

            // anda suksen login
            // ingin mendapatkan akun dalam bentuk array
            $akun = $ambil->fetch_assoc();
            // simpan dalam session pelanggan
            $_SESSION["pelanggan"] = $akun;
            echo "<script>alert('Login Berhasil');</script>";

            if (isset($_SESSION["keranjang"]) or !empty($_SESSION["keranjang"])) {
                echo "<script>location='checkout.php';</script>";
            }else{
                echo "<script>location='riwayat.php';</script>";
            }

        } else { // anda gagal login
    
            echo "<script>alert('Anda Gagal Login, Periksa Kembali Akun Anda');</script>";
            echo "<script>location='login.php';</script>";

        }


    }
    ?>

</body>

</html>