<?php
header('content-type:text/html;charset=utf-8');
require('../../../public_include.php');
include Pagecall('static');
if($data['filter'] == 'ON')
{
	if($data['index'] == 1)
	{#静态
		#更新首页
		file_get_contents(apth_url('index.php?static_goto=1'));
		echo '生成首页：'.date('Y-m-d H:i:s').'<br/>';
		echo '-----------------------------------<br/>';
		echo '生成首页成功...！　<a href="'.apth_url(getThemeDir().'/index.html').'" target="_blank">首页面</a><br/>';
		echo '-----------------------------------<br/>';
		
		if($_REQUEST['act'] == 1)
		{
			echo "<script>open('column.php?act=2','update');</script>";
		}
		
	}
	elseif($data['index'] == 0)
	{#动态
		echo '当前使用动态...! <a href="'.apth_url('index.php').'" target="_blank">首页面</a>';
	}
}
else 
{#动态
	echo '当前使用动态...! <a href="'.apth_url('index.php').'" target="_blank">首页面</a>';
}