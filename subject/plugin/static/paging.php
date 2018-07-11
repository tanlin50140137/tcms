<?php
header('content-type:text/html;charset=utf-8');
require('../../../public_include.php');
include Pagecall('static');
#网站设置
$setreview = db()->select('listtotal')->from(PRE.'review_up')->get()->array_row();

$rowsTotal = db()->select('id')->from(PRE.'article')->where('state=0 and (top=0 or top=102 or top=103)')->get()->array_nums();
$showTotal = $setreview['listtotal']==''?10:$setreview['listtotal'];
$pageTotal = ceil($rowsTotal/$showTotal);

if($data['filter'] == 'ON')
{
	if($data['fy'] == 1)
	{#静态	
		if( $pageTotal !=0 )
		{
			$p=0;
			for($i=1;$i<=$pageTotal;$i++)
			{
				file_get_contents(apth_url('index.php?act=article_list&page='.$i));
				$p++;
			}
			echo '更新列表分页：'.date('Y-m-d H:i:s').'<br/>';
			echo '-----------------------------------<br/>';
			echo '更新列表分页成功...！　共 '.$p.' 个分页<br/>';
			echo '-----------------------------------<br/>';
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