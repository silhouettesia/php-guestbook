<?php
include ('include_fns.php');
session_start();
$admin = isset($_SESSION['admin']) ? 1 : 0;
if ($_POST['postid']) {
	$post = get_post($_POST['postid']);
	display_post($post, $admin);
}
?>