<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db.php";

if (
    !isset($_SESSION['admin_id']) ||
    !isset($_SESSION['session_version'])
) {
    header("Location: login.php");
    exit;
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT session_version
     FROM admins
     WHERE id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $_SESSION['admin_id']
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (
    !$admin ||
    (int)$admin['session_version'] !==
    (int)$_SESSION['session_version']
) {
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    header("Location: login.php?session=expired");
    exit;
}
