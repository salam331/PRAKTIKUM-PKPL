<?php
$id = $_GET['id'];
$ambil = $koneksi->query("SELECT * FROM kategori WHERE id_kategori='$id'");
$kategori = $ambil->fetch_assoc();
?>

<h2>Ubah Kategori</h2>
<hr>

<form method="post">
    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" value="<?php echo htmlspecialchars($kategori['nama_kategori']); ?>" required>
    </div>
    <button class="btn btn-primary" name="ubah">Ubah</button>
</form>

<?php
if (isset($_POST['ubah'])) {
    $nama = $_POST['nama_kategori'];
    $koneksi->query("UPDATE kategori SET nama_kategori='$nama' WHERE id_kategori='$id'");
    echo "<script>alert('Kategori berhasil diubah');</script>";
    echo "<script>location='index.php?halaman=kategori';</script>";
}
?>
