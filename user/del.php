<?php
include("../inc/conn.php");
include("check.php");
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
</head>
<body>
<?php
$pagename=isset($_POST["pagename"])?$_POST["pagename"]:'';
$tablename=isset($_POST["tablename"])?$_POST["tablename"]:'';
$id='';
if(!empty($_POST['id'])){
    for($i=0; $i<count($_POST['id']);$i++){
	checkid($_POST['id'][$i]);
    $id=$id.($_POST['id'][$i].',');
    }
	$id=substr($id,0,strlen($id)-1);//去除最后面的","
}

if (!isset($id) || $id==''){
showmsg('操作失败！至少要选中一条信息。');
}

$tablenames='';
$rs = query("SHOW TABLES"); 
while($row = fetch_array($rs)) { 
$tablenames=$tablenames.$row[0]."#"; 
}
$tablenames=substr($tablenames,0,strlen($tablenames)-1);

if (str_is_inarr($tablenames,$tablename)=='no'){
echo "tablename 参数有误";
exit();
}

if ($tablename=="zzcms_main"){
	if (strpos($id,",")>0){
		$sql="select id,img,flv,editor from zzcms_main where id in (".$id.")";
	}else{
		$sql="select id,img,flv,editor from zzcms_main where id ='$id'";
	}
$rs=query($sql);
$row=num_rows($rs);
if ($row){
while ($row=fetch_array($rs)){
	if ($row["editor"]<>$username){
	markit();showmsg('非法操作！警告：你的操作已被记录！小心封你的用户及IP！');
	}
	if ($row['img']<>"/image/nopic.gif"){
			$f="../".substr($row['img'],1);
			if (file_exists($f)){
			unlink($f);
			}
			$fs="../".substr(str_replace(".","_small.",$row['img']),1)."";
			if (file_exists($fs)){
			unlink($fs);		
			}
	}
	if ($row['flv']<>''){//flv里没有设默认值
			$f="../".substr($row['flv'],1);
			if (file_exists($f)){
			unlink($f);
			}
	}
	query("delete from `".$tablename."` where id =".$row['id']."");
}
echo "<script>location.href='".$pagename."';</script>";
}

}elseif ($tablename=="zzcms_pp" || $tablename=="zzcms_licence"){

	if (strpos($id,",")>0){
		$sql="select id,img,editor from `".$tablename."` where id in (".$id.")";
	}else{
		$sql="select id,img,editor from `".$tablename."` where id ='$id'";
	}
$rs=query($sql);
$row=num_rows($rs);
if ($row){
while ($row=fetch_array($rs)){
	if ($row["editor"]<>$username){
	markit();showmsg('非法操作！警告：你的操作已被记录！小心封你的用户及IP！');
	}
	if ($row['img']<>"/image/nopic.gif"){
			$f="../".substr($row['img'],1);
			if (file_exists($f)){
			unlink($f);
			}
			$fs="../".substr(str_replace(".","_small.",$row['img']),1)."";
			if (file_exists($fs)){
			unlink($fs);		
			}
	}
	query("delete from `".$tablename."` where id =".$row['id']."");
}
echo "<script>location.href='".$pagename."';</script>";
}

}elseif ($tablename=='zzcms_guestbook'){

if (strpos($id,",")>0){	
	$sql="select id,saver from `".$tablename."` where id in (".$id.")";
}else{	
	$sql="select id,saver from `".$tablename."` where id ='$id'";
}
$rs=query($sql);
$row=num_rows($rs);
if ($row){
while ($row=fetch_array($rs)){	
	if ($row["saver"]<>$username){
	markit();showmsg('非法操作！警告：你的操作已被记录！小心封你的用户及IP！');
	}
	query("delete from `".$tablename."` where id =".$row['id']."");
}	
echo "<script>location.href='".$pagename."';</script>";
}

}elseif ($tablename=='zzcms_dl'){//不是从数据库中删，而是隐藏
if (strpos($id,",")>0){	
	$sql="select id,saver from zzcms_dl where id in (".$id.")";
}else{	
	$sql="select id,saver from zzcms_dl where id ='$id'";
}
$rs=query($sql);
$row=num_rows($rs);
if ($row){
while ($row=fetch_array($rs)){	
	if ($row["saver"]<>$username){
	markit();showmsg('非法操作！警告：你的操作已被记录！小心封你的用户及IP！');
	}
	query("update zzcms_dl set del=1 where id =".$row['id']."");		
}
echo "<script>location.href='".$pagename."';</script>";
}

}else{

	if (strpos($id,",")>0){	
	$sql="select id,editor from `".$tablename."` where id in (". $id .")";
	}else{	
	$sql="select id,editor from `".$tablename."` where id ='$id'";
	}
$rs=query($sql);
$row=num_rows($rs);
if ($row){
while ($row=fetch_array($rs)){	
	if ($row["editor"]<>$username){
	markit();showmsg('非法操作！警告：你的操作已被记录！小心封你的用户及IP！');
	}
	query("delete from `".$tablename."` where id =".$row['id']."");	
}
echo "<script>location.href='".$pagename."';</script>";
}

}
?>
</body>
</html>