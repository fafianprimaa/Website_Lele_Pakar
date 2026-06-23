<?php include 'config.php'; ?>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<title>Daftar Admin - Sistem Pakar Lele</title>

<!-- Bootstrap & FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    background: linear-gradient(to bottom, #e0f7fa, #ffffff);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.card {
    border-radius: 15px;
    padding: 30px;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    transition: 0.3s;
}
.card:hover {
    transform: scale(1.02);
}
.btn-custom {
    border-radius: 50px;
    padding: 10px 25px;
}
.alert-custom {
    margin-bottom: 15px;
}
</style>
</head>
<body>

<div class="card text-center">
    <h3 class="mb-4 text-success"><i class="fas fa-user-plus"></i> REGISTER </h3>

    <?php
    if(isset($_POST['daftar'])){
        $u = $_POST['username'];
        $e = $_POST['email'];
        $p = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // cek username
        $cek1 = $conn->query("SELECT * FROM users WHERE username='$u'");
        if($cek1->num_rows > 0){
            echo "<div class='alert alert-danger alert-custom'>Username sudah dipakai!</div>";
        } 
        // cek email
        elseif($conn->query("SELECT * FROM users WHERE email='$e'")->num_rows > 0){
            echo "<div class='alert alert-danger alert-custom'>Email sudah digunakan!</div>";
        } 
        else {
            $conn->query("INSERT INTO users(username,email,password,role) 
                         VALUES('$u','$e','$p','admin')");
            echo "<div class='alert alert-success alert-custom'>
                    Admin berhasil didaftarkan! <a href='login.php'>Login</a>
                  </div>";
        }
    }
    ?>

    <form method="post">
        <div class="mb-3 text-start">
            <label>Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
        </div>
        <div class="mb-3 text-start">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email aktif" required>
        </div>
        <div class="mb-3 text-start">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
        <div class="mb-3 text-start">
            <label>Role</label>
            <input type="password" name="password" class="form-control" placeholder="Admin/user" required>
        </div>
        <button type="submit" name="daftar" class="btn btn-success btn-custom w-100">
            <i class="fas fa-user-plus"></i> Daftar
        </button>
    </form>

    <div class="mt-3">
        <a href="login.php"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
    </div>
</div>

</body>
</html>