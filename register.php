<?php
include "koneksi.php";

$message = "";
$message_class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        // Cek username
        $cek = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $cek->execute([$username]);
        
        if ($cek->fetchColumn() > 0) {
            $message = "Username sudah terdaftar!";
            $message_class = "error";
        } else {
            // Insert username
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $password])) {
                $message = "Registrasi berhasil! Silakan <a href='login.php'>Login</a>";
                $message_class = "success";
            } else {
                $message = "Gagal mendaftar!";
                $message_class = "error";
            }
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $message_class = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register System</title>
    <link rel="stylesheet" href="style/style_log_res.css">
</head>
<body>

<div class="auth-container">
    <h2>Daftar Akun</h2>

    <?php if ($message): ?>
        <div class="message <?php echo $message_class; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Buat username baru" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Buat password" required>
        </div>
        <button type="submit">Daftar</button>
    </form>

    <div class="auth-footer">
        Sudah punya akun? <a href="login.php">Login di sini</a>
    </div>
</div>

</body>
</html>