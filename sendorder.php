<?php 

// print_r($_POST);
define('ACC',true);
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');

// $post_srr = implode(',',$_POST);
//print_r($_POST);



$mysql = mysql::get_instance();
//Array ( [name] => [phone] => [email] => [passwd] => [repasswd] => [bank_account] => [datas] => B區&900&2017/6/17&11&2017/6/28&3&29700 )
//orderDetails = 	[aid,areaname, price , arrive , stayDays ,  leave , seats , totalPrice];


$datas_arr = explode('&',$_POST['datas']);
$arrive = explode('/',$datas_arr[3]);


$aid = $datas_arr[0];
$booked = $datas_arr[6];
$stayDays = $datas_arr[4];
$guest_name = $_POST['name'];
$phone = $_POST['phone'];
$bank_account = $_POST['bank_account'];
$email = $_POST['email'];
$passwd = md5($email."1970". $_POST['passwd']);
$aid = $datas_arr[0];
$year = $arrive[0];
$month = $arrive[1];
$day = $arrive[2];


$first_link_title = "回首頁";
$first_link = "index.php";
$second_link_title = "行車動線";
$second_link = "map.php";

// print_r($_POST);



// exit();
$sql  = "insert into guestinfo(aid,booked,stayDays,guest_name,phone,bank_account";
$sql .= ",email,passwd,year,month,day)value(";
// $sql .=  $aid  .  "," . $booked . "," . $stayDays . ",'" . $guest_name . "'," . $phone . ",";
$sql .= ":aid,:booked,:stayDays,:guest_name,:phone,";
// $sql .= $bank_account . ",'" . $email . "','" . $passwd . "',"  . $year . "," . $month .  ",";
$sql .= ":bank_account,:email,:passwd,:year,:month,";
// $sql  .= $day . ")";
$sql  .= ":day)";

$rs= $mysql->prepare($sql);
$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
$rs->bindParam(':booked', $booked, PDO::PARAM_INT);
$rs->bindParam(':stayDays', $stayDays, PDO::PARAM_INT);
$rs->bindParam(':guest_name', $guest_name, PDO::PARAM_STR);
$rs->bindParam(':phone', $phone, PDO::PARAM_INT);
$rs->bindParam(':bank_account', $bank_account, PDO::PARAM_INT);
$rs->bindParam(':email', $email, PDO::PARAM_STR);
$rs->bindParam(':passwd', $passwd, PDO::PARAM_STR);
$rs->bindParam(':year', $year, PDO::PARAM_INT);
$rs->bindParam(':month', $month, PDO::PARAM_INT);
$rs->bindParam(':day', $day, PDO::PARAM_INT);
$rs->execute();
// $rs->fetchAll(PDO::FETCH_CLASS);
$count = $rs->rowCount();

if($count!=0){
	// $last_id = $mysql->lastInsertId();
	$sql = "SELECT LAST_INSERT_ID() as last_id";
	$rs = $mysql->query($sql);
	$last_id = $rs->fetchAll(PDO::FETCH_CLASS)[0]->last_id;
	
	// print_r($datas);
	/*
	Array ( [0] => stdClass Object ( [Tables_in_b14_20045506_camp2] => area ) 
	[1] => stdClass Object ( [Tables_in_b14_20045506_camp2] => guestinfo )
	[2] => stdClass Object ( [Tables_in_b14_20045506_camp2] => orders )
	[3] => stdClass Object ( [Tables_in_b14_20045506_camp2] => users ) )
	*/
	
	if($stayDays>1){//多於一天開始循環添加

		for($i=1;$i<$stayDays;$i++){
				// d-m-y
				$new_year = date("Y",strtotime($day."-".$month."-".$year) + ($i*86400));
				$new_month = date("m",strtotime($day."-".$month."-".$year) + ($i*86400));
				$new_day = date("d",strtotime($day."-".$month."-".$year) + ($i*86400));
				
				$sql = "insert into guestinfo (year,month,day,aid,booked,guest_name,phone,bank_account,stayDays,email,passwd,parent_id)values(".
				//$new_year.",".$new_month.",".$new_day.",".$aid.",".$booked.",'".$guest_name."',".$phone.",".$bank_account.",".$stayDays.",'".$email."','".$passwd.
				//"'," . $last_id.")";
				 ":new_year, :new_month, :new_day, :aid, :booked, :guest_name, :phone , :bank_account, :stayDays, :email, :passwd, :parent_id)";
				
				
				$rs= $mysql->prepare($sql);
				$rs->bindParam(':new_year', $new_year, PDO::PARAM_INT);
				$rs->bindParam(':new_month', $new_month, PDO::PARAM_INT);
				$rs->bindParam(':new_day', $new_day, PDO::PARAM_INT);
				$rs->bindParam(':aid', $aid, PDO::PARAM_INT);
				$rs->bindParam(':booked', $booked, PDO::PARAM_INT);
				$rs->bindParam(':guest_name', $guest_name, PDO::PARAM_STR);
				$rs->bindParam(':phone', $phone, PDO::PARAM_INT);
				$rs->bindParam(':bank_account', $bank_account, PDO::PARAM_INT);
				$rs->bindParam(':stayDays', $stayDays, PDO::PARAM_INT);
				$rs->bindParam(':email', $email, PDO::PARAM_STR);
				$rs->bindParam(':passwd', $passwd, PDO::PARAM_STR);
				$rs->bindParam(':parent_id', $last_id, PDO::PARAM_INT);
				
				 $rs->execute();
				 $count = $rs->rowCount();
				 
				if($count==0){
					$msg = "資料有誤訂單送出失敗";
					

					include("./view/calender.html");
					 //include("./view/order.html");
					 include("./view/message.html");
					include("./view/footer.html");
					// exit();
				}
		}
		
	}
// exit();
	
	$msg = "訂單已送出，請至首頁查看訂單";
	$_SESSION['usertoken'] = $passwd;
	
}else{
	$msg = "資料有誤訂單送出失敗..";
}



include("./view/calender.html");
 //include("./view/order.html");
 include("./view/message.html");
include("./view/footer.html");

?>