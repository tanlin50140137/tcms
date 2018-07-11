<?php
require '../public_include.php';

##############################################################################################
function Daywebs()
{
	#当日星期几
	$day = $_POST['yyyy'].'-'.$_POST['mm'].'-01';
	$times = strtotime($day);
	$wt = date("w",$times); 
	#月份
	if(in_array($_POST['mm'], array(1, 3, 5, 7, 8, 10, 12)))
	{#大月 1 3 5 7 8 10 12 - 31天
		$dates = 31;
	}
	elseif(in_array($_POST['mm'], array(4, 6, 9, 11)))
	{#小月4 6 9 11 - 30天
		$dates = 30;
	}
	else 
	{#2-闰29天，平28天
		if(($_POST['yyyy']%4==0)&&($_POST['yyyy'] % 100!=0)||($_POST['yyyy'] % 400==0))
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
	
	$html = '<table style="border:1px solid #F0F0F0;width:100%;">
			<tr>
				<td colspan="7" style="border-bottom:1px solid #F0F0F0;text-align:center;">
				<a href="javascript:;" onclick="upmoth();" style="font-size:16px;">&laquo;</a>
				 <span id="yyyy">'.$_POST['yyyy'].'</span>-<span id="mm">'.$_POST['mm'].'</span> 
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
		if( $_POST['yyyy'] == date('Y') && $_POST['mm'] == date('m') && date('d') == $val )
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
	$html .= '</table>';
	
	echo $html;
}
##############################################################################################
/**
 * 生成xml字符串
 * @param Array $array
 * @return string
 */
function Generate_str($array)
{
	$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$xml .= '<box>'."\n";
	foreach($array as $key=>$val)
	{
		$xml .= '<'.$key.'>'.$val.'</'.$key.'>'."\n";
	}
	$xml .= '</box>';
	return $xml;
}
##############################################################################################
//成生xml文件
function Generate_xml($array,$filename)
{
	file_put_contents($filename, Generate_str($array));
}
###############################################################################################
function Verification($array)
{
	session_start();
	if($array['username']==''){
		echo '请输入用户名';
	}else{
		if($array['password']==''){
			echo '请输入密码';
		}else{
			//安全处理
			$username = mysql_escape_string(mb_substr($array['username'], 0,32,'utf-8'));
			$password = mysql_escape_string(mb_substr($array['password'], 0,32,'utf-8'));		
			$password = substr(md5(base64_decode($password)),10,10);
			//保持登录
			if($array['check']=='1'){
				setcookie(username,$username,time()+60*60*24*365,"/");
				setcookie(password,$password,time()+60*60*24*365,"/");
			}else{
				setcookie(username,null,time()-1,"/");
				setcookie(password,null,time()-1,"/");
			}
			#选看权限
			$level = db()->select('level')->from(PRE.'login')->where(array('userName'=>$username))->get()->array_row();
			if( $level['level'] !=5 )
			{
				//验证用户名、密码
				$result = db()->select('userName,pwd')->from(PRE.'login')->where(array('userName'=>$username,'pwd'=>$password,'flag'=>0,'state'=>0))->get();
				$item = $result->array_nums();
				
				if($item==1){
					$_SESSION['username'] = $username;
					echo 'OK';	
				}else{
					echo '用户名、密码不正确或被管理员禁止、审核状态权限不足无法登录';
				}
			}
			else
			{
				echo '权限不足无法登录';
			}			
		}
	}
}
###########################################################################################
function ChangeStyle($array,$filename)
{
	Generate_xml($array,$filename);
}
function MenuStyle($array,$filename)
{
	Generate_xml($array,$filename);
}
###########################################################################################
function Classified()
{
	$rows = db()->select('*')->from(PRE.'classified')->get()->array_nums();
	if( $rows !=0 )
	{
		$int = db()->select('*')->from(PRE.'classified')->where(array('classified'=>$_POST['classified']))->get()->array_nums();
		if($int)
		{
			echo 'ON';
		}
		else 
		{
			echo 'OK';
		}
	}
	else 
	{
		echo 'OK';
	}
}
function TagEdt()
{
	$rows = db()->select('*')->from(PRE.'tag')->get()->array_nums();
	if( $rows !=0 )
	{
		$int = db()->select('*')->from(PRE.'tag')->where(array('keywords'=>$_POST['TagEdt']))->get()->array_nums();
		if($int)
		{
			echo 'ON';
		}
		else 
		{
			echo 'OK';
		}
	}
	else 
	{
		echo 'OK';
	}
}
function PageEdt()
{	
if(!empty($_POST['name']))
{	
	if( !empty($_POST['name']) )
	{
		$arr = array('name'=>$_POST['name']);
	}
	else if( !empty($_POST['module']) )
	{
		$arr = array('module'=>$_POST['module']);
	}
	
	$int = db()->select('*')->from(PRE.'template')->where($arr)->get()->array_nums();
	if($int)
	{
		echo 'ON';
	}
	else 
	{
		echo 'OK';
	}
}
}
function Theme_edit()
{
	$int = db()->select('*')->from(PRE.'theme')->where(array('themename'=>$_POST['val']))->get()->array_nums();
	if($int)
	{
		echo 'ON';
	}
	else 
	{
		echo 'OK';
	}
}
function ModuleEdt2()
{
	$int = db()->select('id')->from(PRE.'module')->where(array('filename'=>$_POST['val']))->get()->array_nums();
	if($int)
	{
		echo 'ON';
	}
	else 
	{
		echo 'OK';
	}
}
function SidebarSet()
{
	#no1
	$row = db()->select('flags')->from(PRE.'storage')->where(array('name'=>'sidebar'))->get()->array_row();	
	if( !empty($row) )
	{
		if( $row['flags'] != $_POST['sidebar'] )
		{
			$sidebar = explode('|', $_POST['sidebar']);
			SidebarSet_fu(PRE.'storage',$sidebar,'sidebar',$_POST['sidebar']);
		}
	}
	else 
	{
		$sidebar = explode('|', $_POST['sidebar']);
		SidebarSet_fu(PRE.'storage',$sidebar,'sidebar',$_POST['sidebar']);
	}
	#no2
	$row = db()->select('flags')->from(PRE.'storage')->where(array('name'=>'sidebar2'))->get()->array_row();	
	if( !empty($row) )
	{
		if( $row['flags'] != $_POST['sidebar2'] )
		{
			$sidebar2 = explode('|', $_POST['sidebar2']);
			SidebarSet_fu(PRE.'storage',$sidebar2,'sidebar2',$_POST['sidebar2']);
		}
	}
	else 
	{
		$sidebar2 = explode('|', $_POST['sidebar2']);
		SidebarSet_fu(PRE.'storage',$sidebar2,'sidebar2');
	}
	#no3
	$row = db()->select('flags')->from(PRE.'storage')->where(array('name'=>'sidebar3'))->get()->array_row();	
	if( !empty($row) )
	{
		if( $row['flags'] != $_POST['sidebar3'] )
		{
			$sidebar3 = explode('|', $_POST['sidebar3']);
			SidebarSet_fu(PRE.'storage',$sidebar3,'sidebar3',$_POST['sidebar3']);
		}
	}
	else 
	{
		$sidebar3 = explode('|', $_POST['sidebar3']);
		SidebarSet_fu(PRE.'storage',$sidebar3,'sidebar3');
	}
	#no4
	$row = db()->select('flags')->from(PRE.'storage')->where(array('name'=>'sidebar4'))->get()->array_row();	
	if( !empty($row) )
	{
		if( $row['flags'] != $_POST['sidebar4'] )
		{
			$sidebar4 = explode('|', $_POST['sidebar4']);
			SidebarSet_fu(PRE.'storage',$sidebar4,'sidebar4',$_POST['sidebar4']);
		}
	}
	else 
	{
		$sidebar4 = explode('|', $_POST['sidebar4']);
		SidebarSet_fu(PRE.'storage',$sidebar4,'sidebar4');
	}
	#no5
	$row = db()->select('flags')->from(PRE.'storage')->where(array('name'=>'sidebar5'))->get()->array_row();	
	if( !empty($row) )
	{
		if( $row['flags'] != $_POST['sidebar5'] )
		{
			$sidebar5 = explode('|', $_POST['sidebar5']);
			SidebarSet_fu(PRE.'storage',$sidebar5,'sidebar5',$_POST['sidebar5']);
		}
	}
	else 
	{
		$sidebar5 = explode('|', $_POST['sidebar5']);
		SidebarSet_fu(PRE.'storage',$sidebar5,'sidebar5');
	}
	
}
function SidebarSet_fu($table,$sidebar,$name,$flags)
{
	array_pop($sidebar);
	$len = count($sidebar);
	$html = '';
	foreach( $sidebar as $k => $v )
	{
		if( $v != '' )
		{
			$row = db()->select('id,modulename,flag')->from(PRE.'module')->where(array('filename'=>$v))->get()->array_row();
			if( $row['flag'] == 1 || $row['flag'] == 4 )
			{
				$html .= '<div class="widget widget_source_system widget_id_navbar ui-draggable ui-draggable-handle" style="display: block;">
				<div class="widget-title">
				<img class="more-action" width="16" src="'.apth_url('system/admin/images/brick.png').'" alt="">
				'.$row['modulename'].'
				<span class="widget-action">
				<a href="index.php?act=ModuleEdtUp&amp;id='.$row['id'].'">
				<img class="edit-action" src="'.apth_url('system/admin/images/brick_edit.png').'" alt="编辑" title="编辑" width="16">
				</a>
				</span>
				</div>
				<div class="funid" style="display:none">'.$v.'</div>
				</div>';
			}
			elseif( $row['flag'] == 0 )
			{
				$html .= '<div class="widget widget_source_user widget_id_navbar ui-draggable ui-draggable-handle" style="display: block;">
				<div class="widget-title">
				<img class="more-action" width="16" src="'.apth_url('system/admin/images/brick.png').'" alt="">
				'.$row['modulename'].'
				<span class="widget-action">
				<a href="index.php?act=ModuleEdtUp&amp;id='.$row['id'].'">
				<img class="edit-action" src="'.apth_url('system/admin/images/brick_edit.png').'" alt="编辑" title="编辑" width="16">
				</a>
				</span>
				</div>
				<div class="funid" style="display:none">'.$v.'</div>
				</div>';
			}
			elseif( $row['flag'] == 2 )
			{
				$html .= '<div class="widget widget_source_theme widget_id_navbar ui-draggable ui-draggable-handle" style="display: block;">
				<div class="widget-title">
				<img class="more-action" width="16" src="'.apth_url('system/admin/images/brick.png').'" alt="">
				'.$row['modulename'].'
				<span class="widget-action">
				<a href="index.php?act=ModuleEdtUp&amp;id='.$row['id'].'">
				<img class="edit-action" src="'.apth_url('system/admin/images/brick_edit.png').'" alt="编辑" title="编辑" width="16">
				</a>
				</span>
				</div>
				<div class="funid" style="display:none">'.$v.'</div>
				</div>';
			}
			elseif( $row['flag'] == 3 )
			{
				$html .= '<div class="widget widget_source_other widget_id_navbar ui-draggable ui-draggable-handle" style="display: block;">
				<div class="widget-title">
				<img class="more-action" width="16" src="'.apth_url('system/admin/images/brick.png').'" alt="">
				'.$row['modulename'].'
				<span class="widget-action">
				<a href="index.php?act=ModuleEdtUp&amp;id='.$row['id'].'">
				<img class="edit-action" src="'.apth_url('system/admin/images/brick_edit.png').'" alt="编辑" title="编辑" width="16">
				</a>
				</span>
				</div>
				<div class="funid" style="display:none">'.$v.'</div>
				</div>';
			}
			elseif($row['flag'] == 5) 
			{
				$html .= '<div class="widget widget_source_plugin widget_id_navbar ui-draggable ui-draggable-handle" style="display: block;">
				<div class="widget-title">
				<img class="more-action" width="16" src="'.apth_url('system/admin/images/brick.png').'" alt="">
				'.$row['modulename'].'
				<span class="widget-action">
				<a href="index.php?act=ModuleEdtUp&amp;id='.$row['id'].'">
				<img class="edit-action" src="'.apth_url('system/admin/images/brick_edit.png').'" alt="编辑" title="编辑" width="16">
				</a>
				</span>
				</div>
				<div class="funid" style="display:none">'.$v.'</div>
				</div>';
			}
		}
		else 
		{
			$len = 0;
		}	
	}

	$int = db()->select('id')->from($table)->where(array('name'=>$name))->get()->array_nums(); 
	if( $int == 0 )
	{#添加
		db()->insert(PRE.'storage',array('body'=>$html,'name'=>$name,'flag'=>$len,'flags'=>$flags));
	}
	else 
	{#修改
		db()->update(PRE.'storage', array('body'=>$html,'flag'=>$len,'flags'=>$flags),array('name'=>$name));
	}
	return $len;
}
#评论点赞
function dianzan()
{
	session_start();
	$id = $_POST['id'];
	
	$_SESSION['countuseID'][0] = '';
	
	if( !in_array($id, $_SESSION['countuseID']) )
	{
		
		$int = db()->update(PRE.'review', 'likes=likes+1', array('id'=>$id));
		if($int)
		{
			$_SESSION['countuseID'][] = $id;
			$row = db()->select('likes')->from(PRE.'review')->where(array('id'=>$id))->get()->array_row();
			echo $row['likes'];
		}
		else 
		{
			echo 'no';
		}
	}
	else 
	{
		echo 'no';
	}	
}
#评论举报
function report()
{
	session_start();
	$id = $_POST['id'];
	
	$_SESSION['usernamesid'][0] = '';
	
	if( !in_array($id, $_SESSION['usernamesid']) )
	{	
		$int = db()->update(PRE.'review', 'report=report+1', array('id'=>$id));
		if($int)
		{
			$_SESSION['usernamesid'][] = $id;
			echo 'ok';
		}
	}
	else 
	{
		echo 'no';
	}	
}
#浏览次数
function BrowseClick()
{
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$int = db()->select('id')->from(PRE.'browse')->where('FROM_UNIXTIME(publitime,"%Y-%m-%d")="'.date('Y-m-d').'" and templateid='.$theme['id'])->get()->array_nums();
	if( $int == 0 )
	{
		db()->insert(PRE.'browse',array('browse'=>1,'publitime'=>time(),'templateid'=>$theme['id']));
	}
	else 
	{
		db()->update(PRE.'browse', 'browse=browse+1','FROM_UNIXTIME(publitime,"%Y-%m-%d")="'.date('Y-m-d').'" and templateid='.$theme['id']);
	}
	$row = db()->select('browse')->from(PRE.'browse')->where('FROM_UNIXTIME(publitime,"%Y-%m-%d")="'.date('Y-m-d').'" and templateid='.$theme['id'])->get()->array_row();
}
#记录屏幕的宽度
function pingmupixels()
{
	$array = $_POST;
	$filename = dirname(__File__).'/pingmupixels.xml';
	file_put_contents($filename, Generate_str($array));
}
#反回分类名称
function ResourcesMng_edt()
{
	$id = mysql_escape_string($_POST['id']);
	if($id!='')
	{
		$pieces = GetResourcesFenLai_name_edt($id,$_POST['flag']);
		echo join('/', $pieces);
	}
	else 
	{
		echo null;
	}
}
#检测手机号是已经存在
function tel_checked()
{
	$int = db()->select('tel')->from(PRE.'member')->where(array('tel'=>$_POST['tel']))->get()->array_nums();
	echo $int;
}
#检测邮箱号是已经存在
function email_checked()
{
	$int = db()->select('email')->from(PRE.'member')->where(array('email'=>$_POST['email']))->get()->array_nums();
	echo $int;
}
###########################################################################################
$act = $_REQUEST['act'];
switch ($act)
{
	case "Generate_xml":
	$col['ColorVal'] = $_POST['vars'];	
	$act($col,dirname(__File__).'/ColorXml.xml');
	break;
	case "Verification":
	$act($_POST);
	break;
	case "ChangeStyle":
	$act($_POST,dirname(__File__).'/admin/ColorXml.xml');
	break;
	case "MenuStyle":
	$act($_POST,dirname(__File__).'/admin/MenuXml.xml');
	break;
	case "Classified":
	$act();
	break;
	case "TagEdt":
	$act();
	break;
	case "PageEdt":
	$act();
	break;
	case "Theme_edit":
	$act();
	break;
	case "ModuleEdt2":
	$act();
	break;
	case "SidebarSet":
	$act();
	break;
	case "dianzan":
	$act();
	break;
	case "report":
	$act();
	break;
	case "Daywebs":
	$act();
	break;
	case "BrowseClick":
	$act();
	break;
	case "pingmupixels":
	$act();
	break;
	case "ResourcesMng_edt":
	$act();
	break;
	case "tel_checked":
	$act();
	break;
	case "email_checked":
	$act();
	break;
}