

<?php 

define('ACC',true);
include('../include/init.inc.php');


if(!isset($_SESSION['username'])){
	$login=true;
	$buttonMsg="送出";
	
	
	include("../view/header_admin.html");
	
	include("../view/footer_admin.html");
}else{


include('../include/config.ini.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');

// print_r($_POST);
$mode = '';
if(isset($_GET['m'])){
	if($_GET['m']=='area'){
		$mode='area';
		$_POST['available'] +=0;

		$_POST['price'] +=0;

	}else if($_GET['m']=='data'){
		$mode='data';
		$_POST['booked'] +=0;
	}
}


// exit();

//進出資料庫
$conn = mysqli_connect($_CFG['host'],$_CFG['user'],$_CFG['passwd']);
// echo "connect";
if($conn){
	// echo " :success!<br/>";
}else{
	// echo " :fail!<br/>";
}



function sqlexe($conn,$sql){
	$rs = mysqli_query($conn,$sql);
	if($rs){
		// echo $sql ." :success!<br/>";
	}else{
		// echo $sql ." :fail!<br/>";
	}
	return $rs;
}

function getAll($conn,$sql){
	$rs = sqlexe($conn,$sql);
	$data = array();
	while($rows = mysqli_fetch_assoc($rs)){
		$data[]=$rows;
	}
	return $data;
}


$sql = "use " . $_CFG['db'];

sqlexe($conn,$sql);

$sql = "set names utf8";

sqlexe($conn,$sql);


switch($mode){
	case "area":
	// print_r($_POST);


	if(!is_int($_POST['available']) || ($_POST['available']<=0) ||!is_int($_POST['price']) || ($_POST['price']<=0)){
		$msg = "編輯失敗，請輸入正確的數字";
		$areastatus='view';
		$sql ="select * from area order by aid";
		$datas = array();
		$datas = getAll($conn,$sql);
	}else{
		
		$sql = "update area set name = '" . $_POST['name'] . "',available=";
		$sql .= $_POST['available'] . ",price=" . $_POST['price'] . " where aid='" . $_POST['aid'] . "'";
		
		$rs = sqlexe($conn,$sql);
		if($rs){
			$msg = "編輯成功";
		}else{
			$msg = "編輯失敗";
		}
		$areastatus='view';
		$sql ="select * from area";
		$datas = array();
		$datas = getAll($conn,$sql);
	}
	break;
	case "data":	
	$sql = "select available from area where aid=" . $_POST['aid'];
	
	$available = getAll($conn,$sql)[0]['available'];
	
	$table = (!isset($_GET['table']) )?"orders":$_GET['table'];
	
	if(!is_numeric($_POST['year']) || ($_POST['year']<=0)||($_POST['year']>2100)){
		$msg = "編輯失敗，請輸入正確的年份: 您輸入了" . $_POST['year'];
		$status='view';
		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table .".createtime from  " . $table;
		$sql .= " left join area on area.aid=" . $table .".aid where parent_id=0 order by year,month,day," . $table .".aid";
		
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from " . $table . " where aid not in (select aid from area)";
		sqlexe($conn,$sql);
	}else if(!is_numeric($_POST['month']) || ($_POST['month']<=0) || ($_POST['month']>12)){
		$msg = "編輯失敗，請輸入正確的月份: 您輸入了" . $_POST['month'];
		$status='view';
		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table .".createtime from  " . $table;
		$sql .= " left join area on area.aid=" . $table .".aid where parent_id=0 order by year,month,day," . $table .".aid";
		
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from " . $table ." where aid not in (select aid from area)";
		sqlexe($conn,$sql);
		
	}else if(!is_numeric($_POST['day']) || ($_POST['day']<=0) || ($_POST['day']>31)){
		$msg = "編輯失敗，請輸入正確的日期: 您輸入了" . $_POST['day'];
		$status='view';
		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table .".createtime from  " . $table;
		$sql .= " left join area on area.aid=" . $table .".aid where parent_id=0 order by year,month,day," . $table .".aid";
		
		$datas = array();
		
		$datas = getAll($conn,$sql);
		$sql = "delete from " . $table ." where aid not in (select aid from area)";
		sqlexe($conn,$sql);
	}else if(!is_numeric($_POST['booked']) || ($_POST['booked']<=0)){
		$msg = "編輯失敗，請輸入正確的數字" . $_POST['booked'];
		$status='view';
		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table .".createtime from  " . $table;
		$sql .= " left join area on area.aid=" . $table .".aid  where parent_id=0 order by year,month,day," . $table .".aid";
		
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from " . $table ." where aid not in (select aid from area)";
		sqlexe($conn,$sql);
	}else if($_POST['day'] > cal_days_in_month(CAL_GREGORIAN, $_POST['month'], $_POST['year'])){
		$msg = "編輯失敗，請輸入正確的日期: 您輸入了" . 
		$_POST['year'] . "年 " . $_POST['month'] . "月 " . $_POST['day'] . "日";
		$status='view';
		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table .".createtime from  " . $table;
		$sql .= " left join area on area.aid=" . $table .".aid  where parent_id=0 order by year,month,day," . $table .".aid";
		
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from " . $table ." where aid not in (select aid from area)";
		sqlexe($conn,$sql);
	}else  if(empty($_POST['guest_name']) ){
		$msg = "編輯失敗，客戶名稱為空白";
		$status='view';
		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table .".createtime from " . $table;
		$sql .= " left join area on area.aid=" . $table .".aid  where parent_id=0 order by year,month,day," . $table .".aid";
		
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from " . $table ." where aid not in (select aid from area)";
		sqlexe($conn,$sql);
	}else if(empty($_POST['phone']) || !is_numeric($_POST['phone'])){
		$msg = "編輯失敗，客戶聯絡電話為空白/或非數字";
		$status='view';
		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table .".createtime from  " . $table;
		$sql .= " left join area on area.aid=" . $table .".aid  where parent_id=0 order by year,month,day," . $table .".aid";
		
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from " . $table ." where aid not in (select aid from area)";
		sqlexe($conn,$sql);
	}else if(empty($_POST['bank_account']) || !is_numeric($_POST['bank_account'])){
		$msg = "編輯失敗，銀行帳戶電話為空白/或非數字";
		$status='view';
		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table .".createtime from  " . $table;
		$sql .= " left join area on area.aid=" . $table .".aid  where parent_id=0 order by year,month,day," . $table .".aid";
		
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from " . $table ." where aid not in (select aid from area)";
		sqlexe($conn,$sql);
	}else if(empty($_POST['stayDays']) || !is_numeric($_POST['stayDays'])){
		$msg = "編輯失敗，預訂天數為空白/或非數字";
		$status='view';
		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table .".createtime from  " . $table;
		$sql .= " left join area on area.aid=" . $table .".aid  where parent_id=0 order by year,month,day," . $table .".aid";
		
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from " . $table ." where aid not in (select aid from area)";
		sqlexe($conn,$sql);
	}else if((($_POST['booked'])>$available) || empty($_POST['booked']) || !is_numeric(($_POST['booked'])) || !is_int(($_POST['booked']))){
		$msg = "編輯失敗，預訂位置";
		if($_POST['booked']>$available ||  $_POST['booked']<0){
			$msg.="數量錯誤(大於露營區數量)";
		}else if($_POST['booked']<0){
			$msg.="數量未填(數字為負數)";
		}else if(empty($_POST['booked'])){
			$msg.="數量未填";
		}else if( !is_numeric(($_POST['booked']))  ||  !is_int(($_POST['booked'])) ){
			$msg.="數量非整數數字";
			
		}
		
		//$msg = "編輯失敗，預訂位置數量為空白/數量錯誤或非數字";
		$status='view';
		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table .".createtime from  " . $table;
		$sql .= " left join area on area.aid=" . $table .".aid  where parent_id=0 order by year,month,day," . $table .".aid";
		// echo $sql;
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from " . $table . " where aid not in (select aid from area)";
		sqlexe($conn,$sql);
		
	}else{
			if($table=="orders"){
				$sql = "delete from " . $table ." where id =". $_POST['id'] . " or parent_id=" . $_POST['id'];
				
				sqlexe($conn,$sql);
				
				$sql = "insert into ". $table ." (year,month,day,aid,booked,guest_name,phone,bank_account,stayDays,status)values(".
				$_POST['year'].",".$_POST['month'].",".$_POST['day'].",".$_POST['aid'].",".
				$_POST['booked'].",'".$_POST['guest_name']."',".$_POST['phone'].",".$_POST['bank_account'].",".$_POST['stayDays'] ."," .$_POST['status'].")";
				
				$rs = sqlexe($conn,$sql);
				
				if($_POST['stayDays']>1){//多於一天開始循環添加
					$sql = "SELECT LAST_INSERT_ID() AS `id`";
					$last_id = getAll($conn,$sql)[0]['id'];//找父id
				
					$year = $_POST['year'];
					$month = $_POST['month'];
					$day = $_POST['day'];
				
					for($i=1;$i<$_POST['stayDays'];$i++){
						// d-m-y
						$new_year = date("Y",strtotime($day."-".$month."-".$year) + ($i*86400));
						$new_month = date("m",strtotime($day."-".$month."-".$year) + ($i*86400));
						$new_day = date("d",strtotime($day."-".$month."-".$year) + ($i*86400));
							
						$sql = "insert into ". $table ." (year,month,day,aid,booked,guest_name,phone,bank_account,stayDays,parent_id,status)values(".
						$new_year.",".$new_month.",".$new_day.",".$_POST['aid'].",".
						$_POST['booked'].",'".$_POST['guest_name']."',".$_POST['phone'].",".$_POST['bank_account'].",".
						$_POST['stayDays'] . "," . $last_id .",".$_POST['status'].")";
						
							
						 sqlexe($conn,$sql);
					}
				}
			}else{
				
				$sql = "select email,passwd,createtime from " . $table . " where id=" . $_POST['id'];
				$datas = getAll($conn,$sql);
				
				if(empty($datas)){
					$status='view';
					$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table . ".createtime from  " . $table;
					$sql .= " left join area on area.aid=" . $table . ".aid  where parent_id=0 order by year,month,day," . $table  	.".aid";
					$datas = array();
					$datas = getAll($conn,$sql);
					$sql = "delete from ". $table ." where aid not in (select aid from area)";
					sqlexe($conn,$sql);
					include('../view/header_admin.html');
					include('../view/message_admin.html');
					include('../view/footer_admin.html');
					exit();
				}
				
				$email = $datas[0]['email'];
				$passwd = $datas[0]['passwd'];
				$createtime = $datas[0]['createtime'];
				
				
				
				$sql = "delete from " . $table ." where id =". $_POST['id'] . " or parent_id=" . $_POST['id'];
				sqlexe($conn,$sql);
				
				$sql = "insert into ". $table ." (year,month,day,aid,booked,guest_name,phone,bank_account,stayDays,status,email,passwd,createtime)values(".
				$_POST['year'].",".$_POST['month'].",".$_POST['day'].",".$_POST['aid'].",".
				$_POST['booked'].",'".$_POST['guest_name']."',".$_POST['phone'].",".$_POST['bank_account'].",".$_POST['stayDays'] ."," .$_POST['status'].
				",'".$email ."','". $passwd. "','".$createtime. "')";
				// echo $sql;
				 // exit();
				
				$rs = sqlexe($conn,$sql);
				
				if($_POST['stayDays']>1){//多於一天開始循環添加
					$sql = "SELECT LAST_INSERT_ID() AS `id`";
					$last_id = getAll($conn,$sql)[0]['id'];//找父id
				
					$year = $_POST['year'];
					$month = $_POST['month'];
					$day = $_POST['day'];
				
					for($i=1;$i<$_POST['stayDays'];$i++){
						// d-m-y
						$new_year = date("Y",strtotime($day."-".$month."-".$year) + ($i*86400));
						$new_month = date("m",strtotime($day."-".$month."-".$year) + ($i*86400));
						$new_day = date("d",strtotime($day."-".$month."-".$year) + ($i*86400));
							
						$sql = "insert into ". $table ." (year,month,day,aid,booked,guest_name,phone,bank_account,stayDays,parent_id,status,email,passwd,createtime)values(".
						$new_year.",".$new_month.",".$new_day.",".$_POST['aid'].",".
						$_POST['booked'].",'".$_POST['guest_name']."',".$_POST['phone'].",".$_POST['bank_account'].",".
						$_POST['stayDays'] . "," . $last_id .",".$_POST['status'].",'".$email ."','". $passwd. "','".$createtime. "')";
						
							
						 sqlexe($conn,$sql);
					}
				}
				
			}
			// exit();

		if($rs){
			$msg = "編輯成功";
		}else{
			$msg = "編輯失敗";
		}
		$status='view';

		$sql ="select year,month,day,name,booked,guest_name,phone,bank_account,status,price,stayDays," . $table . ".createtime from  " . $table;
		$sql .= " left join area on area.aid=" . $table . ".aid  where parent_id=0 order by year,month,day," . $table  	.".aid";
		// echo $sql;
		$datas = array();
		$datas = getAll($conn,$sql);
		$sql = "delete from ". $table ." where aid not in (select aid from area)";

		sqlexe($conn,$sql);
	}
	break;
	
	
}




include('../view/header_admin.html');
include('../view/message_admin.html');
include('../view/footer_admin.html');


}













?>