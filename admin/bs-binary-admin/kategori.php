<h2 style="text-align: center">Data Kategori</h2>
<hr>

<?php
$semuadata = [];
$ambil = $koneksi->query("SELECT * FROM kategori ORDER BY id_kategori ASC");
while ($tiap = $ambil->fetch_assoc()) {
    $semuadata[] = $tiap;
}
?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($semuadata as $key => $value): ?>
            <tr>
                <td><?php echo $key + 1; ?></td>
                <td><?php echo htmlspecialchars($value["nama_kategori"]); ?></td>
                <td>
                    <a href="index.php?halaman=ubahkategori&id=<?php echo $value['id_kategori']; ?>" class="btn btn-warning btn-sm">Ubah</a>
                    <a href="index.php?halaman=hapuskategori&id=<?php echo $value['id_kategori']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus kategori ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="index.php?halaman=tambahkategori" class="btn btn-primary">Tambah Kategori</a>
