<?php
include 'config.php';

$nama = $_SESSION['nama'] ?? '';
$telp = $_SESSION['telp'] ?? '';

if(!$nama || !$telp){
    header("Location: diagnosa.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Riwayat Diagnosa Saya</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    background:#f0f8ff;
}
.card{
    border-radius:15px;
}
</style>

</head>
<body>

<div class="container mt-5">

<div class="card shadow p-4">

<h3 class="text-primary">
<i class="fas fa-history"></i> Riwayat Diagnosa Saya
</h3>

<hr>

<table class="table table-bordered table-striped">
<thead>
<tr>
<th>No</th>
<th>Penyakit</th>
<th>Persentase</th>
<th>Tanggal</th>
<th>PDF</th>
</tr>
</thead>

<tbody>

<?php

$no=1;

$q=$conn->query("
SELECT *
FROM riwayat
WHERE user='$nama'
AND telp='$telp'
ORDER BY id DESC
");

while($d=$q->fetch_assoc()){

    $hasil=json_decode($d['hasil'],true);

    $penyakit = $hasil[0]['nama'] ?? '-';
    $persen   = round($hasil[0]['persen'] ?? 0);

    echo "
    <tr>
        <td>$no</td>
        <td>$penyakit</td>
        <td>$persen%</td>
        <td>$d[tanggal]</td>
        <td>
            <a href='export_pdf.php?id=$d[id]'
               target='_blank'
               class='btn btn-danger btn-sm'>
               PDF
            </a>
        </td>
    </tr>
    ";

    $no++;
}
?>

</tbody>
</table>

<a href="javascript:history.back()" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Kembali
</a>

</div>

</div>

</body>
</html>