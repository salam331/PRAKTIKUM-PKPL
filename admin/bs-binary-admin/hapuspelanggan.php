<?php
$ambil = $koneksi->query("SELECT * FROM pelanggan WHERE id_pelanggan='{$_GET['id']}'");
$pecah = $ambil->fetch_assoc();
// $fotoproduk = $pecah['foto_produk'];
// if (file_exists("../foto_produk/$fotoproduk")) {
//     unlink("../foto_produk/$fotoproduk");
// }

$koneksi->query("DELETE FROM pelanggan WHERE id_pelanggan='$_GET[id]'");

echo "<script>alert('pelanggan terhapus');</script>";
echo "<script>location='index.php?halaman=pelanggan'</script>";
?>