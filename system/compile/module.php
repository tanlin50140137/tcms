<?php
/**
 * 动态模块
 * */
require_once '../../public_include.php';

#系统模块
$act = $_REQUEST['act']==''?'':trim($_REQUEST['act']);

#导航条
function Muen()
{
	$comlist = get_columnList(0);
	
	if( $_REQUEST['divorul'] == 2 )
	{
		$html = '<li><a href="'.apth_url('index.php').'">首页</a></li>';
		if(!empty($comlist))
		{
			foreach($comlist as $k=>$v)
			{
				$html .= '<li><a href="'.$v['link'].'" target="_blank">'.$v['title'].'</a></li>';
			}
		}
	}
	else 
	{
		$html = '<div><a href="'.apth_url('index.php').'">首页</a></div>';
		if(!empty($comlist))
		{
			foreach($comlist as $k=>$v)
			{
				$html .= '<div><a href="'.$v['link'].'" target="_blank">'.$v['title'].'</a></div>';
			}
		}
	}
	return $html;
}
#日历
function Calendar()
{
	#当日星期几
	$day = date('Y-m').'-01';
	$times = strtotime($day);
	$wt = date("w",$times); 
	#月份
	if(in_array(date('m'), array(1, 3, 5, 7, 8, 10, 12)))
	{#大月 1 3 5 7 8 10 12 - 31天
		$dates = 31;
	}
	elseif(in_array(date('m'), array(4, 6, 9, 11)))
	{#小月4 6 9 11 - 30天
		$dates = 30;
	}
	else 
	{#2-闰29天，平28天
		if( date('L') == 1 )
		{
			$dates = 29;
		}
		else 
		{
			$dates = 28;
		}
	}
	$c=1+$wt;
	for($i=1;$i<=$dates;$i++)
	{
		$tmp='';
		if( $c == 7 ){$tmp='-';$c = 0;}
		
		$lists .= $i.','.$tmp;
		
		$c++;
	}	
	$zwf = str_repeat('&nbsp;'.',', $wt);
	$array = explode('-', $zwf.$lists);
	foreach( $array as $k => $v )
	{
		$arr = explode(',', $v);
		array_pop($arr);
		$rows[] = $arr;
	}
	
	$flag = 'style="text-align:center;background:#F0F0F0;color:#666666;"';
	
	if( $_REQUEST['divorul'] == 2 )
	{
		$html = '<li id="dayListLd">
		<table style="border:1px solid #F0F0F0;width:100%;">
			<tr>
				<td colspan="7" style="border-bottom:1px solid #F0F0F0;text-align:center;">
				<a href="javascript:;" onclick="upmoth();" style="font-size:16px;">&laquo;</a>
				 <span id="yyyy">'.date('Y').'</span>-<span id="mm">'.date('m').'</span> 
				 <a href="javascript:;" onclick="nextmoth();" style="font-size:16px;">&raquo;</a>
				 </td>
			</tr>
			<tr style="text-align:center;">
				<td>日</td>
				<td>一</td>
				<td>二</td>
				<td>三</td>
				<td>四</td>
				<td>五</td>
				<td>六</td>
			</tr>';
foreach( $rows as $k => $v )
{			
		$html .= '<tr style="text-align:center;cursor:pointer;color:#999999;">';
	foreach($v as $val)
	{	
		if( date('d') == $val )
		{
			$html .= '<td '.$flag.'><a href="'.apth_url('?act=article_list&filed='.date('Y').'-'.$val).'">'.$val.'</a></td>';
		}
		else 
		{
			if($val!="&nbsp;")
			{
				$html .= '<td><a href="'.apth_url('?act=article_list&filed='.date('Y').'-'.$val).'">'.$val.'</a></td>';
			}
			else 
			{
				$html .= '<td>'.$val.'</td>';
			}
		}
	}	
		$html .= '</tr>';
}			
	$html .= '</table>
		</li>';
	}
	else 
	{
		$html = '<div id="dayListLd">
			<table style="border:1px solid #F0F0F0;width:100%;">
			<tr>
				<td colspan="7" style="border-bottom:1px solid #F0F0F0;text-align:center;">
				<a href="javascript:;" onclick="upmoth();" style="font-size:16px;">&laquo;</a>
				 <span id="yyyy">'.date('Y').'</span>-<span id="mm">'.date('m').'</span> 
				 <a href="javascript:;" onclick="nextmoth();" style="font-size:16px;">&raquo;</a>
				</td>
			</tr>
			<tr style="text-align:center;">
				<td>日</td>
				<td>一</td>
				<td>二</td>
				<td>三</td>
				<td>四</td>
				<td>五</td>
				<td>六</td>
			</tr>';
foreach( $rows as $k => $v )
{			
		$html .= '<tr style="text-align:center;cursor:pointer;color:#999999;">';
	foreach($v as $val)
	{	
		if( date('d') == $val )
		{
			$html .= '<td '.$flag.'><a href="'.apth_url('?act=article_list&filed='.date('Y').'-'.$val).'">'.$val.'</a></td>';
		}
		else 
		{
			if($val!="&nbsp;")
			{
				$html .= '<td><a href="'.apth_url('?act=article_list&filed='.date('Y').'-'.$val).'">'.$val.'</a></td>';
			}
			else 
			{
				$html .= '<td>'.$val.'</td>';
			}
		}
	}	
		$html .= '</tr>';
}			
	$html .= '</table>
		</div>';
	}
	
	$html .= '<script>
		var y = "'.date('Y').'"; 
		var m = "'.date('m').'";
		function upmoth()
		{	
			m--;
			if( m < 1 )
			{
				m = 12;
				y = parseInt(y)-1;
			}
			document.getElementById("yyyy").innerHTML = y;
			document.getElementById("mm").innerHTML = getNumer(m);
			$.post("'.apth_url('system/external_request.php').'",{act:"Daywebs",yyyy:y,mm:getNumer(m)},function(data){
				document.getElementById("dayListLd").innerHTML = data;
			});		
		}
		function nextmoth()
		{
			m++;
			if( m > 12 )
			{
				m = 1;
				y = (parseInt(y)+1);
			}
			document.getElementById("yyyy").innerHTML = y;
			document.getElementById("mm").innerHTML = getNumer(m);
			$.post("'.apth_url('system/external_request.php').'",{act:"Daywebs",yyyy:y,mm:getNumer(m)},function(data){
				document.getElementById("dayListLd").innerHTML = data;
			});
		}
		function getNumer(int)
		{
			if(int<10){int="0"+int;}
			return int;
		}
	</script>';
	
	return $html;
}
#站点信息
function Siteinformation()
{
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$artTotal = db()->select('id')->from(PRE.'article')->where('timerel<="'.time().'" and templateid='.$theme['id'])->get()->array_nums();
	
	if(!empty($theme))
	{
	$tempTotal = db()->select('id')->from(PRE.'template')->where(array('templateid'=>$theme['id']))->get()->array_nums();
	$browseRows = db()->select('browse')->from(PRE.'browse')->where('FROM_UNIXTIME(publitime,"%Y-%m-%d")="'.date('Y-m-d').'" and templateid='.$theme['id'])->get()->array_row();
	}
	$claTotal = db()->select('id')->from(PRE.'classified')->where(array('templateid'=>$theme['id']))->get()->array_nums();
	$tagTotal = db()->select('id')->from(PRE.'tag')->get()->where(array('templateid'=>$theme['id']))->array_nums();
	$reviewTotal = db()->select('id')->from(PRE.'review')->where(array('templateid'=>$theme['id']))->get()->array_nums();
	if( $_REQUEST['divorul'] == 2 )
	{
		$html = '<li>文章总数: '.$artTotal.'</li>';
		$html .= '<li>页面总数: '.$tempTotal.'</li>';
		$html .= '<li>分类总数: '.$claTotal.'</li>';
		$html .= '<li>标签总数: '.$tagTotal.'</li>';
		$html .= '<li>评论总数: '.$reviewTotal.'</li>';
		$html .= '<li>浏览总数: '.$browseRows['browse'].'</li>';
	}
	else 
	{
		$html = '<div>文章总数: '.$artTotal.'</div>';
		$html .= '<div>页面总数: '.$tempTotal.'</div>';
		$html .= '<div>分类总数: '.$claTotal.'</div>';
		$html .= '<div>标签总数: '.$tagTotal.'</div>';
		$html .= '<div>评论总数: '.$reviewTotal.'</div>';
		$html .= '<div>浏览总数: '.$browseRows['browse'].'</div>';
	}
	return $html;
}
#控制面板
function Controlpanel()
{
	if( $_REQUEST['divorul'] == 2 )
	{
		$html = '<li><span class="cp-hello">您好，欢迎到访网站！</span><br/>
		<span class="cp-login">
		<a href="'.apth_url('system/login.php').'">登录后台</a>
		</span>&nbsp;&nbsp;<span class="cp-vrs">
		<a href="'.apth_url('system/power.php').'" target="_blank">权限管理</a>
		</span></li>';
	}
	else 
	{
		$html = '<div><span class="cp-hello">您好，欢迎到访网站！</span><br/>
		<span class="cp-login">
		<a href="'.apth_url('system/login.php').'">登录后台</a>
		</span>&nbsp;&nbsp;<span class="cp-vrs">
		<a href="'.apth_url('system/power.php').'" target="_blank">权限管理</a>
		</span></div>';
	}
	return $html;
}
#站内搜索
function Search()
{
	if( $_REQUEST['divorul'] == 2 )
	{
		$html = '<li><!--<form action="'.apth_url('?act=article_list').'" method="post" name="frm" id="m_frm" class="m_frm">-->
		<input type="text" name="search" placeholder="搜索" id="m_search" class="m_search"/>
		<input type="submit" value="搜索" id="m_button" class="m_button" onclick="if(document.getElementById(\'m_search\').value!=\'\'){location.href=\''.apth_url('?act=article_list&search=').'\'+document.getElementById(\'m_search\').value;}"/>
		<!--</from>--></li>';
	}
	else 
	{
		$html = '<div><!--<form action="'.apth_url('?act=article_list').'" method="post" name="frm" id="m_frm" class="m_frm">-->
		<input type="text" name="search" placeholder="搜索" id="m_search" class="m_search"/>
		<input type="submit" value="搜索" id="m_button" class="m_button" onclick="if(document.getElementById(\'m_search\').value!=\'\'){location.href=\''.apth_url('?act=article_list&search=').'\'+document.getElementById(\'m_search\').value;}"/>
		<!--</from>--></div>';
	}
	return $html;
}
#网站分类
function Categories()
{
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$rows = db()->select('a.id,classified,(select count(id) from '.PRE.'article where cipid=a.id) as total')->from(PRE.'classified as a')->where(array('templateid'=>$theme['id']))->get()->array_rows();
	if($_REQUEST['flag'] == 0)
	{
		if( !empty( $rows ) )
		{
			if( $_REQUEST['divorul'] == 2 )
			{
				foreach( $rows as $k => $v )
				{
					$html .= '<li><a href="'.apth_url('?act=article_list&cipid='.$v['id']).'">'.$v['classified'].'('.$v['total'].')</a></li>';
				}
			}
			else 
			{
				foreach( $rows as $k => $v )
				{
					$html .= '<div><a href="'.apth_url('?act=article_list&cipid='.$v['id']).'">'.$v['classified'].'('.$v['total'].')</a></div>';
				}
			}
		}
	}
	else 
	{
		$html = json_encode($rows);
	}			
	return $html;
}
#最新评论
function Comments()
{
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$rows = db()->select('id,body,likes')->from(PRE.'review')->where(array('templateid'=>$theme['id']))->order_by('publitime desc')->limit('0,6')->get()->array_rows();
	if($_REQUEST['flag'] == 0)
	{
		if( !empty( $rows ) )
		{
			if( $_REQUEST['divorul'] == 2 )
			{
				foreach( $rows as $k => $v )
				{
					$html .= '<li><a href="'.apth_url('?act=article_content&id='.$v['id']).'">'.$v['body'].'('.$v['likes'].')</a></li>';
				}
			}
			else 
			{
				foreach( $rows as $k => $v )
				{
					$html .= '<div><a href="'.apth_url('?act=article_content&id='.$v['id']).'">'.$v['body'].'('.$v['likes'].')</a></div>';
				}
			}
		}	
	}
	else 
	{
		$html = json_encode($rows);
	}	
	return $html;
}
#文章归档
function Archive()
{
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$rows = db()->select('FROM_UNIXTIME(publitime,"%Y") as year,FROM_UNIXTIME(publitime,"%m") as nomth,count(id) as count')->from(PRE.'article')->group_by('FROM_UNIXTIME(publitime,"%Y"),FROM_UNIXTIME(publitime,"%m"),templateid')->having('templateid='.$theme['id'])->order_by('FROM_UNIXTIME(publitime,"%Y") desc,FROM_UNIXTIME(publitime,"%m")  desc')->get()->array_rows();
	if($_REQUEST['flag'] == 0)
	{
		if( !empty( $rows ) )
		{
			if( $_REQUEST['divorul'] == 2 )
			{
				foreach( $rows as $k => $v )
				{
					$html .= '<li><a href="'.apth_url('?act=article_list&filed='.$v['year'].'-'.$v['nomth']).'">'.$v['year'].'-'.$v['nomth'].'('.$v['count'].')</a></li>';
				}
			}
			else 
			{
				foreach( $rows as $k => $v )
				{
					$html .= '<div><a href="'.apth_url('?act=article_list&filed='.$v['year'].'-'.$v['nomth']).'">'.$v['year'].'-'.$v['nomth'].'('.$v['count'].')</a></div>';
				}
			}
		}
	}
	else 
	{
		$html = json_encode($rows);
	}		
	return $html;
}
#作者列表
function AuthorList()
{	
	$rows = db()->select('userName,(select count(id) from '.PRE.'article where author=userName) as total')->from(PRE.'login')->get()->array_rows();
	if($_REQUEST['flag'] == 0)
	{	
		if( !empty( $rows ) )
		{
			if( $_REQUEST['divorul'] == 2 )
			{
				foreach( $rows as $k => $v )
				{
					$html .= '<li><a href="'.apth_url('?act=article_list&author='.$v['userName']).'">'.$v['userName'].'('.$v['total'].')</a></li>';
				}
			}
			else 
			{
				foreach( $rows as $k => $v )
				{
					$html .= '<div><a href="'.apth_url('?act=article_list&author='.$v['userName']).'">'.$v['userName'].'('.$v['total'].')</a></div>';
				}
			}
		}	
	}
	else 
	{
		$html = json_encode($rows);
	}	
	return $html;
}
#最近发表
function Published()
{
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	include Pagecall('static');
	$rows = db()->select('id,title,FROM_UNIXTIME(publitime,"%Y-%m-%d %H:%i") as publitime,alias,keywords,description,imgurl,Thumurl,browse,cover,price,orprice,Sales,chain,sizetype,columnid,poslink')->from(PRE.'article')->where('timerel<="'.time().'" and templateid='.$theme['id'])->order_by("publitime desc")->limit('0,'.($_REQUEST['r']==''?5:$_REQUEST['r']))->get()->array_rows();
	if($_REQUEST['flag'] == 0)
	{	
		if( !empty( $rows ) )
		{
			if( $data['filter'] == 'ON' && $data['lanmu'] == 1 )
			{
				if( $_REQUEST['divorul'] == 2 )
				{
					foreach( $rows as $k => $v )
					{
						$html .= '<li><a href="'.getArtStaticUrl($v['poslink']).'">'.$v['title'].'</a></li>';
					}
				}
				else 
				{
					foreach( $rows as $k => $v )
					{
						$html .= '<div><a href="'.getArtStaticUrl($v['poslink']).'">'.$v['title'].'</a></div>';
					}
				}
			}
			else 
			{
				if( $_REQUEST['divorul'] == 2 )
				{
					foreach( $rows as $k => $v )
					{
						$html .= '<li><a href="'.apth_url('?act=article_content&id='.$v['id']).'">'.$v['title'].'</a></li>';
					}
				}
				else 
				{
					foreach( $rows as $k => $v )
					{
						$html .= '<div><a href="'.apth_url('?act=article_content&id='.$v['id']).'">'.$v['title'].'</a></div>';
					}
				}
			}
		}	
	}
	else 
	{
		if(!empty($rows))
		{
			foreach( $rows as $k=>$v )
			{
				if(!strrpos($v['cover'], 'a-ettra01.jpg')&&$v['cover']!='')
				{#封面图片不是默认值时,使用封面
					$rows[$k]['img'] = $v['cover'];
				}
				else 
				{#封面图片是默认值时,使用其他图片
					if( $v['imgurl']=='null' || $v['imgurl']=="" )
					{#内容图片不空时使用，默认图片
						$rows[$k]['img'] = apth_url($v['cover']);
					}
					else 
					{
						$rows[$k]['img'] = showImg($v['imgurl'],'rand');
					}
				}
				#多加一个无<html>描述
				$rows[$k]['descriptions'] = subString(strip_tags($v['description']),250);
				#动态链接与静态链接,文章列表
				if( $data['filter'] == 'ON' && $data['lanmu']==1 )
				{#静态链接
					$rows[$k]['link'] = getArtStaticUrl($v['poslink']);
				}
				else 
				{#动态链接
					$rows[$k]['link'] = apth_url('?act=article_content&id='.$v['id']);
				}
			}
		}
		$html = json_encode($rows);
	}
	return $html;
}
#热门推荐
function Hot()
{
	include Pagecall('static');
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	if($_REQUEST['columnmodule']!='')
	{
		$template = db()->select('id')->from(PRE.'template')->where(array('module'=>$_REQUEST['columnmodule']))->get()->array_row();
		
		$idList = get_columlist_id($template['id']);
		if( !empty($idList) )
		{
			$listsql = ' or columnid='.join(' or columnid=',$idList);
			$where = " templateid={$theme['id']} and timerel<='".time()."' and (columnid={$template['id']} {$listsql})";
		}
		else 
		{
			$where = " templateid={$theme['id']} and timerel<='".time()."' ";
		}
	}
	else 
	{
		$where = " templateid={$theme['id']} and timerel<='".time()."' ";
	}
	
	$rows = db()->select('id,title,FROM_UNIXTIME(publitime,"%Y-%m-%d %H:%i") as publitime,alias,keywords,description,imgurl,Thumurl,browse,cover,price,orprice,Sales,chain,sizetype,columnid,poslink')->from(PRE.'article')->where($where)->order_by("browse desc")->limit('0,'.($_REQUEST['r']==''?5:$_REQUEST['r']))->get()->array_rows();
	if($_REQUEST['flag'] == 0)
	{
		if( !empty( $rows ) )
		{
			if( $data['filter'] == 'ON' && $data['lanmu'] == 1 )
			{
				if( $_REQUEST['divorul'] == 2 )
				{
					foreach( $rows as $k => $v )
					{
						$html .= '<li><a href="'.getArtStaticUrl($v['poslink']).'">'.$v['title'].'</a></li>';
					}
				}
				else 
				{
					foreach( $rows as $k => $v )
					{
						$html .= '<div><a href="'.getArtStaticUrl($v['poslink']).'">'.$v['title'].'</a></div>';
					}
				}
			}
			else 
			{
				if( $_REQUEST['divorul'] == 2 )
				{
					foreach( $rows as $k => $v )
					{
						$html .= '<li><a href="'.apth_url('?act=article_content&id='.$v['id']).'">'.$v['title'].'</a></li>';
					}
				}
				else 
				{
					foreach( $rows as $k => $v )
					{
						$html .= '<div><a href="'.apth_url('?act=article_content&id='.$v['id']).'">'.$v['title'].'</a></div>';
					}
				}
			}
		}	
	}
	else 
	{
		if(!empty($rows))
		{
			foreach( $rows as $k=>$v )
			{
				if(!strrpos($v['cover'], 'a-ettra01.jpg')&&$v['cover']!='')
				{#封面图片不是默认值时,使用封面
					$rows[$k]['img'] = $v['cover'];
				}
				else 
				{#封面图片是默认值时,使用其他图片
					if( $v['imgurl']=='null' || $v['imgurl']=="" )
					{#内容图片不空时使用，默认图片
						$rows[$k]['img'] = apth_url($v['cover']);
					}
					else 
					{
						$rows[$k]['img'] = showImg($v['imgurl'],'rand');
					}
				}
				#多加一个无<html>描述
				$rows[$k]['descriptions'] = subString(strip_tags($v['description']),50);
				#动态链接与静态链接,文章列表
				if( $data['filter'] == 'ON' && $data['lanmu']==1 )
				{#静态链接
					$rows[$k]['link'] = getArtStaticUrl($v['poslink']);
				}
				else 
				{#动态链接
					$rows[$k]['link'] = apth_url('?act=article_content&id='.$v['id']);
				}
			}
		}
		$html = json_encode($rows);
	}
	return $html;
}
#标签列表
function TagList()
{
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$rows = db()->select('keywords,addmenu')->from(PRE.'tag')->where(array('templateid'=>$theme['id']))->order_by('id desc')->get()->array_rows();
	if($_REQUEST['flag'] == 0)
	{	
		if( !empty( $rows ) )
		{
			if( $_REQUEST['divorul'] == 2 )
			{
				foreach( $rows as $k => $v )
				{
					$html .= '<li><a href="'.apth_url('?act=article_list&tag='.$v['keywords']).'">'.$v['keywords'].'</a></li>';
				}
			}
			else 
			{
				foreach( $rows as $k => $v )
				{
					$html .= '<div><a href="'.apth_url('?act=article_list&tag='.$v['keywords']).'">'.$v['keywords'].'</a></div>';
				}
			}
		}	
	}
	else 
	{
		$html = json_encode($rows);
	}
	return $html;
}
if( $act!=null && function_exists( $act ) )
{
	echo $act();
}
else 
{
	echo null;
}