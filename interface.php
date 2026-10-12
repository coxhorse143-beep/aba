<?php

/**
 * Interface MetodePembayaran
 */
interface MetodePembayaran
{
    public function prosesBayar(float $jumlah, string $keterangan): bool;
    public function getNamaMetode(): string;
}

/**
 * Helper: format uang ke format Indonesia
 */
function formatRupiah(float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Class TransferBank
 * Biaya admin: 0%
 */
class TransferBank implements MetodePembayaran
{
    private string $nomorRekening;

    public function __construct(string $nomorRekening)
    {
        $this->nomorRekening = $nomorRekening;
    }

    public function getNamaMetode(): string
    {
        return 'Transfer Bank';
    }

    public function prosesBayar(float $jumlah, string $keterangan): bool
    {
        $biayaAdmin = 0;
        $total = $jumlah + $biayaAdmin;

        echo "=== PEMBAYARAN VIA " . strtoupper($this->getNamaMetode()) . " ===\n";
        echo "Nomor Rekening   : {$this->nomorRekening}\n";
        echo "Keterangan       : {$keterangan}\n";
        echo "Jumlah           : " . formatRupiah($jumlah) . "\n";
        echo "Biaya Admin (0%) : " . formatRupiah($biayaAdmin) . "\n";
        echo "Total Bayar      : " . formatRupiah($total) . "\n";
        echo "Status           : BERHASIL\n\n";

        return true;
    }
}

/**
 * Class QRIS
 * Biaya admin: 0.7%
 */
class QRIS implements MetodePembayaran
{
    public function getNamaMetode(): string
    {
        return 'QRIS';
    }

    public function prosesBayar(float $jumlah, string $keterangan): bool
    {
        $biayaAdmin = $jumlah * 0.007;
        $total = $jumlah + $biayaAdmin;

        echo "=== PEMBAYARAN VIA " . strtoupper($this->getNamaMetode()) . " ===\n";
        echo "Keterangan         : {$keterangan}\n";
        echo "Jumlah             : " . formatRupiah($jumlah) . "\n";
        echo "Biaya Admin (0.7%) : " . formatRupiah($biayaAdmin) . "\n";
        echo "Total Bayar        : " . formatRupiah($total) . "\n";
        echo "Status             : BERHASIL\n\n";

        return true;
    }
}

/**
 * Class KartuKredit
 * Biaya admin: 2.5%
 * Tampilkan 4 digit terakhir nomor kartu
 */
class KartuKredit implements MetodePembayaran
{
    private string $nomorKartu;

    public function __construct(string $nomorKartu)
    {
        $this->nomorKartu = $nomorKartu;
    }

    public function getNamaMetode(): string
    {
        return 'Kartu Kredit';
    }

    private function getEmpatDigitTerakhir(): string
    {
        $bersih = preg_replace('/\D/', '', $this->nomorKartu);
        return substr($bersih, -4);
    }

    public function prosesBayar(float $jumlah, string $keterangan): bool
    {
        $biayaAdmin = $jumlah * 0.025;
        $total = $jumlah + $biayaAdmin;

        echo "=== PEMBAYARAN VIA " . strtoupper($this->getNamaMetode()) . " ===\n";
        echo "Nomor Kartu        : **** **** **** " . $this->getEmpatDigitTerakhir() . "\n";
        echo "Keterangan         : {$keterangan}\n";
        echo "Jumlah             : " . formatRupiah($jumlah) . "\n";
        echo "Biaya Admin (2.5%) : " . formatRupiah($biayaAdmin) . "\n";
        echo "Total Bayar        : " . formatRupiah($total) . "\n";
        echo "Status             : BERHASIL\n\n";

        return true;
    }
}

/* ============================================
   CONTOH PENGGUNAAN
   ============================================ */

$metodePembayaran = [
    new TransferBank('1234567890'),
    new QRIS(),
    new KartuKredit('4321-8765-1234-5678'),
];

$jumlahBayar = 500000;
$keterangan  = 'Pembelian Laptop';

foreach ($metodePembayaran as $metode) {
    $metode->prosesBayar($jumlahBayar, $keterangan);
}

?>
