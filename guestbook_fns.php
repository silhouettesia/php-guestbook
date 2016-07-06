<?php
function store_new_post($post) {
	$rtn = array();
	if (isset($post['url'])) {
		if (!empty($post['url'])) {
			if (strstr($post['url'], 'http://') === false) {
				$post['url'] = 'http://'.$post['url'];
			}
		}		
	}	
	$post = clean_all($post);

	$conn = db_connect();
	if (!$conn) {
		return array('code' => -1, 'content'=>"<p class=\"err\">Oops, database cannot be connected.</p>");
	}

	//check parent exists
	if($post['parent']!=0)   {
		$query = "select postid from header where postid = '".$post['parent']."'";
		$result = $conn->query($query);
		if (!$result) {
			return array('code' => -1, 'content' =>"<p class=\"err\">Oops, Select failed.(0)</p>");
		}
		if($result->num_rows!=1)  {
			return array('code' => -1, 'content' =>"<p class=\"err\">Parent post does not exsit.</p>");
		}
	}

	$query = "select header.postid from header, body where
		header.postid = body.postid and
		header.poster = '".$post['poster']."' and
		header.parent = ".$post['parent']." and
		body.message = '".$post['message']."'";
	$result = $conn->query($query);
	if (!$result) {
		return array('code' => -1, 'content' =>"<p class=\"err\">Oops, Select failed.(1)</p>");
	}
	if($result->num_rows>0) {
		$this_row = $result->fetch_row();
		return array('code' => -2, 'content' =>"<p class=\"err\">Oops, Post exists already.</p>", 'param' => $this_row[0]);
		//show the existed message
		//return $this_row[0];
	}

	$query = "insert into header values
		(NULL,
		'".$post['poster']."',
		now(),
		'".$post['parent']."',
		0,
		'".$post['email']."',
		'".$post['url']."',
		".$post['admin']."
		)";

	$result = $conn->query($query);
	if (!$result) {
		return array('code' => -1, 'content' =>"<p class=\"err\">Oops, Insert data into header failed.</p>");
	}

	// note that our parent now has a child
	if ($post['parent'] != 0) {
		$query = "update header set child = 1 where postid = '".$post['parent']."'";
		$result = $conn->query($query);
		if (!$result) {
			return array('code' => -1, 'content' =>"<p class=\"err\">Oops, Set parent's child failed.</p>");
		}
	}
	
	// find our post id, note that there could be multiple headers
	// that are the same except for id and probably posted time
	$query = "select header.postid from header left join body on header.postid = body.postid
		where parent = '".$post['parent']."'
		and poster = '".$post['poster']."'
		and body.postid is NULL";
	/*just for getting the new id*/
	$result = $conn->query($query);
	if (!$result)  {
		return array('code' => -1, 'content' =>"<p class=\"err\">Oops, Select failed.(3)</p>");
	}
	if($result->num_rows>0) {
		$this_row = $result->fetch_array();
		$id = $this_row[0];
	}
	if($id) {
		$query = "insert into body values ($id, '".$post['message']."')";
		$result = $conn->query($query);
		if (!$result) {
			return array('code' => -1, 'content' =>"<p class=\"err\">Oops, Insert data into body failed.</p>");
		}
		return $id;
	}
}

function get_posts($idx) {
	$offset = 15;
	$post_list = array();
	$conn = db_connect();
	if (!$conn) {
		echo "<p class=\"err\">Oops, database cannot be connected.</p>";
		return false;
	}
	$query = "select postid, poster, posttime, child from header where parent = 0 order by postid desc" ;
	$result = $conn->query($query);
	if (!$result) {
		echo "<p class=\"err\">Oops, Select failed.(4)</p>";
		return false;
	}
	$num = $result->num_rows;
	if ($num > 0) {
		for ($i=0; $i < $num; $i++) { 
			$this_post = $result->fetch_assoc();
			array_push($post_list, $this_post);
		}
		$pages = ceil($num / $offset);
		if (($idx < $pages) && ($idx > 0)) {
			$post_list = array_slice($post_list, $offset*($idx - 1), $offset);
		} elseif ($idx == $pages) {
			$post_list = array_slice($post_list, $offset*($idx - 1));
		} else {
			$post_list = array_slice($post_list, 0, $offset);
		}
		$post_list['end'] = $pages;
		return $post_list;
	}
	$post_list['end'] = 1;
	return $post_list;
}

function get_post($postid) {
	if ($postid<=0) {
		return false;
	}
	$conn = db_connect();
	if (!$conn) {
		echo "<p class=\"err\">Oops, database cannot be connected.</p>";
		return false;
	}
	$query = "select * from header where postid='".$postid."'";
	$result = $conn->query($query);
	if (!$result) {
		echo "<p class=\"err\">Oops, Select failed.</p>";
		return false;
	}
	if ($result->num_rows != 1) {
		return false;
	}
	$post = $result->fetch_assoc();
	$query = "select * from body where postid='".$postid."'";
	$result2 = $conn->query($query);
	if ($result2->num_rows > 0) {
		$body = $result2->fetch_assoc();
		if ($body) {
			$post['message'] = $body['message'];
		}
	}
	return $post;
}

function get_reply($parent) {
	if ($parent == 0) {
		echo "<p class=\"err\">Oops: No reply.</p>";
		return false;
	}
	$re_list = array();
	$conn = db_connect();
	if (!$conn) {
		echo "<p class=\"err\">Oops, database cannot be connected.</p>";
		return false;
	}
	$query = "select *  from header inner join body using(postid) where parent='".$parent."'";
	$result = $conn->query($query);
	if(!$result) {
		echo "<p class=\"err\">Oops: Select failed.</p>";
		return false;
	}
	$num = $result->num_rows;
	if ($num > 0) {
		echo "<p>$num Repl";
		$num>1?print("ies"):print("y");
		echo "</p>";
		for ($i=0; $i < $num; $i++) { 
			$this_row = $result->fetch_assoc();
			array_push($re_list, $this_row);
		}
	}	
	return $re_list;
}

function comment_mail_notify($parent, $poster, $msg) {
	$conn = db_connect();
	if (!$conn) {
		echo "<p class=\"err\">Oops, database cannot be connected.</p>";
		return false;
	}
	$query = "select email from header where postid = ".$parent ;
	$result = $conn->query($query);
	if (!$result) {
		echo "<p class=\"err\">Oops, Select failed.</p>";
		return false;
	}
	if ($result->num_rows > 0) {
		$to = $result->fetch_row;
		$to = $to[0];
		$subject = "您在[没 有 派 对]的留言有了回复";
		$message = "$poster给您的回复:<br><p>$msg</p><p><a href=\"".$_SERVER['PHP_SELF']."\">戳我继续回复</a></p><p>(此邮件由系统自动发送，请勿回复.)</p>";
		$from = "admin@missia.webatu.com";
		$headers = "From: $from";
		mail($to,$subject,$message,$headers);
	}
}

function get_admin_info($user) {
	$conn = db_connect();
	if (!$conn) {
		echo "<p class=\"err\">Oops, database cannot be connected.</p>";
		return false;
	}
	$query = "select email, url from admin where user = '$user'" ;
	$result = $conn->query($query);
	if (!$result) {
		echo "<p class=\"err\">Oops, Select failed.</p>";
		return false;
	}
	if ($result->num_rows) {
		return $result->fetch_assoc();
	}
}
?>