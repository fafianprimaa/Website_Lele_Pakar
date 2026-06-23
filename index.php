<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Sistem Pakar Lele</title>

<!-- Bootstrap & FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    background: linear-gradient(to bottom, #e0f7fa, #ffffff);
}

.card {
    border-radius: 15px;
    transition: 0.3s;
}
.card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.navbar-brand i {
    margin-right: 10px;
}

.title {
    color: #0a3d62;
    font-weight: bold;
}
.btn-custom {
    border-radius: 50px;
    padding: 10px 25px;
}
</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
  <div class="container">

    <a class="navbar-brand" href="#">
      <i class="fas fa-fish"></i> Sistem Pakar Lele
    </a>

    <div class="ms-auto">
      <a href="login.php" class="btn btn-outline-light">
        <i class="fas fa-sign-in-alt"></i> Login Admin
      </a>
    </div>

  </div>
</nav>

<!-- MENU -->
<div class="container mt-5">
  <div class="row justify-content-center g-4">

    <!-- LOGIN -->
    

    <!-- DIAGNOSA -->
    <div class="col-md-4">
      <div class="card shadow p-4 text-center">
        <i class="fas fa-stethoscope fa-3x mb-3 text-success"></i>
        <h4>Mulai Diagnosa</h4>
        <p>Mulai diagnosa penyakit lele berdasarkan gejala.  </p>
        <p></p>
        <p></p>
        <a href="diagnosa.php" class="btn btn-success btn-custom mt-2">
          <i class="fas fa-arrow-right"></i> Diagnosa
        </a>
      </div>
    </div>

    <!-- KONTAK ADMIN -->
    <div class="col-md-4">
      <div class="card shadow p-4 text-center">
        <i class="fas fa-headset fa-3x mb-3 text-danger"></i>
        <h4>Kontak Admin</h4>
        <p>Hubungi admin jika ada kendala atau pertanyaan.</p>

        <p class="mb-1">
          <i class="fas fa-phone"></i> 0822-1375-0066
        </p>
        <p class="mb-2">
          <i class="fas fa-envelope"></i> fafian048@gmail.com
        </p>

        <a href="https://wa.me/6282213750066" target="_blank" 
        class="btn btn-danger btn-custom mt-2">
          <i class="fab fa-whatsapp"></i> Chat WhatsApp
        </a>
      </div>
    </div>

  </div>
</div>

<!-- Footer -->
<footer class="text-center mt-5 mb-3 text-muted">
  &copy; <?= date('Y') ?> Sistem Pakar Lele Dengan Motede Backward Chaining
</footer>

</body>
</html>