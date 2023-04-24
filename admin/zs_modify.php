<?php
include("admin.php");
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../js/timer.js"></script>
<script language = "JavaScript" src="../js/gg.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script>  
<script type="text/javascript" language="javascript">
$.ajaxSetup ({
cache: false //close AJAX cache
});

$(document).ready(function(){  
  $("#name").change(function() { //jquery 中change()函数  
	$("#span_szm").load(encodeURI("../ajax/zsadd_ajax.php?id="+$("#name").val()));//jqueryajax中load()函数 加encodeURI，否则IE下无法识别中文参数 
  });  
});  

function CheckForm(){
if (document.myform.bigclassid.value==""){
    alert("请选择产品类别！");
	document.myform.bigclassid.focus();
	return false;
  }
  if (document.myform.cpname.value==""){
    alert("产品名称不能为空！");
	document.myform.cpname.focus();
	return false;
  }
  if (document.myform.prouse.value==""){
    alert("产品特点不能为空！");
	document.myform.prouse.focus();
	return false;
  } 
	document.getElementById('loading').style.display='block'   
}

function doClick_E(o){
	 var id,e;
	 for(var i=1;i<=document.myform.bigclassid.length;i++){
	   id ="E"+i;
	   e = document.getElementById("E_con"+i);
	   if(id != o.id){
	   	 e.style.display = "none";		
	   }else{
		e.style.display = "block";
	   }
	 }
	   if(id==0){
		document.getElementById("E_con1").style.display = "block";
	   }
	 }
</script>  
</head>
<body>
<?php
$do=isset($_GET['do'])?$_GET['do']:'';
if ($do=="modify"){
?>
<div class="admintitle">修改<?php echo channelzs?>信息</div>
<?php
checkadminisdo("zs_modify");
$page=isset($_GET["page"])?$_GET["page"]:1;
checkid($page);
$id=isset($_GET["id"])?$_GET["id"]:0;
checkid($id,1);

$sql="select * from zzcms_main where id='$id'";
$rs=query($sql);
$row=fetch_array($rs);
?>
<form action="?do=save" method="post" name="myform" id="myform" onSubmit="return CheckForm();">
  <table width="100%" border="0" cellpadding="5" cellspacing="0">
    <tr> 
      <td align="right" class="border">产品名称</td>
      <td width="82%" class="border"> <input name="cpname" type="text" id="cpname" value="<?php echo $row["proname"]?>" size="45" maxlength="50">
	  <span id="span_szm">  <input name="szm" type="hidden" value="<?php echo $row["szm"]?>"  />
        </span>      </td>
    </tr>
    <tr> 
      <td width="18%" align="right" class="border"> 所属类别</td>
      <td class="border"> 
        
		<table width="100%" border="0" cellpadding="0" cellspacing="1">
                <tr> 
                  <td>
				   <fieldset class="fieldsetstyle">
                    <legend>请选择所属大类</legend>
                    <?php
        $sqlB = "select classid,classname from zzcms_zsclass where parentid=0 order by xuhao asc";
		$rsB =query($sqlB); 
		$rowB= num_rows($rsB);
		if(!$rowB){
		echo "<a href='class2.php?tablename=zzcms_zsclass'>添加大类</a>";
		}else{
		
		$n=1;
		while($rowB= fetch_array($rsB)){
		if ($row['bigclassid']==$rowB['classid']){
		echo "<input name='bigclassid' type='radio' id='E$n'  onclick='javascript:doClick_E(this);uncheckall()' value='".$rowB['classid']."' checked/><label for='E$n'>".$rowB['classname']."</label>";
		}else{
		echo "<input name='bigclassid' type='radio' id='E$n'  onclick='javascript:doClick_E(this);uncheckall()' value='".$rowB['classid']."' /><label for='E$n'>".$rowB['classname']."</label>";
		}
		$n ++;
		if (($n-1) % 7==0) {echo "<br/>";}
		}
		}
			?>
                    </fieldset></td>
                </tr>
                <tr> 
                  <td> 
                    <?php
$sqlB="select classid,classname from zzcms_zsclass where parentid=0 order by xuhao asc";
$rsB =query($sqlB); 
$n=1;
while($rowB= fetch_array($rsB)){
if ($row["bigclassid"]==$rowB["classid"]) {  
echo "<div id='E_con$n' style='display:block;'>";
}else{
echo "<div id='E_con$n' style='display:none;'>";
}
echo "<fieldset class='fieldsetstyle'><legend>请选择所属小类</legend>";
$sqlS="select classid,classname from zzcms_zsclass where parentid='".$rowB['classid']."' order by xuhao asc";
$rsS =query($sqlS); 
$nn=1;
while($rowS= fetch_array($rsS)){
if (zsclass_isradio=='Yes'){
	if ($row['smallclassid']==$rowS['classid']){
	echo "<input name='smallclassid[]' id='radio$nn$n' type='radio' value='".$rowS['classid']."' checked/>";
	}else{
	echo "<input name='smallclassid[]' id='radio$nn$n' type='radio' value='".$rowS['classid']."' />";
	}
}else{
	if (strpos($row['smallclassids'],$rowS['classid'])!==false && $row['bigclassid']==$rowB['classid']){
	echo "<input name='smallclassid[]' id='radio$nn$n' type='checkbox' value='".$rowS['classid']."' checked/>";
	}else{
	echo "<input name='smallclassid[]' id='radio$nn$n' type='checkbox' value='".$rowS['classid']."' />";
	}
}
echo "<label for='radio$nn$n'>".$rowS['classname']."</label>";
$nn ++;
if (($nn-1) % 7==0) {echo "<br/>";}            
}
echo "</fieldset>";
echo "</div>";
$n ++;
}
?>                  </td>
                </tr>
        </table>		 </td>
    </tr>
	  
    <tr> 
      <td align="right" class="border">产品特点</td>
      <td class="border"> <textarea name="prouse" cols="60" rows="3" id="prouse"><?php echo stripfxg($row["prouse"])?></textarea>      </td>
    </tr>
    <?php
	if (shuxing_name!=''){
	$shuxing_name = explode("|",shuxing_name);
	$shuxing_value = explode("|||",$row["shuxing_value"]);
	for ($i=0; $i< count($shuxing_name);$i++){
	?>
	<tr>
      <td align="right" class="border" ><?php echo $shuxing_name[$i]?></td>
      <td class="border" ><input name="sx[]" type="text" value="<?php echo @$shuxing_value[$i]?>" size="45"></td>
    </tr>
	<?php
	}
	}
	?>
    <tr> 
      <td align="right" class="border">产品说明</td>
      <td class="border"> 
	  <textarea name="sm" id="sm"><?php echo stripfxg($row["sm"]) ?></textarea> 
             <script type="text/javascript" src="/3/ckeditor/ckeditor.js"></script>
			  <script type="text/javascript">CKEDITOR.replace('sm');</script>	  </td>
    </tr>
    <tr> 
      <td align="right" class="border">图片地址 
        <input name="img" type="hidden" id="img" value="<?php echo $row["img"]?>" size="45"> </td>
      <td class="border"> <table height="140"  width="140" border="0" cellpadding="5" cellspacing="1" bgcolor="#999999">
          <tr> 
            <td align="center" bgcolor="#FFFFFF" id="showimg" onClick="openwindow('/uploadimg_form.php',400,300)"> 
              <?php
				  if($row["img"]<>""){
				  echo "<img src='".$row["img"]."' border=0 width=120 /><br>点击可更换图片";
				  }else{
				  echo "<input name='Submit2' type='button'  value='上传图片'/>";
				  }
				  ?>            </td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td align="right" class="border">视频地址</td>
      <td class="border"> <input name="flv" type="text" id="flv" value="<?php echo $row["flv"]?>" size="60"></td>
    </tr>
    <tr> 
      <td align="right" class="border">可提供的支持</td>
      <td class="border"> <textarea name="zc" cols="60" rows="3" id="zc"><?php echo stripfxg($row["zc"])?></textarea>      </td>
    </tr>
    <tr> 
      <td align="right" class="border">对<?php echo channeldl?>商的要求</td>
      <td class="border"> <textarea name="yq" cols="60" rows="3" id="yq"><?php echo stripfxg($row["yq"])?></textarea>      </td>
    </tr>
    <tr> 
      <td align="right" class="border">发布人</td>
      <td class="border"><input name="editor" type="text" id="editor" value="<?php echo $row["editor"]?>" size="45"> 
        <input name="oldeditor" type="hidden" id="oldeditor" value="<?php echo $row["editor"]?>"></td>
    </tr>
    <tr> 
      <td align="right" class="border">审核</td>
      <td class="border"><input name="passed" type="checkbox" id="passed" value="1"  <?php if ($row["passed"]==1) { echo "checked";}?>>
        （选中为通过审核） </td>
    </tr>
    <tr>
      <td align="right" class="border">&nbsp;</td>
      <td class="border"><input type="submit" name="Submit22" value="修 改"></td>
    </tr>
	 <tr> 
      <td colspan="2" class="userbar">SEO设置</td>
    </tr>
    <tr>
      <td align="right" class="border" >标题（title）</td>
      <td class="border" ><input name="title" type="text" id="title" value="<?php echo $row["title"] ?>" size="60" maxlength="255"></td>
    </tr>
    <tr>
      <td align="right" class="border" >关键词（keywords）</td>
      <td class="border" ><input name="keywords" type="text" id="keywords" value="<?php echo $row["keywords"] ?>" size="60" maxlength="255">
        (多个关键词以“,”隔开)</td>
    </tr>
    <tr>
      <td align="right" class="border" >描述（description）</td>
      <td class="border" ><input name="description" type="text" id="description" value="<?php echo $row["description"] ?>" size="60" maxlength="255">
        (适当出现关键词，最好是完整的句子)</td>
    </tr>
    <tr> 
      <td align="right" class="border">&nbsp;</td>
      <td class="border"><input type="submit" name="Submit2" value="修 改"></td>
    </tr>
    <tr> 
      <td colspan="2" class="userbar">排名（推荐）设置</td>
    </tr>
    <tr> 
      <td align="right" class="border">设为关键字排名产品</td>
      <td class="border"><input name="elite" type="checkbox" id="elite" value="1" <?php if ($row["elite"]==1) { echo "checked";}?>>
      （选中后生效）
        时间： 
        <input name="elitestarttime" type="text" value="<?php echo $row["elitestarttime"]?>" size="20" onFocus="JTC.setday(this)">
        至 
        <input name="eliteendtime" type="text" value="<?php echo $row["eliteendtime"]?>" size="20" onFocus="JTC.setday(this)">      </td>
    </tr>
    <tr> 
      <td align="right" class="border">搜索热门词</td>
      <td class="border"><input name="tag" type="text" id="tag" value="<?php echo $row["tag"]?>" size="45">
        (多个词可用 , 隔开) </td>
    </tr>
	  <tr>
            <td align="right" class="border" >模板</td>
            <td class="border" >
              <label><input type="radio" name="skin" value="cp" id="cp" <?php if ($row["skin"]=='cp'){ echo "checked";}  ?>/>
              产品型</label>
              <label><input type="radio" name="skin" value="xm" id="xm" <?php if ($row["skin"]=='xm'){ echo "checked";}  ?>/>
              项目型</label>            </td>
    </tr>
	  <tr>
        <td align="right" class="border">外链地址</td>
	    <td class="border"><input name="link" type="text" id="link" value="<?php echo $row["link"]?>" size="45"></td>
    </tr>	  
    <tr> 
      <td align="center" class="border">&nbsp;</td>
      <td class="border"><input type="submit" name="Submit" value="修 改">
		<input name="cpid" type="hidden" id="cpid" value="<?php echo $row["id"]?>">
		<input name="sendtime" type="hidden" id="sendtime" value="<?php echo $row["sendtime"]?>"> 
        <input name="page" type="hidden" id="page" value="<?php echo $page?>"> 
		<input name="kind" type="hidden" id="kind" value="<?php echo $_GET["kind"]?>"> 
		<input name="keyword" type="hidden" id="keyword" value="<?php echo $_GET["keyword"]?>">	  </td>
    </tr>
  </table>
</form>
<div id='loading' style="display:none">正在保存，请稍候...</div>
<?php
}

if ($do=="save"){
global $page;
$bigclassid=isset($_POST['bigclassid'])?$_POST["bigclassid"]:1;
checkid($bigclassid);
$skin=isset($_POST['skin'])?$_POST["skin"]:"cp";
$smallclassid=isset($_POST['smallclassid'])?$_POST["smallclassid"][0]:0;//加[]可同多选共用同一个JS判断函数uncheckall
$smallclassids="";
if(!empty($_POST['smallclassid'])){
    for($i=0; $i<count($_POST['smallclassid']);$i++){
    //$smallclassids=$smallclassids.('"'.$_POST['smallclassid'][$i].'"'.',');//为字符串时的写法
	$smallclassids=$smallclassids.($_POST['smallclassid'][$i].',');
    }
	$smallclassids=substr($smallclassids,0,strlen($smallclassids)-1);//去除最后面的","
}

$shuxing_value="";
	if(!empty($_POST['sx'])){
    for($i=0; $i<count($_POST['sx']);$i++){
	$shuxing_value=$shuxing_value.($_POST['sx'][$i].'|||');
    }
	$shuxing_value=substr($shuxing_value,0,strlen($shuxing_value)-3);//去除最后面的"|||"
	}

//---保存内容中的远程图片，并替换内容中的图片地址
$msg='';
$imgs=getimgincontent(stripfxg($sm,true),2);
if (is_array($imgs)){
foreach ($imgs as $value) {
	checkstr($value,"upload");//入库前查上传文件地址是否合格
	if (substr($value,0,4) == "http"){
	$value=getimg2($value);//做二次提取，过滤后面的图片样式
	$img_bendi=grabimg($value,"");//如果是远程图片保存到本地
	if($img_bendi):$msg=$msg.  "远程图片：".$value."已保存为本地图片：".$img_bendi."<br>";else:$msg=$msg.  "远程图片".$value."保存到本地 失败";endif;
	$img_bendi=substr($img_bendi,strpos($img_bendi,"/uploadfiles"));//在grabimg函数中$img被加了zzcmsroo。这里要去掉
	$sm=str_replace($value,$img_bendi,$sm);//替换内容中的远程图片为本地图片
	}
}
}
//---end
if ($img==''){//放到内容下面，避免多保存一张远程图片
$img=getimgincontent(stripfxg($sm,true));
$img=getimg2($img);
}

if ($img<>''){
checkstr($img,"upload");//入库前查上传文件地址是否合格
	if (substr($img,0,4) == "http"){//$img=trim($_POST["img"])的情况下，这里有可能是远程图片地址
		$img=grabimg($img,"");//如果是远程图片保存到本地
		if($img):$msg=$msg.  "远程图片已保存到本地：".$img."<br>";else:$msg=$msg.  "远程图片保存到本地 失败";endif; 
		$img=substr($img,strpos($img,"/uploadfiles"));//在grabimg函数中$img被加了zzcmsroo。这里要去掉 
	}
		
	$imgsmall=str_replace(siteurl,"",getsmallimg($img));
	if (file_exists(zzcmsroot.$imgsmall)===false && file_exists(zzcmsroot.$img)!==false){//小图不存在，且大图存在的情况下，生成缩略图
	makesmallimg($img);//同grabimg一样，函数里加了zzcmsroot
	}	
}

$passed=isset($_POST["passed"])?$_POST["passed"]:0;
checkid($passed,1);
$elite=isset($_POST["elite"])?$_POST["elite"]:0;
checkid($elite,1);

if ($title=="") {$title=$cpname;}
if ($keywords=="") {$keywords=$cpname;}
if ($description=="") {$description=$cpname;}

if ($elitestarttime=="") {$elitestarttime=date('Y-m-d H:i:s');}
if ($eliteendtime=="") {$eliteendtime=date('Y-m-d H:i:s',time()+365*3600*24);}

$isok=query("update zzcms_main set bigclassid='$bigclassid',smallclassid='$smallclassid',smallclassids='$smallclassids',szm='$szm',prouse='$prouse',proname='$cpname',sm='$sm',img='$img',
flv='$flv',zc='$zc',yq='$yq',shuxing_value='$shuxing_value',passed='$passed',elite='$elite',elitestarttime='$elitestarttime',eliteendtime='$eliteendtime',
title='$title',keywords='$keywords',description='$description',sendtime='$sendtime',tag='$tag',skin='$skin',link='$link' where id='$cpid'");

if ($editor<>$oldeditor) {
$rs=query("select groupid,qq,comane,id,renzheng,province,city,xiancheng from zzcms_user where username='".$editor."'");
$row = num_rows($rs);
if ($row){
$row = fetch_array($rs);
$groupid=$row["groupid"];
$userid=$row["id"];
$qq=$row["qq"];
$comane=$row["comane"];
$renzheng=$row["renzheng"];
$province_user=$row["province"];
$city_user=$row["city"];
$xiancheng_user=$row["xiancheng"];
}else{
$groupid=0;$userid=0;$qq="";$comane="";$renzheng=0;$province_user='';$city_user='';$xiancheng_user='';
}
query("update zzcms_main set editor='$editor',userid='$userid',groupid='$groupid',qq='$qq',comane='$comane',province_user='$province_user',city_user='$city_user',xiancheng_user='$xiancheng_user',renzheng='$renzheng' where id='$cpid'");
}
//echo "<script>location.href='zs_manage.php?keyword=".$_POST["editor"]."&page=".$_REQUEST["page"]."'<//script>";
?>
<div class="boxsave"> 
    <div class="title"> <?php if ($isok) {echo "发布成功";}else{echo "发布失败";}?></div>
	<div class="content_a">
	名称：<?php echo $cpname?><br>
	
	<div class="editor">
	<li><a href="?do=modify&id=<?php echo $cpid?>&page=<?php echo $page?>&kind=<?php echo $_POST["kind"]?>&keyword=<?php echo $_POST["keyword"]?>">[修改]</a></li>
	<li><a href="<?php echo "zs_manage.php?keyword=".$_POST["keyword"]."&kind=".$_POST["kind"]."&page=".$_REQUEST["page"]?>">[返回]</a></li>
	<li><a href="<?php echo getpageurl("zs",$cpid)?>" target="_blank">[预览]</a></li>
	</div>
	</div>
	</div>
<?php 
if ($msg<>'' ){echo "<div class='border'>" .$msg."</div>";}
}
?>
</body>
</html>