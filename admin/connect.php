<?php


define('ACC',true);
include("../include/mysql_connect.php");
include("../include/init.inc.php");

$rs = mysqli_query($conn,"show tables");

$tables = array();

while($rows = mysqli_fetch_assoc($rs)){
	$tables[]=$rows;
}

$id = empty($_POST['id'])?"":trim($_POST['id']);
$pw = empty($_POST['pw'])?"":trim($_POST['pw']);


$chPasswd = array_key_exists('new_pw',$_POST)?'true':'false';

$pw = md5($id . '1970' . $pw);

$sql = "select * from users where name='" . $_POST['id'] . "'";


$row = getOne($conn,$sql);


if($chPasswd=='true'){
	if($_POST['new_pw']==null && $_POST['re_new_pw']==null){
		
		$chPasswd='false';
			$bannerMsg = '密碼欄位不得為空 !<br/>';
			$buttonMsg ="回到管理頁面";
			
			// print_r(SID);
			include("../view/header_admin.html");
			
			include("../view/footer_admin.html");
	}else if($_POST['new_pw']==$_POST['re_new_pw']){//新密碼一致
		if($id != null && $pw != null && $row[1] == $id && $row[2] == $pw){//密碼存在
			$sql = "update users set passwd='" . md5($id . '1970' . $_POST['new_pw']) . "'";
			$sql .= " where name='" . $id ."'";
			$rs = mysqli_query($conn,$sql);
 			$_SESSION['username'] = $id;
			$bannerMsg = '更改密碼成功，下次登入請使用新密碼 ! 將會在5秒內自動跳轉頁面<br/>';
			$chPasswd='false';
			// include('./logout.php');
			include("../view/header_admin.html");
			
			include("../view/footer_admin.html");
			 echo '<meta http-equiv=REFRESH CONTENT=5;url=index.php?'.SID.'>';
		}else{
			$chPasswd='false';
			$bannerMsg = '密碼或帳號錯誤，密碼更改失敗 !<br/>';
			$buttonMsg ="回到管理頁面";
			
			// print_r(SID);
			include("../view/header_admin.html");
			
			include("../view/footer_admin.html");
		}
	}else{
		$chPasswd='false';
		$bannerMsg = '新密碼不一致，密碼更改失敗 !<br/>';
		$buttonMsg ="回到管理頁面";
			
		// print_r(SID);
		include("../view/header_admin.html");
		
		include("../view/footer_admin.html");
	}
	
}else{




	if($id != null && $pw != null && $row[1] == $id && $row[2] == $pw)
	{
			//將帳號寫入session，方便驗證使用者身份
			$_SESSION['username'] = $id;
			$bannerMsg = '登入成功 ! 將會在3秒內自動跳轉頁面<br/>';
			
			// print_r(SID);
			include("../view/header_admin.html");
			
			include("../view/footer_admin.html");
			 echo '<meta http-equiv=REFRESH CONTENT=2;url=index.php?'.SID.'>';
			// print_r($_SESSION);
	}
	else
	{	
			
			
			$bannerMsg = '登入失敗，帳號或密碼錯誤 !<br/>';
			$buttonMsg ="回到登入頁面";
			
			// print_r(SID);
			include("../view/header_admin.html");
			
			include("../view/footer_admin.html");
	}

}
?>