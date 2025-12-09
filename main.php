<?php include("setup.php"); ?>
<!doctype html>
<html lang="zh-cmn-Hans">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
	body{margin:0px;background-color:#FFF;font:12px Arial, Helvetica, sans-serif; padding:0;}
	.normal{font:12px Arial, Helvetica, sans-serif;}
	.small{font:12px Arial, Helvetica, sans-serif ;}
	.smallbold{font:bold 12px Arial, Helvetica, sans-serif ;}
	.dynamic{position:absolute;}
	#root{top:99px;left:99px;background:transparent url('images/bg.gif');width:100%;height:300px;}
	#content{position:absolute;color:#FFF;width:240px;padding:5px;left:-250px }
	#titlebar{position:absolute;background-color:#999;color:#FFF;top:-100px;padding:5px;width:240px }
	#close{top:-100px;line-height:11px;}
	#close img{vertical-align:middle;}
	#top{left:0px;top:-1px;height:1px;width:100%;border-top:1px #CCC solid;}
	#bottom{left:0px;top:-1px;height:1px;width:100%;border-top:1px #CCC solid;}
	#left{left:-1px;top:0px;height:1000px;width:1px;border-left:1px #CCC solid;}
	#right{left:-1px;top:0px;height:1000px;width:1px;border-left:1px #CCC solid;}
	#titlediv{position:absolute;padding-top:10px;padding-left:10px;line-height:18px;}
	#typediv{position:absolute;padding-top:1px;padding-left:3px;}
	#daydiv{top:-100px;margin-left: -1px;}
	#scrolldiv{position:absolute;width:9px;height:50px;border:1px #FFF solid;visibility:hidden;}
	#loading{position:absolute;width:250px;height:250px;vertical-align:50%;text-align:center;visibility:hidden;}
	.dyimg{max-width:7px;display:inline-block;}
	.dyimg>span{display:inline-block;height:7px;width:7px;float:left;}
	.dyimg>span>img{ vertical-align:middle; }
	.loadingtxt{display: flex;flex-direction: row;flex-wrap: nowrap;justify-content: center;align-items: center;align-content: stretch;color:#FFF;height: 100%;}
	.dynamic>div>p{display: flex;flex-direction: row;flex-wrap: nowrap;justify-content: center;align-items: center;align-content: stretch;margin-bottom:0!important;}
	.dynamic>div>p>a>img{vertical-align:middle;}
</style>
</head>
<body>
<div id="titlediv" class="smallbold"></div>
<div id="root" class="dynamic">
<?php

$conn = db_connect();
$day_count = 0;
$title_array = "{";
$num_types = 0;

$setup_query = mysqli_query($conn, "SELECT * FROM blog_setup");
$colors = [];
$type_array = [];

while ($setup = mysqli_fetch_array($setup_query)){
    $colors["light"][$num_types] = $setup['color_light'];
    $colors["dark"][$num_types] = $setup['color_dark'];
    $type_array[$num_types++] = preg_replace("/'/", "\\'", $setup['name']);
}

$day_query = mysqli_query($conn, "SELECT posted_date FROM blog ORDER BY posted_date DESC");
$day_array = mysqli_fetch_array($day_query);
$day = "0000-00-00";
$first_topic = -1;

// Iterate over each day
while (isset($day_array['posted_date'])){
    // Clear query of all entries with the same date
    while ($day == $day_array['posted_date']) {
        $day_array = mysqli_fetch_array($day_query);
    }
    $day = $day_array['posted_date'];
    // Iterate over each row (type)
    for ($i = 0; $i < $num_types; $i++){
        $entry_query = mysqli_query($conn, "SELECT id, type, posted_date, title FROM blog WHERE posted_date='$day' AND type=$i");
        $row = "row_{$day_count}_{$i}";
        // Show an entry if it exists
        if (mysqli_num_rows($entry_query) > 0){
            $entry = mysqli_fetch_array($entry_query);
            $cell = "cell_{$day_count}_{$i}";
            $light = $colors['light'][$i];
            $dark = $colors['dark'][$i];
            $id = $entry['id'];
            if ($first_topic == -1)
                $first_topic = $i;
            $entry_query = mysqli_query($conn, "SELECT title FROM blog WHERE posted_date='$day' AND type=$i");
            $title_array .= "$cell:[";
            while ($entry = mysqli_fetch_array($entry_query)) {
                $title_array .= "'" . addslashes($entry['title']) . "',";
            }
            $title_array = substr($title_array, 0, -1);
            $title_array .= "],";
            echo "<div id=\"$cell\" class=\"dynamic\" ";
            echo "style=\"cursor:pointer;background-color:$light;width:19px;height:19px;top:1px;left:1px;overflow:hidden\" ";
            echo "onmouseover=\"handleMouseOver('$cell', $i, '$day')\" ";
            echo "onmouseout=\"handleMouseOut('$cell', '$light')\" ";
            echo "onmousedown=\"handleMouseDown('$cell', '$light')\" ";
            echo "onmouseup=\"handleMouseUp('$cell', $id)\"";
            echo "></div>\n";
        }
        echo "<div id=\"$row\" class=\"dynamic\" style=\"top:20px\">\n";
    }
    // Close each row div
    for ($i = 0; $i < $num_types; $i++)
        echo "</div>\n";
    echo "<div id=\"col_{$day_count}\" class=\"dynamic\" style=\"left:20px;width:20px\">\n";
    // Get the next day's entries
    $day_count++;
    $day_array = mysqli_fetch_array($day_query);
}
// Close each col div
for ($i = 0; $i < $day_count + 1; $i++)
    echo "</div>\n";
$title_array = substr($title_array, 0, -1);
$title_array .= "}";
?>

<div id="close" class="dynamic" onMouseOver="this.style.backgroundColor = colors['light'][getj(current)];document.getElementById(current).onmouseover();" onMouseOut="this.style.backgroundColor = '';if (!busy) {unselect();}" onMouseUp="if (close_click) {document.images['closebox'].src = 'images/closebox.gif';close_click=false;closeCell(current, '') }" onMouseDown="close_click=true;document.images['closebox'].src = 'images/closebox_pressed.gif'"><img src="images/closebox.gif" name="closebox"/></div>
<div id="top" class="dynamic"><img src="images/blank.gif" height="1" width="1" /></div>
<div id="bottom" class="dynamic"><img src="images/blank.gif" height="1" width="1" /></div>
<div id="left" class="dynamic"><img src="images/blank.gif" height="1" width="1" /></div>
<div id="right" class="dynamic"><img src="images/blank.gif" height="1" width="1" /></div>
<div id="typediv" class="smallbold"></div>
<div id="daydiv" class="dynamic">
	<div class="dyimg">
		<span><img src="images/digit0.gif" name="dy2" /></span>
		<span><img src="images/digit0.gif" name="dy1" /></span>
		<span><img src="images/digitdot.gif" /></span>
		<span><img src="images/digit0.gif" name="mo2" /></span>
		<span><img src="images/digit0.gif" name="mo1" /></span>
		<span><img src="images/digitdot.gif" /></span>
		<span><img src="images/digit0.gif" name="yr4" /></span>
		<span><img src="images/digit0.gif" name="yr3" /></span></span>
		<span><img src="images/digit0.gif" name="yr2" /></span>
		<span><img src="images/digit0.gif" name="yr1" /></span>
	</div>
</div>
<div id="masktop" class="dynamic" style="background-color:#FFF"><div id="masktopinner" class="dynamic" style="background:url('images/bg.gif')"></div></div>
<div id="maskbottom" class="dynamic" style="background-color:#FFF"><div id="maskbottominner" class="dynamic" style="background:url('images/bg.gif')"></div></div>
<div id="scrolldiv" onMouseOver="this.style.backgroundColor = colors['light'][getj(current)];document.getElementById(current).onmouseover();" onMouseOut="scrolldiv.style.backgroundColor='';unselect()"></div>
<div id="loading" onMouseOver="document.getElementById(current).onmouseover();" onMouseOut="unselect()"><div class="loadingtxt"><span>正在载入…</span></div></div>

<script type="text/javascript">
	var busy = false, current = "none";
	var root, topdiv, bottom, left, right;
	var curr_type, type_array, typediv, titlediv;
	var curr_day, daydiv;
	var close, scrolldiv, loading;
	var visited, max_days;
	var digits, colors, titles, num_days, num_types;
	var close_click = false;
	var Drag = {
		obj :null,
		init :function(o, oRoot, minX, maxX, minY, maxY, bSwapHorzRef, bSwapVertRef, fXMapper, fYMapper){
			o.onmousedown	= Drag.start;
			o.hmode			= bSwapHorzRef ? false :true ;
			o.vmode			= bSwapVertRef ? false :true ;
			o.root = oRoot && oRoot != null ? oRoot :o ;

			if (o.hmode  && isNaN(parseInt(o.root.style.left  ))) o.root.style.left   = "0px";
			if (o.vmode  && isNaN(parseInt(o.root.style.top   ))) o.root.style.top    = "0px";
			if (!o.hmode && isNaN(parseInt(o.root.style.right ))) o.root.style.right  = "0px";
			if (!o.vmode && isNaN(parseInt(o.root.style.bottom))) o.root.style.bottom = "0px";

			o.minX	= typeof minX != 'undefined' ? minX :null;
			o.minY	= typeof minY != 'undefined' ? minY :null;
			o.maxX	= typeof maxX != 'undefined' ? maxX :null;
			o.maxY	= typeof maxY != 'undefined' ? maxY :null;

			o.xMapper = fXMapper ? fXMapper :null;
			o.yMapper = fYMapper ? fYMapper :null;

			o.root.onDragStart	= new Function();
			o.root.onDragEnd	= new Function();
			o.root.onDrag		= new Function();
		},

		start :function(e)	{
			var o = Drag.obj = this;
			e = Drag.fixE(e);
			var y = parseInt(o.vmode ? o.root.style.top  :o.root.style.bottom);
			var x = parseInt(o.hmode ? o.root.style.left :o.root.style.right );
			o.root.onDragStart(x, y);
			o.lastMouseX	= e.clientX;
			o.lastMouseY	= e.clientY;

			if (o.hmode) {
				if (o.minX != null)	o.minMouseX	= e.clientX - x + o.minX;
				if (o.maxX != null)	o.maxMouseX	= o.minMouseX + o.maxX - o.minX;
			} else {
				if (o.minX != null) o.maxMouseX = -o.minX + e.clientX + x;
				if (o.maxX != null) o.minMouseX = -o.maxX + e.clientX + x;
			}

			if (o.vmode) {
				if (o.minY != null)	o.minMouseY	= e.clientY - y + o.minY;
				if (o.maxY != null)	o.maxMouseY	= o.minMouseY + o.maxY - o.minY;
			} else {
				if (o.minY != null) o.maxMouseY = -o.minY + e.clientY + y;
				if (o.maxY != null) o.minMouseY = -o.maxY + e.clientY + y;
			}

			document.onmousemove	= Drag.drag;
			document.onmouseup		= Drag.end;

			return false;
		},
		drag :function(e){
			e = Drag.fixE(e);
			var o = Drag.obj;
			var ey	= e.clientY;
			var ex	= e.clientX;
			var y = parseInt(o.vmode ? o.root.style.top  :o.root.style.bottom);
			var x = parseInt(o.hmode ? o.root.style.left :o.root.style.right );
			var nx, ny;

			if (o.minX != null) ex = o.hmode ? Math.max(ex, o.minMouseX) :Math.min(ex, o.maxMouseX);
			if (o.maxX != null) ex = o.hmode ? Math.min(ex, o.maxMouseX) :Math.max(ex, o.minMouseX);
			if (o.minY != null) ey = o.vmode ? Math.max(ey, o.minMouseY) :Math.min(ey, o.maxMouseY);
			if (o.maxY != null) ey = o.vmode ? Math.min(ey, o.maxMouseY) :Math.max(ey, o.minMouseY);

			nx = x + ((ex - o.lastMouseX) * (o.hmode ? 1 :-1));
			ny = y + ((ey - o.lastMouseY) * (o.vmode ? 1 :-1));

			if (o.xMapper)		nx = o.xMapper(y)
			else if (o.yMapper)	ny = o.yMapper(x)

			Drag.obj.root.style[o.hmode ? "left" :"right"] = nx + "px";
			Drag.obj.root.style[o.vmode ? "top" :"bottom"] = ny + "px";
			Drag.obj.lastMouseX	= ex;
			Drag.obj.lastMouseY	= ey;

			Drag.obj.root.onDrag(nx, ny);
			return false;
		},
		end :function(){
			document.onmousemove = null;
			document.onmouseup   = null;
			Drag.obj.root.onDragEnd(	parseInt(Drag.obj.root.style[Drag.obj.hmode ? "left" :"right"]), 
										parseInt(Drag.obj.root.style[Drag.obj.vmode ? "top" :"bottom"]));
			Drag.obj = null;
		},
		fixE :function(e){
			if (typeof e == 'undefined') e = window.event;
			if (typeof e.layerX == 'undefined') e.layerX = e.offsetX;
			if (typeof e.layerY == 'undefined') e.layerY = e.offsetY;
			return e;
		}
	};

	colors = <?
	$colors_text = "{";
	foreach($colors as $cat_name => $color_cat){
	$colors_text .= "$cat_name:[";
	foreach ($color_cat as $value){
	$colors_text .= "'$value',";
	}
	$colors_text = substr($colors_text, 0, strlen($colors_text) - 1);
	$colors_text .= "],";
	}
	$colors_text = substr($colors_text, 0, strlen($colors_text) - 1);
	$colors_text .= "}";
	echo $colors_text;
	?>;
	titles = <?=$title_array?>;
	type_array = [<? echo "'$type_array[0]'";for($i = 1;$i < $num_types;$i++) echo ", '$type_array[$i]'";?>];
	num_days = <?=$day_count?>;
	num_types = <?=$num_types?>;

	setup();
	document.getElementById('cell_0_<?=$first_topic?>').onmouseover();
	setTimeout("document.getElementById('cell_0_<?=$first_topic?>').onmouseup();", 1000);

	function setup(){
		root		= document.getElementById("root");
		topdiv		= document.getElementById("top");
		bottom		= document.getElementById("bottom");
		left		= document.getElementById("left");
		right		= document.getElementById("right");
		typediv		= document.getElementById("typediv");
		titlediv	= document.getElementById("titlediv");
		daydiv		= document.getElementById("daydiv");
		close		= document.getElementById("close");
		scrolldiv	= document.getElementById("scrolldiv");
		loading 	= document.getElementById("loading");
		visited		= new Array();
		max_days	= 10;
	    root.style.height = (num_types * 20 + 1) + "px";
		root.style.width = (num_days * 20 + 1) + "px";
	    titlediv.style.top = (100 + num_types * 20) + 'px';
		curr_day = "0000-00-00";
		digits = new Array();
		for (i = 0;i < 10;i++){
			digits[i] = new Image();
			digits[i].src = "images/digit" + i + ".gif";
		}
		window.onresize = getHeight;
		getHeight();
	}

	function handleKeyDown(e){
		if (document.all)
			alert(window.event.keyCode)
		else
			alert(e.which);
		return false;
	}

	function getHeight(){
		var height;
		if (window.innerHeight > 0)
			height = window.innerHeight;
		else if (document.body.offsetHeight > 0)
			height = document.body.offsetHeight;
		else
			height = 1000;
		
		left.style.height = height + "px";
		right.style.height = height + "px";
	}

	function handleMouseOver(cell, type, day, id){
		if (!busy){
			if (current != cell)
				document.getElementById(cell).style.backgroundColor = colors['dark'][getj(cell)];
			select(cell, type, day);
		}
	}

	function handleMouseOut(cell, color){
		if (!busy){
			if (current != cell && visited[cell] != true)
				document.getElementById(cell).style.backgroundColor = color;
			unselect();
		}
	}

	function handleMouseDown(target, color){
		if (current != target)
			document.getElementById(target).style.backgroundColor = color;
	}

	function handleMouseUp(cell, id){
		if (current != cell){
			document.getElementById(cell).style.backgroundColor = colors['dark'][getj(cell)];
			if (!busy){
				if (current == 'none')
					openCell(cell, id);
				else
					closeCell(current, 'openCell("' + cell + '", ' + id + ');');
			}
		}
	}

	function select(target, type, day){
		var t = document.getElementById(target);
		var tx = getx(target);
		var ty = gety(target);
		
		if (type != null) {
		    // 确保 typediv 存在
		    var typediv = document.getElementById('typediv');
		    if (typediv) {
		        // 确保 typediv 有子节点
		        if (typediv.firstChild) {
		            typediv.removeChild(typediv.firstChild);
		        }
		        typediv.appendChild(document.createTextNode(type_array[type]));
		        typediv.style.top = ty + "px";
		    }

		    // 确保 titlediv 存在
		    var titlediv = document.getElementById('titlediv');
		    if (titlediv) {
		        // 清空 titlediv 的所有子节点
		        while (titlediv.firstChild) {
		            titlediv.removeChild(titlediv.firstChild);
		        }

		        var titles_arr = titles[target];
		        if (titles_arr) {
		            for (var i = 0; i < titles_arr.length; i++) {
		                titlediv.appendChild(document.createTextNode(titles_arr[i]));
		                titlediv.appendChild(document.createElement('br'));
		            }
		        }
		    }
		}
		
		if (day != null){
			if (day != curr_day){
				if (day.substr(0, 1) != curr_day.substr(0, 1))
					document.images['yr1'].src = digits[day.substr(0, 1)].src;
				if (day.substr(1, 1) != curr_day.substr(1, 1))
					document.images['yr2'].src = digits[day.substr(1, 1)].src;
				if (day.substr(2, 1) != curr_day.substr(2, 1))
					document.images['yr3'].src = digits[day.substr(2, 1)].src;
				if (day.substr(3, 1) != curr_day.substr(3, 1))
					document.images['yr4'].src = digits[day.substr(3, 1)].src;
				if (day.substr(5, 1) != curr_day.substr(5, 1))
					document.images['mo1'].src = digits[day.substr(5, 1)].src;
				if (day.substr(6, 1) != curr_day.substr(6, 1))
					document.images['mo2'].src = digits[day.substr(6, 1)].src;
				if (day.substr(8, 1) != curr_day.substr(8, 1))
					document.images['dy1'].src = digits[day.substr(8, 1)].src;
				if (day.substr(9, 1) != curr_day.substr(9, 1))
					document.images['dy2'].src = digits[day.substr(9, 1)].src;
				curr_day = day;
			}
			daydiv.style.left = (getx(target) + 7) + "px";
			daydiv.style.top = "10px";
		}

		topdiv.style.borderColor = colors['light'][getj(target)];
		left.style.borderColor = colors['light'][getj(target)];
		bottom.style.borderColor = colors['light'][getj(target)];
		right.style.borderColor = colors['light'][getj(target)];
		titlediv.style.color =  colors['dark'][getj(target)];
		typediv.style.color =  colors['dark'][getj(target)];
		topdiv.style.top = (ty - 1) + "px";
		left.style.left = (tx - 1) + "px";
		bottom.style.top = (ty + parseInt(t.style.height)) + "px";
		right.style.left = (tx + parseInt(t.style.width)) + "px";
		
		if (current != "none")
			titlediv.style.top = (80 + num_types * 20 + parseInt(document.getElementById(current).style.height)) + "px";
		else
			titlediv.style.top = (100 + num_types * 20) + 'px';
		titlediv.style.left = right.style.left;
	}

	function unselect(){
		typediv.style.top = "-100px";
		titlediv.style.top = "-100px";
		daydiv.style.top = "-100px";	
		topdiv.style.top = "-1px";
		bottom.style.top = "-1px";
		left.style.left = "-1px";
		right.style.left = "-1px";
	}

	function openCell(target, id){
		var t = document.getElementById(target);
		
		if (current != target){
			if (visited[target] != true)
			parent.frames[0].location = "load.php?id=" + id + "&cell=" + target;
			busy = true;
			curr_type = getj(target);
			current = target;
			select(current, curr_type, curr_day);
			t.style.width = "19px";
			t.style.height = "19px";
			t.style.cursor = "default";
			openCell(target, id);
		}
		else if (parseInt(t.style.width) < 259){
			var col = document.getElementById("col_" + geti(target));
			t.style.width = (parseInt(t.style.width) + 40) + "px";
			right.style.left = (getx(target) + parseInt(t.style.width)) + "px";
			titlediv.style.left = right.style.left;
			root.style.width = (parseInt(t.style.width) + (num_days - 1) * 20 + 2) + "px";
			col.style.left = (parseInt(t.style.width) + 1) + "px";
			setTimeout("openCell('" + target + "', " + id + ")", 20);
		}
		else if (parseInt(t.style.height) < 259){
			var row = document.getElementById("row_" + geti(target) + "_" + getj(target));
			t.style.height = (parseInt(t.style.height) + 40) + "px";
			bottom.style.top = (gety(target) + parseInt(t.style.height)) + "px";
			titlediv.style.top = (80 + num_types * 20 + parseInt(t.style.height)) + "px";
			root.style.height = (parseInt(t.style.height) + (num_types - 1) * 20 + 2) + "px";
			row.style.top = (parseInt(t.style.height) + 1) + "px";
			for(i = 0;i < num_days;i++)
				if (i != geti(target) && document.getElementById("row_" + i + "_" + getj(target)))
					document.getElementById("row_" + i + "_" + getj(target)).style.top = (parseInt(t.style.height) + 1) + "px";
			setTimeout("openCell('" + target + "', " + id + ")", 20);
		}
		else if (close.style.top != (gety(target) + 5) + "px")	{
			close.style.top = (gety(target) + 5) + "px";
			close.style.left = (getx(target) + parseInt(t.style.width) - 16) + "px";
			if (visited[target] != true)
				showLoading(target, id);
			else
				openCell(target, id);
		}
		else{
			loading.style.visibility = "hidden";
			t.firstChild.style.visibility = "visible";
			var contentHeight = t.firstChild.offsetHeight;
			var barHeight = (200 / contentHeight) * 225;
			
			if (contentHeight > 250){
				scrolldiv.style.left = (getx(target) + 243) + "px";
				scrolldiv.style.top = (gety(target) + 20) + "px";
				scrolldiv.style.visibility = "visible";
				scrolldiv.style.height = barHeight;
				t.firstChild.style.top = "20px";
				Drag.init(scrolldiv, null, (getx(target) + 243), (getx(target) + 243), (gety(target) + 20), (gety(target) + 255 - barHeight));
				scrollTravel = scrolldiv.maxY - scrolldiv.minY;
				ratio = (t.firstChild.offsetHeight - 235) / scrollTravel;
				scrolldiv.onDrag = function(x, y) {
				scrolldiv.style.backgroundColor = "#FFF";
				t.firstChild.style.top = (20 - Math.round((y - scrolldiv.minY) * ratio)) + "px";
				}
				scrolldiv.onDragStart = function() {
					close_click = false;
					scrolldiv.style.backgroundColor = "#FFF";
				}
				scrolldiv.onDragEnd = function() {
					scrolldiv.style.backgroundColor = "";
				}
			}
			busy = false;
			unselect();
			visited[target] = true;
		}
	}

	function closeCell(target, callback){
		var t = document.getElementById(target);
		if (busy == false)	{
			busy = true;
			if (callback != "")
				unselect();
			close.style.top = "-100px";
			t.firstChild.style.visibility = "hidden";
			scrolldiv.style.visibility = "hidden";
			closeCell(target, callback);
		}
		else if (parseInt(t.style.height) > 20){
			var row = document.getElementById("row_" + geti(target) + "_" + getj(target));
			t.style.height = (parseInt(t.style.height) - 40) + "px";
			if (callback == "")	{
				bottom.style.top = (gety(target) + parseInt(t.style.height)) + "px";
				titlediv.style.top = (80 + num_types * 20 + parseInt(t.style.height)) + "px";
			}
			row.style.top = (parseInt(t.style.height) + 1) + "px";
			root.style.height = (parseInt(t.style.height) + (num_types - 1) * 20 + 2) + "px";
			for(i = 0;i < num_days;i++)
				if (i != geti(target) && document.getElementById("row_" + i + "_" + getj(target)))
					document.getElementById("row_" + i + "_" + getj(target)).style.top = (parseInt(t.style.height) + 1) + "px";
			setTimeout("closeCell('" + target + "', '" + callback + "')", 20);
		}
		else if (parseInt(t.style.width) > 20)	{
			
			var col = document.getElementById("col_" + geti(target));
			t.style.width = (parseInt(t.style.width) - 40) + "px";
			if (callback == "")	{
				right.style.left = (getx(target) + parseInt(t.style.width)) + "px";
				titlediv.style.left = right.style.left;
			}
			col.style.left = (parseInt(t.style.width) + 1) + "px";
			root.style.width = (parseInt(t.style.width) + (num_days - 1) * 20 + 2) + "px";
			setTimeout("closeCell('" + target + "', '" + callback + "')", 20);
		}
		else{
			t.style.cursor = "pointer";
			if (callback == "")	{
				busy = false;
				unselect();
				current = "none";
			}
			else
				setTimeout("eval('" + callback + "');", 40);// Used in the case when clicking from one cell to another
		}
	}

	// Returns the date index
	function geti(target){
		var arr = target.split("_");
		return arr[1];
	}

	// Returns the topic index
	function getj(target){
		var arr = target.split("_");
		return arr[2];
	}

	function getx(target){
		var x = 100 + 20 * parseInt(geti(target));
		if (current != "none" && parseInt(geti(target)) > parseInt(geti(current)))
			x += 240;
		//if (document.getElementById("debug").checked)
		//alert(parseInt(geti(target)) > parseInt(geti(current)));
		return x;
	}

	function gety(target){
		var y = 100 + 20 * parseInt(getj(target));
		if (current != "none" && parseInt(getj(current)) < parseInt(getj(target)))
			y += 240;
		return y;
	}

	function showLoading(target, id){
		if (document.getElementById(target).childNodes.length == 0)
		{
			loading.style.left = getx(target) + "px";
			loading.style.top = gety(target) + "px";
			loading.style.visibility = "visible";
			setTimeout("showLoading('" + target + "', " + id + ")",  250);
		}
		else
			openCell(target, id);
	}
</script>
</body>
</html>