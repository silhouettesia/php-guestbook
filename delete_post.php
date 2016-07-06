<?php
include ('include_fns.php');
if (isset($_POST['postid'])) {
	$postid = $_POST['postid'];
	$conn = db_connect();
	if (!$conn) {
		echo "<p class=\"err\">Oops, database cannot be connected.</p>";
		return false;
	}
	$result = $conn->query("select child, parent from header where postid = $postid");
	if (!$result) {
		echo "<p class=\"err\">Oops, select failed.</p>";
		return false;
	}
	$rtn = $result->fetch_assoc();
	$child = $rtn['child'];
	$parent = $rtn['parent'];
	if ($child) {
		$result = $conn->query("select postid from header where parent = $postid");
		if (!$result) {
			echo "<p class=\"err\">Oops, select child failed.</p>";
			return false;
		}
		for ($i=0; $i < $result->num_rows; $i++) { 
			deletePost($conn, $result->fetch_row()[0]);
		}
	}
	if ($parent !== 0) {
		$result = $conn->query("select postid from header where parent = $parent");
		if (!$result) {
			echo "<p class=\"err\">Oops, select siblings failed.</p>";
			return false;
		}
		$sibling_num = $result->num_rows;
		if ($sibling_num == 1) {
			$result = $conn->query("update header set child = 0 where postid = $parent");
			if (!$result) {
				echo "<p class=\"err\">Oops, update failed.(4)</p>";
				return false;
			}
		}
	}
	deletePost($conn, $postid);
	echo "<p class=\"err\">Please refresh.</p>";
}

function deletePost($conn, $postid)
{
	$conn->query("BEGIN");
	$query1 = "delete from header where postid = ".$postid ;
	$query2 = "delete from body where postid = ".$postid ;
	$result1 = $conn->query($query1);
	$result2 = $conn->query($query2);
	if (!$result1 && !$result2) {
		$conn->query("ROLLBACK");
		echo "<p class=\"err\">Oops, delete failed.</p>";
	} else {
		$conn->query("COMMIT");
		echo "<p class=\"err\">message($postid) delete.<br></p>";
	}
}
?>