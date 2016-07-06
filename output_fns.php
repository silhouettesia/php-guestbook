<?php
function guestbook_title($title='没 有 派 对') {
  echo $title;
}

function get_header() {
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php guestbook_title(); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	<base target="_blank" />
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="stylesheet" type="text/css" href="loaders.min.css">
	<script src="js/ajax_fns.js" type="text/javascript"></script>
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<script src="js/jquery.nicescroll.js" type="text/javascript"></script>
</head>
<body>
	<div id="wrap">
<?php
}

function get_footer() {
	display_wrap_footer();
?>
	</div>
</body>
</html>
<?php
}

function display_wrap_footer() {
?>
	<p style="position: absolute; bottom:5px; right: 15px; text-shadow: #999 4px 4px 3px; color: #666"><?php guestbook_title();?></p>
<?php
}

function display_list_and_nav($page = 1) {
	$end = display_post_list($page);
	display_page_nav($page, $end);
}

function display_post_list($page = 1) {
	$list = get_posts($page);
	if ($list !== false) {
		$end = $list['end'];
		$list = array_slice($list, 0, count($list) - 1);
		echo "<ul id=\"post_list\">";
		if (count($list) > 0) {
			foreach ($list as $post) {
				echo "<li onClick=\"javascript:viewPost(".$post['postid'].")\"";
				echo "><img src='img/";
				if ($post['child'] == 0) echo "no_";
				echo "child.png' width=\"14px\">&nbsp;";
				$time = explode(" ", $post['posttime']);
				$time = $time[0];
				echo $time."&nbsp;&raquo;&nbsp;".$post['poster'];
				echo "</li>";
			}
		} else {
				echo "<li style=\"text-align:center\">No message.</li>";
		}
		echo "</ul>";
	}
	if ($end) {
		return  $end;
	}
}

function display_page_nav($page_current, $end) {
?>
	<div id="page_nav">
		<span id="page_set_1">
		<input type="image" src="img/refresh.png" class="jpbtn" onclick="javascript:pageJump(<?php echo $page_current; ?>)">
		<?php
		if ($page_current == 1) {
			echo "<input type=\"image\" src=\"img/start0.png\">";
			echo "<input type=\"image\" src=\"img/pre0.png\">";
		} else {
			echo "<input type=\"image\" src=\"img/start.png\" class=\"jpbtn\" onClick=\"javascript:pageJump(1);\">";
			echo "<input type=\"image\" src=\"img/pre.png\" class=\"jpbtn\" onClick=\"javascript:pageJump($page_current - 1);\">";
		}
		if ($page_current == $end) {
			echo "<input type=\"image\" src=\"img/next0.png\">";
			echo "<input type=\"image\" src=\"img/end0.png\">";
		} else {
			echo "<input type=\"image\" src=\"img/next.png\" class=\"jpbtn\" onClick=\"javascript:pageJump($page_current + 1);\">";
			echo "<input type=\"image\" src=\"img/end.png\" class=\"jpbtn\" onClick=\"javascript:pageJump($end);\">";
		}
		?>
		</span>
		<span id="page_set_2">go to :<input type="text" size="2" id="page_jp" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"><input type="image" src="img/go.png" class="jpbtn" onClick="javascript:pageJump(page_jp.value,<?php echo $end?>)" style="vertical-align: bottom; margin-left: 4px;"></span>
		<span id="page_set_3"><?php echo $page_current."/".$end; ?></span>
	</div>	
<?php
}

function display_new_post_form($parent = 0, $admin = false , $nameerr = "", $msgerr = "") {
?>
	<form id="post_form">
		<table id="post_form_table">
			<tr>
		<?php
		if ($admin) {
			$user = $_SESSION['admin'];
			$admininfo = get_admin_info($user);
			echo "<td>Logged as :</td><td align=\"left\">".$user."</td><input type=\"hidden\" name=\"poster\" value=\"$user\">";
		} else {?>
				<td width="50">Name:</td>
				<td><input type="text" name="poster" id="poster" size="20" maxlength="20"  required="required" value="<?php echo $nameerr ?>"></td>
		<?php }
		?>
			</tr>
			<tr>
				<td>Email:</td>
				<td><input type="text" name="email" id="email" size="20" maxlength="100" value="<?php echo @$admininfo['email'];?>"></td>
			</tr>
			<tr>
				<td>Url:</td>
				<td><input type="text" name="url" id="url" size="20" maxlength="255" value="<?php echo @$admininfo['url'];?>"></td>
			</tr>
		</table>
		<textarea name="message" id="msg_textarea" required="required"><?php echo $msgerr ?></textarea>
		<input type="hidden" name="parent" value="<?php echo $parent; ?>">
		<input type="hidden" name="admin" value="<?php echo $admin;?>">
		<?php
		if ($parent) {
		?>
		<input type="button" name="back" value="CANCEL" id="back" onClick="javascript:viewPost(<?php echo $parent;?>);">
		<?php
		} else {
		?>
		<input type="reset" name="reset" value="CANCEL" id="back">
		<?php
		}
		?>		
		<input type="button" name="submit" value="POST" id="submit" onClick = "javascript:addNewPost();">
	</form>
<?php
}

function display_post($post, $admin) {
	if (!$post) {
		return false;
	}
?>
	<table id="post_table">
		<tr>
			<td style="width: 55px; height:55px;">
				<?php display_avatar($post['email'])?>
			</td>
			<td>
				<p id="posttime"><?php echo $post['posttime']; ?></p>
				<p id="poster">
				<?php
					if ($post['url']) {
						echo "<a href=\"".$post['url']."\">";
					}
					if ($post['admin']) {
						echo "<b><strong>";
					}
					echo $post['poster'];
					if ($post['admin']) {
						echo "</b></strong>";
					}
					if ($post['url']) {
					 	echo "</a>";
					 }
				?>
				</p>
			</td>
		</tr>
	</table>
	<div id="message">
		<?php echo nl2br($post['message']); ?>
		<?php 
		if ($post['child']) {
			display_replies($post['postid'], $admin);
		}		
		?>
	</div>
	<?php
	if ($admin) {
		echo "<input type=\"button\" value=\"DELETE\" id=\"delete\" onClick=\"deletePost(".$post['postid'].")\">";
	}
	?>
	<input type="button" value="BACK" id="back" onClick="javascript:newPostForm(0)">
	<input type="button" value="REPLY" id="submit" onClick="javascript:newPostForm(<?php echo $post['postid'] ?>)">
<?php
}

function display_replies($postid, $admin) {
	echo "<div id=\"reply\">";
	$list = get_reply($postid);
	if (!$list) {
		return false;
	}
	echo "<ul id=\"reply_list\">";
	foreach ($list as $re) {
		echo "<li><table style=\"width: 100%;\"><tr><td style=\"width: 32px;\">";
		display_avatar($re['email'], true);
		echo "</td><td style=\"vertical-align: bottom;\"><p>".$re['posttime']."</p><p>";
		if ($re['url']) {
			echo "<a href=\"".$re['url']."\">";
		}
		if ($re['admin']) {
			echo "<b><strong>";
		}
		echo $re['poster'];
		if ($re['admin']) {
			echo "</b></strong>";
		}
		if ($re['url']) {
			echo "</a>";
		}
		echo "</p></td></tr></table>";
		echo nl2br($re['message'])."<br>";
		if ($admin) {
			echo "<p class=\"delete\" onClick=\"deletePost(".$re['postid'].")\">[ delete ]</p></li>";
		}		
	}
	echo "</ul></div>";
}

function display_avatar($email, $re = false) {
	$re ? $size = 30 : $size = 40;
	if ($email) {
		$default = 'http://kiwisia.ml/avatar';
		$re ? $default .= '-s.png' : $default .= '-m.png';
		$url = 'https://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$size&d=$default";
		echo "<img src=\"$url\" id=\"avatar";
		if ($re) echo "-s\">";
		else echo "\" width=40>";
	} else {
	if ($re) echo "<img src=\"img/avatar-s.png\" id=\"avatar-s\">";
	else echo "<img src=\"img/avatar.png\" style=\"vertical-align:middle\">";
	}
}

?>