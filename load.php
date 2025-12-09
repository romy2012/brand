<?php include("setup.php"); ?>
<?php
$id = $_GET['id'];
$cell = $_GET['cell'];
$conn = db_connect();
if (!empty($id) && !empty($cell))
{
    $archive_query = mysqli_query($conn, "SELECT * FROM blog WHERE id=$id");
    $test = mysqli_fetch_array($archive_query);
    $archive_query = mysqli_query($conn, "SELECT * FROM blog WHERE type={$test['type']} AND posted_date='{$test['posted_date']}'");
    $setup_query = mysqli_query($conn, "SELECT * FROM blog_setup WHERE id={$test['type']}");
    $setup = mysqli_fetch_array($setup_query);
    $count = 0;
?>
<script language="javascript" type="text/javascript">
if (parent.frames[1].current == '<?=$cell?>')
{
    var doc = parent.frames[1].document;
    var cell = doc.getElementById("<?=$cell?>");
    var content = cell.appendChild(doc.createElement("div"));
    content.style.position = "absolute";
    content.style.font = "12px Arial, Helvetica, sans-serif";
    content.style.color = "#FFF";
    content.style.left = "17px";
    content.style.top = "20px";
    content.style.width = "225px";
    content.style.height = "220px";
    content.style.cursor = "default";
    content.style.visibility = "hidden";
    content.style.border = "0px #FFF solid";

<?php
    while ($archive = mysqli_fetch_array($archive_query))
    {
        $count++;
        $para_array = explode("\r\n", addslashes($archive['content']));
        for($j = 0; $j < count($para_array); $j++)
        {
            if ($para_array[$j][0] == "<")
            {
                $match_string = $para_array[$j];
                preg_match_all("/(<([^<>]+)\|([^<>]+)>)+([^<>]*)/", $match_string, $address_array);
                $non_address = $address_array[4];
                $display_address = $address_array[2];
                $url_address = $address_array[3];
            }
            else
            {
                preg_match_all("/([^<>]+)(<([^<>]+)\|([^<>]+)>)*/", $para_array[$j], $address_array);
                $non_address = $address_array[1];
                $display_address = $address_array[3];
                $url_address = $address_array[4];
            }

            echo "para = content.appendChild(doc.createElement('p'));\n";
            echo "para.style.lineHeight = \"18px\";\n";
            echo "para.style.color = \"#FFF\";\n";
            echo "para.style.marginTop = \"0px\";\n";
            echo "para.style.marginBottom = \"10px\";\n";
            echo "para.style.textAlign = \"justify\";\n";
            echo "para.style.textJustify = \"distribute\";\n";
            echo "para.style.wordBreak = \"break-all\";\n";
            echo "para.style.wordWrap = \"break-word\";\n";

            for($i = 0; $i < count($non_address); $i++)
            {
                if ($non_address[$i] != "")
                    echo "para.appendChild(doc.createTextNode('{$non_address[$i]}'));\n";
                if ($display_address[$i] != "")
                {

                    echo "anchor = para.appendChild(doc.createElement('a'));\n";
                    echo "anchor.href = '{$url_address[$i]}';\n";
                    echo "anchor.target = '_blank';\n";
                    echo "img = anchor.appendChild(doc.createElement('img'));\n";
                    echo "img.src = '{$display_address[$i]}';\n";
                    echo "img.style.width = '225px';\n";
                    echo "img.style.height = '220px';\n";
                    echo "img.style.border = '0px';\n";
                    echo "img.style.margin = '0px';\n";

                }
            }
        }
    }
?>
    var topmargin = cell.appendChild(doc.createElement("div"));
    var botmargin = cell.appendChild(doc.createElement("div"));

    topmargin.style.backgroundColor = cell.style.backgroundColor;
    topmargin.style.position = "absolute";
    topmargin.style.height = "20px";
    topmargin.style.width = "259px";
    topmargin.style.top = "0px";

    botmargin.style.backgroundColor = cell.style.backgroundColor;
    botmargin.style.position = "absolute";
    botmargin.style.height = "20px";
    botmargin.style.width = "259px";
    botmargin.style.top = "241px";
}
</script>
<?php
}
?>