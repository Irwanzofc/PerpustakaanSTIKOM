<?php
/* ---------- Class Peminjaman ---------- */
// Class ini memakai objek Buku dan Anggota, jadi kedua file itu harus sudah dimuat
require_once __DIR__ . '/Buku.php';
require_once __DIR__ . '/Anggota.php';

class Peminjaman {
    const DENDA_PER_HARI = 1000;

    private $buku;
    private $anggota;
    private $tglPinjam;
    private $jatuhTempo;
    private $tglKembali = null;
    private $status = "baru"; // baru, dipinjam, dikembalikan

    public function __construct(Buku $buku, Anggota $anggota, $tglPinjam) {
        $this->buku      = $buku;
        $this->anggota   = $anggota;
        $this->tglPinjam = new DateTime($tglPinjam);

        // Lama pinjam mengikuti jenis anggota (polimorfisme)
        $lama = $anggota->getLamaPinjam();
        $this->jatuhTempo = (clone $this->tglPinjam)->modify("+$lama days");
    }

    public function getStatus() { return $this->status; }

    public function getBuku()    { return $this->buku; }
    public function getAnggota() { return $this->anggota; }

    // Memproses peminjaman. Return true kalau berhasil.
    public function proses() {
        if (!$this->anggota->bolehPinjam()) {
            echo "Gagal: {$this->anggota->getNama()} sudah mencapai batas pinjam.<br>";
            return false;
        }
        if (!$this->buku->pinjam()) {
            echo "Gagal: stok \"{$this->buku->getJudul()}\" habis.<br>";
            return false;
        }
        $this->anggota->tambahPinjaman();
        $this->status = "dipinjam";
        echo "{$this->anggota->getNama()} meminjam \"{$this->buku->getJudul()}\". "
           . "Jatuh tempo: {$this->jatuhTempo->format('d-m-Y')}<br>";
        return true;
    }

    public function kembalikan($tglKembali) {
        if ($this->status !== "dipinjam") {
            echo "Gagal: buku ini tidak sedang dipinjam.<br>";
            return;
        }
        $this->tglKembali = new DateTime($tglKembali);
        $this->buku->kembalikan();
        $this->anggota->kurangiPinjaman();
        $this->status = "dikembalikan";
        echo "{$this->anggota->getNama()} mengembalikan \"{$this->buku->getJudul()}\" "
           . "pada {$this->tglKembali->format('d-m-Y')}<br>";
    }

    // Denda = hari terlambat x tarif per hari. Return 0 kalau tepat waktu.
    public function hitungDenda() {
        if ($this->tglKembali === null || $this->tglKembali <= $this->jatuhTempo) {
            return 0;
        }
        $hariTerlambat = $this->jatuhTempo->diff($this->tglKembali)->days;
        return $hariTerlambat * self::DENDA_PER_HARI;
    }

    public function tampilkanTabel() {
    $kembali = ($this->tglKembali === null) ? "-" : $this->tglKembali->format('d-m-Y');

    echo "<table border='1' cellpadding='5'>
            <tr>
                <th>Peminjam</th><th>Jenis</th><th>Buku</th>
                <th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Tgl Kembali</th>
                <th>Status</th><th>Denda</th>
            </tr>
            <tr>
                <td>{$this->anggota->getNama()}</td>
                <td>{$this->anggota->getJenis()}</td>
                <td>{$this->buku->getJudul()}</td>
                <td>{$this->tglPinjam->format('d-m-Y')}</td>
                <td>{$this->jatuhTempo->format('d-m-Y')}</td>
                <td>{$kembali}</td>
                <td>{$this->status}</td>
                <td>Rp " . $this->hitungDenda() . "</td>    
            </tr>
          </table><br>";
}
}
