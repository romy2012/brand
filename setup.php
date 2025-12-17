<?php
function db_connect()
{
    // Read DB credentials from environment variables or fall back to defaults.
    $db_host = getenv('DB_HOST') ?: 'localhost';
    $db_user = getenv('DB_USER') ?: 'subject';
    $db_pass = getenv('DB_PASS') ?: '@subject';
    $db_name = getenv('DB_NAME') ?: 'subject';

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_errno()) {
        error_log("MySQL connect error: " . mysqli_connect_error());
        // Do not expose DB errors to users in production.
        die("Cannot connect to database.");
    }
    // Use proper charset (utf8mb4) to support full unicode.
    mysqli_set_charset($conn, 'utf8mb4');
    return $conn;
}
header('Content-Type: text/html; charset=UTF-8');
?>
