<?php


$year = date("Y");
$month = date("m")+0;
$day = date("d");

echo "year = " . $year ."<br/>";

echo "month = "  .$month."<br/>";

echo "day = " . $day . "<br/>";

?>
<html>
<script language=javascript>
changecalender();
function changecalender()
{
      switch(document.data.test.value)
      {
            case "<?php echo $year?>":
            document.all.namelist.innerHTML="<select name=actname>"+
			
			<?php 
				$value = '"<option value=0>請選擇月份';
				for($i=$month;$i<=12;$i++){
					$value .= '<option value='.$i.'>'.$i;
				}

				$value .= '</select>"';
				echo $value;
			?>
			
			
			
            break;
			
            case "<?php echo ($year+1)?>": 
			document.all.namelist.innerHTML="<select name=actname>"+
			<?php
				$value = '"';
				
				for($i=1;$i<=12;$i++){
					$value .= '<option value='.$i.'>' . $i;
				}
				
				$value .='"';
				echo $value;
			?>
			
			
			
            break;
      }
}                                                                                
</script>      
                                                                          
<form name=data>                                                                           
<select name=test onChange=Javascript:changecalender();>
	<option value=0 selected>請選擇年份
	<option value=<?php echo $year?>><?php echo $year?>
	<option value=<?php echo ($year+1)?>><?php echo ($year+1)?>
</select>
                                                                              
<div id=namelist></div>
                                                                                
</form>                                                                             
</html>