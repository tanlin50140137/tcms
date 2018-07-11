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
	$filename = '../config/config.php';
	if(!file_exists($filename))
	{
		header("location:../../install/");exit;
	}
}

TestInstallation();

require '../../public_include.php';
require 'admin_module.php';

$data = file_get_contents('../pingmupixels.xml');
$pixels = simplexml_load_string($data);
$w = (int)$pixels->pixels;

if( $w > 1250)
{
	$act = $_GET['act']==''?'admin':$_GET['act'];
}
else 
{
	$act = $_GET['act']==''?'admin_phone':$_GET['act'];
}

$loTop();

$CTB = $CTB();
$MC = $MC();
if( $act!=null && function_exists( $act ) )
{
	$act = $act();
}
#T开框架
$OF($CTB,$MC,$act);

$BC();