<?php
// 1. Semua class harus dimuat SEBELUM session_start(),
//    supaya objek di session bisa dibaca kembali.
require_once __DIR__ . '/Buku.php';
require_once __DIR__ . '/Anggota.php';
require_once __DIR__ . '/Peminjaman.php';

session_start();

if (!isset($_SESSION['daftarBuku'])) {
    $_SESSION['daftarBuku'] = [];
}

// 2. Proses form kalau halaman dibuka lewat pengiriman form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode    = trim($_POST['kode']);
    $judul   = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $stok    = (int) $_POST['stok'];

    if ($kode === '' || $judul === '') {
        echo "Kode dan judul wajib diisi.<br>";
    } else {
        $_SESSION['daftarBuku'][$kode] = new Buku($kode, $judul, $penulis, $stok);
    }
}
?>

<form method="post">
    Kode: <input type="text" name="kode" required><br>
    Judul: <input type="text" name="judul" required><br>
    Penulis: <input type="text" name="penulis"><br>
    Stok: <input type="number" name="stok" value="1"><br>
    <button type="submit">Tambah Buku</button>
</form>

<?php
// 3. Tampilkan semua buku
foreach ($_SESSION['daftarBuku'] as $buku) {
    $buku->tampilkanInfo();
}