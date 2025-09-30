<?php
date_default_timezone_set('Asia/Jakarta'); // Tambahkan baris ini
session_start();
include 'koneksi.php';

if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Token tidak valid.");
}

$token = $koneksi->real_escape_string($_GET['token']);

// Cek token di database dan pastikan belum kedaluwarsa
$result = $koneksi->query("SELECT * FROM pelanggan WHERE reset_token = '$token' AND reset_token_expire > NOW()");

if ($result->num_rows == 0) {
    die("Token tidak valid atau sudah kedaluwarsa.");
}

$user = $result->fetch_assoc();

if (isset($_POST['reset'])) {
    $password_baru = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if ($password_baru !== $konfirmasi_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } elseif (strlen($password_baru) < 6) { // Validasi sederhana
        $error = "Password minimal harus 6 karakter.";
    } else {
        // Hash password baru
        $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $email_pelanggan = $user['email_pelanggan'];

        // Update password dan hapus token
        $koneksi->query("UPDATE pelanggan SET password_pelanggan = '$password_hash', reset_token = NULL, reset_token_expire = NULL WHERE email_pelanggan = '$email_pelanggan'");

        echo "<script>alert('Password berhasil diubah. Silakan login kembali.');</script>";
        echo "<script>location='login.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Masukkan Password Baru</h3>
                    </div>
                    <div class="panel-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="form-group">
                                <label>Password Baru</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group">
                                <label>Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="konfirmasi_password" required>
                            </div>
                            <button class="btn btn-primary" name="reset">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>