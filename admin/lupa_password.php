<?php
date_default_timezone_set('Asia/Jakarta'); // Tambahkan baris ini
session_start();
include 'koneksi.php';

// Memuat autoload dari composer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST["kirim"])) {
    $email = $koneksi->real_escape_string($_POST["email"]);

    // Cek apakah email ada di database
    $result = $koneksi->query("SELECT * FROM pelanggan WHERE email_pelanggan = '$email'");

    if ($result->num_rows > 0) {
        // Generate token unik
        $token = bin2hex(random_bytes(50));
        // Set waktu kedaluwarsa token (contoh: 1 jam dari sekarang)
        $expire_time = date("Y-m-d H:i:s", time() + 3600);

        // Simpan token dan waktu kedaluwarsa ke database
        $koneksi->query("UPDATE pelanggan SET reset_token = '$token', reset_token_expire = '$expire_time' WHERE email_pelanggan = '$email'");

        // Kirim email menggunakan PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Konfigurasi Server SMTP (Gunakan detail dari penyedia email Anda, contoh: Gmail)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Ganti dengan host SMTP Anda
            $mail->SMTPAuth = true;
            $mail->Username = 'samdokal27@gmail.com'; // Ganti dengan email SMTP Anda
            $mail->Password = 'ntzc bgfa gscb mbrc'; // Ganti dengan password SMTP Anda
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Penerima
            $mail->setFrom('email.gmail.samdokal27@gmai.com', 'toko buah');
            $mail->addAddress($email);

            // Konten Email
            // BENAR
            $reset_link = "http://localhost/salam/admin/reset_password.php?token=" . $token; // Sesuaikan URL
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Akun Anda';
            $mail->Body = "Halo,<br><br>Anda meminta untuk mereset password Anda. Silakan klik link di bawah ini untuk melanjutkan:<br><a href='$reset_link'>$reset_link</a><br><br>Jika Anda tidak merasa meminta ini, abaikan saja email ini.<br><br>Terima kasih.";

            $mail->send();
            $pesan = "Link reset password telah dikirim ke email Anda.";
        } catch (Exception $e) {
            $pesan = "Gagal mengirim email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        // Beri pesan yang sama untuk mencegah orang menebak email yang terdaftar
        $pesan = "Jika email Anda terdaftar, link reset password akan dikirim.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lupa Password</title>
    <link rel="stylesheet" href="bs-binary-admin/assets/css/bootstrap.css">
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Lupa Password</h3>
                    </div>
                    <div class="panel-body">
                        <?php if (isset($pesan)): ?>
                            <div class="alert alert-info"><?php echo $pesan; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="form-group">
                                <label>Masukkan Email Anda</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <button class="btn btn-primary" name="kirim">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>