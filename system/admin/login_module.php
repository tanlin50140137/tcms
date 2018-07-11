<?php
/**
 * @author Tanlin
 * 模块化模式
 * 头部
 */
function LoginInterfaceTop()
{
	$row = db()->select('title')->from(PRE.'setting')->get()->array_row();
	$filename = apth_url('subject/version.txt');
	$subject = file_get_contents($filename);
	$arr = explode('/', str_replace("\n", '', $subject));
	$subject = '<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1" />
	<meta name="robots" content="none" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0"/>
	<meta name="generator" content="'.$arr[1].' '.$row['title'].'" />
	<meta name="renderer" content="webkit" />
	<link rel="stylesheet" href="'.site_url('admin/css/common_style.css').'" type="text/css" media="screen" />
	<link rel="stylesheet" href="'.site_url('admin/css/login_style.css').'" type="text/css" media="screen" />
	<script src="'.site_url('admin/js/jquery-1.8.3.min.js').'" type="text/javascript"></script>
	<title>'.$row['title'].'-登录</title>
</head>';
	echo str_replace(array("\n","\t"), array("",""), $subject);
}
/**
 * 中部
 */
function LoginControl()
{
	$colorval = '#3A6EA5';
	if(file_exists('ColorXml.xml')){
		$xml = simplexml_load_file("ColorXml.xml");
		$colorval = (string)$xml->ColorVal;
	}
	$ck = '';
	$username = '';
	$password = '';
	if(isset($_COOKIE['username'])&&isset($_COOKIE['password'])){
		$ck = 'checked="checked"';
		$username = $_COOKIE['username'];
		$password = $_COOKIE['password'];
	}
		
	$body = '<body>';
	$body .= '<div class="big" style="background:'.$colorval.';">
			<div class="pifu">
				<label><a href="javascript::" id="p">换肤</a></label>
				<dl class="colors">
					<dd style="background:#2C82FC"></dd>
					<dd style="background:#CC0A0C"></dd>
					<dd style="background:#F0AD4E"></dd>
				</dl>
			</div>
			<div class="logo"><img src="../system/admin/images/logo.png"/></div>
		 </div>';
	$body .= '<form id="frm"><dl class="flow_layout clear">
		<dd class="fill1"><label class="us">用户名 </label><input type="text" class="input-txt" name="username" value="'.$username.'"/></dd><dd class="fill2"><label class="us">密码 </label><input type="password" class="input-txt" name="password" value="'.$password.'"/></dd>
		<dd><div class="fillBox1"><input type="checkbox" name="check" '.$ck.' id="cke" value="1"/> <label for="cke">保持登录</label></div></dd><dd class="fill2"><div class="fillBox2"><input type="submit" value="登录" class="sub"/></div></dd>
		</dl></form>';
	$body .= '<script>
		$(function(){
			var col = new Array("#3A6EA5","#CC0A0C","#F0AD4E");
			var i=0;
			$("#p").click(function(){
				if(i%2==0){
					$("#p").html("返回"),$(".colors").show();
				}else{
					$("#p").html("换肤"),$(".colors").hide();
				}
				i++;
			});
			$(".colors dd").each(function(index){
				$(this).click(function(){
					var colstr = col[index];
					$.post("external_request.php",{act:"Generate_xml",vars:colstr},function(data){
						$(".big").css({"background":colstr});
						$("#p").html("换肤"),$(".colors").hide();
						i=0;
					});
				});
			});			
});
		$(function(){
		$("#frm").submit(function(){		
			$.ajax({
			url:"external_request.php",
			type:"post",
			data:$("#frm").serialize()+"&act=Verification",
			success:function(data){
				if(data=="OK"){
location.href="admin/index.php";
}else{
	alert(data);
}				
			}
			});
			return false;
});
});
	</script>';
	$body .= '</body>';
	echo str_replace(array("\n","\t"), array("",""), $body);
}
/**
 * 底部
 */
function LoginInterfaceBottom()
{
	LoginControl();
	echo '</html>';
	if(isset($_COOKIE['username'])&&isset($_COOKIE['password'])){
		$result = db()->select('userName,pwd')->from(PRE.'login')->where(array('userName'=>$_COOKIE['username'],'pwd'=>$_COOKIE['password']))->get();
		$item = $result->array_nums();		
		if($item==1){
			echo '<script>location.href="admin/index.php";</script>';
		}
	}
}