<?php include 'config.php'; ?> // Mengambil koneksi database dari file config.php
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?> // Memulai session jika belum ada (biasanya untuk login)

<?php
// Menentukan halaman yang aktif dari parameter URL (?page=...)
$page = $_GET['page'] ?? 'penyakit'; // Default halaman = penyakit

// ================= INSERT (TAMBAH DATA) =================

// Tambah data penyakit
if(isset($_POST['p'])){
    $conn->query("INSERT INTO penyakit(nama) VALUES('$_POST[nama]')"); // Menyimpan nama penyakit ke database
    header("Location: admin.php?page=penyakit"); exit; // Redirect agar tidak input ulang saat refresh
}

// Tambah data gejala
if(isset($_POST['g'])){
    $conn->query("INSERT INTO gejala(nama) VALUES('$_POST[nama2]')"); // Menyimpan gejala
    header("Location: admin.php?page=gejala"); exit;
}

// Tambah relasi (menghubungkan gejala dengan penyakit)
if(isset($_POST['r'])){
    $conn->query("INSERT INTO relasi(gejala_id,penyakit_id) VALUES('$_POST[gid]','$_POST[pid]')"); // Simpan hubungan
    header("Location: admin.php?page=relasi"); exit;
}

// Tambah solusi berdasarkan penyakit
if(isset($_POST['s'])){
    $conn->query("INSERT INTO solusi(penyakit_id,deskripsi) VALUES('$_POST[pid2]','$_POST[solusi]')"); // Simpan solusi
    header("Location: admin.php?page=solusi"); exit;
}

// ================= DELETE (HAPUS DATA) =================

// Hapus penyakit berdasarkan ID
if(isset($_GET['hapus_p'])){
    $conn->query("DELETE FROM penyakit WHERE id=".$_GET['hapus_p']);
    header("Location: admin.php?page=penyakit"); exit;
}

// Hapus gejala
if(isset($_GET['hapus_g'])){
    $conn->query("DELETE FROM gejala WHERE id=".$_GET['hapus_g']);
    header("Location: admin.php?page=gejala"); exit;
}

// Hapus relasi
if(isset($_GET['hapus_r'])){
    $conn->query("DELETE FROM relasi WHERE id=".$_GET['hapus_r']);
    header("Location: admin.php?page=relasi"); exit;
}

// Hapus solusi
if(isset($_GET['hapus_s'])){
    $conn->query("DELETE FROM solusi WHERE id=".$_GET['hapus_s']);
    header("Location: admin.php?page=solusi"); exit;
}

// ================= UPDATE (EDIT DATA) =================

// Update data penyakit
if(isset($_POST['update_p'])){
    $conn->query("UPDATE penyakit SET nama='$_POST[nama]' WHERE id=$_POST[id]");
    header("Location: admin.php?page=penyakit"); exit;
}

// Update data gejala
if(isset($_POST['update_g'])){
    $conn->query("UPDATE gejala SET nama='$_POST[nama2]' WHERE id=$_POST[id]");
    header("Location: admin.php?page=gejala"); exit;
}

// Update data solusi
if(isset($_POST['update_s'])){
    $conn->query("UPDATE solusi SET deskripsi='$_POST[solusi]' WHERE id=$_POST[id]");
    header("Location: admin.php?page=solusi"); exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>

<!-- Menggunakan Bootstrap untuk tampilan -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Menggunakan Font Awesome untuk icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { background:#f0f8ff; padding:20px; } /* Background halaman */
.card { border-radius:15px; padding:20px; margin-bottom:30px; box-shadow:0 5px 15px rgba(0,0,0,0.1);} /* Tampilan kotak */
</style>
</head>
<body>

<div class="container"> <!-- Container utama -->

<h2 class="mb-4 text-primary">
<i class="fas fa-user-cog"></i> Admin Panel <!-- Judul halaman -->
</h2>

<!-- MENU PILIH HALAMAN -->
<form method="get" class="mb-4">
<select name="page" class="form-select" onchange="this.form.submit()"> <!-- Saat dipilih langsung reload -->
    <option value="penyakit" <?= $page=='penyakit'?'selected':'' ?>>Penyakit</option>
    <option value="gejala" <?= $page=='gejala'?'selected':'' ?>>Gejala</option>
    <option value="relasi" <?= $page=='relasi'?'selected':'' ?>>Relasi</option>
    <option value="solusi" <?= $page=='solusi'?'selected':'' ?>>Solusi</option>
</select>
</form>

<?php
// ================= PENYAKIT =================
if($page=='penyakit'){
?>
<div class="card">
<h4>Penyakit</h4>

<!-- Form tambah penyakit -->
<form method="post" class="mb-3">
<input name="nama" class="form-control mb-2" placeholder="Nama Penyakit" required>
<button name="p" class="btn btn-primary">Tambah</button>
</form>

<!-- Tabel data penyakit -->
<table class="table table-bordered">
<tr><th>Nama</th><th>Aksi</th></tr>
<?php
$p=$conn->query("SELECT * FROM penyakit"); // Ambil semua data penyakit
while($d=$p->fetch_assoc()){
echo "<tr>
<td>$d[nama]</td>
<td>
<a href='?page=penyakit&edit_p=$d[id]' class='btn btn-warning btn-sm'>Edit</a>
<a href='?page=penyakit&hapus_p=$d[id]' class='btn btn-danger btn-sm'>Hapus</a>
</td>
</tr>";
}
?>
</table>

<?php
// Form edit penyakit jika tombol edit diklik
if(isset($_GET['edit_p'])){
$e=$conn->query("SELECT * FROM penyakit WHERE id=".$_GET['edit_p'])->fetch_assoc();
?>
<form method="post">
<input type="hidden" name="id" value="<?= $e['id'] ?>">
<input name="nama" value="<?= $e['nama'] ?>" class="form-control mb-2">
<button name="update_p" class="btn btn-warning">Update</button>
</form>
<?php } ?>

</div>
<?php
}

// ================= GEJALA =================
if($page=='gejala'){
?>
<div class="card">
<h4>Gejala</h4>

<form method="post" class="mb-3">
<input name="nama2" class="form-control mb-2" placeholder="Nama Gejala" required>
<button name="g" class="btn btn-success">Tambah</button>
</form>

<table class="table table-bordered">
<tr><th>Nama</th><th>Aksi</th></tr>
<?php
$g=$conn->query("SELECT * FROM gejala");
while($d=$g->fetch_assoc()){
echo "<tr>
<td>$d[nama]</td>
<td>
<a href='?page=gejala&edit_g=$d[id]' class='btn btn-warning btn-sm'>Edit</a>
<a href='?page=gejala&hapus_g=$d[id]' class='btn btn-danger btn-sm'>Hapus</a>
</td>
</tr>";
}
?>
</table>

<?php
if(isset($_GET['edit_g'])){
$e=$conn->query("SELECT * FROM gejala WHERE id=".$_GET['edit_g'])->fetch_assoc();
?>
<form method="post">
<input type="hidden" name="id" value="<?= $e['id'] ?>">
<input name="nama2" value="<?= $e['nama'] ?>" class="form-control mb-2">
<button name="update_g" class="btn btn-warning">Update</button>
</form>
<?php } ?>

</div>
<?php
}

// ================= RELASI =================
if($page=='relasi'){
?>
<div class="card">
<h4>Relasi</h4>

<form method="post" class="mb-3">
<select name="gid" class="form-control mb-2"> <!-- Pilih gejala -->
<?php $g=$conn->query("SELECT * FROM gejala"); while($d=$g->fetch_assoc()) echo "<option value='$d[id]'>$d[nama]</option>"; ?>
</select>

<select name="pid" class="form-control mb-2"> <!-- Pilih penyakit -->
<?php $p=$conn->query("SELECT * FROM penyakit"); while($d=$p->fetch_assoc()) echo "<option value='$d[id]'>$d[nama]</option>"; ?>
</select>

<button name="r" class="btn btn-info">Tambah</button>
</form>

<table class="table table-bordered">
<tr><th>Gejala</th><th>Penyakit</th><th>Aksi</th></tr>
<?php
$r=$conn->query("SELECT relasi.*, gejala.nama as g, penyakit.nama as p FROM relasi 
JOIN gejala ON gejala.id=relasi.gejala_id
JOIN penyakit ON penyakit.id=relasi.penyakit_id"); // Menggabungkan tabel

while($d=$r->fetch_assoc()){
echo "<tr>
<td>$d[g]</td>
<td>$d[p]</td>
<td><a href='?page=relasi&hapus_r=$d[id]' class='btn btn-danger btn-sm'>Hapus</a></td>
</tr>";
}
?>
</table>
</div>
<?php
}

// ================= SOLUSI =================
if($page=='solusi'){
?>
<div class="card">
<h4>Solusi</h4>

<form method="post" class="mb-3">
<select name="pid2" class="form-control mb-2"> <!-- Pilih penyakit -->
<?php $p=$conn->query("SELECT * FROM penyakit"); while($d=$p->fetch_assoc()) echo "<option value='$d[id]'>$d[nama]</option>"; ?>
</select>

<textarea name="solusi" class="form-control mb-2" required></textarea> <!-- Isi solusi -->

<button name="s" class="btn btn-warning">Tambah</button>
</form>

<table class="table table-bordered">
<tr><th>Penyakit</th><th>Solusi</th><th>Aksi</th></tr>
<?php
$s=$conn->query("SELECT solusi.*, penyakit.nama as p FROM solusi 
JOIN penyakit ON penyakit.id=solusi.penyakit_id");

while($d=$s->fetch_assoc()){
echo "<tr>
<td>$d[p]</td>
<td>$d[deskripsi]</td>
<td>
<a href='?page=solusi&edit_s=$d[id]' class='btn btn-warning btn-sm'>Edit</a>
<a href='?page=solusi&hapus_s=$d[id]' class='btn btn-danger btn-sm'>Hapus</a>
</td>
</tr>";
}
?>
</table>

<?php
if(isset($_GET['edit_s'])){
$e=$conn->query("SELECT * FROM solusi WHERE id=".$_GET['edit_s'])->fetch_assoc();
?>
<form method="post">
<input type="hidden" name="id" value="<?= $e['id'] ?>">
<textarea name="solusi" class="form-control mb-2"><?= $e['deskripsi'] ?></textarea>
<button name="update_s" class="btn btn-warning">Update</button>
</form>
<?php } ?>

</div>
<?php
}
?>

<a href="aktivitas.php" class="btn btn-secondary">Aktivitas</a> <!-- Ke halaman aktivitas -->
<a href="export.php" class="btn btn-danger">Export</a> <!-- Export data -->

</div>
</body>
</html>