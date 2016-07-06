var reqAdd;
var reqView;
var reqRe;
var reqForm;

function getXMLHTTPRequest() {
	var req = null;
	try 	{
		// Firefox, Opera 8.0+, Safari
		req = new XMLHttpRequest();
	} catch (e) {
		// Internet Explorer
		try {
			req = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			req = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return req;
}

function addNewPost() {
	reqAdd = getXMLHTTPRequest()
	if (reqAdd == null) {
		alert("Browser does not support HTTP Request");
	}
	var url = "store_new_post.php";
	var form = document.getElementById('post_form');
	var params = "poster=" + escape(form.elements['poster'].value) + "&email=" + escape(form.elements['email'].value) + "&url=" + escape(form.elements['url'].value) + "&message=" + escape(form.elements['msg_textarea'].value) + "&parent=" + escape(form.elements['parent'].value) + "&admin=" + escape(form.elements['admin'].value);
	reqAdd.open("POST", url, true);
	reqAdd.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	reqAdd.setRequestHeader("Content-length", params.length);
	reqAdd.setRequestHeader("Connection", "close");
	reqAdd.onreadystatechange = addPostResponse;
	reqAdd.send(params);
}

function addPostResponse() {
	if (reqAdd.readyState == 4) {
		if(reqAdd.status == 200) {
			if (reqAdd.responseText) {
				document.getElementById('wrap').innerHTML = reqAdd.responseText;
			}
		} else {
			alert('There was a problem with the request for add post.');
		}
	} else {
		document.getElementById('right').innerHTML="<div class=\"loader-inner ball-clip-rotate\" <div class=\"loader-inner ball-clip-rotate\" style=\"width: 100px;text-align: center;margin: 0 auto;\"><div></div>"
	}
}

function newPostForm(postid) {
	reqForm = getXMLHTTPRequest()
	if (reqForm == null) {
		alert("Browser does not support HTTP Request");
	}
	var url = "new_post.php";
	var params = "parent=" + postid;
	reqForm.open("POST", url, true);
	reqForm.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	reqForm.setRequestHeader("Content-length", params.length);
	reqForm.setRequestHeader("Connection", "close");
	reqForm.onreadystatechange = newFormResponse;
	reqForm.send(params);
}

function newFormResponse() {
	if (reqForm.readyState == 4) {
		if(reqForm.status == 200) {
			if (reqForm.responseText) {
				document.getElementById('right').innerHTML = reqForm.responseText;
			}
		} else {
		alert('There was a problem with the request for view msg.');
		}
	} else {
		document.getElementById('right').innerHTML="<div class=\"loader-inner ball-clip-rotate\" <div class=\"loader-inner ball-clip-rotate\" style=\"width: 100px;text-align: center;margin: 0 auto;\"><div></div>"
	}
}

function viewPost(postid) {
	reqView = getXMLHTTPRequest()
	if (reqView == null) {
		alert("Browser does not support HTTP Request");
	}
	var url = "view_post.php";
	var params = "postid=" + postid;
	reqView.open("POST", url, true);
	reqView.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	reqView.setRequestHeader("Content-length", params.length);
	reqView.setRequestHeader("Connection", "close");
	reqView.onreadystatechange = viewPostResponse;
	reqView.send(params);
}

function viewPostResponse() {
	if (reqView.readyState == 4) {
		if(reqView.status == 200) {
			if (reqView.responseText) {
				document.getElementById('right').innerHTML = reqView.responseText;
				var right;
				$(document).ready(	
				function() { 
				right = $("#message").niceScroll({cursorcolor:"#999",cursorborder:"0px solid #999",cursorborderradius:"5px"});
				}
				);
			}
		} else {
		alert('There was a problem with the request for view msg.');
		}
	} else {
		document.getElementById('right').innerHTML="<div class=\"loader-inner ball-clip-rotate\" <div class=\"loader-inner ball-clip-rotate\" style=\"width: 100px;text-align: center;margin: 0 auto;\"><div></div>"
	}
}

function pageJump(page, end) {
	if ((0<page && page<=end) || (arguments.length == 1)) {
		reqPage = getXMLHTTPRequest();
		if (reqPage == null) {
			alert("Browser does not support HTTP Request");
		}
		var url = "jump_page.php";
		var params = "page=" + page;
		reqPage.open("POST", url, true);
		reqPage.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		reqPage.setRequestHeader("Content-length", params.length);
		reqPage.setRequestHeader("Connection", "close");
		reqPage.onreadystatechange = jumpPageResponse;
		reqPage.send(params);
	};
}

function jumpPageResponse() {
	if (reqPage.readyState == 4) {
		if(reqPage.status == 200) {
			if (reqPage.responseText) {
				document.getElementById('left').innerHTML = reqPage.responseText;
			}
		} else {
		alert('There was a problem with the request for view msg.');
		}
	} else {
		document.getElementById('left').innerHTML="<div class=\"loader-inner ball-clip-rotate\" <div class=\"loader-inner ball-clip-rotate\" style=\"width: 100px;text-align: center;margin: 0 auto;\"><div></div>"
	}
}

function deletePost(postid) {
	reqDlt = getXMLHTTPRequest()
	if (reqDlt == null) {
		alert("Browser does not support HTTP Request");
	}
	var url = "delete_post.php";
	var params = "postid=" + postid;
	reqDlt.open("POST", url, true);
	reqDlt.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	reqDlt.setRequestHeader("Content-length", params.length);
	reqDlt.setRequestHeader("Connection", "close");
	reqDlt.onreadystatechange = dltPostResponse;
	reqDlt.send(params);
}

function dltPostResponse() {
	if (reqDlt.readyState == 4) {
		if(reqDlt.status == 200) {
			if (reqDlt.responseText) {
				document.getElementById('right').innerHTML = reqDlt.responseText;
			} else {
				alert('There was a problem with the request for view msg.');
			}
		} else {
			document.getElementById('right').innerHTML="<div class=\"loader-inner ball-clip-rotate\" <div class=\"loader-inner ball-clip-rotate\" style=\"width: 100px;text-align: center;margin: 0 auto;\"><div></div>"
		}
	}
}