<?php
header('content-type:text/html;charset=utf-8');
date_default_timezone_set('PRC');
require('../../../public_include.php');

$path_zip = 'pack/data_packet.zip';

$act = $_GET['f'] == ''?'':$_GET['f'];
if( $act == '' )
{
	$path_filename = 'backups';
}
else 
{
	$path_filename = 'backups/'.$act;
}

#检测目录和文件是否空内容
$sizes = 0;
/*
if( is_dir( $path_filename ) )
{#如果是目录
	$arrlist = scandir($path_filename);
	foreach($arrlist as $k=>$v)
	{
		if( $v!='.' && $v!='..' )
		{
			$sizes += filesize($path_filename.'/'.$v);
		}
	}
}
elseif( is_file($path_filename) ) 
{#如果是文件
	$sizes = filesize($path_filename);
}
*/
if(is_dir($path_filename))
{
		$arrlist = opendir($path_filename);
		while ( ($item = readdir($arrlist))!==false )
		{
			if( $item!='.' && $item!='..' )
			{
				$sizes += filesize($path_filename.'/'.$item);
			}		
		}
}
elseif( is_file($path_filename) ) 
{#如果是文件
	$sizes = filesize($path_filename);
}

if( $sizes != 0 )
{#如果不是空文件或空目录才压缩

	$data = pclzip_compress($path_zip,$path_filename);
	
	if( $data )
	{
		$farr = 0;
		if( is_file($path_zip) )
		{
			$farr = filesize($path_zip);
		
			echo '<p>&gt; 备份数据压缩成功！</p>';
			echo '<p>&gt; 共 1 份文件，压缩格式 ZIP，大小 '.get_size($farr).'</p>';
			echo '<p>&gt; <a href="clear.php">清理压缩文件，节省空间</a></p>';
			echo '<script>open("'.$path_zip.'","update");</script>';
		}
	}
	else 
	{
		echo '<p>&gt; 还没有备份，未查到相关历史备份信息，下载失败！</p>';
	}
}
else 
{
	echo '<p>&gt; 还没有备份，未查到相关历史备份信息，下载失败！</p>';
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