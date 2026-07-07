<?php
require_once '../app/helpers/SessionHelper.php';
require_once '../app/config/DatabaseConnection.php';
require_once '../app/models/Auth.php';

SessionHelper::start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = (new DatabaseConnection())->getConnection();
    $auth = new Auth($db);
    
    $user = $auth->login($_POST['email'], $_POST['password']);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['nama'] = $user['nama'];
        
        if ($user['role_id'] == 1) {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: customer/home.php");
        }
        exit;
    } else {
        $error = "Email atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Salon</title>
</head>
<body style="font-family: Arial; padding: 20px;">
    <h2>Login Aplikasi Salon</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
    <form method="POST" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</body>
</html>