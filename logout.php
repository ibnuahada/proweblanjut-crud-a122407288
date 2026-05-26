<?php
session_start();

// 1. Hapus semua data Session dari memori server
$_SESSION = array();

// 2. Hapus Session Cookie yang ada di browser pengguna
if (ini_get("session_use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hapus Cookie 'user_id' (Fitur Remember Me) secara permanen
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/');
}

// 4. Hancurkan Session sepenuhnya
session_destroy();

// 5. Alihkan pengguna kembali ke halaman login
header("Location: login.php");
exit();
?>