<?php
include ('include_fns.php');
session_start();
if (isset($_POST['user']) && isset($_POST['passwd'])) {
	$user = $_POST['user'];
	$passwd = $_POST['passwd'];
	$conn = db_connect();
	if (!$conn) {
		echo "<p class=\"err\">Oops, database cannot be connected.</p>";
		return false;
	}
	$query = "select * from admin where user = '".$user."' and passwd=sha1('".$passwd."')";
	$result = $conn->query($query);
	if (!$result) {
		echo "<p class=\"err\">Oops, Select failed.</p>";
		return false;
	}
	if ($result->num_rows) {
		$_SESSION['admin'] = $user;
	} else {
		$err = "user or password incorrect";
	}
}
if (isset($_GET['logout'])) {
	unset($_SESSION['admin']);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<?php
if (isset($_SESSION['admin'])) {
	echo "<div class=\"center\"><div class=\"logged\">";
	echo "You are logged in as ".$_SESSION['admin'].'<br>';
	echo "<a href='index.php'>Manage</a> / ";
	echo "<a href='admin.php?logout=1'>Log out</a>";
	echo "</div></div>";
} else {
?>
<div class="center">
<form method="post" action="admin.php" class="logform">
	<fieldset>
	<div class = "loghead">
		<span>Log In</span>
	</div>
	<div class = "logbox">
		<div class = "logholder">
			<div class = "txt-field">
				<input type="text" name="user" placeholder="Username" class="loginput">
			</div>
			<div class = "txt-field">
				<input type="password" name="passwd" placeholder="Password" class="loginput">
			</div>
		</div>
		<input type="submit" value="LOG IN" class="btnlogin">
	</div>
	<?php
	if (@$err) {
		echo "<p align=\"center\">$err</p>";
	}
	?>
	</fieldset>
</form>
</div>
<?php
}
?>
</body>
</html>
