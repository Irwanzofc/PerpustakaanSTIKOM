<?php
require_once __DIR__ . '/Buku.php';
require_once __DIR__ . '/Anggota.php';      
require_once __DIR__ . '/Peminjaman.php';


// Data buku
$bukubackend = new Buku("BA253", "Pengembangan Sistem Backend", "Herdian", 5);
$bukubisnis = new Buku("BA245", "Analisis Dan Bisnis", "Yudi", 3);
$bukuperancangan = new Buku("BA115", "Perancangan Sistem Informasi", "Puritan", 2);
// Data anggota
$irwan = new Mahasiswa("M001", "irwan");
$mahesa = new Mahasiswa("M002", "mahesa");
$kenza = new Mahasiswa("M003", "kenza");

echo "<br><b>Transaksi</b><br>";

$p1 = new Peminjaman($bukubackend, $irwan, "2026-10-01");
$p2 = new Peminjaman($bukubisnis, $mahesa, "2026-10-01");
$p3 = new Peminjaman($bukubisnis, $irwan, "2026-10-02");
$p4 = new Peminjaman($bukuperancangan, $kenza, "2026-10-11");

// Peminjaman
echo "<br><i>Transaksi 1: peminjaman</i><br>";
$p1->proses();
$p1->tampilkanTabel();

echo "<i>Transaksi 2: peminjaman</i><br>";
$p2->proses();
$p2->tampilkanTabel();

echo "<i>Transaksi 3: peminjaman</i><br>";
$p3->proses();
$p3->tampilkanTabel();

echo "<i>Transaksi 4: peminjaman</i><br>";
$p4->proses();
$p4->tampilkanTabel();


// Pengembalian
// kenza
echo "<i>Transaksi 5: pengembalian</i><br>";
$p4->kembalikan("2026-11-10");
$p4->tampilkanTabel();

// irwan 
echo "<i>Transaksi 6: pengembalian</i><br>";
$p1->kembalikan("2026-10-12");
$p1->tampilkanTabel();

// mahesa 
echo "<i>Transaksi 7: pengembalian</i><br>";
$p2->kembalikan("2026-10-10");
$p2->tampilkanTabel();



echo "<br><hr><h3>Data Keseluruhan</h3>";

/* ---------- Tabel semua transaksi ---------- */
$daftarPinjam = [$p1, $p2, $p3, $p4];

echo "<b>Semua Transaksi</b><br>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>No</th><th>Kode</th><th>Judul</th><th>Peminjam</th><th>Jenis</th><th>Status</th><th>Denda</th></tr>";

$no = 1;
$totalDenda = 0;
foreach ($daftarPinjam as $p) {
    $denda = $p->hitungDenda();
    $totalDenda += $denda;

    echo "<tr>
            <td>{$no}</td>
            <td>{$p->getBuku()->getKode()}</td>
            <td>{$p->getBuku()->getJudul()}</td>
            <td>{$p->getAnggota()->getNama()}</td>
            <td>{$p->getAnggota()->getJenis()}</td>
            <td>{$p->getStatus()}</td>
            <td>Rp {$denda}</td>
          </tr>";
    $no++;
}
echo "<tr><td colspan='6'><b>Total denda</b></td><td><b>Rp {$totalDenda}</b></td></tr>";
echo "</table><br>";