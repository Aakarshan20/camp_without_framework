<?php
define('ACC',true);

header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');
include('./Model/mysql.php');
include('./include/config.ini.php');
include('./include/init.inc.php');
$mysql = Mysql::get_instance();

//echo "124567";

// exit();
//print_r($_GET);
/*
 A區,900,2017/6/24,4,2017/6/28,5,18000 )
*/
//exit();
//orderDetails = 	[aid,areaname, price , arrive , stayDays ,  leave , seats , totalPrice];

if(!isset($_GET)){
	exit();
}

$data = explode(',',$_GET['v']);

if(count($data)!=8){
	exit();
}

if(!is_numeric($data[0])||!is_numeric($data[2])|| !is_numeric($data[4])||!is_numeric($data[6]) ||!is_numeric($data[7])){
	exit();
}

$data_str = implode('&',$data);

/*
$sql  = "select A.aid,name,booked,available,available-booked as remain,price";
$sql .= " from orders A left join area B on A.aid=B.aid where year=:year and month=:month and day=:day";


$rs=$mysql->prepare($sql);

$rs->bindParam(':year', $year, PDO::PARAM_INT);
$rs->bindParam(':month', $month, PDO::PARAM_INT);
$rs->bindParam(':day', $day, PDO::PARAM_INT);

$rs->execute();


$orders_arr = $rs->fetchAll(PDO::FETCH_CLASS);

$sql = "select count(1) as count from area";
$rs = $mysql->query($sql);
$counts_of_area = $rs->fetchAll(PDO::FETCH_CLASS)[0]->count;
*/

// echo '<pre>';
// print_r($orders_arr);
// echo '</pre>';

//exit();
?>
<html>

<head>
</head>
<body>

<script>

</script>
<style>
	.transfer tr td{
		padding:10px;
	}
	.transfer th{
		color:red;
		padding:10px;
		text-align:center;
	}
	.transinfo{
		color:red;
		padding:10px;
		margin:10px;
	}
	.transfer{
		width:90%;
	}
	.transtitle{
		padding:10px;
		background:#7FFFD4;
		text-align:center;
	}
</style>
<center>
<table border="solid 1px"  class="transfer">
<tr>
	<th colspan="2">【匯款資訊－ATM轉帳/匯款】</th>
</tr>
<tr>
	<td class="transtitle">匯款銀行</td>
	<td>大湖郵局 </td>
</tr>
<tr>
	<td class="transtitle">匯款戶名</td>
	<td>李少興</td>
</tr>
<tr>
	<td class="transtitle">銀行代碼</td>
	<td>700</td>
</tr>
<tr>
	<td rowspan="2" class="transtitle">銀行帳號</td>
	<td>局號: 0291164</td>
</tr>
<tr>
	<td>帳號: 0224686</td>
</tr>
</table>
</center>
<div class="transinfo">
 ※匯款後，請務必來電告知「匯款帳號末5碼」及留下您的「姓名」、「聯絡電話」、「訂位日期」、「匯款金額」，電話【0921-636063】，即完成預訂手續。
</div>
<form name="send" action ="sendorder.php" method="post" onsubmit="return chk();" >
<table style="border:3px #cccccc solid;width:100%" cellpadding="10" border='1'>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;">姓名(請填寫全名)</td>
				</tr>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:white;">
						<input type="text" name = "name" onkeyup="isComplete()" id="guestName"/>
					</td>
				</tr>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;"><div id="infoPhone">電話(請勿填寫"-"號)</div></td>
				</tr
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:white;">
						
						<input type="text" name="phone" style="ime-mode:disabled" onkeyup="ValidateNumber(value)" id="guestPhone"/>
					</td>
				</tr>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;" id="infoEmail">email</td>
				</tr>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:white;">
						<input type="text" name = "email" onkeyup="ValidateEmail(value)" id="guestEmail"/>
					</td>
				</tr>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;"><div id="infoPasswd">密碼</div></td>
				</tr>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:white;">
						<input  type="password" name = "passwd" onkeyup="ValidateRePasswd(value)" id="guestPasswd"/>
					</td>
				</tr>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;"><div id="infoRePasswd">確認密碼</div></td>
				</tr>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:white;">
						<input type="password" name = "repasswd" onkeyup="ValidatePasswd(value)" id = "guestRePasswd"/>
					</td>
				</tr>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:#7FFFD4;"><div id="infoBankAccount">銀行帳號後五碼</div></td>
				</tr>
				<tr>
					<td style="padding:5px;text-align:center;;width:33.3%;text-align:center;background:white;">
						<input type="text" name = "bank_account" onkeyup="ValidateAccount(value)" id="guestBank_account" />
					</td>
				</tr>
		</table>
		<div style="margin:10px">
			<input type="hidden" name = "datas" value = "<?php echo $data_str?>">
			<center>
			<input id="orderSubmit" type="submit" value="送出" disabled="disabled">
			</center>
		</div>
</form>
</body>

</html>