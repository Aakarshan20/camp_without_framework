<?php

//include('./views/index.html');

if(isset($_GET['did'])){
$did =  $_GET['did'];
}else{
	$did = "did not found";
}

$mid = isset($_GET['mid'])?$_GET['mid']:"mid not found ";


$host = "sql211.byethost14.com";
$user = "b14_20045506";
$password = "camp168ing";
$db = "b14_20045506_camp";
$conn = mysqli_connect($host,$user,$password);
/*
echo "connect";
if($conn){
	echo " :success!<br/>";
}else{
	echo " :fail!<br/>";
}
*/

$sql = "use b14_20045506_camp";
sqlexe($conn,$sql);
$sql = "set names utf8";
sqlexe($conn,$sql);


function sqlexe($conn,$sql){
	$rs = mysqli_query($conn,$sql);
	/*
	echo $sql;
	if($rs){
		echo " :success!<br/>";
	}else{
		echo " :fail!<br/>";
	}
	*/
	return $rs;
}
$sql = "select * from messages";

$rs=sqlexe($conn,$sql);


function getAll($rs){
	$datas = array();
	while($rows = mysqli_fetch_assoc($rs)){
		$datas[]=$rows;
	}
	return $datas;
}


$datas = getAll($rs);
//echo "~~";
//print_r($datas);
//$mid=48;

//$mid = isset($mid)?$mid:0;

function getMsg($conn,$mid){
	$sql = "select content,title from messages where id=" . $mid;
	$rs = sqlexe($conn,$sql);
	return getAll($rs)[0];
	
}
$singleMsg=array();
$singleMsg = getMsg($conn,$mid);

if(!$singleMsg){
	$singleMsg['title'] = "title not found";
	$singleMsg['content'] = "content not found";
}


?>
<html>
<head>
<title><?php echo $singleMsg['title']?></title>
</head>
<body>
<center>
<table border="1px">
<tr>
	
	<th>裝置id</th><td><?php echo $did?></td>
</tr>
<tr>
	<th>訊息id</th><td><?php echo $mid?></td>
</tr>
<tr>
	<th>標題</th><td><?php echo $singleMsg['title']?></td>
</tr>
<tr>
	<th>內容</th><td><?php echo $singleMsg['content']?></td>
</tr>
</table>
</center>
</body>
</html>

