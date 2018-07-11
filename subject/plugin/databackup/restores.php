<?php
require('../../../public_include.php');
date_default_timezone_set('PRC');
require 'this_base_backups.php';

$bacdir = dir_url('subject/plugin/databackup/backups');

$name = $_GET['f']==''?'':$_GET['f'];

$filename = $bacdir.'/'.$name;

$configpath =  dir_url('system/config/config.php');

#导入
include $configpath;
$db = new This_base_backups(SERVER, USERNAME, PASSWORD, BASENAME);

$int = $db->import('subject/plugin/databackup/backups/'.$name);
;
if($int){
	echo '<p>&gt; '.BAS.' -h'.SERVER.' -u'.USERNAME.' -p****** '.$db -> dbname.'　< ./backups/'.$name.'</p>';
	echo '<p>&gt; 本次还原  -> '.date('Y-m-d H:i:s',filemtime($filename)).' '.get_day((time()-filemtime($filename))).' 数据</p>';
	echo '<p>&gt; 数据库 '.$db -> dbname.' -> 结构</p>';	
	echo '<p>&gt; 数据表 -> 结构,表数据</p>';
	echo '<p>&gt; 本次还原成功， 大小 '.get_size(filesize($filename)).'</p>';
	echo '<p>&gt; <a href="history.php">返回</a></p>';
}else{
	
}
function get_size($int)
{
	$i = 0;
	while ( $int >= 1024 )
	{
		$int /= 1024;
		$i++;
	}
	$ext = array('B','KB','MB','GB','TB');
	
	return round($int,2).$ext[$i];
}
function get_day($int)
{
	if( $int < 86400 )
	{#秒->分->时 换算
		$i = 0;
		while ( $int >= 60 )
		{
			$int /= 60;
			$i++;
		}
		$ext = array('秒前','分钟前','小时前');
	}
	
	if( $int >= 86400 && $int < 2592000)
	{#时->天 换算
		$i = 0;
		while ( $int >= 86400 )
		{
			$int /= 86400;
			$i++;
		}
		$ext = array('小时前','天前');
	}
	if( $int >= 2592000 && $int < 31104000)
	{#天->月 换算
		$i = 0;
		while ( $int >= 2592000 )
		{
			$int /= 2592000;
			$i++;
		}
		$ext = array('天前','个月前');
	}
	if( $int >= 31104000 )
	{#月->年 换算
		$i = 0;
		while ( $int >= 31104000 )
		{
			$int /= 31104000;
			$i++;
		}
		$ext = array('个月前','年前');
	}
	return floor($int).$ext[$i];
}
?>