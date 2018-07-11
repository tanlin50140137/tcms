<?php
require('../../../public_include.php');
date_default_timezone_set('PRC');

$bacdir = dir_url('subject/plugin/databackup/backups');

$name = $_GET['f']==''?'':$_GET['f'];

$filename = $bacdir.'/'.$name;

if(file_exists($filename))
{
	unlink($filename);
	
	header('location:history.php?page='.$_GET['page']);
}
else 
{
	echo "<script>alert('备份文件不存在，无法删除！');location.href='history.php?page='".$_GET['page'].";</script>";
}