<?php include("setup.php"); ?>
<?php
$conn = db_connect(); // Establish a connection and store it in $conn

// Explicitly read inputs instead of using extract()
$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : '';

if ($task !== "submit") {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $title = '';
    $type = '';
    $date = '';
    $time = '';
    $content = '';
    $login = '';

    if ($id) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM blog WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $entry = mysqli_fetch_assoc($result);
        if ($entry) {
            $title = $entry['title'];
            $type = $entry['type'];
            $date = $entry['posted_date'];
            $time = $entry['posted_time'];
            $content = $entry['content'];

            $stmt2 = mysqli_prepare($conn, "SELECT * FROM blog_users WHERE id = ?");
            mysqli_stmt_bind_param($stmt2, 'i', $entry['author']);
            mysqli_stmt_execute($stmt2);
            $res2 = mysqli_stmt_get_result($stmt2);
            $user = mysqli_fetch_assoc($res2);
            if ($user) $login = $user['login'];
        }
    }
    ?>
    <!doctype html>
    <html lang="zh-cmn-Hans">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>品牌管理</title>
    <style>
    body{margin:10px;background-color:#FFF;font:12px Arial,Helvetica,sans-serif ;}
    h1{font:bold 12px Arial,Helvetica,sans-serif ;}
    em{font-style:normal;font-weight:bold;}
    ol{margin:0px;padding:0px;margin-left:30px;}
    ul{margin:0px;padding:0px;margin-left:25px;}
    a{color:#369;text-decoration:none }
    a:hover{color:#69C;text-decoration:underline;}
    .normal{font:12px Arial,Helvetica,sans-serif;}
    input.normal {font:12px Arial,Helvetica,sans-serif ;}
    .bold{font:bold 12px Arial,Helvetica,sans-serif ;}
    .small{font:12px Arial,Helvetica,sans-serif;}
    .smallbold{font:bold 12px Arial,Helvetica,sans-serif;}
    textarea.normal,input.normal{border:1px solid #DDD;}
    textarea.normal{font-size:14px;}
    input.button{overflow:hidden;display:inline-block;height:22px;line-height:20px;padding:0px 8px;background:#FFFFC1;border:1px solid;border-top-color:#FFF2E6;border-right-color:#FFBE7D;border-bottom-color:#FFBE7D;border-left-color:#FFF2E6;font-size:12px;color:#009933;font-weight:normal;-moz-border-radius:3px;}
    </style>
    </head>
    <body>
    <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
    <?php if ($id) { ?>
    <input type="hidden" name="id" value="<?php echo (int)$id; ?>">
    <input type="hidden" name="login" value="<?php echo htmlspecialchars($login, ENT_QUOTES, 'UTF-8'); ?>">
    <?php } ?>
    <input type="hidden" name="task" value="submit">
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
    <td align="center" width="100%">
    <table cellspacing="10" cellpadding="0" border="0" width="80%" style="padding-top:20px">
    <tr>
    <td width="55%" valign="top">
    <table cellspacing="0" cellpadding="5" border="0" width="100%">
    <tr>
    <td>品牌管理</td>
    </tr>
    <tr>
    <td class="normal">
    企业全称：<br>
    <input type="text" name="title" class="normal" size="48"<?php if ($id) echo ' value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'"';?>>
    </td>
    </tr>
    </table>
    <table cellspacing="0" cellpadding="5" border="0">    
    <tr>
    <td class="normal">
    企业分类：<br>
    <select name="topic" class="normal">
    <?php
    $topic_stmt = mysqli_prepare($conn, "SELECT id, name FROM blog_setup ORDER BY id");
    mysqli_stmt_execute($topic_stmt);
    $topic_res = mysqli_stmt_get_result($topic_stmt);
    while ($topic_row = mysqli_fetch_assoc($topic_res)) {
        $selected = ($id && $type == $topic_row['id']) ? ' selected' : '';
        echo "<option value=\"".htmlspecialchars($topic_row['id'], ENT_QUOTES,'UTF-8')."\"$selected>".htmlspecialchars($topic_row['name'], ENT_QUOTES,'UTF-8')."</option>\n";
    }
    ?>
    </select>
    </td>
    <td class="normal">
    登记日期：<br>
    <input type="text" name="date" class="normal" size="12" value="<?php if ($id) echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); else echo date('Y-m-d'); ?>">
    </td>
    <td class="normal">
    登记时间：<br>
    <input type="text" name="time" class="normal" size="12" value="<?php if ($id) echo htmlspecialchars($time, ENT_QUOTES, 'UTF-8'); else echo date('H:i'); ?>">
    </td>
    </tr>
    <tr>
    <td class="normal" colspan="3">
    投放代码：<br>
    <textarea name="content" class="normal" cols="50" rows="8"><?php if ($id) echo htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); ?></textarea>
    </td>
    </tr>
    </table>
    <table cellspacing="0" cellpadding="5" border="0">
    <tr>
    <td class="normal">
    授权登记：<br>
    <input type="text" name="login_disabled" class="normal" size="20"<?php if ($id) echo " value=\"".htmlspecialchars($login, ENT_QUOTES, 'UTF-8')."\" disabled"; ?>>
    </td>
    <td class="normal">                    
    授权密码：<br>
    <input type="password" name="password" class="normal" size="20">
    </td>
    </tr>
    <tr>
    <td align="right" colspan="2">
    <input type="reset" class="button" value="清除">
    <input type="submit" class="button" value="提交">
    </td>
    </tr>
    </table>
    </td>
    <td width="5%"></td>
    <td valign="top" class="normal" width="40%">
    <img src="images/blank.gif" width="1" height="40"><br>
    <em><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>">新增</a></em><br>
    <em>修改：</em>
    <ol>
    <?php
    $entry_stmt = mysqli_prepare($conn, "SELECT id, title FROM blog ORDER BY posted_date DESC");
    mysqli_stmt_execute($entry_stmt);
    $entry_res = mysqli_stmt_get_result($entry_stmt);
    while ($entry_row = mysqli_fetch_assoc($entry_res)) {
        echo "<li><a href=\"" . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . "?id=" . (int)$entry_row['id'] . "\">" . htmlspecialchars($entry_row['title'], ENT_QUOTES, 'UTF-8') . "</a></li>\n";
    }
    ?>
    </ol>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </form>
    </body>
    </html>

    <?php
} else {
    // Handle submission securely
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $login = isset($_POST['login']) ? $_POST['login'] : (isset($_POST['login_disabled']) ? $_POST['login_disabled'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $time = isset($_POST['time']) ? $_POST['time'] : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $topic = isset($_POST['topic']) ? (int)$_POST['topic'] : 0;

    $validated = false;

    // Basic validation
    if ($login !== '' && $password !== '') {
        $stmt = mysqli_prepare($conn, "SELECT id, login, password FROM blog_users WHERE login = ? OR email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'ss', $login, $login);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($user = mysqli_fetch_assoc($res)) {
            $stored = $user['password'];
            // Prefer password_verify; allow legacy md5 and rehash immediately
            if (password_verify($password, $stored)) {
                $validated = true;
                // Rehash if needed
                if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $up = mysqli_prepare($conn, "UPDATE blog_users SET password = ? WHERE id = ?");
                    mysqli_stmt_bind_param($up, 'si', $newHash, $user['id']);
                    mysqli_stmt_execute($up);
                }
            } elseif (md5($password) === $stored) {
                // Legacy md5 match: rehash to modern algorithm
                $validated = true;
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $up = mysqli_prepare($conn, "UPDATE blog_users SET password = ? WHERE id = ?");
                mysqli_stmt_bind_param($up, 'si', $newHash, $user['id']);
                mysqli_stmt_execute($up);
            }

            if ($validated) {
                $title = trim($title);
                $content = trim($content);
                $date = trim($date);
                $time = trim($time);

                if ($id) {
                    // Update existing entry if user is the author
                    $stmtE = mysqli_prepare($conn, "SELECT author FROM blog WHERE id = ? LIMIT 1");
                    mysqli_stmt_bind_param($stmtE, 'i', $id);
                    mysqli_stmt_execute($stmtE);
                    $rE = mysqli_stmt_get_result($stmtE);
                    $entry = mysqli_fetch_assoc($rE);
                    if ($entry && $entry['author'] == $user['id']) {
                        $up2 = mysqli_prepare($conn, "UPDATE blog SET type = ?, posted_date = ?, posted_time = ?, title = ?, content = ? WHERE id = ?");
                        mysqli_stmt_bind_param($up2, 'issssi', $topic, $date, $time, $title, $content, $id);
                        mysqli_stmt_execute($up2);
                    } else {
                        $validated = false; // Not allowed to update
                    }
                } else {
                    $ins = mysqli_prepare($conn, "INSERT INTO blog (author, type, posted_date, posted_time, title, content) VALUES (?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($ins, 'iissss', $user['id'], $topic, $date, $time, $title, $content);
                    mysqli_stmt_execute($ins);
                }
            }
        }
    }
    ?>

    <!doctype html>
    <html lang="zh-cmn-Hans">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>品牌管理</title>
    <?php if ($validated) { ?>
    <meta http-equiv="refresh" content="0;URL=admin.php">
    <style>
    body{margin:10px;background-color:#FFF;font:12px Arial,Helvetica,sans-serif ;}
    h1{font:bold 12px Arial,Helvetica,sans-serif ;}
    em{font-style:normal;font-weight:bold;}
    ol{margin:0px;padding:0px;margin-left:30px;}
    ul{margin:0px;padding:0px;margin-left:25px;}
    a{color:#369;text-decoration:none }
    a:hover{color:#69C;text-decoration:underline;}
    .normal{font:12px Arial,Helvetica,sans-serif;}
    input.normal {font:12px Arial,Helvetica,sans-serif ;}
    .bold{font:bold 12px Arial,Helvetica,sans-serif ;}
    .small{font:12px Arial,Helvetica,sans-serif;}
    .smallbold{font:bold 12px Arial,Helvetica,sans-serif;}
    textarea.normal,input.normal{border:1px solid #DDD;}
    textarea.normal{font-size:14px;}
    input.button {overflow:hidden;display:inline-block;height:22px;line-height:20px;padding:0px 8px;background:#FFFFC1;border:1px solid;border-top-color:#FFF2E6;border-right-color:#FFBE7D;border-bottom-color:#FFBE7D;border-left-color:#FFF2E6;font-size:12px;color:#009933;font-weight:normal;-moz-border-radius:3px;}
    </style>
    </head>
    </html>
    <?php } else { ?>
    </head>
    <body>
    <h1>提交结果</h1>
    <p class="normal">提交错误 <a href="javascript:history.back()">返回</a></p>
    <?php } ?>
    </body>
    </html>

    <?php
}
?>
