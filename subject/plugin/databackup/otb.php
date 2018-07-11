<?php
require('../../../public_include.php');
date_default_timezone_set('PRC');

require 'this_base_backups.php';

$configpath =  dir_url('system/config/config.php');

if(is_file($configpath))
{
	$bacdir = dir_url('subject/plugin/databackup/backups');
	
	if(is_dir($bacdir))
	{
		$arrlist = opendir($bacdir);
		while ( ($item = readdir($arrlist))!==false )
		{
			if( $item!='.' && $item!='..' )
			{
				$f[] = $item;
			}		
		}
	}
	$lens = count($f);
	
	include $configpath;
	$db = new This_base_backups(SERVER, USERNAME, PASSWORD, BASENAME);
	#导出
	
	$directory = 'backups';
	
	if( !is_dir($directory) )
	{
		mkdir($directory,0777,true);
	}
	
	$int = $db -> export('subject/plugin/databackup/'.$directory.'/'); 
	
	if($int)
	{
		echo '<p>&gt; '.BAS.'dump -h'.SERVER.' -u'.USERNAME.' -p****** '.$db -> dbname.'　> ./backups/'.$db->filename.'</p>';
		echo '<p>&gt; 备份项、备份时间 '.date('Y-m-d H:i:s').'</p>';
		echo '<p>&gt; 数据库 '.$db -> dbname.' -> 结构</p>';	
		echo '<p>&gt; 数据表 </p>';
		foreach($db->tablename as $v)
		{
			echo '<p> &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; '.$v[0].' -> 结构,表数据</p>';
		}
		echo '<p>&gt; 数据备份成功，请点击查看历史备份</p>';
		echo '<p>&gt; 本次备分数量 1 份 大小 '.get_size($int).'　，　历史备份数量 '.$lens.' 份</p>';
	}
	else 
	{
		echo '<p>&gt; 数据备份失败</p>';
	}
	
}
else 
{
	echo '<p>&gt; 系统找不到数据配置文件</p>';
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