<?php
// Isključivanje keširanja kako bi player uvijek povlačio najnoviji token
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");

// 1. Provjera je li korisnik poslao MAC adresu u linku
if (!isset($_GET['deviceMac']) || empty($_GET['deviceMac'])) {
    header("HTTP/1.1 400 Bad Request");
    echo "Greska: Nedostaje deviceMac parametar.";
    exit;
}

$korisnik_mac = $_GET['deviceMac'];
$master_lista_putanja = "art_local.m3u";

// 2. Provjera postoji li master lista na serveru
if (!file_exists($master_lista_putanja)) {
    header("HTTP/1.1 404 Not Found");
    echo "Greska: Master lista nije pronadjena na serveru.";
    exit;
}

// 3. Postavljanje zaglavlja kako bi TiviMate prepoznao da preuzima M3U datoteku
header('Content-Type: audio/x-mpegurl');
header('Content-Disposition: inline; filename="playlist.m3u"');

// 4. Citanje master liste
$sadrzaj = file_get_contents($master_lista_putanja);

// 5. Zamjena markera sa stvarnom MAC adresom korisnika
$personalizirana_lista = str_replace("10:27:BE:0A:80:A4", $korisnik_mac, $sadrzaj);

// 6. Ispis liste korisniku
echo $personalizirana_lista;
?>
