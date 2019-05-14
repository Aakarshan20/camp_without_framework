<?php


define('ACC',true);
include('../include/config.ini.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');

// print_r($_POST);


// exit();

//進出資料庫
$conn = mysqli_connect($_CFG['host'],$_CFG['user'],$_CFG['passwd']);
// echo "connect";
if($conn){
	// echo " :success!<br/>";
}else{
	echo " :fail!<br/>";
}

$sql = "use " . $_CFG['db'];

sqlexe($conn,$sql);

function sqlexe($conn,$sql){
	$rs = mysqli_query($conn,$sql);
	if($rs){
		// echo $sql ." :success!<br/>";
	}else{
		echo $sql ." :fail!<br/>";
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


$sql = "set names utf8";

sqlexe($conn,$sql);

$sql = "select * from area";
$areas = getAll($conn,$sql);


/*
Array ( [0] => Array ( [aid] => 1 [name] => A區 [available] => 30 ) 
[1] => Array ( [aid] => 14 [name] => B區 [available] => 30 ) 
[2] => Array ( [aid] => 3 [name] => C區 [available] => 30 )
 [3] => Array ( [aid] => 4 [name] => D區(草棚區) [available] => 30 ) 
 [4] => Array ( [aid] => 5 [name] => D區(屋棚區) [available] => 30 ) 
 [5] => Array ( [aid] => 25 [name] => sseere [available] => 34 ) 
 [6] => Array ( [aid] => 26 [name] => aaaa [available] => 2 ) )

*/
// print_r($areas);
?>
<html>
<script language=javascript>
changelist();
function changelist()
{
      switch(document.data.aid.value)
      {		
			<?php foreach($areas as $v){ ?>
				case "<?php echo $v['aid']?>":
				document.all.namelist.innerHTML="預定數量<select name=booked>"+
				
				<?php 	
					$value='"';
					for($i=1;$i<=$v['available'];$i++){
						$value.='<option value='.$i.'>'.$i;
					}
					$value.='"';
						echo $value;
				?>
				
				+"</select>"
				break;
            <?php } ?>
      }
}                                                                                
</script>      
                                                                          
<form name=data action="recieve.php" method="post">                                                                           
預約區域:<select name=aid onChange=Javascript:changelist();>
	<option value=0 selected>請選擇露營區</option>
	<?php foreach($areas as $v){ ?>
	<option value=<?php echo $v['aid']?>><?php echo $v['name']?></option>
	<?php } ?>
</select>
<br/>                                                                 
<div id=namelist>預定數量</div>
<input type="submit" value="submit"/>                                                                    
</form>                                                                             
</html>