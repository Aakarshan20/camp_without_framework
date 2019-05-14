<?php 

define('ACC',true);

include('./include/config.ini.php');
// include('./include/init.inc.php');
header("Content-Type:text/html; charset=utf-8");
date_default_timezone_set('Asia/Taipei');

$conn = mysqli_connect($_CFG['host'],$_CFG['user'],$_CFG['passwd']);

$sql = "set names utf8";

mysqli_query($conn,$sql);

$sql = "use " . $_CFG['db'];

mysqli_query($conn,$sql);

$sql = "select * from area order by aid";

$rs = mysqli_query($conn,$sql);

$areas = array();

while($rows = mysqli_fetch_assoc($rs)){
	$areas[] = $rows;
}

// print_r($areas);

$area_covers=array(
"./view/images/a_cover.jpg",
"./view/images/b_cover.jpg",
"./view/images/c_cover.jpg",
"./view/images/d_cover.jpg",
"./view/images/ian1_cover.jpg",
"./view/images/ian2_cover.jpg",
"./view/images/ian3_cover.jpg",
"./view/images/ian4_cover.jpg",);


$area_banners=array(
"./view/images/a_banner.jpg",
"./view/images/b_banner.jpg",
"./view/images/c_banner.jpg",
"./view/images/d_banner.jpg",
"./view/images/ian1_banner.jpg",
"./view/images/ian2_banner.jpg",
"./view/images/ian3_banner.jpg",
"./view/images/ian4_banner.jpg");

$area_photos_large = array();
$area_photos_large[] = array(//area a
"./view/images/photos/a/1.JPG",
"./view/images/photos/a/2.JPG",
"./view/images/photos/a/3.JPG",
"./view/images/photos/a/4.JPG",
"./view/images/photos/a/5.JPG",
"./view/images/photos/a/6.JPG",
"./view/images/photos/a/7.JPG",
"./view/images/photos/a/8.JPG",
"./view/images/photos/a/9.JPG",
"./view/images/photos/a/10.JPG",
"./view/images/photos/a/11.JPG",
"./view/images/photos/a/12.JPG",
"./view/images/photos/a/13.JPG",
"./view/images/photos/a/14.JPG",
"./view/images/photos/a/15.JPG",
"./view/images/photos/a/16.JPG",
"./view/images/photos/a/17.JPG",
"./view/images/photos/a/18.JPG",
"./view/images/photos/a/19.JPG",
"./view/images/photos/a/20.JPG",
"./view/images/photos/a/21.JPG",
"./view/images/photos/a/22.JPG",
"./view/images/photos/a/23.JPG",
"./view/images/photos/a/24.JPG",
"./view/images/photos/a/25.JPG",
"./view/images/photos/a/26.JPG",
"./view/images/photos/a/27.JPG",
"./view/images/photos/a/28.JPG",
"./view/images/photos/a/29.JPG",
"./view/images/photos/a/30.JPG",
"./view/images/photos/a/31.JPG",
"./view/images/photos/a/32.JPG",
"./view/images/photos/a/33.JPG",
"./view/images/photos/a/34.JPG",
"./view/images/photos/a/35.JPG",
"./view/images/photos/a/36.JPG",
"./view/images/photos/a/37.JPG",
"./view/images/photos/a/38.JPG",
"./view/images/photos/a/39.JPG",
"./view/images/photos/a/40.JPG",
);

$area_photos_large[] = array(//area b
"./view/images/photos/b/1.JPG",
"./view/images/photos/b/2.JPG",
"./view/images/photos/b/3.JPG",
"./view/images/photos/b/4.JPG",
"./view/images/photos/b/5.JPG",
"./view/images/photos/b/6.JPG",
"./view/images/photos/b/7.JPG",
"./view/images/photos/b/8.JPG",
"./view/images/photos/b/9.JPG",
"./view/images/photos/b/10.JPG",
"./view/images/photos/b/11.JPG",
"./view/images/photos/b/12.JPG",
"./view/images/photos/b/13.JPG",
"./view/images/photos/b/14.JPG",
"./view/images/photos/b/15.JPG",

);
$area_photos_large[] = array(//area c
"./view/images/photos/b/1.JPG",
"./view/images/photos/b/2.JPG",
"./view/images/photos/b/3.JPG",
"./view/images/photos/b/4.JPG",
"./view/images/photos/b/5.JPG",
"./view/images/photos/b/6.JPG",
"./view/images/photos/b/7.JPG",
"./view/images/photos/b/8.JPG",
"./view/images/photos/b/9.JPG",
"./view/images/photos/b/10.JPG",
"./view/images/photos/b/11.JPG",
"./view/images/photos/b/12.JPG",
"./view/images/photos/b/13.JPG",
"./view/images/photos/b/14.JPG",
"./view/images/photos/b/15.JPG",
"./view/images/photos/b/16.JPG",
"./view/images/photos/b/17.JPG",
"./view/images/photos/b/18.JPG",

);
$area_photos_large[] = array(//area d
"./view/images/photos/b/1.JPG",
"./view/images/photos/b/2.JPG",
"./view/images/photos/b/3.JPG",
"./view/images/photos/b/4.JPG",
"./view/images/photos/b/5.JPG",
"./view/images/photos/b/6.JPG",
"./view/images/photos/b/7.JPG",
"./view/images/photos/b/8.JPG",
"./view/images/photos/b/9.JPG",
"./view/images/photos/b/10.JPG",
"./view/images/photos/b/11.JPG",
"./view/images/photos/b/12.JPG",
"./view/images/photos/b/13.JPG",
"./view/images/photos/b/14.JPG",
"./view/images/photos/b/15.JPG",
"./view/images/photos/b/16.JPG",
"./view/images/photos/b/17.JPG",
"./view/images/photos/b/18.JPG",
"./view/images/photos/b/19.JPG",
"./view/images/photos/b/20.JPG",
"./view/images/photos/b/21.JPG",
"./view/images/photos/b/22.JPG",
"./view/images/photos/b/23.JPG",
"./view/images/photos/b/24.JPG",
"./view/images/photos/b/25.JPG",
"./view/images/photos/b/26.JPG",
"./view/images/photos/b/27.JPG",

);
$area_photos_large[] = array(//area ian 1
"./view/images/photos/ian/1/1.JPG",
"./view/images/photos/ian/1/2.JPG",
"./view/images/photos/ian/1/3.JPG",
"./view/images/photos/ian/1/4.JPG",
"./view/images/photos/ian/1/5.JPG",
"./view/images/photos/ian/1/6.JPG",
"./view/images/photos/ian/1/7.JPG",
"./view/images/photos/ian/1/8.JPG",
"./view/images/photos/ian/1/9.JPG",
"./view/images/photos/ian/1/10.JPG",
"./view/images/photos/ian/1/11.JPG",
"./view/images/photos/ian/1/12.JPG",
"./view/images/photos/ian/1/13.JPG",
"./view/images/photos/ian/1/14.JPG",
"./view/images/photos/ian/1/15.JPG",
"./view/images/photos/ian/1/16.JPG",
"./view/images/photos/ian/1/17.JPG",

);
$area_photos_large[] = array(//area ian 2
"./view/images/photos/ian/2/1.JPG",
"./view/images/photos/ian/2/2.JPG",
"./view/images/photos/ian/2/3.JPG",
"./view/images/photos/ian/2/4.JPG",
"./view/images/photos/ian/2/5.JPG",
"./view/images/photos/ian/2/6.JPG",
"./view/images/photos/ian/2/7.JPG",
"./view/images/photos/ian/2/8.JPG",
"./view/images/photos/ian/2/9.JPG",

);
$area_photos_large[] = array(//area ian 3 
"./view/images/photos/ian/3/1.JPG",
"./view/images/photos/ian/3/2.JPG",
"./view/images/photos/ian/3/3.JPG",
"./view/images/photos/ian/3/4.JPG",
"./view/images/photos/ian/3/5.JPG",
"./view/images/photos/ian/3/6.JPG",
"./view/images/photos/ian/3/7.JPG",
"./view/images/photos/ian/3/8.JPG",
"./view/images/photos/ian/3/9.JPG",

);
$area_photos_large[] = array(//area ian 4
"./view/images/photos/ian/4/1.JPG",
"./view/images/photos/ian/4/2.JPG",
"./view/images/photos/ian/4/3.JPG",
"./view/images/photos/ian/4/4.JPG",
"./view/images/photos/ian/4/5.JPG",
"./view/images/photos/ian/4/6.JPG",
"./view/images/photos/ian/4/7.JPG",
"./view/images/photos/ian/4/8.JPG",
"./view/images/photos/ian/4/9.JPG",
"./view/images/photos/ian/4/10.JPG",
"./view/images/photos/ian/4/11.JPG",
"./view/images/photos/ian/4/12.JPG",
"./view/images/photos/ian/4/13.JPG",

);


$area_photos= array();

$area_photos[] = array(//area a
"./view/images/a/1.jpg",
"./view/images/a/2.jpg",
"./view/images/a/3.jpg",
"./view/images/a/4.jpg",
"./view/images/a/5.jpg",
"./view/images/a/6.jpg",
"./view/images/a/7.jpg",
"./view/images/a/8.jpg",
"./view/images/a/9.jpg",
"./view/images/a/10.jpg",
"./view/images/a/11.jpg",
"./view/images/a/12.jpg",
"./view/images/a/13.jpg",
"./view/images/a/14.jpg",
"./view/images/a/15.jpg",
"./view/images/a/16.jpg",
"./view/images/a/17.jpg",
"./view/images/a/18.jpg",
"./view/images/a/19.jpg",
"./view/images/a/20.jpg",
"./view/images/a/21.jpg",
"./view/images/a/22.jpg",
"./view/images/a/23.jpg",
"./view/images/a/24.jpg",
"./view/images/a/25.jpg",
"./view/images/a/26.jpg",
"./view/images/a/27.jpg",
"./view/images/a/28.jpg",
"./view/images/a/29.jpg",
"./view/images/a/30.jpg",
"./view/images/a/31.jpg",
"./view/images/a/32.jpg",
"./view/images/a/33.jpg",
"./view/images/a/34.jpg",
"./view/images/a/35.jpg",
"./view/images/a/36.jpg",
"./view/images/a/37.jpg",
"./view/images/a/38.jpg",
"./view/images/a/39.jpg",
"./view/images/a/40.jpg",
);
$area_photos[] = array(//area b
"./view/images/b/1.jpg",
"./view/images/b/2.jpg",
"./view/images/b/3.jpg",
"./view/images/b/4.jpg",
"./view/images/b/5.jpg",
"./view/images/b/6.jpg",
"./view/images/b/7.jpg",
"./view/images/b/8.jpg",
"./view/images/b/9.jpg",
"./view/images/b/10.jpg",
"./view/images/b/11.jpg",
"./view/images/b/12.jpg",
"./view/images/b/13.jpg",
"./view/images/b/14.jpg",
"./view/images/b/15.jpg",
);

$area_photos[] = array(//area c
"./view/images/c/1.jpg",
"./view/images/c/2.jpg",
"./view/images/c/3.jpg",
"./view/images/c/4.jpg",
"./view/images/c/5.jpg",
"./view/images/c/6.jpg",
"./view/images/c/7.jpg",
"./view/images/c/8.jpg",
"./view/images/c/9.jpg",
"./view/images/c/10.jpg",
"./view/images/c/11.jpg",
"./view/images/c/12.jpg",
"./view/images/c/13.jpg",
"./view/images/c/14.jpg",
"./view/images/c/15.jpg",
"./view/images/c/16.jpg",
"./view/images/c/17.jpg",
"./view/images/c/18.jpg",
);
$area_photos[] = array(//area d
"./view/images/d/1.jpg",
"./view/images/d/2.jpg",
"./view/images/d/3.jpg",
"./view/images/d/4.jpg",
"./view/images/d/5.jpg",
"./view/images/d/6.jpg",
"./view/images/d/7.jpg",
"./view/images/d/8.jpg",
"./view/images/d/9.jpg",
"./view/images/d/10.jpg",
"./view/images/d/11.jpg",
"./view/images/d/12.jpg",
"./view/images/d/13.jpg",
"./view/images/d/14.jpg",
"./view/images/d/15.jpg",
"./view/images/d/16.jpg",
"./view/images/d/17.jpg",
"./view/images/d/18.jpg",
"./view/images/d/19.jpg",
"./view/images/d/20.jpg",
"./view/images/d/21.jpg",
"./view/images/d/22.jpg",
"./view/images/d/23.jpg",
"./view/images/d/24.jpg",
"./view/images/d/25.jpg",
"./view/images/d/26.jpg",
"./view/images/d/27.jpg"
);


$area_photos[] = array(//area ian 1
"./view/images/ian/1/1.jpg",
"./view/images/ian/1/2.jpg",
"./view/images/ian/1/3.jpg",
"./view/images/ian/1/4.jpg",
"./view/images/ian/1/5.jpg",
"./view/images/ian/1/6.jpg",
"./view/images/ian/1/7.jpg",
"./view/images/ian/1/8.jpg",
"./view/images/ian/1/9.jpg",
"./view/images/ian/1/10.jpg",
"./view/images/ian/1/11.jpg",
"./view/images/ian/1/12.jpg",
"./view/images/ian/1/13.jpg",
"./view/images/ian/1/14.jpg",
"./view/images/ian/1/15.jpg",
"./view/images/ian/1/16.jpg",
"./view/images/ian/1/17.jpg",

);
$area_photos[] = array(//area ian2
"./view/images/ian/2/1.jpg",
"./view/images/ian/2/2.jpg",
"./view/images/ian/2/3.jpg",
"./view/images/ian/2/4.jpg",
"./view/images/ian/2/5.jpg",
"./view/images/ian/2/6.jpg",
"./view/images/ian/2/7.jpg",
"./view/images/ian/2/8.jpg",

);
$area_photos[] = array(//area ian 3
"./view/images/ian/3/1.jpg",
"./view/images/ian/3/2.jpg",
"./view/images/ian/3/3.jpg",
"./view/images/ian/3/4.jpg",
"./view/images/ian/3/5.jpg",
"./view/images/ian/3/6.jpg",
"./view/images/ian/3/7.jpg",
"./view/images/ian/3/8.jpg",
"./view/images/ian/3/9.jpg",

);
$area_photos[] = array(//area ian 4
"./view/images/ian/4/1.jpg",
"./view/images/ian/4/2.jpg",
"./view/images/ian/4/3.jpg",
"./view/images/ian/4/4.jpg",
"./view/images/ian/4/5.jpg",
"./view/images/ian/4/6.jpg",
"./view/images/ian/4/7.jpg",
"./view/images/ian/4/8.jpg",
"./view/images/ian/4/9.jpg",
"./view/images/ian/4/10.jpg",
"./view/images/ian/4/11.jpg",
"./view/images/ian/4/12.jpg",

);




$pid = isset($_GET['p'])?trim($_GET['p']):"0";

if(!is_numeric($pid) || ($pid>=count($area_covers))){
	$pid = 0;
}






include("./view/header.html");
include("./view/gallery.html");
include("./view/footer.html");
?>