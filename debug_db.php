<?php
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
        echo "Error: " . mysqli_error($conn) . "<br>";
    } else {
        echo "Success. Rows: " . mysqli_num_rows($result) . "<br>";
    }
}

echo "<br>Tables in database:<br>";
$result = mysqli_query($conn, "SHOW TABLES");
if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        echo $row[0] . "<br>";
    }
} else {
    echo "Could not list tables: " . mysqli_error($conn) . "<br>";
}
?>
