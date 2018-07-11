<?php
header('content-type:text/html;charset=utf-8');
date_default_timezone_set('PRC');

$path_zip = 'pack/data_packet.zip';

if(is_file($path_zip))
{
	$size = filesize($path_zip);
	if(unlink($path_zip))
	{
		echo '<p>&gt; 清理时间：'.date('Y-m-d H:i:s').'</p>';
		echo '<p>&gt; 清理成功，节省 '.get_size($size).' 空间</p>';
	}
	else 
	{
		echo '<p>&gt; 清空失败！</p>';
	}
}
else 
{
	echo '<p>&gt; 已经清空！</p>';
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