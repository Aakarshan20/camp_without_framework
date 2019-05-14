<?php

?>
<html>
<script language=javascript>
changelist();
function changelist()
{
      switch(document.data.test.value)
      {
            case "1":
            document.all.namelist.innerHTML="<select name=actname><option value=1>u1A<option value=2>u1B</select>"
            break;
            case "2": 
            document.all.namelist.innerHTML="<select name=actname><option value=1>u1A<option value=2>u1B<option value=3> u1C</select>"
            break;
      }
}                                                                                
</script>      
                                                                          
<form name=data>                                                                           
<select name=test onChange=Javascript:changelist();>
	<option value=0 selected>check
	<option value=1>mis
	<option value=2>csie
</select>
                                                                              
<div id=namelist></div>
                                                                                
</form>                                                                             
</html>