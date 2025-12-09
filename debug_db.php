<?php
// Only allow debug DB output when explicitly enabled via environment variable or CLI
if (php_sapi_name() !== 'cli' && getenv('ALLOW_DB_DEBUG') !== '1') {
    header('HTTP/1.1 403 Forbidden');
    echo 'Not allowed.';
    exit;
}
include("setup.php");

$conn = db_connect();

echo "Connected to database.<br>";

$queries = [
    "SELECT * FROM blog_setup",
    "SELECT posted_date FROM blog ORDER BY posted_date DESC"
];

foreach ($queries as $sql) {
    echo "Running query: $sql<br>";
    $result = mysqli_query($conn, $sql);
    if ($result === false) {
        echo "Error: " . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8') . "<br>";
    } else {
        echo "Success. Rows: " . mysqli_num_rows($result) . "<br>";
    }
}

echo "<br>Tables in database:<br>";
$result = mysqli_query($conn, "SHOW TABLES");
if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        echo htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8') . "<br>";
    }
} else {
    echo "Could not list tables: " . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8') . "<br>";
}
?>
