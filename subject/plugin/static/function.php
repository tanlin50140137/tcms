<?php
require('../../../public_include.php');
include Pagecall('static');

$id = trim(htmlspecialchars($_REQUEST['id'],ENT_QUOTES));

if($id!=null)
{
	if($data['filter'] == 'ON')
	{	
		#内容页面
		file_get_contents(apth_url('index.php?act=article_content&id='.$id));
		if( $data['lanmu'] == 1 )
		{
			#列表页面
			file_get_contents(apth_url('index.php?act=article_list'));
		}
		if( $data['fy'] == 1 )
		{
			#网站设置
			$setreview = db()->select('listtotal')->from(PRE.'review_up')->get()->array_row();
			
			$rowsTotal = db()->select('id')->from(PRE.'article')->where('state=0 and (top=0 or top=102 or top=103)')->get()->array_nums();
			$showTotal = $setreview['listtotal']==''?10:$setreview['listtotal'];
			$pageTotal = ceil($rowsTotal/$showTotal);
			if( $pageTotal !=0 )
			{
				for($i=1;$i<=$pageTotal;$i++)
				{
					file_get_contents(apth_url('index.php?act=article_list&page='.$i));
				}
			}
		}
	}
}