<?php include("setup.php"); ?>
<?php
$conn = db_connect(); // Establish a connection and store it in $conn
extract($_REQUEST);
extract($_SERVER);

if ($task != "submit") {
    if (isset($id)) {
        $id = (int) $id; // Ensure $id is an integer
        $entry_query = mysqli_query($conn, "SELECT * FROM blog WHERE id=$id");
        $entry = mysqli_fetch_array($entry_query);
        $title = $entry['title'];
        $type = $entry['type'];
        $date = $entry['posted_date'];
        $time = $entry['posted_time'];
        $content = $entry['content'];
        $user_query = mysqli_query($conn, "SELECT * FROM blog_users WHERE id={$entry['author']}");
        $user = mysqli_fetch_array($user_query);
        $login = $user['login'];
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
    <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
    <?php if (isset($id)) { ?>
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="login" value="<?php echo $login; ?>">
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
    <input type="text" name="title" class="normal" size="48"<?php if (isset($id)) echo " value=\"$title\"";?>>
    </td>
    </tr>
    </table>
    <table cellspacing="0" cellpadding="5" border="0">    
    <tr>
    <td class="normal">
    企业分类：<br>
    <select name="topic" class="normal">
    <?php
    $topic_query = mysqli_query($conn, "SELECT * FROM blog_setup");
    while ($topic = mysqli_fetch_array($topic_query)) {
        $selected = (isset($id) && $type == $topic['id']) ? " selected" : "";
        echo "<option value=\"{$topic['id']}\"$selected>{$topic['name']}</option>\n";
    }
    ?>
    </select>
    </td>
    <td class="normal">
    登记日期：<br>
    <input type="text" name="date" class="normal" size="12" value="<?php if (isset($id)) echo $date; else echo date('Y-m-d'); ?>">
    </td>
    <td class="normal">
    登记时间：<br>
    <input type="text" name="time" class="normal" size="12" value="<?php if (isset($id)) echo $time; else echo date('H:i'); ?>">
    </td>
    </tr>
    <tr>
    <td class="normal" colspan="3">
    投放代码：<br>
    <textarea name="content" class="normal" cols="50" rows="8"><?php if (isset($id)) echo $content; ?></textarea>
    </td>
    </tr>
    </table>
    <table cellspacing="0" cellpadding="5" border="0">
    <tr>
    <td class="normal">
    授权登记：<br>
    <input type="text" name="login<?php if (isset($id)) echo "_disabled";?>" class="normal" size="20"<?php if (isset($id)) echo " value=\"$login\" disabled"; ?>>
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
    <em><a href="<?php echo $_SERVER['PHP_SELF']; ?>">新增</a></em><br>
    <em>修改：</em>
    <ol>
    <?php
    $entry_query = mysqli_query($conn, "SELECT * FROM blog");
    while ($entry = mysqli_fetch_array($entry_query)) {
        echo "<li><a href=\"{$_SERVER['PHP_SELF']}?id={$entry['id']}\">{$entry['title']}</a></li>\n";
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
    $login = mysqli_real_escape_string($conn, $login);
    $password = mysqli_real_escape_string($conn, $password);
    $user_query = mysqli_query($conn, "SELECT * FROM blog_users WHERE login='$login' OR email='$login'");
    $validated = false;
    if (mysqli_num_rows($user_query) > 0) {
        $user = mysqli_fetch_array($user_query);
        if (md5($password) == $user['password']) {
            $content = mysqli_real_escape_string($conn, $content);
            $title = mysqli_real_escape_string($conn, $title);
            $date = mysqli_real_escape_string($conn, $date);
            $time = mysqli_real_escape_string($conn, $time);
            $topic = (int) $topic;

            if (isset($id)) {
                $id = (int) $id; // Ensure $id is an integer
                $entry_query = mysqli_query($conn, "SELECT * FROM blog WHERE id=$id");
                $entry = mysqli_fetch_array($entry_query);
                if ($user['id'] == $entry['author']) {
                    $validated = true;
                    mysqli_query($conn, "UPDATE blog SET type=$topic, posted_date='$date', posted_time='$time', title='$title', content='$content' WHERE id=$id");
                    echo mysqli_error($conn);
                }
            } else {
                $validated = true;
                mysqli_query($conn, "INSERT INTO blog (author, type, posted_date, posted_time, title, content) VALUES ({$user['id']}, $topic, '$date', '$time', '$title', '$content')");
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