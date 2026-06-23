<?php include 'config.php'; ?>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>

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
}
.btn-custom {
    border-radius: 50px;
}
</style>
</head>
<body>

<div class="card text-center">

<?php
$token = $_GET['token'] ?? '';

if(!$token){
    echo "<div class='alert alert-danger'>Token tidak valid</div>";
    exit;
}

// cek token + expired (kalau pakai)
$q = $conn->query("SELECT * FROM users WHERE token='$token'");
$d = $q->fetch_assoc();

if(!$d){
    echo "<div class='alert alert-danger'>Token tidak ditemukan / sudah digunakan</div>";
    exit;
}
?>

<h3 class="mb-4 text-primary">
<i class="fas fa-lock"></i> Reset Password
</h3>

<?php
if(isset($_POST['reset'])){

    $pass1 = $_POST['password'];
    $pass2 = $_POST['confirm'];

    if($pass1 != $pass2){
        echo "<div class='alert alert-danger'>Password tidak sama!</div>";
    } else {

        $p = password_hash($pass1, PASSWORD_DEFAULT);

        $conn->query("UPDATE users SET password='$p', token=NULL WHERE token='$token'");

        echo "<div class='alert alert-success'>
        Password berhasil diubah! <br>
        <a href='login.php'>Login sekarang</a>
        </div>";
    }
}
?>

<form method="post">
<div class="mb-3 text-start">
<label>Password Baru</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="mb-3 text-start">
<label>Konfirmasi Password</label>
<input type="password" name="confirm" class="form-control" required>
</div>

<button name="reset" class="btn btn-primary btn-custom w-100">
<i class="fas fa-key"></i> Reset Password
</button>
</form>

</div>

</body>
</html>