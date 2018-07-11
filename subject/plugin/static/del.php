<?php
header('content-type:text/html;charset=utf-8');
require('../../../public_include.php');
include Pagecall('static');
echo '当前状态： '.date('Y-m-d H:i:s').'<br/>';
echo '-------------------------------------<br/>';
if($data['filter'] == 'ON')
{
	echo '静态化功能 -> 开启<br/>';
}
else 
{
	echo '静态化功能 -> 关闭<br/>';
}
if($data['art'] == 1)
{
	echo '文章发布 -> 静态<br/>';
}
else 
{
	echo '文章发布 -> 动态<br/>';
}
if($data['index'] == 1)
{
	echo '首页 -> 静态<br/>';
}
else 
{
	echo '首页 -> 动态<br/>';
}
if($data['lanmu'] == 1)
{
	echo '栏目列表 -> 静态<br/>';
}
else 
{
	echo '栏目列表 -> 动态<br/>';
}
if($data['fy'] == 1)
{
	echo '列表分页 -> 静态<br/>';
}
else 
{
	echo '列表分页-> 动态<br/>';
}