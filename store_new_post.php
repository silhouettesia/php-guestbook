<?php
include ('include_fns.php');
session_start();
$admin = isset($_SESSION['admin']) ? 1 : 0;
$page = $_SESSION['page'];
$parent = $_POST['parent'];
if (empty($_POST['poster']) || empty($_POST['message'])) {
	echo "<div id=\"left\">";
	display_list_and_nav($page);
	echo "</div><div id=\"right\">";
	$nameerr = $_POST['poster'];
	$msgerr = $_POST['message'];
	if (empty($nameerr)) {
		$nameerr = "what's ur name ?";
	}
	if (empty($msgerr)) {
		$msgerr = 'Say Something ?';
	}
	display_new_post_form($parent, $admin, $nameerr, $msgerr);
} else {
	$id = store_new_post($_POST);
	echo "<div id=\"left\">";
	display_list_and_nav($page);
	echo "</div><div id=\"right\">";
	if (is_array($id)) {	//return err
		echo $id['content'];
		if ($id['code'] == -2) {
			if ($parent) {
				echo "<input type=\"button\" value=\"BACK\" id=\"errbtn\" onClick=\"javascript:viewPost(".$parent.");\">";
			} else {
				echo "<input type=\"button\" value=\"VIEW\" id=\"errbtn\" onClick=\"javascript:viewPost(".$id['param'].");\">";
			}
		} else {
			if ($parent == 0) {
			?>
			<input type="button" value="BACK" id="errbtn"  onClick = "javascript:newPostForm(0);">
			<?php
			} else {
			?>
			<input type="button" value="BACK" id="errbtn" onClick="javascript:viewPost(<?php echo $parent;?>);">
			<?php
			//comment_mail_notify($parent, $_POST['poster'], $_POST['msg_textarea']);
			}
		}
	} else {	//store succuss
		$post = get_post($id);
		if ($parent == 0) {
			display_post($post, $admin);
		} else {
			$parentpost = get_post($parent);
			display_post($parentpost, $admin);
		}
	}
}
echo "</div>";
display_wrap_footer();
?>