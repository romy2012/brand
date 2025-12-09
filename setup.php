<?
function db_connect() {
    $conn = mysqli_connect("localhost","brand","brand2025","brand");
    if (mysqli_connect_errno()) {
        die("Connect mysql fail: " . mysqli_connect_error());
    }
    mysqli_query($conn, "set names utf8");
    return $conn;
}
header('Content-Type: text/html; charset=UTF-8');
?>