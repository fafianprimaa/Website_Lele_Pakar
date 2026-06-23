<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>Login - Sistem Pakar Lele</title>

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
    max-width: 400px;
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
a {
    text-decoration: none;
}
</style>
</head>
<body>

<div class="card text-center">
    <h3 class="mb-4 text-primary"><i class="fas fa-sign-in-alt"></i> Login</h3>

    <?php
    if(isset($_POST['login'])){
        $u = $_POST['username'];
        $p = $_POST['password'];

        $q = $conn->query("SELECT * FROM users WHERE username='$u'");
        $d = $q->fetch_assoc();

        if($d && password_verify($p, $d['password'])){
            $_SESSION['user'] = $u;
            $_SESSION['role'] = $d['role'];
            header("Location: ".($d['role']=='admin'?'admin.php':'diagnosa.php'));
            exit;
        } else {
            echo "<div class='alert alert-danger'>Login gagal! Username atau password salah.</div>";
        }
    }
    ?>

    <form method="post">
        <div class="mb-3 text-start">
            <label>Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
        </div>
        <div class="mb-3 text-start">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary btn-custom w-100">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>
    </form>

    <div class="mt-3">
        <a href="register.php" class="text-success me-2"><i class="fas fa-user-plus"></i> Daftar Admin</a> |
        <a href="lupa.php" class="text-warning ms-2"><i class="fas fa-key"></i> Lupa Password</a>
    </div>
</div>

</body>
</html>