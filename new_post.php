<?php
include('include_fns.php');
session_start();
$admin = isset($_SESSION['admin']) ? 1 : 0;
$parent = isset($_POST['parent']) ? $_POST['parent'] : 0;
display_new_post_form($parent, $admin);
?>