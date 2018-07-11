<?php
/**
 * 默认模板主题
 * */
define(DIRECTORY, $dir);
$act = $_GET['act']==''?'index':$_GET['act'];

define(ACT, $act);

if( $act!=null && function_exists( $act ) )
{
	$act();
}
else 
{
	header("content-type:text/html;charset=utf-8");
	echo '加载失败：模块不存在!';
}