<?php
/**
 * @author Tanlin
 * 配置文件
 * */
$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
################################################################################################
#全局变量
$arr = explode('/', $_SERVER['REQUEST_URI']);
if(!empty($arr))
{
	array_pop($arr);
	$site_url = join('/',$arr);
}
#当前
define("SITE_URL", $sys_protocal.$_SERVER['HTTP_HOST'].$site_url);#绝对路径url
define("BASE_URL", $_SERVER['DOCUMENT_ROOT'].$site_url);#绝对路径dir
#全局路径
$row = db()->select('link')->from(PRE.'setting')->get()->array_row();
#自动获取路径,优先
$automatic_path = $sys_protocal.$_SERVER['HTTP_HOST'];
#验证自动路径
$checkPath = $automatic_path.'/check.txt';
@$str = file_get_contents( $checkPath );
if( $str == '验证成功' || $str != '' )
{#验证成功
	define("APTH_URL", $automatic_path);#绝对路径url 
}
else
{#验证不成功
	define("APTH_URL", $row['link']);#绝对路径url 
}
define("DIR_URL", $_SERVER['DOCUMENT_ROOT'].str_replace($sys_protocal.$_SERVER['HTTP_HOST'], '', APTH_URL));#绝对路径dir
################################################################################################
#登录模块
$loTop = 'LoginInterfaceTop';
$lobottom = 'LoginInterfaceBottom';
################################################################################################
#后台
$CTB = 'CreateTopBar';
$BC = 'BottomColumn';
$MC = 'MenuComponent';
$OF = 'OuterFrame';
################################################################################################
#全局配置
$review = db()->select('sitetimezone,development')->from(PRE.'review_up')->get()->array_row();
#网站时区
date_default_timezone_set($review['sitetimezone']);
#屏蔽错误提示信息,默认false不开启，true时开启
set_ini_error($review);
################################################################################################
#服务链接Service link
define("SERVICE_LINK", 'http://127.0.0.1/ThisCMSSystem/');
################################################################################################