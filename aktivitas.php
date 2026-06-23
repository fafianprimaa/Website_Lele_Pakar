<?php include 'config.php'; ?> // Mengambil koneksi database
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?> // Memulai session jika belum aktif

<!DOCTYPE html>
<html>
<head>
<title>Aktivitas User - Sistem Pakar Lele</title> <!-- Judul halaman -->

<!-- Bootstrap & FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Untuk tampilan -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> <!-- Untuk icon -->

<style>
body {
    background: #f0f8ff; /* Warna background halaman */
    padding: 20px; /* Jarak isi dari pinggir */
}
.card {
    border-radius: 15px; /* Sudut melengkung */
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1); /* Bayangan kotak */
    transition: 0.3s; /* Efek animasi */
}
.card:hover {
    transform: scale(1.02); /* Efek saat disentuh mouse */
}
table th, table td {
    vertical-align: middle !important; /* Isi tabel rata tengah */
}
</style>
</head>
<body>

<div class="container"> <!-- Pembungkus utama -->

<div class="card"> <!-- Kotak tampilan -->
    <h3 class="mb-4 text-primary">
        <i class="fas fa-history"></i> Aktivitas User <!-- Judul isi -->
    </h3>

    <div class="table-responsive"> <!-- Agar tabel bisa scroll di layar kecil -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark"> <!-- Header tabel -->
            <tr>
                <th>Nama</th> <!-- Nama user -->
                <th>Hasil Diagnosa</th> <!-- Hasil sistem pakar -->
                <th>Tanggal</th> <!-- Waktu -->
            </tr>
        </thead>
        <tbody>
        <?php
        // Mengambil semua data riwayat, urut dari yang terbaru
        $q=$conn->query("SELECT * FROM riwayat ORDER BY id DESC");

        // Perulangan untuk menampilkan setiap data
        while($d=$q->fetch_assoc()){
            echo "<tr>";
            echo "<td>$d[user]</td>"; // Menampilkan nama user
            echo "<td>";

            // Mengubah data JSON menjadi array PHP
            $hasil = json_decode($d['hasil'], true);

            // Jika data berupa array (hasil diagnosa lebih dari satu)
            if($hasil && is_array($hasil)){
                foreach($hasil as $h){
                    // Menampilkan nama penyakit + persentase
                    echo $h['nama'] . " (" . round($h['persen']) . "%)<br>";
                }
            } else {
                // Jika bukan JSON, tampilkan langsung
                echo $d['hasil'];
            }

            echo "</td>";
            echo "<td>$d[tanggal]</td>"; // Menampilkan tanggal
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    </div>

    <!-- Tombol kembali ke halaman admin -->
    <a href="admin.php" class="btn btn-secondary btn-custom mt-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Admin
    </a>
</div>

</div>

</body>
</html>