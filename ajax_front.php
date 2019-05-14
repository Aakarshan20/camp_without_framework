<html>
<head>
</head>
<body>
	<div id="demo"></div>
	<input type="button" value="取得AJAX結果" name="ppp" onclick="getValue()" id="next">
	<script>
	
	function getXmlHttpObject(){
			var xmlHttpRequest;
		try{
			xmlHttpRequest = new XMLHttpRequest();
		}catch(e){
			try{
				xmlHttpRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}catch(e){
				xmlHttpRequest = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		return xmlHttpRequest;
	}
	
	var myXmlHttpRequest="";
	
	function getValue(){
		myXmlHttpRequest = getXmlHttpObject();
		if(myXmlHttpRequest){
			// alert("創建成功");
			var url = "./ajax_back.php?m=" + $("next").name;
			// var url = "./ajax_back.php";
			// console.log(url);
			myXmlHttpRequest.open("get",url,true);
			
			myXmlHttpRequest.onreadystatechange = proc;
			myXmlHttpRequest.send(null);
			
		}else{
			alert("創建失敗");
		}
	}
	
	function proc(){
		console.log(123);
		if(myXmlHttpRequest.readyState==4){
			if(myXmlHttpRequest.status==200){
				$("demo").innerHTML = myXmlHttpRequest.responseText;
				console.log(myXmlHttpRequest.responseText);
			}else{
				console.log("not 200");
			}
		}else{
			console.log("not 4");
		}

	}
	
	function $(id){
		return document.getElementById(id);
	}
</script>
</body>



</html>