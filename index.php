<?php
/*
 * Tugas Mandiri: Sistem peminjaman buku perpustakaan sederhana.
 * File ini hanya untuk pengujian. Class ada di file lain.
 * Jalankan dengan: php -S localhost:8000 lalu buka index.php di browser.
 */
require_once __DIR__ . '/Buku.php';
require_once __DIR__ . '/Anggota.php';      // berisi Anggota, Mahasiswa, Dosen
require_once __DIR__ . '/Peminjaman.php';

// Data buku
$buku1 = new Buku("B001", "Pemrograman PHP Dasar", "Penulis A", 2);
$buku2 = new Buku("B002", "Basis Data", "Penulis B", 1);

// Data anggota
$wayan = new Mahasiswa("M001", "Wayan");
$ketut = new Dosen("D001", "Dr. Ketut");

echo "<b>Data awal</b><br>";
$buku1->tampilkanInfo();
$buku2->tampilkanInfo();
$wayan->tampilkanInfo();
$ketut->tampilkanInfo();

// Polimorfisme: satu pemanggilan, hasil berbeda tiap objek
echo "<br><b>Batas dan lama pinjam</b><br>";
$daftar = [new Anggota("U001", "Tamu"), $wayan, $ketut];
foreach ($daftar as $a) {
    echo "{$a->getJenis()}: maks {$a->getBatasPinjam()} buku, {$a->getLamaPinjam()} hari<br>";
}

// Enkapsulasi: baris di bawah akan error karena $stok private
// $buku1->stok = 99;
$buku1->setStok(-5); // ditolak oleh validasi

echo "<br><b>Transaksi</b><br>";
$p1 = new Peminjaman($buku1, $wayan, "2026-10-01");
$p2 = new Peminjaman($buku2, $ketut, "2026-10-01");
$p3 = new Peminjaman($buku2, $wayan, "2026-10-02"); // stok B002 sudah habis

$p1->proses();
$p2->proses();
$p3->proses(); // gagal

// Wayan terlambat 4 hari (jatuh tempo 08-10-2026, kembali 12-10-2026)
$p1->kembalikan("2026-10-12");
// Dr. Ketut tepat waktu (jatuh tempo 15-10-2026)
$p2->kembalikan("2026-10-10");

echo "<br><b>Rekap</b><br>";
$p1->tampilkanInfo();
$p2->tampilkanInfo();
$buku1->tampilkanInfo();
$buku2->tampilkanInfo();