<?php
include('include_fns.php');
session_start();
$admin = isset($_SESSION['admin']) ? 1 : 0;
$parent = isset($_POST['parent']) ? $_POST['parent'] : 0;
if (!isset($_SESSION['page'])) {
	$_SESSION['page'] = 1;
}
$page= $_SESSION['page'];
get_header();
?>
<div id="left">
	<?php display_list_and_nav($page); ?>
</div>
<div id="right">
	<?php display_new_post_form($parent, $admin) ?>
</div>
<?php
get_footer();
?>