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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Login Salon</h3>
                        
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        
                        <p class="text-center mt-3 small">
                            Belum punya akun? <a href="register.php">Daftar di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>