<?php
require('../../../public_include.php');

$uppath = 'upload';

if($_FILES['file']['error'] == 0)
{
	$arr = explode('.', $_FILES['file']['name']);
	$ext = end($arr);
	if( strtolower($ext) != 'zip' )
	{
		echo '<p>&gt; 上传文件格式不正确！</p>';exit;
	}
	
	$destination = $uppath.'/'.$_FILES['file']['name'];
	
	if(move_uploaded_file($_FILES['file']['tmp_name'], $destination))
	{
		$path = './';
		$data = pclzip($destination, $path);
		if($data)
		{
			if(unlink($destination))
			{
				echo '<p>&gt; 备份文件导入成功：'.date('Y-m-d H:i:s').'</p>';
				echo '<p>&gt; 大小 '.get_size($_FILES['file']['size']).'</p>';
				echo '<p>&gt; 请点击“查看历史备份”，还原数据!</p>';
			}
			else 
			{
				echo '<p>&gt; 自动清理失败！</p>';
			}
		}
		else 
		{
			echo '<p>&gt; 备份文件导入失败！</p>';
		}
	}
	else 
	{
		echo '<p>&gt; 上传文件失败！</p>';
	}
}
else 
{
	echo '<p>&gt; 没有文件上传！</p>';
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