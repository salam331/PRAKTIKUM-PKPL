<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "salam");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salam: Login</title>

    <!-- CSS Dependencies -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
</head>

<body>
    <div class="container">
        <!-- Title Section -->
        <div class="row text-center">
            <div class="col-md-12">
                <br><br>
                <h2>Salam: Login</h2>
                <h5>(Login yourself to get access)</h5>
                <br>
            </div>
        </div>

        <!-- Login Form Section -->
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Enter Details to Login</strong>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" name="user" placeholder="Username" required>
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" name="pass" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-inline">
                                    <input type="checkbox"> Remember me
                                </label>
                                <span class="pull-right">
                                    <a href="#">Forgot Password?</a>
                                </span>
                            </div>
                            <button class="btn btn-primary btn-block" name="login">Login</button>
                        </form>

                        <!-- PHP Login Logic -->
                        <?php
                        if (isset($_POST['login'])) {
                            $user = $_POST['user'];
                            $pass = $_POST['pass'];

                            // Query to check admin credentials
                            $ambil = $koneksi->query("SELECT * FROM admin WHERE username='$user' AND password='$pass'");
                            $yangcocol = $ambil->num_rows;

                            if ($yangcocol == 1) {
                                $_SESSION['admin'] = $ambil->fetch_assoc();
                                echo "<div class='alert alert-info'>Login Sukses</div>";
                                echo "<meta http-equiv='refresh' content='1;index.php'>";
                            } else {
                                echo "<div class='alert alert-danger'>Login Gagal</div>";
                                echo "<meta http-equiv='refresh' content='1;login.php'>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Dependencies -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>
