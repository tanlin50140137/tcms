<?php
/**
 * @author Tanlin
 * 登录后台管理
 * 响应式登录页面
 * 登录支持：pc+手机+平板
 * php安全高效运行
 * 
 * */
#检测安装
function TestInstallation()
{
	$string = $_SERVER['REQUEST_URI'];
	$arr = explode('/', $string);	
	
	$dirpath = '../';		
	$urlpath = 'http://'.$_SERVER['HTTP_HOST'].'/'.($arr[1]==''?'':$arr[1].'/'); 

	$filename = $dirpath.'system/config/config.php';
	if(!file_exists($filename))
	{
		header("location:".$urlpath."install/");exit;
	}
}

TestInstallation();
require '../public_include.php';
require 'admin/login_module.php';
$loTop();
$lobottom();