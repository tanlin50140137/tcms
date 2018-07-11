<?php
header('content-type:text/html;charset=utf-8');
ini_set("max_execution_time", "180");
require('../../../public_include.php');
include Pagecall('static');
if($data['filter'] == 'ON')
{
	if($data['lanmu'] == 1)
	{#静态
		#更新栏目
		$columnList = GetFenLaiList_static(0);

		$c = 0;
		if(!empty($columnList))
		{
			foreach($columnList as $k=>$v)
			{
				file_get_contents(apth_url('index.php?act=article_list&id='.$v['id']));
				$c++;
			}
		}
		echo '更新栏目：'.date('Y-m-d H:i:s').'<br/>';
		echo '-----------------------------------<br/>';
		echo '更新栏目成功...！　共 '.$c.' 个栏目<br/>';
		echo '-----------------------------------<br/>';
		if($_REQUEST['act'] == 2)
		{
			echo "<script>open('content.php?act=3','update');</script>";
		}
	}
	elseif($data['lanmu'] == 0)
	{#动态		
		echo '当前使用动态...';
	}
}
else 
{#动态
	echo '当前使用动态...';
}