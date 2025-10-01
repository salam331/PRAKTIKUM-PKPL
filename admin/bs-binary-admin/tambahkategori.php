<h2>Tambah Kategori</h2>
<hr>

<form method="post">
    <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" required>
    </div>
    <button class="btn btn-primary" name="save">Simpan</button>
</form>

<?php
if (isset($_POST['save'])) {
    $nama = $_POST['nama_kategori'];
    $koneksi->query("INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
    echo "<script>alert('Kategori berhasil ditambahkan');</script>";
    echo "<script>location='index.php?halaman=kategori';</script>";
}
?>
