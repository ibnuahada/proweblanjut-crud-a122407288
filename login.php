<?php
session_start();
include "koneksi.php";

// Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Remember Me
if (!isset($_SESSION["user_id"]) && isset($_COOKIE["user_id"])) {
    $_SESSION["user_id"] = $_COOKIE["user_id"];
    header("Location: index.php?page=data_barang");
    exit();
}

if (isset($_SESSION["user_id"])) {
    header("Location: index.php?page=data_barang");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Permintaan tidak sah (CSRF Token Invalid).");
    }

    $username = $_POST["username"];
    $password = $_POST["password"];
    $remember = isset($_POST["remember"]);

    try {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            if ($remember) {
                setcookie("user_id", $user["id"], time() + (86400 * 7), "/");
            }
            header("Location: index.php?page=data_barang");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan sistem.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    <link rel="stylesheet" href="style/style_log_res.css">
</head>
<body>

<div class="auth-container">
    <h2>Login</h2>

    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>

        <div class="remember-group">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me!</label>
        </div>

        <button type="submit">Login</button>
    </form>
    
    <div class="auth-footer">
        Belum punya akun? <a href="register.php">Daftar sekarang</a>
    </div>
</div>

</body>
</html>