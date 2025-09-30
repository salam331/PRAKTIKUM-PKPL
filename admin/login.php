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
                            <span class="pull-right">
                                <a href="lupa_password.php">Lupa Password</a>
                            </span>
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

        // Gunakan prepared statement untuk keamanan
        $stmt = $koneksi->prepare("SELECT * FROM pelanggan WHERE email_pelanggan = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika email ditemukan (hasilnya 1 baris)
        if ($result->num_rows == 1) {
            $akun = $result->fetch_assoc();

            // Verifikasi password yang di-hash
            if (password_verify($password, $akun['password_pelanggan'])) {
                // Password cocok, login berhasil
                $_SESSION["pelanggan"] = $akun;
                echo "<script>alert('Login Berhasil');</script>";

                if (isset($_SESSION["keranjang"]) && !empty($_SESSION["keranjang"])) {
                    echo "<script>location='checkout.php';</script>";
                } else {
                    echo "<script>location='riwayat.php';</script>";
                }
                exit(); // Hentikan eksekusi setelah redirect
    
            } else {
                // Password salah
                echo "<script>alert('Anda Gagal Login, Email atau Password Salah');</script>";
                echo "<script>location='login.php';</script>";
            }
        } else { // Email tidak ditemukan
            echo "<script>alert('Anda Gagal Login, Email atau Password Salah');</script>";
            echo "<script>location='login.php';</script>";
        }
    }
    ?>

</body>

</html>