<?php include 'config.php'; ?>
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>Lupa Password</title>

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
<h3 class="mb-4 text-warning">
<i class="fas fa-key"></i> Lupa Password
</h3>

<?php
if(isset($_POST['kirim'])){

 $email = $_POST['email'];

 $q=$conn->query("SELECT * FROM users WHERE email='$email'");
 $d=$q->fetch_assoc();

 if($d){

   $token = bin2hex(random_bytes(16));
   $conn->query("UPDATE users SET token='$token' WHERE email='$email'");

   $link="http://localhost/sp_lele_final/reset.php?token=$token";

   $mail = new PHPMailer(true);

   try {
       $mail->isSMTP();
       $mail->Host       = 'smtp.gmail.com';
       $mail->SMTPAuth   = true;
       $mail->Username   = 'fafian048@gmail.com';
       $mail->Password   = 'kxjp bjsi pzdx smux';
       $mail->SMTPSecure = 'tls';
       $mail->Port       = 587;

       $mail->setFrom('emailkamu@gmail.com', 'Sistem Pakar Lele');
       $mail->addAddress($email);

       $mail->isHTML(true);
       $mail->Subject = 'Reset Password';
       $mail->Body    = "
       <h3>Reset Password</h3>
       <p>Klik tombol berikut untuk reset password:</p>
       <a href='$link' style='padding:10px 20px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:5px;'>Reset Password</a>
       ";

       $mail->send();

       echo "<div class='alert alert-success'>Link reset berhasil dikirim ke email!</div>";

   } catch (Exception $e) {
       echo "<div class='alert alert-danger'>Gagal kirim email: {$mail->ErrorInfo}</div>";
   }

 } else {
   echo "<div class='alert alert-danger'>Email tidak ditemukan!</div>";
}
}
?>

<form method="post">
<div class="mb-3 text-start">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<button name="kirim" class="btn btn-warning btn-custom w-100">
<i class="fas fa-envelope"></i> Kirim Link Reset
</button>
</form>

<div class="mt-3">
<a href="login.php"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
</div>

</div>

</body>
</html>