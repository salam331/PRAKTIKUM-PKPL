<?php

$id_foto = $_GET["idfoto"];
$id_produk = $_GET["idproduk"];

// ambil terlebih dahulu data produk yang dari database
$ambilfoto = $koneksi->query("SELECT * FROM produk_foto WHERE id_produk_foto = '$id_foto'");
$detailfoto = $ambilfoto->fetch_assoc();

$namafilefoto = $detailfoto["nama_produk_foto"];

// hapus file foto dari file folder foto
unlink("../foto_produk/".$namafilefoto);

// echo "<pre>";
// print_r($namafilefoto);
// echo "</pre>";

// hanya menghapus file yang ada di dalam mysql
$koneksi->query("DELETE FROM produk_foto WHERE id_produk_foto = '$id_foto'");

echo "<script>alert('Foto Produk Berhasil Dihapus');</script>";
echo "<script>location='index.php?halaman=detailproduk&id=$id_produk';</script>";


?>