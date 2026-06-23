<?php
require __DIR__ . '/dompdf/autoload.inc.php'; // Mengambil library DomPDF untuk membuat PDF

use Dompdf\Dompdf;
use Dompdf\Options;

include 'config.php'; // Koneksi database

// ===== AMBIL ID =====
// Mengambil ID dari URL (contoh: export_pdf.php?id=1)
$id = $_GET['id'] ?? '';

// Jika ID kosong, hentikan program
if(empty($id)){
    die("ID tidak ditemukan");
}

// ===== QUERY DATA =====
// Mengambil data dari tabel riwayat berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM riwayat WHERE id=?");
$stmt->bind_param("i", $id); // Mengamankan input (prepared statement)
$stmt->execute();
$result = $stmt->get_result();
$d = $result->fetch_assoc();

// Jika data tidak ditemukan
if(!$d){
    die("Data tidak ditemukan");
}

// ===== AMBIL DATA =====
$nama = $d['user'] ?? 'Tidak diketahui'; // Ambil nama user
$data = json_decode($d['hasil'], true); // Mengubah data JSON menjadi array

// Jika data diagnosa kosong
if(!$data || !is_array($data)){
    die("Data diagnosa kosong");
}

// ===== SIAPKAN GRAFIK =====
$labels = []; // Untuk nama penyakit
$values = []; // Untuk persentase

// Loop data untuk isi grafik
foreach($data as $dt){
    $labels[] = $dt['nama']; // Nama penyakit
    $values[] = round($dt['persen']); // Persentase (dibulatkan)
}

// ===== CHART =====
// Membuat URL grafik menggunakan QuickChart (diagram pie)
$chart_url = "https://quickchart.io/chart?c=" . urlencode("
{
type:'pie',
data:{
 labels:['".implode("','",$labels)."'],
 datasets:[{data:[".implode(",",$values)."]}]
}
}
");

// Mengambil gambar grafik dari URL
$img = @file_get_contents($chart_url);

// Mengubah gambar menjadi base64 agar bisa ditampilkan di PDF
$base64 = $img ? 'data:image/png;base64,' . base64_encode($img) : '';

// ===== HTML =====
// Membuat tampilan isi PDF dalam bentuk HTML
$html = "
<h2 style='text-align:center;'>HASIL DIAGNOSA PENYAKIT LELE</h2>
<hr>

<p><b>Nama:</b> $nama</p>

<h3>Hasil Diagnosa:</h3>

<table border='1' cellpadding='8' width='100%' style='border-collapse:collapse;'>
<tr style='background:#f2f2f2;'>
<th>No</th>
<th>Penyakit</th>
<th>Persentase</th>
</tr>
";

// Nomor urut
$no = 1;

// Menampilkan semua hasil diagnosa
foreach($data as $row){

    // Baris pertama diberi highlight (hasil utama)
    $highlight = ($no == 1) ? "style='background:#d4edda;font-weight:bold;'" : "";

    $html .= "
    <tr $highlight>
        <td>$no</td>
        <td>{$row['nama']}</td>
        <td>".round($row['persen'])."%</td>
    </tr>";

    $no++;
}

$html .= "</table><br>";

// ===== GRAFIK =====
// Jika grafik berhasil dibuat, tampilkan di PDF
if($base64){
    $html .= "<h3>Grafik Diagnosa:</h3>
    <div style='text-align:center'>
    <img src='$base64' width='300'>
    </div>";
}

// ===== FOOTER =====
$html .= "
<br><hr>
<p style='text-align:center;font-size:12px;'>
Dicetak otomatis oleh Sistem Pakar Diagnosa Penyakit Lele
</p>
";

// ===== PDF =====
// Pengaturan DomPDF
$options = new Options();
$options->set('isRemoteEnabled', true); // Agar bisa load gambar dari internet

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html); // Memasukkan HTML ke PDF
$dompdf->setPaper('A4', 'portrait'); // Ukuran kertas A4
$dompdf->render(); // Generate PDF

// ===== OUTPUT =====
// Menampilkan PDF di browser (tidak langsung download)
$dompdf->stream("hasil_diagnosa.pdf", ["Attachment"=>false]);