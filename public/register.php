<?php
require_once '../app/config/DatabaseConnection.php';
require_once '../app/models/Auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = (new DatabaseConnection())->getConnection();
    $auth = new Auth($db);
    
    if ($auth->register($_POST['nama'], $_POST['email'], $_POST['password'])) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
    } else {
        $error = "Gagal mendaftar! Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Salon</title>
</head>
<body style="font-family: Arial; padding: 20px;">
    <h2>Daftar Akun Pelanggan</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
    <form method="POST" action="">
        <label>Nama Lengkap :</label><br>
        <input type="text" name="nama" required><br><br>
        
        <label>Email :</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Password :</label><br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>
</html>