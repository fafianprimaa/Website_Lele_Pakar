<?php 
include 'config.php'; // Menghubungkan ke database
if (session_status() === PHP_SESSION_NONE) session_start(); // Memulai session

// ================= RESET =================
// Jika tombol reset diklik
if(isset($_GET['reset'])){
    unset($_SESSION['penyakit_id']);
    unset($_SESSION['sudah_simpan']);
    unset($_SESSION['last_id']);
    header("Location: diagnosa.php");
exit;
}

// ================= STEP 1 =================
// Saat form input pertama dikirim
if(isset($_POST['step1'])){
    $_SESSION['nama'] = $_POST['nama']; // Simpan nama ke session
    $_SESSION['telp'] = $_POST['telp']; // Simpan nomor telepon
    $_SESSION['alamat'] = $_POST['alamat']; // Simpan alamat
    $_SESSION['penyakit_id'] = $_POST['penyakit_id']; // Simpan pilihan penyakit (hipotesis)
}

// Mengambil data dari session
$nama   = $_SESSION['nama'] ?? '';
$telp   = $_SESSION['telp'] ?? '';
$alamat = $_SESSION['alamat'] ?? '';
$pid    = $_SESSION['penyakit_id'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Sistem Pakar Lele</title>

<!-- Bootstrap & Icon -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { background: linear-gradient(to bottom, #e0f7fa, #ffffff); } /* Background gradasi */
.card { border-radius: 15px; transition: 0.3s; } /* Tampilan card */
.card:hover { transform: scale(1.02); } /* Efek hover */
.title { color: #0a3d62; font-weight: bold; } /* Judul */
</style>
</head>

<body>

<!-- Navbar atas -->
<nav class="navbar navbar-dark bg-primary shadow">
  <div class="container">
    <span class="navbar-brand">
      <i class="fas fa-fish"></i> Sistem Pakar Lele
    </span>
  </div>
</nav>

<div class="container mt-5">

<?php
// ================= STEP 1 (FORM INPUT) =================
// Jika belum isi data
if(!$nama || !$pid){
?>

<div class="card shadow p-4 mx-auto" style="max-width:500px;">

<h3 class="text-center title">
<i class="fas fa-user"></i> Input Data
</h3>

<hr>

<form method="post">

<input name="nama" class="form-control mb-3" placeholder="Nama" required> <!-- Input nama -->

<input name="telp" class="form-control mb-3" placeholder="No Telp" required> <!-- Input telp -->

<textarea name="alamat" class="form-control mb-3" placeholder="Alamat" required></textarea> <!-- Input alamat -->

<select name="penyakit_id" class="form-control mb-3" required>
<option value="">-- Pilih Penyakit (Hipotesis) --</option>

<?php
// Menampilkan daftar penyakit dari database
$p=$conn->query("SELECT * FROM penyakit");
while($d=$p->fetch_assoc()){
    echo "<option value='$d[id]'>$d[nama]</option>";
}
?>

</select>

<button name="step1" class="btn btn-primary w-100">
<i class="fas fa-arrow-right"></i> Lanjut
</button>

</form>

</div>

<?php
}
// ================= STEP 2 (DIAGNOSA) =================
else{

// Ambil data penyakit berdasarkan pilihan user
$pen = $conn->query("SELECT * FROM penyakit WHERE id='$pid'")->fetch_assoc();
?>

<div class="card shadow p-4">

<!-- Tombol reset -->
<a href="?reset=1" class="btn btn-secondary mb-3">
<i class="fas fa-refresh"></i> Input Ulang
</a>

<h3 class="title">
<i class="fas fa-stethoscope"></i> Diagnosa Penyakit
</h3>

<hr>

<!-- Menampilkan data user -->
<h5><i class="fas fa-user"></i> <?= $nama ?></h5>
<p><i class="fas fa-phone"></i> <?= $telp ?></p>
<p><i class="fas fa-map-marker-alt"></i> <?= $alamat ?></p>

<!-- Menampilkan hipotesis -->
<h5 class="mt-3">
<i class="fas fa-virus"></i> Hipotesis: <b><?= $pen['nama'] ?></b>
</h5>

<hr>

<?php if(!isset($_POST['proses'])){ ?>

<form method="post">

<h5>Pilih Gejala:</h5>

<?php
// Ambil gejala yang berhubungan dengan penyakit
$g = $conn->query("
SELECT gejala.* FROM relasi
JOIN gejala ON gejala.id = relasi.gejala_id
WHERE relasi.penyakit_id='$pid'
");

while($d=$g->fetch_assoc()){
    echo "<div class='form-check'>";
    echo "<input class='form-check-input' type='checkbox' name='gejala[]' value='$d[id]'>";
    echo "<label class='form-check-label'>$d[nama]</label>";
    echo "</div>";
}
?>

<br>
<button name="proses" class="btn btn-primary">
<i class="fas fa-stethoscope"></i> Diagnosa
</button>

</form>

<?php
}else{

// Mengambil gejala yang dipilih user
$input = $_POST['gejala'] ?? [];

// Menghitung total gejala yang tersedia
$totalQ = $conn->query("SELECT COUNT(*) as total FROM relasi WHERE penyakit_id='$pid'");
$total = $totalQ->fetch_assoc()['total'];

// Menghitung jumlah gejala yang dipilih
$cocok = count($input);

// Menghitung persentase kecocokan
$persen = ($total > 0) ? ($cocok/$total)*100 : 0;

// ================= VALIDASI =================
if($persen < 0){

    echo "<div class='alert alert-danger'>
    <b>Hasil tidak valid!</b><br>
    Kecocokan hanya <b>".round($persen)."%</b>.<br>
    Silakan pilih hipotesis lain atau cek kembali gejala.
    </div>";

    echo "<a href='diagnosa.php?reset=1' class='btn btn-warning'>
    <i class='fas fa-redo'></i> Pilih Ulang
    </a>";

    exit;
}
?>

<div class="card p-3 mt-3 shadow">

<h5><i class="fas fa-diagnoses"></i> Hasil Diagnosa</h5>
<hr>

<!-- Menampilkan hasil -->
<h4 class="text-success">
<?= $pen['nama'] ?> (<?= round($persen) ?>%)
</h4>

<!-- Progress bar -->
<div class="progress mb-3">
<div class="progress-bar bg-success" style="width:<?= $persen ?>%"></div>
</div>

<ul>
<?php
// Menampilkan solusi
$s=$conn->query("SELECT * FROM solusi WHERE penyakit_id='$pid'");
while($sol=$s->fetch_assoc()){
    echo "<li>$sol[deskripsi]</li>";
}
?>
</ul>

</div>

<?php
// ================= SIMPAN RIWAYAT =================
if(!isset($_SESSION['sudah_simpan'])){

    // Menyimpan hasil dalam bentuk JSON
    $hasil = json_encode([
        [
            'nama'=>$pen['nama'],
            'persen'=>$persen
        ]
    ]);

    // Simpan ke tabel riwayat
    $conn->query("
    INSERT INTO riwayat(user,telp,alamat,penyakit_id,hasil) 
    VALUES('$nama','$telp','$alamat','$pid','$hasil')
    ");

    $_SESSION['last_id'] = $conn->insert_id; // Ambil ID terakhir
    $_SESSION['sudah_simpan'] = true; // Tandai sudah disimpan
}

// Ambil ID terakhir
$id = $_SESSION['last_id'] ?? 0;

// Jika ada ID, tampilkan tombol export
if($id){
    echo "<a href='export_pdf.php?id=$id' target='_blank' class='btn btn-danger mt-3'>
    <i class='fas fa-file-pdf'></i> Export PDF
    </a>";

    echo "<a href='riwayat_user.php' class='btn btn-info mt-3'>
    <i class='fas fa-history'></i> Riwayat Saya
    </a>";
}

?>

<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>