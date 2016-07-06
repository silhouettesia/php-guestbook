<?php
include ('include_fns.php');
session_start();
if ($_POST['page']) {
	$_SESSION['page'] = $_POST['page'];
	display_list_and_nav($_POST['page']);
}
?>