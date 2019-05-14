console.log("tools");
//定義xmlHttpRequest物件
var myXmlHttpRequest="";

function get_remains_site(select){
	
	var url = "get_remains_site.php";
	
	var aid = select.value;
	remember_aid(aid);
	
	var reference  = "?t=" + aid;
	
	ajax_function(url,reference);
	
}

function remember_aid(aid){
	 $id("area_id").value=aid;
}

function get_remains_site_next(){
	var year = parseInt($id("get_remains_site_table_year").innerHTML);
	var month = parseInt($id("get_remains_site_table_month").innerHTML);
	var aid = parseInt($id("area_id").value);
	
	if((month+1)>12){
		month=1;
		year +=1;
		$id("get_remains_site_table_year").innerHTML = year;
		
	}else{
		month+=1;
	}
	
	$id("get_remains_site_table_month").innerHTML =  month
	
	// console.log(month);
	// console.log(year);

	var url = "get_remains_site.php";
	var reference = "?t=" + aid + "&yr=" + year + "&mon=" + month;
	
	ajax_function(url,reference);
}


function get_remains_site_prev(){
	console.log("remember_prev from tools.js");
	var year = parseInt($id("get_remains_site_table_year").innerHTML);
	var month = parseInt($id("get_remains_site_table_month").innerHTML);
	var aid = parseInt($id("area_id").value);
	
	if((month-1)<1){
		month=12;
		year -=1;
		
		$id("get_remains_site_table_year").innerHTML = year;
	}else{
		month-=1;
	}
	
	$id("get_remains_site_table_month").innerHTML =  month
	
	var url = "get_remains_site.php";	
	var reference = "?t=" + aid + "&yr=" + year + "&mon=" + month;
	
	
	ajax_function(url,reference);
}

function getXmlHttpObject(){//創建AJAX

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

//公用函式串接網址
function ajax_function(url,reference){

	myXmlHttpRequest = getXmlHttpObject();
	if(myXmlHttpRequest){
		
		var url_add_t = url + reference;
		myXmlHttpRequest.open("get",url_add_t,true);
		myXmlHttpRequest.onreadystatechange = proc_remains_site;
		myXmlHttpRequest.send(null);
								
	}else{
		
	}
}
						
function proc_remains_site(){
	if(myXmlHttpRequest.readyState==4){
		if(myXmlHttpRequest.status==200){
			var str = myXmlHttpRequest.responseText;
			$id("show_remains_area").innerHTML = str;
						
		}
	}else{
					
	}

}

function $id(id){
	return document.getElementById(id);
}
