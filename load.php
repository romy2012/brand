<?php include("setup.php"); ?>
<?php
// This endpoint returns structured JSON instead of emitting executable JS.
header('Content-Type: application/json; charset=utf-8');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cell = isset($_GET['cell']) ? $_GET['cell'] : '';

if ($id <= 0) {
    echo json_encode(['error' => 'invalid_id']);
    exit;
}

$conn = db_connect();

// Fetch the requested entry
$stmt = mysqli_prepare($conn, "SELECT * FROM blog WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$entry = mysqli_fetch_assoc($res);

if (!$entry) {
    echo json_encode(['error' => 'not_found']);
    exit;
}

// Fetch sibling entries (same type and date)
$stmt2 = mysqli_prepare($conn, "SELECT * FROM blog WHERE type = ? AND posted_date = ? ORDER BY id");
mysqli_stmt_bind_param($stmt2, 'is', $entry['type'], $entry['posted_date']);
mysqli_stmt_execute($stmt2);
$res2 = mysqli_stmt_get_result($stmt2);

$paragraphs = [];

while ($row = mysqli_fetch_assoc($res2)) {
    // Split content into paragraphs by CRLF or LF
    $paras = preg_split('/\r?\n/', $row['content']);
    $paraObjs = [];
    foreach ($paras as $p) {
        $p = trim($p);
        if ($p === '') continue;
        $obj = ['text' => '', 'images' => []];

        // Find patterns like <display|url>
        $pattern = '/<([^\|>]+)\|([^>]+)>/';
        $matches = [];
        preg_match_all($pattern, $p, $matches, PREG_SET_ORDER);

        if ($matches) {
            // Remove the image tags from text
            $text = preg_replace($pattern, '', $p);
            $obj['text'] = $text;
            foreach ($matches as $m) {
                $display = $m[1];
                $url = $m[2];
                // Basic URL validation
                if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                    // try to allow relative URLs
                    $url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
                }
                $obj['images'][] = ['display' => $display, 'url' => $url];
            }
        } else {
            $obj['text'] = $p;
        }

        $paraObjs[] = $obj;
    }

    $paragraphs[] = ['id' => (int)$row['id'], 'title' => $row['title'], 'paras' => $paraObjs];
}

$setup_stmt = mysqli_prepare($conn, "SELECT * FROM blog_setup WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($setup_stmt, 'i', $entry['type']);
mysqli_stmt_execute($setup_stmt);
$setup_res = mysqli_stmt_get_result($setup_stmt);
$setup = mysqli_fetch_assoc($setup_res);

$out = [
    'cell' => $cell,
    'type' => $entry['type'],
    'setup' => $setup,
    'entries' => $paragraphs
];

echo json_encode($out, JSON_UNESCAPED_UNICODE);
?>
