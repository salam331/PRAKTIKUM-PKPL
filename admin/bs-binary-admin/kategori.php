<h2 style="text-align: center">Data Kategori</h2>
<hr>

<?php
$semuadata = array();
$ambil = $koneksi->query("SELECT * FROM kategori");
while ($tiap = $ambil->fetch_assoc()) {
    $semuadata[] = $tiap;
}

// echo "<pre>";
// print_r($semuadata);
// echo "</pre>";
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
                <td><?php echo $key+1 ?></td>
                <td><?php echo $value ["nama_kategori"]; ?></td>
                <td>
                    <a href="index.php?halaman=hapusproduk&id=<?php echo $pecah['id_produk']; ?>" class="btn-danger btn">Hapus</a>
                    <a href="" class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
        <?php endforeach ?>

    </tbody>
</table>

<a href="" class="btn btn-primary">Tambah Data</a>