<?php
header('content-type:text/html;charset=utf-8');
ini_set("max_execution_time", "180");
require('../../../public_include.php');
include Pagecall('static');

if($data['filter'] == 'ON' && $data['lanmu']==1)
{
		$rows = db()->select('a.id,a.title,a.poslink')->from(PRE.'article as a')->get()->array_rows();
	
		if(!empty($rows))
		{
			$i=0;
			echo '更新文档：'.date('Y-m-d H:i:s').'<br/>';
			foreach( $rows as $k => $v )
			{
				file_get_contents(apth_url('index.php?act=article_content&id='.$v['id']));
				$i++;
			}
			echo '-----------------------------------<br/>';
			echo '本次更新成功...! 共  '.$i.' 个文档<br/>';
			echo '-----------------------------------';
			
			//echo "<script>open('paging.php','update');</script>";
			if($_REQUEST['act'] == 3)
			{
				echo "<script>open('success.php','update');</script>";
			}
		}
}
else 
{#动态
	echo '当前使用动态...';
}