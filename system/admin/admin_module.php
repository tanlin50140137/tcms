<?php
header('content-type:text/html;charset=utf-8');
#合法性验证#########################################################################
function ValidityVerification()
{
	session_start();

	if($_COOKIE['username']==null && !isset($_COOKIE['username']))
	{
		if($_SESSION['username']==null && !isset($_SESSION['username']))
		{
			header('location:../login.php');
			exit('非法登录');
		}
		else 
		{
			#如果有
			$int = db()->select('id')->from(PRE.'login')->where(array('userName'=>$_SESSION['username']))->get()->array_nums();
			if( $int == 0 )
			{
				header('content-type:text/html;charset=utf-8');
				echo "<script>alert('已经有一个登录，不需要重复登录');location.href='../login.php';</script>";exit;
			}
		}
	}
	else
	{	
		#如果有
		$int = db()->select('id')->from(PRE.'login')->where(array('userName'=>$_COOKIE['username']))->get()->array_nums();
		if( $int == 0 )
		{
			header('content-type:text/html;charset=utf-8');
			echo "<script>alert('已经有一个登录，不需要重复登录');location.href='../login.php';</script>";exit;
		}
		#保持登录状态
		$_SESSION['username'] = $_COOKIE['username'];
	}
}
###################################################################################
#后台头部
function LoginInterfaceTop()
{
	ValidityVerification();
	$row = db()->select('title')->from(PRE.'setting')->get()->array_row();
	$filename = apth_url('subject/version.txt');
	$subject = file_get_contents($filename);
	$arr = explode('/', str_replace("\n", '', $subject));
	$subject = '<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1" />
	<meta name="robots" content="none" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0"/>
	<meta name="generator" content="'.$arr[1].' '.$row['title'].'" />
	<meta name="renderer" content="webkit" />
	<link rel="stylesheet" href="'.site_url('css/common_style.css').'" type="text/css" media="screen" />
	<link rel="stylesheet" href="'.site_url('css/admin_style.css').'" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="'.site_url('css/jquery-ui.custom.css').'"/>
	<link href="'.site_url('css/admin2.css').'" rel="stylesheet" type="text/css" />	
	<link href="'.site_url('css/framework7-icons.css').'" rel="stylesheet" type="text/css" />
	<link href="'.site_url('plugin/froala_editor/css/font-awesome.min.css').'" rel="stylesheet" type="text/css">
	<link href="'.site_url('plugin/froala_editor/css/froala_editor.min.css').'" rel="stylesheet" type="text/css">
	<link rel="icon" href="'.apth_url("subject/htx/images/icon_in.png").'">
	<script src="'.site_url('js/jquery-1.8.3.min.js').'" type="text/javascript"></script>
	<script src="'.site_url('js/jquery-ui.custom.min.js').'" type="text/javascript"></script>
	<script type="text/javascript" src="'.site_url('js/jquery-ui-timepicker-addon.js').'"></script>
	<title>'.$row['title'].'-管理系统</title>
</head><body>';

	echo str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#头部栏
function CreateTopBar()
{
	$colorval = '#3A6EA5';
	if(file_exists("../ColorXml.xml"))
	{
		$xml = simplexml_load_file("../ColorXml.xml");
		$colorval = (string)$xml->ColorVal;
	}
	
	$subject = '<div class="ctTop" style="background:'.$colorval.'">';
	$subject .= CreateTopBar_parts_a();
	$subject .= CreateTopBar_parts_b();
	$subject .= CreateTopBar_parts_c();
	$subject .= '</div>';
	return str_replace(array("\n","\t"), array("",""), $subject);
	
}
###################################################################################
#logo组件
function CreateTopBar_parts_a()
{
	session_start();
	$row = db()->select('id,userName,alias,pic')->from(PRE.'login')->where(array('userName'=>$_SESSION['username']))->get()->array_row();
	
	return '<div class="ctParts_a">
	<a href="'.apth_url('index.php').'" target="_blank"><img src="'.site_url('images/logo.png').'"/></a>
	</div>
	<a href="'.apth_url('index.php').'" target="_blank" class="logo_imgs"><img src="'.site_url('images/logo.png').'"/></a>
	<div class="menglist"><i class="f7-icons size-29"><b>bars</b></i></div>
	<div class="floatMenus">
		<dl>
			<dd><a href="index.php?act=UserEdit_phone"><img src="'.($row['pic']==''?site_url("header/0.png"):site_url($row['pic'])).'" class="userpic" align="absmiddle"/> '.$_SESSION['username'].'</a></dd>
			<dd><a href="'.apth_url('index.php').'"><i class="f7-icons size-12"><b>navigation</b></i> 网站首页</a></dd>
			<dd><a href="index.php?act=admin_phone"><i class="f7-icons size-12"><b>home</b></i> 返回</a></dd>
			<dd><a href="block_execution.php?act=Annotation"><i class="f7-icons size-12"><b>logout</b></i> 注消</a></dd>
		</dl>
	</div>';
}
###################################################################################
#用户信息组件
function CreateTopBar_parts_b()
{
	session_start();
	$row = db()->select('id,userName,alias,pic')->from(PRE.'login')->where(array('userName'=>$_SESSION['username']))->get()->array_row();
	$user = $row['alias']==''?$row['userName']:$row['alias'];
	
	$subject = '<div class="ctParts_b">
	<div class="pimage_nex">管理员：'.$user.'</div>
	<div class="pimage_par"><a href="block_execution.php?act=Annotation" style="background:url('.site_url('images/tuic_2.png').') no-repeat 2px 2px;">注消</a> &nbsp; &nbsp; <a href="#" style="background:url('.site_url('images/home_2.png').') no-repeat 0 1px;" class="fanhui">返回</a></div>
	<div class="pimage"><a href="index.php?act=UserEdit">
	<img src="'.($row['pic']==''?site_url("header/0.png"):site_url($row['pic'])).'" width="40" height="40"/></a>
	</div>
	</div>';
	$subject .= '
	<script>
	$(function(){
	$(".pimage_par a").each(function(index){
	$(this).hover(function(){
	if(index==0)
	{
		$(".pimage_par a:eq("+index+")").css({"color":"#000000","background":"url('.site_url('images/tuic_1.png').') no-repeat 2px 2px"});
	}else{
		$(".pimage_par a:eq("+index+")").css({"color":"#000000","background":"url('.site_url('images/home_1.png').') no-repeat 0 1px"});
	}
},function(){
	if(index==0)
	{
		$(".pimage_par a:eq("+index+")").css({"color":"#FFFFFF","background":"url('.site_url('images/tuic_2.png').') no-repeat 2px 2px"});
	}else{
		$(".pimage_par a:eq("+index+")").css({"color":"#FFFFFF","background":"url('.site_url('images/home_2.png').') no-repeat 0 1px"});
	}
});
});
});
$(function(){
	$(".menglist i b").click(function(){
		if( $(this).html() == "bars" )
		{
			$(this).html("close")
			$(".floatMenus").show();
		}
		else
		{
			$(this).html("bars");
			$(".floatMenus").hide();
		}
	});
});
	</script>';
	return $subject;
}
###################################################################################
#后台首页
function admin()
{
	session_start();
	#文章总数
	$artRows = db()->select('id')->from(PRE.'article')->get()->array_nums();
	#页面总数
	$theme = db()->select('id,themename,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$themeRows = db()->select('id')->from(PRE.'template')->where(array('templateid'=>$theme['id']))->get()->array_nums();
	if(!empty($theme))
	{
	$browseRows = db()->select('browse')->from(PRE.'browse')->where('FROM_UNIXTIME(publitime,"%Y-%m-%d")="'.date('Y-m-d').'" and templateid='.$theme['id'])->get()->array_row();
	}
	#评论总数
	$comRows = db()->select('id')->from(PRE.'review')->get()->array_nums();
	#分类总数
	$flRows = db()->select('id')->from(PRE.'classified')->get()->array_nums();
	#标签总数
	$tagRows = db()->select('id')->from(PRE.'tag')->get()->array_nums();
	#用户总数
	$userRows = db()->select('id')->from(PRE.'login')->get()->array_nums();
	#当前版本
	$filename = apth_url('subject/version.txt');
	$string = file_get_contents($filename);
	$version = explode('/', str_replace("\n", '', $string));
	
	#最新动态信息
	$path = 'system/remotelink.php';
	$filename = SERVICE_LINK.$path;
	$json = vcurl($filename,'act=InDataNews');
	$rows1 = json_decode($json,true);
	$artArr = $rows1[0];
	
	#匹配权限
	if( isset($_SESSION['username']) && $_SESSION['username'] != null )
	{
		$row = db()->select('level')->from(PRE.'login')->where(array('userName'=>$_SESSION['username']))->get()->array_row();
	}
	$subject = '<div class="useredit">';	
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}	
	$subject .= '<div class="userheader2">网站摘要</div>
		<table class="tableBox">
			<tr>
				<th colspan="4">网站信息 ';
	if(!($row['level']==4||$row['level']==5))
	{
	$subject .= '<font color="#1D4C7D" style="cursor:pointer;">[<a onclick="conf();" id="compile">清空缓存并重新编译模板</font>]</a>';
	}
	$subject .= '</th>
			</tr>
			<tr>
				<td>当前用户</td>
				<td>'.$_SESSION['username'].'</td>
				<td>当前版本</td>
				<td>'.$version[1].'</td>
			</tr>
			<tr>
				<td>文章总数</td>
				<td>'.$artRows.'</td>
				<td>分类总数</td>
				<td>'.$flRows.'</td>
			</tr>
			<tr>
				<td>页面总数</td>
				<td>'.$themeRows.'</td>
				<td>标签总数</td>
				<td>'.$tagRows.'</td>
			</tr>
			<tr>
				<td>评论总数</td>
				<td>'.$comRows.'</td>
				<td>浏览总数</td>
				<td>'.$browseRows['browse'].'</td>
			</tr>
			<tr>
				<td>当前主题ID / 主题名称</td>
				<td>'.$theme['themename'].'/'.$theme['themeas'].'</td>
				<td>用户总数</td>
				<td>'.$userRows.'</td>
			</tr>
			<tr>
				<td>XML-RPC协议地址</td>
				<td>'.apth_url('system/xml-rpc/index.php').'</td>
				<td>系统环境</td>
				<td>'.$_SERVER['SERVER_SOFTWARE'].'; mysqli; curl; xml</td>
			</tr>
		</table>
		<table class="tableBox">
			<tr style="display:none;"><td></td></tr>
			<tr>
				<th colspan="4">最新动态信息 <font color="#1D4C7D" style="cursor:pointer;">[<a href="'.site_url('index.php?act=admin').'">刷新</a>]</font></th>
			</tr>
			<tr>
				<td colspan="4">';
if(!empty($artArr))
{	
	foreach($artArr as $k=>$v)
	{	
		$subject .= '<p>
		<a href="'.SERVICE_LINK.'index.php?act=article_content&id='.$v['id'].'" target="_blank">'.$v['title'].'</a>
		</p>';
	}
}	
else 
{
	$subject .= '<p>
	<a href="javascript:;">暂时没有更新</a>
	</p>';
}	
		$subject .= '</td>
			</tr>
		</table>
		<table class="tableBox">
			<tr>
				<th colspan="2">This-cms-system网站和程序开发</th>
			</tr>
			<tr>
				<td>程序</td>
				<td>TanLin</td>
			</tr>
			<tr>
				<td>界面</td>
				<td>TanLin</td>
			</tr>
			<tr>
				<td>支持</td>
				<td>TanLin</td>
			</tr>
			<!--
			<tr>
				<td>感谢</td>
				<td>TanLin</td>
			</tr>-->';
	/*	
	if(!($row['level']==4||$row['level']==5))
	{			
		$subject .= '<tr>
				<td>相关链接</td>
				<td>
				<a href="#" target="_blank">官方网站</a> &nbsp;
				<a href="'.site_url('index.php?act=ApplicationCenter').'">应用中心 </a> 
				</td>
			</tr>';
	}	
	*/		
	$subject .= '</table></div>';
	$subject .= '<script>
	function conf()
	{
		var bl = window.confirm("是否要清空缓存并重新编译模板?");
		if(bl)
		{
			location.href="handling_events.php?act=ClearCompile";
		}
	}
	$(function(){
		$(".showerror").hide(5000);
		$(".tableBox p:eq(0),.tableBox p:eq(1)").css({"font-weight":"bold"});
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#后台首页
function admin_phone()
{
	session_start();
	#文章总数
	$artRows = db()->select('id')->from(PRE.'article')->get()->array_nums();
	#页面总数
	$theme = db()->select('id,themename,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$themeRows = db()->select('id')->from(PRE.'template')->where(array('templateid'=>$theme['id']))->get()->array_nums();
	if(!empty($theme))
	{
	$browseRows = db()->select('browse')->from(PRE.'browse')->where('FROM_UNIXTIME(publitime,"%Y-%m-%d")="'.date('Y-m-d').'" and templateid='.$theme['id'])->get()->array_row();
	}
	#评论总数
	$comRows = db()->select('id')->from(PRE.'review')->get()->array_nums();
	#分类总数
	$flRows = db()->select('id')->from(PRE.'classified')->get()->array_nums();
	#标签总数
	$tagRows = db()->select('id')->from(PRE.'tag')->get()->array_nums();
	#用户总数
	$userRows = db()->select('id')->from(PRE.'login')->get()->array_nums();
	#当前版本
	$version = get_version();
	#最新动态信息
	$artArr = db()->select('id,title')->from(PRE.'article')->where(array('top'=>105))->order_by('publitime desc')->limit('0,6')->get()->array_rows();
	
	#匹配权限
	if( isset($_SESSION['username']) && $_SESSION['username'] != null )
	{
		$row = db()->select('level')->from(PRE.'login')->where(array('userName'=>$_SESSION['username']))->get()->array_row();
	}
		$subject .= '<div class="useredit_phone">';
		if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
		{
			$subject .= '<div class="showerror">
				<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
				操作成功
			</div>';
		}	
		$subject .= '<div class="userheader2_phone"><i class="f7-icons size-22">home_fill</i> 后台首页</div>';
		$subject .= '<table class="tableBox">';
		$subject .= '<tr>
						<td>网站信息</td>
						<td><font color="#1D4C7D" style="cursor:pointer;">[<a onclick="conf();" id="compile">清空缓存并重新编译模板</a>]</font></td>
					 </tr>';
		$subject .= '<tr>
						<td>当前版本</td>
						<td>'.$version[1].'</td>
					 </tr>';
		$subject .= '<tr>
						<td>当前用户</td>
						<td>'.$_SESSION['username'].'</td>
					 </tr>';
		$subject .= '<tr>
						<td>文章总数</td>
						<td>'.$artRows.'</td>
					 </tr>';
		$subject .= '<tr>
						<td>分类总数</td>
						<td>'.$tagRows.'</td>
					 </tr>';
		$subject .= '<tr>
						<td>页面总数</td>
						<td>'.$themeRows.'</td>
					 </tr>';
		$subject .= '<tr>
						<td>标签总数</td>
						<td>'.$tagRows.'</td>
					 </tr>';
		$subject .= '<tr>
						<td>评论总数</td>
						<td>'.$comRows.'</td>
					 </tr>';	
		$subject .= '<tr>
						<td>浏览总数</td>
						<td>'.$browseRows['browse'].'</td>
					 </tr>';
		$subject .= '<tr>
						<td>当前主题ID / 主题名称</td>
						<td>'.$theme['themename'].'/'.$theme['themeas'].'</td>
					 </tr>';
		$subject .= '<tr>
						<td>用户总数</td>
						<td>'.$userRows.'</td>
					 </tr>';
		$subject .= '</table>
		
		<table class="tableBox">
			<tr style="display:none;"><td></td></tr>
			<tr>
				<th colspan="4">最新动态信息 <font color="#1D4C7D" style="cursor:pointer;">[<a href="'.site_url('index.php?act=admin_phone').'">刷新</a>]</font></th>
			</tr>
			<tr>
				<td colspan="4">';
if(!empty($artArr))
{	
	foreach($artArr as $k=>$v)
	{	
		$subject .= '<p>
		<a href="'.apth_url('index.php?act=article_content&id='.$v['id']).'" target="_blank">'.$v['title'].'</a>
		</p>';
	}
}	
else 
{
	$subject .= '<p>
	<a href="javascript:;">暂时没有更新</a>
	</p>';
}	
		$subject .= '</td>
			</tr>
		</table>
		<table class="tableBox">
			<tr>
				<th colspan="2">This-cms-system系统程序开发</th>
			</tr>
			<tr>
				<td>程序</td>
				<td>TanLin &nbsp; TanQingZuo &nbsp; WeiZhi</td>
			</tr>
			<tr>
				<td>界面</td>
				<td>P2P &nbsp; TanQingZuo &nbsp; WeiZhi</td>
			</tr>
			<tr>
				<td>支持</td>
				<td>P2P &nbsp; TanQingZuo &nbsp; WeiZhi </td>
			</tr>
			<tr>
				<td>感谢</td>
				<td>TanLin &nbsp; TanQingZuo &nbsp; WeiZhi &nbsp; 小美食 &nbsp; 小布 P2P</td>
			</tr>';
if(!($row['level']==4||$row['level']==5))
{		
		$subject .= '<tr>
				<td>相关链接</td>
				<td>
				<a href="#" target="_blank">官方网站</a> &nbsp; 
				<a href="'.site_url('index.php?act=ApplicationCenter').'">应用中心</a>
				</td>
			</tr>';;;;;
}　;;;;		
		$subject .= '</table></div>';
		
	$subject .= '<script>
	function conf()
	{
		var bl = window.confirm("是否要清空缓存并重新编译模板?");
		if(bl)
		{
			location.href="handling_events.php?act=ClearCompile";
		}
	}
	$(function(){
		$(".showerror").hide(5000);
		$(".tableBox p:eq(0),.tableBox p:eq(1)").css({"font-weight":"bold"});
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#分类管理
function CategoryMng()
{
	session_start();
	
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$result = db()->select('id,pid,classified,clalias,sort,description,addmenu,(select count(cipid) from '.PRE.'article where cipid=a.id) as newTotal')->from(PRE.'classified as a')->where(array('templateid'=>$theme['id']))->order_by('id desc')->get();
	$rows = $result->array_rows();
	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader" style="border:none;">分类管理</div>
	<ul class="menuWeb clear"><li class="menuWebAction"><a href="index.php?act=CategoryEdt">新建分类</a></li></ul>
	<table class="tableBox" style="margin-top:8px;">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">排序</th>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;">别名</th>
				<th style="text-align:center;">文章数量</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if(!empty($rows))
{		
	foreach($rows as $k=>$v)
	{	
	$subject .= '<tr>
				<td width="60">'.$v["id"].'</td>
				<td width="100">'.$v["sort"].'</td>
				<td width="350"><a href="'.apth_url('index.php?act=index&cipid='.$v["id"]).'" class="hoverstyle" target="_blank" title="文章列表页面"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.$v["classified"].'</td>
				<td width="300">'.$v["clalias"].'</td>
				<td width="100">'.$v["newTotal"].'</td>
				<td style="text-align:center;">
					<a href="index.php?act=CategoryUp&id='.$v["id"].'" title="修改" class="hoverstyle"><img src="'.site_url('images/folder_edit.png').'" align="absmiddle"/></a>
					 &nbsp; &nbsp; 
					<a href="javascript:;" onclick="conf('.$v["id"].');" title="清除" class="hoverstyle"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
			</tr>';
	}
}
else 
{
	$subject .= '<tr>
		<td colspan="6" align="center" style="color:#999999;">没有新建分类</td>
	</tr>';
}					
	$subject .= '<script>
	function conf(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=CategoryDeletet&id="+id;
		}
	}
	$(function(){
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
			$(this).css({"background":"#FFFFDD"});
		},function(){
			$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		});
		$(".showerror").hide(2000);
	});
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#分类管理
function CategoryMng_phone()
{
	session_start();
	
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$result = db()->select('id,pid,classified,clalias,sort,description,addmenu,(select count(cipid) from '.PRE.'article where cipid=a.id) as newTotal')->from(PRE.'classified as a')->where(array('templateid'=>$theme['id']))->order_by('id desc')->get();
	$rows = $result->array_rows();
	$subject = '<div class="useredit_phone">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">folder_fill</i> 分类管理</div>
	<ul class="menuWeb clear"><li class="menuWebAction"><a href="index.php?act=CategoryEdt_phone">新建分类</a></li></ul>
	<table class="tableBox" style="margin-top:8px;">
			<tr>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;">文章</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if(!empty($rows))
{		
	foreach($rows as $k=>$v)
	{	
	$subject .= '<tr>
				<td><a href="'.apth_url('index.php?act=index&cipid='.$v["id"]).'" class="hoverstyle" target="_blank" title="文章列表页面"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.$v["classified"].'</td>
				<td>'.$v["newTotal"].'</td>
				<td style="text-align:center;">
				<p class="art_floats">
					<a href="index.php?act=CategoryUp_phone&id='.$v["id"].'" title="修改" class="hoverstyle"><img src="'.site_url('images/folder_edit.png').'" align="absmiddle"/></a>
				</p>	
				<p class="art_floats">
					<a href="javascript:;" onclick="conf('.$v["id"].');" title="清除" class="hoverstyle"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</p>	
				</td>
			</tr>';
	}
}
else 
{
	$subject .= '<tr>
		<td colspan="3" align="center" style="color:#999999;">没有新建分类</td>
	</tr>';
}					
	$subject .= '<script>
	function conf(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=CategoryDeletet&id="+id;
		}
	}
	$(function(){
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
			$(this).css({"background":"#FFFFDD"});
		},function(){
			$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		});
		$(".showerror").hide(2000);
	});
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#修改分类
function CategoryUp()
{
	$id = mysql_escape_string($_GET['id']);
	$row = db()->select('*')->from(PRE.'classified')->where(array('id'=>$id))->get()->array_row();
	
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$subject = '<div class="useredit">
	<form action="handling_events.php" method="post" id="viri">
	<div class="userheader">分类编辑</div>	
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="classified" value="'.$row['classified'].'" class="input-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="clalias" value="'.$row['clalias'].'" class="input-s"/></div>
		<div class="userjibie">排序:</div>
		<div><input type="text" name="sort" value="'.$row['sort'].'" class="input-s"/></div>';
	/*	
	$subject .= '<div class="userjibie">父分类: </div>
		<div>
			<select name="model" class="input-x">
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
			</select>
		</div>';	
	*/	
	$subject .= '<div class="userjibie">主题:</div>
		<div>
			<select name="templateid" class="input-x">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>';
	/*	
	$subject .= '<div class="userjibie">该分类文章的默认模板: </div>
		<div>
			<select name="model" class="input-x">
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
			</select>
		</div>';
		*/
	$subject .= '<div class="userjibie">摘要:</div>
		<div><textarea name="description" class="input-w">'.$row['description'].'</textarea></div>
		<div class="userjibie clear">
		<span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid menuadd" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
		<input type="hidden" name="addmenu" value="'.$row['addmenu'].'" class="pagemenuadd"/>
		</div>	
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="id" value="'.$id.'"/>
		<input type="hidden" name="act" value="CategoryUp"/><!--提交到CategoryEdt方式-->
		<input type="submit" value="提交" class="sub"/>
		</div>
	</div></form>
	</div>';
	$subject .= '<script>
	$(function(){
		if($("[name=addmenu]").val()=="OFF")
		{
			$(".menuadd").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".menuadd").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}		
		$(".menuadd").click(function(){
		if($("[name=addmenu]").val()=="ON" || $("[name=addmenu]").val()== "")
		{
			$(".pagemenuadd").val("OFF");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".pagemenuadd").val("ON");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}
		});
		var clFlag = false;
		$("#viri").submit(function(){
			if( $("[name=classified]").val() == "" )
			{
				alert("分类名称未命名");
				return false;
			}
		});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#修改分类
function CategoryUp_phone()
{
	$id = mysql_escape_string($_GET['id']);
	$row = db()->select('*')->from(PRE.'classified')->where(array('id'=>$id))->get()->array_row();
	
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$subject = '<div class="useredit_phone">
	<form action="handling_events.php" method="post" id="viri">
	<div class="userheader2_phone"><i class="f7-icons size-22">folder_fill</i> 分类编辑</div>	
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="classified" value="'.$row['classified'].'" class="inputs-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="clalias" value="'.$row['clalias'].'" class="inputs-s"/></div>
		<div class="userjibie">排序:</div>
		<div><input type="text" name="sort" value="'.$row['sort'].'" class="inputs-s"/></div>';
	/*	
	$subject .= '<div class="userjibie">父分类: </div>
		<div>
			<select name="model" class="input-x">
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
			</select>
		</div>';
	*/		
	$subject .= '<div class="userjibie">主题:</div>
		<div>
			<select name="templateid" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>';
	/*	
	$subject .= '<div class="userjibie">该分类文章的默认模板: </div>
		<div>
			<select name="model" class="input-x">
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
			</select>
		</div>';
		*/
	$subject .= '<div class="userjibie">摘要:</div>
		<div><textarea name="description" class="input-w">'.$row['description'].'</textarea></div>
		<div class="userjibie clear">
		<span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid menuadd" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
		<input type="hidden" name="addmenu" value="'.$row['addmenu'].'" class="pagemenuadd"/>
		</div>	
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="id" value="'.$id.'"/>
		<input type="hidden" name="act" value="CategoryUp"/><!--提交到CategoryEdt方式-->
		<input type="submit" value="提交" class="subClass"/>
		</div>
	</div></form>
	</div>';
	$subject .= '<script>
	$(function(){
		if($("[name=addmenu]").val()=="OFF")
		{
			$(".menuadd").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".menuadd").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}		
		$(".menuadd").click(function(){
		if($("[name=addmenu]").val()=="ON" || $("[name=addmenu]").val()== "")
		{
			$(".pagemenuadd").val("OFF");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".pagemenuadd").val("ON");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}
		});
		var clFlag = false;
		$("#viri").submit(function(){
			if( $("[name=classified]").val() == "" )
			{
				alert("分类名称未命名");
				return false;
			}
		});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#新建分类
function CategoryEdt()
{
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$subject = '<div class="useredit">
	<form action="handling_events.php" method="post" id="viri">
	<div class="userheader">分类编辑</div>	
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="classified" placeholder="未命名" class="input-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="clalias" value="" class="input-s"/></div>
		<div class="userjibie">排序:</div>
		<div><input type="text" name="sort" value="0" class="input-s"/></div>';
	/*	
	$subject .= '<div class="userjibie">父分类: </div>
		<div>
			<select name="model" class="input-x">
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
			</select>
		</div>';
	*/		
	$subject .= '<div class="userjibie">主题:</div>
		<div>
			<select name="templateid" class="input-x">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>';
	/*	
	$subject .= '<div class="userjibie">该分类文章的默认模板: </div>
		<div>
			<select name="model" class="input-x">
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
			</select>
		</div>';
		*/
	$subject .= '<div class="userjibie">摘要:</div>
		<div><textarea name="description" class="input-w"></textarea></div>
		<div class="userjibie clear">
		<span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid menuadd" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
		<input type="hidden" name="addmenu" value="ON" class="pagemenuadd"/>
		</div>	
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="act" value="CategoryEdt"/><!--提交到CategoryEdt方式-->
		<input type="submit" value="提交" class="sub"/>
		</div>
	</div></form>
	</div>';
	$subject .= '<script>
	$(function(){
		var i=0;
		$(".menuadd").click(function(){
		if(i%2==0)
		{
			$(".pagemenuadd").val("OFF");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".pagemenuadd").val("ON");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}
		i++;
		});
		$("#viri").submit(function(){
			if( $("[name=classified]").val() == "" )
			{
				alert("分类名称未命名");
				return false;
			}
		});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#新建分类
function CategoryEdt_phone()
{
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$subject = '<div class="useredit_phone">
	<form action="handling_events.php" method="post" id="viri">
	<div class="userheader2_phone"><i class="f7-icons size-22">folder_fill</i> 分类编辑</div>	
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="classified" placeholder="未命名" class="inputs-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="clalias" value="" class="inputs-s"/></div>
		<div class="userjibie">排序:</div>
		<div><input type="text" name="sort" value="0" class="inputs-s"/></div>';
	/*	
	$subject .= '<div class="userjibie">父分类: </div>
		<div>
			<select name="model" class="input-x">
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
			</select>
		</div>';
	*/		
	$subject .= '<div class="userjibie">主题:</div>
		<div>
			<select name="templateid" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>';
	/*	
	$subject .= '<div class="userjibie">该分类文章的默认模板: </div>
		<div>
			<select name="model" class="input-x">
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
				<option value="1">用户级别</option>
			</select>
		</div>';
		*/
	$subject .= '<div class="userjibie">摘要:</div>
		<div><textarea name="description" class="input-w"></textarea></div>
		<div class="userjibie clear">
		<span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid menuadd" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
		<input type="hidden" name="addmenu" value="ON" class="pagemenuadd"/>
		</div>	
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="act" value="CategoryEdt"/><!--提交到CategoryEdt方式-->
		<input type="submit" value="提交" class="subClass"/>
		</div>
	</div></form>
	</div>';
	$subject .= '<script>
	$(function(){
		var i=0;
		$(".menuadd").click(function(){
		if(i%2==0)
		{
			$(".pagemenuadd").val("OFF");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".pagemenuadd").val("ON");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}
		i++;
		});
		$("#viri").submit(function(){
			if( $("[name=classified]").val() == "" )
			{
				alert("分类名称未命名");
				return false;
			}
		});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#标签管理
function TagMng()
{
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	#分页
	$totalrow = db()->select('id')->from(PRE.'tag')->where(array('templateid'=>$theme['id']))->get()->array_nums();
	$showpage = $num;
	$totalpage = ceil($totalrow/$showpage);
	$page = $_GET['page']==''?1:$_GET['page'];
	if($page>=$totalpage){$page=$totalpage;}
	if($page<=1||!is_numeric($page)){$page=1;}
	$offset = ($page-1)*$showpage;	
	$rows = db()->select("a.id,a.keywords,a.tagas,a.artrows")->from(PRE.'tag as a')->where(array('templateid'=>$theme['id']))->order_by('id desc')->limit($offset.','.$showpage)->get()->array_rows();
	
	$subject = '<div class="useredit">';
	if(isset($_SESSION['flagEorre'])&&$_SESSION['flagEorre']==1)
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader" style="border:none;">标签管理</div>
	<ul class="menuWeb clear"><li class="menuWebAction"><a href="index.php?act=TagEdt">新建标签</a></li></ul>
	<table class="tableBox" style="margin-top:8px;">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;">别名</th>
				<th style="text-align:center;">文章数量</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if(!empty($rows))
{		
	foreach($rows as $k=>$v)
	{	
		$subject .= '<tr>
				<td width="60">'.$v['id'].'</td>
				<td width="350"><a href="'.apth_url('index.php?act=index&tag='.$v['keywords']).'" class="hoverstyle" target="_blank" title="内容页面"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.$v['keywords'].'</td>
				<td width="300">'.$v['tagas'].'</td>
				<td width="100">'.$v['artrows'].'</td>
				<td style="text-align:center;">
					<a href="index.php?act=TagEdtUp&id='.$v['id'].'" title="修改" class="hoverstyle"><img src="'.site_url('images/tag_blue_edit.png').'" align="absmiddle"/></a>
					 &nbsp; &nbsp; 
					<a href="javascript:;" onclick="conf('.$v['id'].','.$page.')" title="清除" class="hoverstyle"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
			</tr>';
	}	
}				
		$subject .= '<tr>
				<td colspan="8">
				<span style="font-size:15px;color:#666666;">总数:'.$totalrow.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$totalpage.'页</span>
				&nbsp; ';
	if( $totalrow >=$showpage )
	{			
		$subject .= '<a href="index.php?act=TagMng&page='.($page-1).'">
				<input type="submit" value="上一页" class="sub"/></a> &nbsp; 
				<a href="index.php?act=TagMng&page='.($page+1).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="GO" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" id="GO" value="GO" class="sub"/>				
				</span>';
	}			
		$subject .= '</td>
			</tr>
		</table>
	</div>';
	$subject .= '<script>
	function conf(id,page)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=TagEdtDelete&id="+id+"&page="+page;
		}
	}
	$(function(){
		$("#GO").click(function(){
			location.href="index.php?act=TagMng&page="+$("[name=GO]").val();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#标签管理
function TagMng_phone()
{
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	#分页
	$totalrow = db()->select('id')->from(PRE.'tag')->where(array('templateid'=>$theme['id']))->get()->array_nums();
	$showpage = $num;
	$totalpage = ceil($totalrow/$showpage);
	$page = $_GET['page']==''?1:$_GET['page'];
	if($page>=$totalpage){$page=$totalpage;}
	if($page<=1||!is_numeric($page)){$page=1;}
	$offset = ($page-1)*$showpage;	
	$rows = db()->select("a.id,a.keywords,a.tagas,a.artrows")->from(PRE.'tag as a')->where(array('templateid'=>$theme['id']))->order_by('id desc')->limit($offset.','.$showpage)->get()->array_rows();
		
	$subject = '<div class="useredit_phone">';
	if(isset($_SESSION['flagEorre'])&&$_SESSION['flagEorre']==1)
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">tags_fill</i> 标签管理</div>
	<ul class="menuWeb clear"><li class="menuWebAction"><a href="index.php?act=TagEdt_phone">新建标签</a></li></ul>
	<table class="tableBox" style="margin-top:8px;">
			<tr>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;">文章</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if(!empty($rows))
{		
	foreach($rows as $k=>$v)
	{	
		$subject .= '<tr>
				<td><a href="'.apth_url('index.php?act=index&tag='.$v['keywords']).'" class="hoverstyle" target="_blank" title="内容页面"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.$v['keywords'].'</td>
				<td>'.$v['artrows'].'</td>
				<td style="text-align:center;">
					<p class="art_floats">
					<a href="index.php?act=TagEdtUp_phone&id='.$v['id'].'" title="修改" class="hoverstyle"><img src="'.site_url('images/tag_blue_edit.png').'" align="absmiddle"/></a>
					</p>
					<p class="art_floats">
					<a href="javascript:;" onclick="conf('.$v['id'].','.$page.')" title="清除" class="hoverstyle"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
					</p>
				</td>
			</tr>';
	}	
}				
		$subject .= '<tr>
				<td colspan="3">
				<p>
				<span style="font-size:15px;color:#666666;">总数:'.$totalrow.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$totalpage.'页</span>
				</p>';
	if( $totalrow >=$showpage )
	{			
		$subject .= '<p><a class="a_href" href="index.php?act=TagMng&page='.($page-1).'">
				上一页 ; 
				<a class="a_href" href="index.php?act=TagMng&page='.($page+1).'">下一页</a>
				<span>
				<input type="text" name="GO" value="" class="renyiCl" style="width:50px;height:25px;"/>页 &nbsp; 
				<input type="submit" id="GO" value="GO"/>				
				</span></p>';
	}			
		$subject .= '</td>
			</tr>
		</table>
	</div>';
	$subject .= '<script>
	function conf(id,page)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=TagEdtDelete&id="+id+"&page="+page;
		}
	}
	$(function(){
		$("#GO").click(function(){
			location.href="index.php?act=TagMng&page="+$("[name=GO]").val();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#主题管理
function ThemeMng()
{
	session_start();
	#查询所有主题
	$theme = db()->select('id,author,themeas,themeimg,addmenu,autoplug1,autoplug2,style')->from(PRE.'theme')->where(array('flag'=>0))->order_by('sort desc , id desc')->get()->array_rows();
	
	$subject = '<div class="useredit">';
	if(isset($_SESSION['flagEorre'])&&$_SESSION['flagEorre']==1)
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader" style="border:none;">附件管理</div>
		<form action="handling_events.php" method="post" id="frm" enctype="multipart/form-data"/>
		<div class="newsTsBox">
		本地上传并安装主题ZIP文件:
		<input type="file" name="file" size="60" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"/>
		 &nbsp; 
		<span>
		<input type="hidden" name="act" value="ThemeMng" class="sub"/>
		<input type="submit" value="上传" class="sub"/>
		</span>
		 &nbsp; 
		<span><input type="reset" value="重置" class="sub"/></span>		
		</div>
		</form>

		<div class="jectAllBox clear">';
if( !empty($theme) )
{		
	foreach( $theme as $k => $v )
	{
		if($v['addmenu'] == "OFF")
		{
			$subject .= '<div class="jectAllBox-in" style="background:#B0CDEE;">';
		}
		else 
		{
			$subject .= '<div class="jectAllBox-in">';
		}
		$subject .= '<div class="jectAllBox-Tit">';
		if($v['addmenu'] == "OFF")
		{
			if( $v['autoplug1'] != '' )
			{		
			$subject .= '<a href="index.php?act=ThemePlugin&id='.$v['id'].'" title="主题自带插件" class="hoverstyle"><img src="'.site_url('images/setting_tools.png').'" align="absmiddle"/></a>';
			}
			else
			{		
			$subject .= '<img src="'.site_url('images/layout.png').'" align="absmiddle"/>';
			}	
		}
		else 
		{
			$subject .= '<img src="'.site_url('images/layout.png').'" align="absmiddle"/>';		
		}			 
		$subject .= ' &nbsp; <a href="'.$v['homepage'].'" target="_blank">'.$v['themeas'].'</a>
				</div>
				<div class="jectAllBox-img">
				<img src="'.apth_url($v['themeimg']).'" align="absmiddle" width="200" height="150"/>
				</div>
				<div class="jectAllBox-outhr">作者: <a href="'.$v['homepage'].'" target="_blank">'.$v['author'].'</a></div>
				<div class="jectAllBox-outhr">
				<span>样式:</span>
				<span>
					<select name="" class="cssstyle">';
			$styleTheme = getDirFileName(dir_url($v['style']));
			if( !empty($styleTheme) )
			{
				foreach($styleTheme as $v2)
				{
				$subject .= '<option value="">'.$v2.'</option>';
				}
			}
			else 
			{
				$subject .= '<option value="">未发现样式</option>';
			}
			$subject .= '</select>
				</span>
				<span>';
	if($v['addmenu'] == "ON")
	{			
		$subject .= '<input type="submit" value="启用" onclick="requert('.$v['id'].')" class="sub" style="width:45px;"/>';
	}
	else 
	{	
		$subject .= '<input type="submit" value="禁用" onclick="requert('.$v['id'].')" class="sub" style="width:45px;"/>';
	}			
		$subject .= '</span>
				</div>
				<div class="jectAllBox-outhr" style="margin-top:10px;">';
		if($v['addmenu'] == "OFF")
		{		
		$subject .= '<a href="index.php?act=ModuleEdt&flag=3&id='.$v['id'].'" title="创建管理" class="hoverstyle"><img src="'.site_url('images/bricks.png').'" align="absmiddle"/></a>';
		}
		if($v['addmenu'] == "ON")
		{	
		$subject .= ' &nbsp; <a href="javascript:;" onclick="conf('.$v['id'].')" title="清除" class="hoverstyle"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>';
		}
		$subject .= '</div>
			</div>';
	}	
}	
else 
{
	$subject .= '<div>暂时没有主题，请点击“页面管理”创建主题！</div>';
}		
	$subject .= '</div>
		
		</div>';
	$subject .= '<script>
	$(function(){
		$("#frm").submit(function(){
			if( $("[name=file]").val() == "" )
			{
				alert("未选择任何文件");
				$("[name=file]").focus();
				return false;
			}
		});
	});
	function conf(id)
	{
		var bl = window.confirm("是否要删除，删除后无法恢复");
		if(bl)
		{
			location.href="handling_events.php?act=ThemeMngDeletet&id="+id;
		}
	}
	function requert(id)
	{
		location.href="handling_events.php?act=theme_enable&id="+id;
	}
		$(".showerror").hide(2000);
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#主题管理
function ThemeMng_phone()
{
	session_start();
	#查询所有主题
	$theme = db()->select('id,author,themeas,themeimg,addmenu,autoplug1,autoplug2,style')->from(PRE.'theme')->where(array('flag'=>0))->order_by('sort desc , id desc')->get()->array_rows();

	$subject = '<div class="useredit_phone">';
	if(isset($_SESSION['flagEorre'])&&$_SESSION['flagEorre']==1)
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">collection_fill</i> 主题管理</div>
		<form action="handling_events.php" method="post" id="frm" enctype="multipart/form-data"/>
		<div class="newsTsBox_phone">
		<p>
		本地上传并安装主题ZIP文件:
		</p>
		<p style="height:50px;">
		<input type="file" name="file" size="60" style="width:95%;border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"/>
		</p>
		<p style="height:50px;">
		<span>
		<input type="hidden" name="act" value="ThemeMng" class="sub"/>
		<input type="submit" value="上传" class="subClass"/>
		</span>
		</p> 
		<p style="height:50px;">
		<span><input type="reset" value="重置" class="subClass"/></span>	
		</p> 	
		</div>
		</form>
		<div style="height:15px;"></div>
		<div class="jectAllBox clear">';
if( !empty($theme) )
{		
	foreach( $theme as $k => $v )
	{
		if($v['addmenu'] == "OFF")
		{
			$subject .= '<div class="jectAllBox-in" style="background:#B0CDEE;">';
		}
		else 
		{
			$subject .= '<div class="jectAllBox-in">';
		}
		$subject .= '<div class="jectAllBox-Tit">';
		if($v['addmenu'] == "OFF")
		{
			if( $v['autoplug1'] != '' )
			{		
			$subject .= '<a href="index.php?act=ThemePlugin&id='.$v['id'].'" title="主题自带插件" class="hoverstyle show-hoverstyle"><img src="'.site_url('images/setting_tools.png').'" align="absmiddle"/></a>';
			}
			else
			{		
			$subject .= '<img src="'.site_url('images/layout.png').'" align="absmiddle"/>';
			}	
		}
		else 
		{
			$subject .= '<img src="'.site_url('images/layout.png').'" align="absmiddle"/>';		
		}			 
		$subject .= ' &nbsp; <a href="'.$v['homepage'].'" target="_blank">'.$v['themeas'].'</a>
				</div>
				<div class="jectAllBox-img">
				<img src="'.apth_url($v['themeimg']).'" align="absmiddle" width="200" height="150"/>
				</div>
				<div class="jectAllBox-outhr">作者: <a href="'.$v['homepage'].'" target="_blank">'.$v['author'].'</a></div>
				<div class="jectAllBox-outhr">
				<span>样式:</span>
				<span>
					<select name="" class="cssstyle">';
			$styleTheme = getDirFileName(dir_url($v['style']));
			if( !empty($styleTheme) )
			{
				foreach($styleTheme as $v2)
				{
				$subject .= '<option value="">'.$v2.'</option>';
				}
			}
			else 
			{
				$subject .= '<option value="">未发现样式</option>';
			}
			$subject .= '</select>
				</span>
				<span>';
	if($v['addmenu'] == "ON")
	{			
		$subject .= '<input type="submit" value="启用" onclick="requert('.$v['id'].')" class="sub" style="width:45px;"/>';
	}
	else 
	{	
		$subject .= '<input type="submit" value="禁用" onclick="requert('.$v['id'].')" class="sub" style="width:45px;"/>';
	}			
		$subject .= '</span>
				</div>
				<div class="jectAllBox-outhr" style="margin-top:10px;">';
		if($v['addmenu'] == "OFF")
		{		
		$subject .= '<a href="index.php?act=ModuleEdt_phone&flag=3&id='.$v['id'].'" title="模块管理" class="hoverstyle show-hoverstyle"><img src="'.site_url('images/bricks.png').'" align="absmiddle"/></a>';
		}
		if($v['addmenu'] == "ON")
		{	
		$subject .= ' &nbsp; <a href="javascript:;" onclick="conf('.$v['id'].')" title="清除" class="hoverstyle show-hoverstyle"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>';
		}
		$subject .= '</div>
			</div>';
	}	
}	
else 
{
	$subject .= '<div>暂时没有主题，请点击“页面管理”创建主题！</div>';
}		
	$subject .= '</div>
		
		</div>';
	$subject .= '<script>
	$(function(){
		$("#frm").submit(function(){
			if( $("[name=file]").val() == "" )
			{
				alert("未选择任何文件");
				$("[name=file]").focus();
				return false;
			}
		});
	});
	function conf(id)
	{
		var bl = window.confirm("是否要删除，删除后无法恢复");
		if(bl)
		{
			location.href="handling_events.php?act=ThemeMngDeletet&id="+id;
		}
	}
	function requert(id)
	{
		location.href="handling_events.php?act=theme_enable&id="+id;
	}
		$(".showerror").hide(2000);
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#模块插件
function plugIns()
{
	session_start();
	
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	if( $id != '' )
	{
		$row = db()->select('themename')->from(PRE.'theme')->where(array('id'=>$id))->get()->array_row();
	 
		$filename = apth_url('subject/plugin/'.$row['themename'].'/main.php?id='.$id);
	
		if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
		{
			$subject = '<div class="showerror">
			<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
			操作成功
			</div>';
		}
		
		$str = file_get_contents($filename);
		
		$_SESSION['flagEorre'] = null;
		
		return str_replace(array("\n","\t"), array("",""), $subject.$str);
	}
	else 
	{		
		session_start();
		$_SESSION['flagEorre'] = 1;	
		return "<script>location.href='index.php?act=PluginMng';</script>";
	}
}
#主题自带插件
function ThemePlugin()
{
	session_start();
	
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	if( $id != '' )
	{
		$row = db()->select('themename')->from(PRE.'theme')->where(array('id'=>$id))->get()->array_row();
	 
		$filename = apth_url('subject/'.$row['themename'].'/plugin/'.$row['themename'].'/main.php?id='.$id);
	
		if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
		{
			$subject = '<div class="showerror">
			<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
			操作成功
			</div>';
		}
		
		$str = file_get_contents($filename);
		
		if( pingmwh() )
		{
			$sub = $str;
		}
		else
		{
			$sub = str_replace(array('useredit'), array('useredit_phone'), $str);
			//$sub = preg_replace('/width=\"[0-9]+\"/', 'width="100%"', $sub);
		}
		
		$_SESSION['flagEorre'] = null;
		
		return str_replace(array("\n","\t"), array("",""), $subject.$sub);
	}
	else 
	{		
		session_start();
		$_SESSION['flagEorre'] = 1;	
		if( pingmwh() )
		{
			return "<script>location.href='index.php?act=PluginMng';</script>";
		}
		else
		{
			return "<script>location.href='index.php?act=PluginMng_phone';</script>";
		}
	}
}
###################################################################################
#模块管理
function ModuleMng()
{
	session_start();
	#菜单栏,flag=1
	$muen = db()->select('id,modulename')->from(PRE.'module')->where(array('flag'=>1))->get()->array_rows();
	#系统模块,flag=4
	$system = db()->select('id,modulename,filename')->from(PRE.'module')->where('flag=4 or flag=1')->get()->array_rows();
	#自定义模块
	$custom = db()->select('id,modulename,filename')->from(PRE.'module')->where('flag=0')->get()->array_rows();
	#主题和插件创建的模块
	$theme = db()->select('id,modulename,filename,flag')->from(PRE.'module')->where('flag=2 or flag=5')->get()->array_rows();
	#主题include文件夹存储的文件型模块
	$include = db()->select('id,modulename,filename')->from(PRE.'module')->where('flag=3')->get()->array_rows();
	#获取侧栏默认区模块
	$sidebar = db()->select('body,flag')->from(PRE.'storage')->where(array('name'=>'sidebar'))->get()->array_row();
	$sidebar2 = db()->select('body,flag')->from(PRE.'storage')->where(array('name'=>'sidebar2'))->get()->array_row();
	$sidebar3 = db()->select('body,flag')->from(PRE.'storage')->where(array('name'=>'sidebar3'))->get()->array_row();
	$sidebar4 = db()->select('body,flag')->from(PRE.'storage')->where(array('name'=>'sidebar4'))->get()->array_row();
	$sidebar5 = db()->select('body,flag')->from(PRE.'storage')->where(array('name'=>'sidebar5'))->get()->array_row();
	
	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader" style="border:none;">模块管理</div>
		<ul class="menuWeb clear">
		<li class="menuWebAction" style="margin-right:10px;"><a href="index.php?act=ModuleEdt">新建模块</a></li>';
if(!empty($muen))
{		
	foreach( $muen as $k => $v )
	{
		$subject .= '<li class="menuWebAction" style="margin-right:10px;"><a href="index.php?act=ModuleEdtUp&id='.$v['id'].'">'.$v['modulename'].'</a></li>';
	}
}		
	$subject .= '</ul>		
	<div id="divMain2" style="margin-top:5px;font-size:14px;">
<div class="widget-left">
<div class="widget-list">
<div class="widget-list-header">系统模块</div>
<div class="widget-list-note">请拖动需要的模块到右侧区域指定侧栏。侧栏中的模块可排序，也可拖至左侧区域移除。</div>';

if( !empty( $system ) )
{
	foreach( $system as $k => $v )
	{
		$subject .= '<div class="widget widget_source_system widget_id_navbar">
		<div class="widget-title">
		<img class="more-action" width="16" src="'.site_url('images/brick.png').'" alt="" />
		'.$v['modulename'].'
		<span class="widget-action">
		<a href="?act=ModuleEdtUp&id='.$v['id'].'">
		<img class="edit-action" src="'.site_url('images/brick_edit.png').'" alt="编辑" title="编辑" width="16" />
		</a>
		</span>
		</div>
		<div class="funid" style="display:none">'.$v['filename'].'</div>
		</div>';
	}
}
$subject .= '<div class="widget-list-header">用户自定义模块</div>';
if( !empty($custom) )
{
	foreach( $custom as $k => $v )
	{
		$subject .= '<div class="widget widget_source_user widget_id_sdf">
		<div class="widget-title">
		<img class="more-action" width="16" src="'.site_url('images/brick.png').'" alt="" />
		'.$v['modulename'].'
		<span class="widget-action">
		<a href="?act=ModuleEdtUp&id='.$v['id'].'">
		<img class="edit-action" src="'.site_url('images/brick_edit.png').'" alt="编辑" title="编辑" width="16" /></a>
		<a onclick="conf('.$v['id'].');" href="javascript:;">
		<img src="'.site_url('images/delete.png').'" alt="删除" title="删除" width="16" /></a></span></div>
		<div class="funid" style="display:none">'.$v['filename'].'</div>
		</div>';
	}
}
$subject .= '<div class="widget-list-header">主题和插件创建的模块</div>';
if( !empty($theme) )
{
	foreach( $theme as $k => $v )
	{
		if( $v['flag'] == 5 )
		{
			$subject .= '<div class="widget widget_source_plugin widget_id_sdf">
			<div class="widget-title">
			<img class="more-action" width="16" src="'.site_url('images/brick.png').'" alt="" />
			'.$v['modulename'].'
			<span class="widget-action">
			<a href="?act=ModuleEdtUp&id='.$v['id'].'">
			<img class="edit-action" src="'.site_url('images/brick_edit.png').'" alt="编辑" title="编辑" width="16" /></a>
			<a onclick="conf('.$v['id'].');" href="javascript:;">
			<img src="'.site_url('images/delete.png').'" alt="删除" title="删除" width="16" /></a></span></div>
			<div class="funid" style="display:none">'.$v['filename'].'</div>
			</div>';
		}
		elseif( $v['flag'] == 2 ) 
		{
			$subject .= '<div class="widget widget_source_theme widget_id_sdf">
			<div class="widget-title">
			<img class="more-action" width="16" src="'.site_url('images/brick.png').'" alt="" />
			'.$v['modulename'].'
			<span class="widget-action">
			<a href="?act=ModuleEdtUp&id='.$v['id'].'">
			<img class="edit-action" src="'.site_url('images/brick_edit.png').'" alt="编辑" title="编辑" width="16" /></a>
			<a onclick="conf('.$v['id'].');" href="javascript:;">
			<img src="'.site_url('images/delete.png').'" alt="删除" title="删除" width="16" /></a></span></div>
			<div class="funid" style="display:none">'.$v['filename'].'</div>
			</div>';
		}
	}
}
$subject .= '<div class="widget-list-header">主题include文件夹存储的文件型模块</div>';
if( !empty($include) )
{
	foreach( $include as $k => $v )
	{
		$subject .= '<div class="widget widget_source_other widget_id_zxc">
		<div class="widget-title">
		<img class="more-action" width="16" src="'.site_url('images/brick.png').'" alt="" />
		'.$v['modulename'].'
		<span class="widget-action">
		<a href="?act=ModuleEdtUp&id='.$v['id'].'">
		<img class="edit-action" src="'.site_url('images/brick_edit.png').'" alt="编辑" title="编辑" width="16" /></a>
		<a onclick="conf('.$v['id'].');" href="javascript:;">
		<img src="'.site_url('images/delete.png').'" alt="删除" title="删除" width="16" /></a></span></div>
		<div class="funid" style="display:none">'.$v['filename'].'</div>
		</div>';
	}
}
$subject .= '<form id="edit" method="post" action="../external_request.php?act=SidebarSet">
<input type="hidden" id="strsidebar" name="edtSidebar" value="calendar|navbar|controlpanel"/>
<input type="hidden" id="strsidebar2" name="edtSidebar2" value=""/>
<input type="hidden" id="strsidebar3" name="edtSidebar3" value=""/>
<input type="hidden" id="strsidebar4" name="edtSidebar4" value=""/>
<input type="hidden" id="strsidebar5" name="edtSidebar5" value=""/>
</form>
<div class="clear"></div></div>
</div>

<div class="siderbar-list">
<div class="siderbar-drop" id="siderbar">
<div class="siderbar-header">默认侧栏 &nbsp;
<img class="roll" src="'.site_url('images/loading.gif').'" width="16" alt="" />
<span class="ui-icon ui-icon-triangle-1-s"></span>
</div>
<div  class="siderbar-sort-list" >
<div class="siderbar-note" >内置有'.$sidebar['flag'].'个模块</div>
'.$sidebar['body'].'
</div>
</div>
<div class="siderbar-drop" id="siderbar2">
<div class="siderbar-header">侧栏 2&nbsp;
<img class="roll" src="'.site_url('images/loading.gif').'" width="16" alt="" />
<span class="ui-icon ui-icon-triangle-1-s"></span></div>
<div  class="siderbar-sort-list" >
<div class="siderbar-note" >内置有'.$sidebar2['flag'].'个模块</div>
'.$sidebar2['body'].'
</div>
</div>
<div class="siderbar-drop" id="siderbar3">
<div class="siderbar-header">侧栏 3&nbsp;
<img class="roll" src="'.site_url('images/loading.gif').'" width="16" alt="" />
<span class="ui-icon ui-icon-triangle-1-s"></span></div>
<div  class="siderbar-sort-list" >
<div class="siderbar-note" >内置有'.$sidebar3['flag'].'个模块</div>
'.$sidebar3['body'].'
</div>
</div>
<div class="siderbar-drop" id="siderbar4">
<div class="siderbar-header">侧栏 4&nbsp;
<img class="roll" src="'.site_url('images/loading.gif').'" width="16" alt="" />
<span class="ui-icon ui-icon-triangle-1-s"></span></div>
<div  class="siderbar-sort-list" >
<div class="siderbar-note" >内置有'.$sidebar4['flag'].'个模块</div>
'.$sidebar4['body'].'
</div>
</div>
<div class="siderbar-drop" id="siderbar5">
<div class="siderbar-header">侧栏 5&nbsp;
<img class="roll" src="'.site_url('images/loading.gif').'" width="16" alt="" />
<span class="ui-icon ui-icon-triangle-1-s"></span></div>
<div  class="siderbar-sort-list" >
<div class="siderbar-note" >内置有'.$sidebar5['flag'].'个模块</div>
'.$sidebar5['body'].'
</div>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div></div>
		
	</div>';
	$subject .= '<script>
	function conf(id)
	{
		if(window.confirm("单击“确定”继续。单击“取消”停止。"))
		{
			location.href="handling_events.php?act=DeleteModule&id="+id;
		}
	}
	$(".showerror").hide(2000);
	$(function() {
		function sortFunction(){
			var s1="";
			$("#siderbar").find("div.funid").each(function(i){
			   s1 += $(this).html() +"|";
			 });

			 var s2="";
			$("#siderbar2").find("div.funid").each(function(i){
			   s2 += $(this).html() +"|";
			 });

			 var s3="";
			$("#siderbar3").find("div.funid").each(function(i){
			   s3 += $(this).html() +"|";
			 });

			 var s4="";
			$("#siderbar4").find("div.funid").each(function(i){
			   s4 += $(this).html() +"|";
			 });

			 var s5="";
			$("#siderbar5").find("div.funid").each(function(i){
			   s5 += $(this).html() +"|";
			 });

			$("#strsidebar" ).val(s1);
			$("#strsidebar2").val(s2);
			$("#strsidebar3").val(s3);
			$("#strsidebar4").val(s4);
			$("#strsidebar5").val(s5);


			$.post($("#edit").attr("action"),
				{
				"sidebar": s1,
				"sidebar2": s2,
				"sidebar3": s3,
				"sidebar4": s4,
				"sidebar5": s5
				},
			   function(data){
				  /*alert("Data Loaded: " + data);*/
			   });

		};
		$(".ui-icon").each(function(index){
			$(this).click(function(){
				var len = $(".siderbar-sort-list:eq("+index+") .widget").length;
				$(".siderbar-note:eq("+index+")").html("内置有"+len+"个模块");
			});
		});
		var t,f=1;
		function hideWidget(item){
				item.find(".ui-icon").removeClass("ui-icon-triangle-1-s").addClass("ui-icon-triangle-1-w");
				t=item.next();
				t.find(".widget").hide("fast").end().show();
				t.find(".siderbar-note>span").text(t.find(".widget").length);
		}
		function showWidget(item){
				item.find(".ui-icon").removeClass("ui-icon-triangle-1-w").addClass("ui-icon-triangle-1-s");
				t=item.next();
				t.find(".widget").show("fast");
				t.find(".siderbar-note>span").text(t.find(".widget").length);
		}

		$( ".siderbar-header" ).click(function(){
			if($(this).hasClass("clicked")) {
				showWidget($(this));
				$(this).removeClass("clicked");
			}
			else {
				hideWidget($(this));
				$(this).addClass("clicked");
			}
		});

 		$( ".siderbar-sort-list" ).sortable({
 			items:".widget",
			start:function(event, ui){
				showWidget(ui.item.parent().prev());
				 var c=ui.item.find(".funid").html();
				 if(ui.item.parent().find(".widget:contains("+c+")").length>1){
					ui.item.remove();
				 };
			} ,
			stop:function(event, ui){$(this).parent().find(".roll").show("slow");sortFunction();$(this).parent().find(".roll").hide("slow");
				showWidget($(this).parent().prev());
			}
 		}).disableSelection();

		$( ".widget-list>.widget" ).draggable({
			connectToSortable: ".siderbar-sort-list",
			revert: "invalid",
			containment: "document",
			helper: "clone",
			cursor: "move"
		}).disableSelection();

		$( ".widget-list" ).droppable({
			accept:".siderbar-sort-list>.widget",
			drop: function( event, ui ) {
				ui.draggable.remove();				
			}
		});

});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#模块编辑,修改
function ModuleEdtUp()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	#查询数据
	$row = db()->select('id,modulename,filename,htmlid,divorul,body,hiddenmenu,updatepro,sort,flag')->from(PRE.'module')->where(array('id'=>$id))->get()->array_row();
	if( $row['flag'] == 4 || $row['flag'] == 1 )
	{
		if( $row['updatepro'] == 'ON' )
		{
			$modulePaht = apth_url('system/compile/module.php?act='.$row['filename'].'&divorul='.$row['divorul']);
			$str = file_get_contents($modulePaht);
		}
		else 
		{
			$str = null;
		}
	}
	$subject = '<form action="handling_events.php" method="post" id="frm">
	<div class="useredit">';
	
	$subject .= '<div class="userheader">模块编辑</div>
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div class="clear">
		<span class="clzhid" style="width:305px;"><input type="text" name="modulename" value="'.$row['modulename'].'" class="input-s"/></span>
		<span class="clzhid" style="width:62px;line-height:25px;font-size:14px;"> 隐藏标题:</span>
		<span class="clzhid modoelpj" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
		<input type="hidden" name="hiddenmenu" value="'.$row['hiddenmenu'].'" class="pagemodoelpj"/>
		</div>
		<div class="userjibie">文件名: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="filename" value="'.$row['filename'].'" disabled="disabled" class="input-s"/></div>
		<div class="userjibie">HTML ID: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="htmlid" value="'.$row['htmlid'].'" class="input-s"/></div>
		<div class="userjibie">类型: </div>
		<div>
			<input type="radio" name="divorul" value="1" '.($row['divorul']==1?'checked="checked"':'').'/> DIV &nbsp; 
			<input type="radio" name="divorul" value="2" '.($row['divorul']==2?'checked="checked"':'').'/> UL
		</div>
		<div class="userjibie">正文:</div>
		<div><textarea name="body" class="input-w" style="height:195px;">'.($str==null?$row['body']:$str).'</textarea></div>
		<div class="userjibie clear">
		<span class="clzhid" style="width:170px;font-weight:bold;font-size:15px;text-align:center;">禁止系统更新模块内容:</span>
		<span class="clzhid jinzmodel" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;">		
		</span>
		<input type="hidden" name="updatepro" value="'.$row['updatepro'].'" class="pagejinzmodel"/>
		</div>	
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="id" value="'.$id.'"/>
		<input type="hidden" name="flag" value="'.$row['flag'].'"/>
		<input type="hidden" name="act" value="ModuleEdtUp"/>
		<input type="submit" value="提交" class="sub"/>
		</div>
		
	</div>	
		</div></form>';
	$subject .= '<script>
	$(function(){
		var flagmodule2=false;
		$("#frm").submit(function(){
			if( $("[name=modulename]").val() == "" )
			{
				alert("名称不能留空");
				$("[name=modulename]").focus();
				return false;
			}
			if( $("[name=filename]").val() == "" )
			{
				alert("文件名不能留空");
				$("[name=filename]").focus();
				return false;
			}
			if(flagmodule2)
			{
				alert("文件名已经存在");
				$("[name=filename]").focus();
				return false;
			}
			if( $("[name=htmlid]").val() == "" )
			{
				alert("HTML ID不能留空");
				$("[name=htmlid]").focus();
				return false;
			}
		});
		$("[name=filename]").blur(function(){
			$.post("../external_request.php",{act:"ModuleEdt2",val:$(this).val()},function(data){
				if( data == "ON" )
				{
					flagmodule1 = true;
				}
				else
				{
					flagmodule1 = false;
				}
			});
		});
	});
	$(function(){
	if( $("[name=hiddenmenu]").val() == "OFF" )
	{
		$(".modoelpj").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
	else
	{
		$(".modoelpj").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
	var p=0;
	$(".modoelpj").click(function(){
	if(p%2==0){
		$("[name=hiddenmenu]").val("OFF");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}else{
		$("[name=hiddenmenu]").val("ON");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
	p++;
	});
	if( $("[name=updatepro]").val() == "OFF" )
	{
		$(".jinzmodel").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
	else
	{
		$(".jinzmodel").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
	var z=0;
	$(".jinzmodel").click(function(){
	if(z%2==0){
		$("[name=updatepro]").val("OFF");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}else{
		$("[name=updatepro]").val("ON");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
	z++;
});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#模块编辑,添加
function ModuleEdt()
{
	$subject = '<form action="handling_events.php" method="post" id="frm">
	<div class="useredit">';
	
	$subject .= '<div class="userheader">模块编辑</div>
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div class="clear">
		<span class="clzhid" style="width:305px;"><input type="text" name="modulename" value="" class="input-s"/></span>
		<span class="clzhid" style="width:62px;line-height:25px;font-size:14px;"> 隐藏标题:</span>
		<span class="clzhid modoelpj" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
		<input type="hidden" name="hiddenmenu" value="ON" class="pagemodoelpj"/>
		</div>
		<div class="userjibie">文件名: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="filename" value="" class="input-s"/></div>
		<div class="userjibie">HTML ID: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="htmlid" value="" class="input-s"/></div>
		<div class="userjibie">类型: </div>
		<div>
			<input type="radio" name="divorul" value="1"/> DIV &nbsp; 
			<input type="radio" name="divorul" checked="checked" value="2"/> UL
		</div>
		<div class="userjibie">正文:</div>
		<div><textarea name="body" class="input-w" style="height:195px;"></textarea></div>
		<div class="userjibie clear">
		<span class="clzhid" style="width:170px;font-weight:bold;font-size:15px;text-align:center;">禁止系统更新模块内容:</span>
		<span class="clzhid jinzmodel" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;">		
		</span>
		<input type="hidden" name="updatepro" value="ON" class="pagejinzmodel"/>
		</div>';
if( $_GET['id'] == '' && $_GET['flag'] == '' )
{		
	$subject .= '<div><input type="hidden" name="flag" value="0"/></div>';
}
elseif( $_GET['id'] != '' && $_GET['flag'] == 3 )
{
	$subject .= '<div><input type="hidden" name="flag" value="3"/></div>';
}	
elseif( $_GET['id'] != '' && $_GET['flag'] == 5 )
{
	$subject .= '<div><input type="hidden" name="flag" value="5"/></div>';
}	
	$subject .= '<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="templateid" value="'.($_GET['id']==''?0:$_GET['id']).'"/>
		<input type="hidden" name="act" value="ModuleEdt"/>
		<input type="submit" value="提交" class="sub"/>
		</div>
		
	</div>	
		</div></form>';
	$subject .= '<script>
	$(function(){
		var flagmodule2=false;
		$("#frm").submit(function(){
			if( $("[name=modulename]").val() == "" )
			{
				alert("名称不能留空");
				$("[name=modulename]").focus();
				return false;
			}
			if( $("[name=filename]").val() == "" )
			{
				alert("文件名不能留空");
				$("[name=filename]").focus();
				return false;
			}
			if(flagmodule2)
			{
				alert("文件名已经存在");
				$("[name=filename]").focus();
				return false;
			}
			if( $("[name=htmlid]").val() == "" )
			{
				alert("HTML ID不能留空");
				$("[name=htmlid]").focus();
				return false;
			}
		});
		$("[name=filename]").blur(function(){
			$.post("../external_request.php",{act:"ModuleEdt2",val:$(this).val()},function(data){
				if( data == "ON" )
				{
					flagmodule1 = true;
				}
				else
				{
					flagmodule1 = false;
				}
			});
		});
	});
	$(function(){
	var p=0;
	$(".modoelpj").click(function(){
	if(p%2==0){
		$("[name=hiddenmenu]").val("OFF");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}else{
		$("[name=hiddenmenu]").val("ON");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
	p++;
});
var z=0;
	$(".jinzmodel").click(function(){
	if(z%2==0){
		$("[name=updatepro]").val("OFF");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}else{
		$("[name=updatepro]").val("ON");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
	z++;
});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#插件管理
function PluginMng()
{
	session_start();
	#查询插件
	$rows = db()->select('id,author,addmenu,description,FROM_UNIXTIME(publitime) as publitime,themeimg,themename,themeas')->from(PRE.'theme')->where(array('flag'=>1))->get()->array_rows();
	
	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}	
	$subject .= '<div class="userheader" style="border:none;">插件管理</div>
	<form action="handling_events.php" method="post" id="frm" enctype="multipart/form-data">
	<div class="newsTsBox">
		本地上传并安装插件ZIP文件:
		<input type="file" name="file" size="60" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"/>
		 &nbsp; 
		<span>
		<input type="hidden" name="act" value="Pluginupload" class="sub"/>
		<input type="submit" value="上传" class="sub"/>
		</span>
		 &nbsp; 
		<span><input type="reset" value="重置" class="sub"/></span>		
	</div>
	</form>
	<table class="tableBox" style="margin-top:8px;">
			<tr>
				<th style="text-align:center;"></th>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;">作者</th>
				<th style="text-align:center;">日期</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if( !empty($rows) )
{			
	foreach( $rows as $k => $v )
	{
		$subject .= '<tr>
				<td width="60" align="center"><img src="'.apth_url($v['themeimg']).'" alt="" width="32" height="32"></td>
				<td width="300" align="center"><span title="'.$v['description'].'" style="cursor: pointer;">'.$v['themeas'].'</span></td>
				<td width="300" align="center">'.$v['author'].'</td>
				<td width="300" align="center">'.$v['publitime'].'</td>
				<td style="text-align:center;">';
			if($v['addmenu'] == "OFF")
			{
				$subject .= '<a href="handling_events.php?act=plugIns_Enable&id='.$v['id'].'" title="当前启用" class="hoverstyle"><img src="'.site_url('images/control-power.png').'" align="absmiddle"/></a>';
			}
			else 
			{		
				$subject .= '<a href="handling_events.php?act=plugIns_Enable&id='.$v['id'].'" title="当前禁用" class="hoverstyle"><img src="'.site_url('images/control-power-off.png').'" align="absmiddle"/></a>'; 
			}
			$subject .= '&nbsp; &nbsp;'; 
			if($v['addmenu'] == "OFF")
			{
				$subject .= '<a href="index.php?act=plugIns&id='.$v['id'].'" title="管理" class="hoverstyle"><img src="'.site_url('images/setting_tools.png').'" align="absmiddle"/></a>';
				$subject .= '&nbsp; &nbsp;';
			}
			else 
			{	
				if($v['themename'] != "comment")
				{
					$subject .= '<a href="javascript:;" onclick="conf('.$v['id'].')" title="清除" class="hoverstyle"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>';
				}
			}
			$subject .= '</td>
			</tr>';
	}	
}	
else 
{
	$subject .= '<td><td colspan="4">未使用任何插件</td></td>';
}			
	$subject .= '</table>		
	</div>';
	$subject .= '<script>	
	function conf(id)
	{
		var bl = window.confirm("是否要删除，删除后无法恢复");
		if(bl)
		{
			location.href="handling_events.php?act=plugInsDeletet&id="+id;
		}
	}
	$(function(){
		$("#frm").submit(function(){
			if( $("[name=file]").val() == "" )
			{
				alert("未有任何文件上传");
				$("[name=file]").focus();
				return false;
			}
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#插件管理
function PluginMng_phone()
{
	session_start();
	#查询插件
	$rows = db()->select('id,author,addmenu,description,FROM_UNIXTIME(publitime) as publitime,themeimg,themename,themeas')->from(PRE.'theme')->where(array('flag'=>1))->get()->array_rows();
	
	$subject = '<div class="useredit_phone">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}	
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">ticket_fill</i> 插件管理</div>
	<form action="handling_events.php" method="post" id="frm" enctype="multipart/form-data">
	<div class="newsTsBox_phone">
		<p>
		本地上传并安装插件ZIP文件:
		</p>
		<p style="height:50px;">
		<input type="file" name="file" size="60" style="width:95%;border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"/>
		</p> 
		<p style="height:50px;">
		<span>
		<input type="hidden" name="act" value="Pluginupload" class="sub"/>
		<input type="submit" value="上传" class="subClass"/>
		</span>
		</p>  
		<p style="height:50px;">
		<span><input type="reset" value="重置" class="subClass"/></span>	
		</p>	
	</div>
	</form>
	<table class="tableBox" style="margin-top:8px;">
			<tr>
				<th style="text-align:center;"></th>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if( !empty($rows) )
{			
	foreach( $rows as $k => $v )
	{
		$subject .= '<tr>
				<td align="center"><img src="'.apth_url($v['themeimg']).'" alt="" width="32" height="32"></td>
				<td><span title="'.$v['description'].'" style="cursor: pointer;">'.$v['themeas'].'</span></td>
				<td style="text-align:center;">';
			if($v['addmenu'] == "OFF")
			{
				$subject .= '<p class="art_floats">
<a href="handling_events.php?act=plugIns_Enable&id='.$v['id'].'" title="当前启用" class="hoverstyle"><img src="'.site_url('images/control-power.png').'" align="absmiddle"/></a></p>';
			}
			else 
			{		
				$subject .= '<p class="art_floats"><a href="handling_events.php?act=plugIns_Enable&id='.$v['id'].'" title="当前禁用" class="hoverstyle"><img src="'.site_url('images/control-power-off.png').'" align="absmiddle"/></a></p>'; 
			}
			$subject .= '&nbsp; &nbsp;'; 
			if($v['addmenu'] == "OFF")
			{
				$subject .= '<a href="index.php?act=plugIns&id='.$v['id'].'" title="管理" class="hoverstyle show-hoverstyle"><img src="'.site_url('images/setting_tools.png').'" align="absmiddle"/></a>';
				$subject .= '&nbsp; &nbsp;';
			}
			else 
			{	
				if($v['themename'] != "comment")
				{
					$subject .= '<a href="javascript:;" onclick="conf('.$v['id'].')" title="清除" class="hoverstyle show-hoverstyle"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>';
					$subject .= '&nbsp; &nbsp;';
				}
			}
			$subject .= '</td>
			</tr>';
	}	
}	
else 
{
	$subject .= '<td><td colspan="4">未使用任何插件</td></td>';
}			
	$subject .= '</table>		
	</div>';
	$subject .= '<script>	
	function conf(id)
	{
		var bl = window.confirm("是否要删除，删除后无法恢复");
		if(bl)
		{
			location.href="handling_events.php?act=plugInsDeletet&id="+id;
		}
	}
	$(function(){
		$("#frm").submit(function(){
			if( $("[name=file]").val() == "" )
			{
				alert("未有任何文件上传");
				$("[name=file]").focus();
				return false;
			}
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#注册
function ApplicationCenterReset()
{
	session_start();
	
	$b1 = 'background:#3399CC;';
	$c1 = 'color:#FFFFFF;';
	
	$subject = '<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit">
		<div class="userheader" style="border:none;">应用中心(客户端)</div>
		<ul class="menuWeb clear">
			<li class="menuWebAction" style="margin-right:10px;"><a href="index.php?act=ApplicationCenter">在线应用</a></li>
			<li class="menuWebAction" style="margin-right:10px;"><a href="index.php?act=ApplicationUpdate&v=2">应用更新</a></li>
			<li class="menuWebAction" style="margin-right:10px;"><a href="index.php?act=SystemUpdate&v=3">系统更新</a></li>';
if(!isset($_SESSION['ApplicationCenterUser']))
{	
	$subject .= '<li class="menuWebAction" style="margin-right:10px;'.$b1.'"><a href="index.php?act=ApplicationCenterUser" style="'.$c1.'">注册应用商城</a></li>';
}else{	
	$subject .= '<li class="menuWebAction" style="margin-right:10px;'.$b1.'"><a href="index.php?act=ApplicationCenterUser" style="'.$c1.'">应用仓库</a></li>';
}	
	$subject .= '</ul>
		<div class="userjibie">用户级别:</div>
		<div>
			<select name="level" class="input-x">
				<option value="5">游客</option>
			</select>
			&nbsp;
			<span style="font-size:14px;">
			<input type="hidden" name="state" value="0" /> 
			</span>
		</div>
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="userName" vlaue="" class="input-s"/></div>
		<div class="userjibie">密码: </div>
		<div><input type="password" name="pwd" vlaue="" class="input-s"/></div>
		<div class="userjibie">确认密码: </div>
		<div><input type="password" name="pwd2" vlaue="" class="input-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="alias" vlaue="" class="input-s"/></div>
		<div class="userjibie">邮箱: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="email" vlaue="" class="input-s"/></div>
		<div class="userjibie">主页:</div>
		<div><input type="text" name="homepage" vlaue="" class="input-s"/></div>
		<div class="userjibie">摘要:</div>
		<div><textarea name="abst" class="input-w"></textarea></div>
		<div>
			<input type="hidden" name="Template" vlaue="0"/>
		</div>
		<div class="userjibie">默认头像:</div>
		<div class="clear">
		<div class="touxiang1"><img src="'.site_url('header/0.png').'" width="40" height="40"/></div>
		<div class="touxiang2">本地更换</div> &nbsp; <span id="WenPic" style="color:#FF0000;"></span>
		<input type="file" id="tou_file" name="pic" style="display:none">
		</div>
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="act" value="MemberNew" class="sub"/>
		<input type="hidden" name="flag" value="1"/>
		<input type="submit" value="提交" class="sub"/>
		</div>
	</div></form>';
	$subject .= '<script>
		$(function(){
		$("#frm").submit(function(){
			if($("[name=userName]").val()=="")
			{
				alert("名称未命名");
				$("[name=userName]").focus();
				return false;
			}
			if($("[name=pwd]").val()=="")
			{
				alert("请输入密码");
				$("[name=pwd]").focus();
				return false;
			}
			if($("[name=pwd2]").val()=="")
			{
				alert("请输入确认密码");
				$("[name=pwd2]").focus();
				return false;
			}
			if( $("[name=pwd]").val() != $("[name=pwd2]").val() )
			{
				alert("两次密码不一致");
				$("[name=pwd2]").focus();
				return false;
			}
			if($("[name=email]").val()=="")
			{
				alert("邮箱不能留空");
				$("[name=email]").focus();
				return false;
			}
			var email = $("[name=email]").val();
			email = email.replace(/(^\s*)|(\s*$)/g, "");
			var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
			if(reg.test(email)==false)
			{
				alert("邮箱格式正确");
				$("[name=email]").focus();
				return false;
			}
		});
	$(".touxiang2").click(function(){
	document.getElementById("tou_file").click(); 
	});
	$("body").mousemove(function(){
		if( $("[name=pic]").val() != "" )
		{
			$("#WenPic").html($("[name=pic]").val());
		}
		else
		{
			$("#WenPic").html("");
		}
	});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#登录
function ApplicationCenterUser()
{
	session_start();
	
	$b1 = 'background:#3399CC;';
	$c1 = 'color:#FFFFFF;';
	
	$subject = '<div class="useredit">';
	if(isset($_SESSION['flagEorre'])&&$_SESSION['flagEorre']==1)
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader" style="border:none;">应用中心(客户端)</div>
	<ul class="menuWeb clear">
		<li class="menuWebAction" style="margin-right:10px;"><a href="index.php?act=ApplicationCenter">在线应用</a></li>
		<li class="menuWebAction" style="margin-right:10px;"><a href="index.php?act=ApplicationUpdate&v=2">应用更新</a></li>
		<li class="menuWebAction" style="margin-right:10px;"><a href="index.php?act=SystemUpdate&v=3">系统更新</a></li>';
if(!isset($_SESSION['ApplicationCenterUser']))
{	
	$subject .= '<li class="menuWebAction" style="margin-right:10px;'.$b1.'"><a href="index.php?act=ApplicationCenterUser" style="'.$c1.'">登录应用商城</a></li>';
}else{	
	$subject .= '<li class="menuWebAction" style="margin-right:10px;'.$b1.'"><a href="index.php?act=ApplicationCenterUser" style="'.$c1.'">应用仓库</a></li>';
}	
	$subject .= '</ul>
	<form action="handling_events.php" method="post" id="frm">
	<table class="tableBox" style="margin-top:8px;">';	
		$subject .= '<tr>
				<td style="height:50px;text-align:center;">账户登录</td>
			</tr>';
		$subject .= '<tr>
				<td style="height:50px;text-align:center;">用户名: <input type="text" name="userName" vlaue="" class="input-s"></td>
			</tr>';
		$subject .= '<tr>
				<td style="height:50px;text-align:center;">密 &nbsp; 码: <input type="text" name="pwd" vlaue="" class="input-s"></td>
			</tr>';
		$subject .= '<tr>
				<td style="height:50px;text-align:center;">
				<input type="hidden" name="act" value="ApplicationCenterUser" >
				<input type="submit" value="登录" class="sub">
				</td>
			</tr>';			
	$subject .= '</table></form>
	</div>';
	$subject .= '<script>
	
	$(function(){
		$("#frm").submit(function(){
			if( $("[name=userName]").val() == "" )
			{
				alert("请输入用户名");
				$("[name=userName]").focus();
				return false;
			}
			if( $("[name=pwd]").val() == "" )
			{
				alert("请输入密码");
				$("[name=userName]").focus();
				return false;
			}
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#右侧小边栏,插件模块
function ApplicationCenterLeft()
{
	session_start();

	if($_GET['v']=='')
	{
		$back1 = 'background:#E8D853;';
		$b1 = 'background:#3399CC;';
		$c1 = 'color:#FFFFFF;';
	}
	else 
	{
		if($_GET['v']==0)
		{
			$back2 = 'background:#6BE354;';
		}
		elseif($_GET['v']==1) 
		{
			$back3 = 'background:#E85353;';
		}
		elseif($_GET['v']==2) 
		{
			$back1 = 'background:#E8D853;';
			$b2 = 'background:#3399CC;';
			$c2 = 'color:#FFFFFF;';
		}
		elseif($_GET['v']==3) 
		{
			$back1 = 'background:#E8D853;';
			$b3 = 'background:#3399CC;';
			$c3 = 'color:#FFFFFF;';
		}
		elseif($_GET['v']==4) 
		{
			$back1 = 'background:#E8D853;';
			$b4 = 'background:#3399CC;';
			$c4 = 'color:#FFFFFF;';
		}
	}
	
	$path = 'system/remotelink.php';
	$filename = SERVICE_LINK.$path;
	$json = vcurl($filename,'act=InDataTPComment');
	$rows1 = json_decode($json,true);
	
	$subject = '<div class="useredit">
	<div class="userheader" style="border:none;">应用中心(客户端)</div>
	<ul class="menuWeb clear">
		<li class="menuWebAction" style="margin-right:10px;'.$b1.'"><a href="index.php?act=ApplicationCenter" style="'.$c1.'">在线应用</a></li>
		<li class="menuWebAction" style="margin-right:10px;'.$b2.'"><a href="index.php?act=ApplicationUpdate&v=2" style="'.$c2.'">应用更新</a></li>
		<li class="menuWebAction" style="margin-right:10px;'.$b3.'"><a href="index.php?act=SystemUpdate&v=3" style="'.$c3.'">系统更新</a></li>';
if(!isset($_SESSION['ApplicationCenterUser']))
{	
	$subject .= '<li class="menuWebAction" style="margin-right:10px;'.$b4.'"><a href="index.php?act=ApplicationCenterUser&v=4" style="'.$c4.'">登录应用商城</a></li>';
}else{	
	$subject .= '<li class="menuWebAction" style="margin-right:10px;'.$b4.'"><a href="index.php?act=ApplicationRepository&v=4" style="'.$c4.'">应用仓库</a></li>';
}	
	$subject .= '</ul>
	<div class="shangcheng clear">
		<div class="schlr schlrLeft">
			<div class="login-y">';
if(!isset($_SESSION['ApplicationCenterUser']))
{			
		$subject .= '登录“应用中心”商城 ';
}else{		
		$subject .= '用户名: '.$_SESSION['ApplicationCenterUser'];
}		
		$subject .= '</div>
			<div class="login-user">';
if(!isset($_SESSION['ApplicationCenterUser']))
{			
		$subject .= '<div class="renset">
					如果您已有“应用中心”的购买者账号，请点<a href="index.php?act=ApplicationCenterUser">这里登录</a>。<br/><br/>
					购买应用，请点<a href="index.php?act=ApplicationCenterReset">这里注册</a>“应用中心”账号。
				</div>';
}else{				
		$subject .= '<div class="renset">
					'.$_SESSION['ApplicationCenterUser'].'您好！欢迎来到‘应用中心’商城。<br/>下载已购买应用，请点击“我的应用仓库”。<br/><br/>
					〖<a href="javascript:;" onclick="conf();">退出</a>‘应用中心’〗
				</div>';
}				
		$subject .= '</div>
			<div class="search-a">
				<div><input type="text" name="sv" value="" class="renyiCl"/> <input type="submit" value="搜索" id="svds" class="sub"/></div>
				<ul class="menuWeb-bb clear">
					<li class="menuWeb-yy" style="margin-right:10px;'.$back1.'"><a href="index.php?act=ApplicationCenter">全部应用</a></li>
					<li class="menuWeb-yy" style="margin-right:10px;'.$back2.'"><a href="index.php?act=ApplicationCenter&v=0">免费应用</a></li>
					<li class="menuWeb-yy" style="margin-right:10px;'.$back3.'"><a href="index.php?act=ApplicationCenter&v=1">收费应用</a></li>
				</ul>
			</div>
			<div class="login-y">网站分类</div>
			<dl class="ddlist-dd">
				<dd><p style="background:#DDA0DD;"></p><a href="index.php?act=ApplicationCenter&flag=0">PHP主题</a></dd>
				<dd><p style="background:#DAA520;"></p><a href="index.php?act=ApplicationCenter&flag=1">PHP插件</a></dd>
			</dl>
			<div class="login-y">最新留言</div>
			<dl class="ddlist-dd" style="height:100%;">';
if(!empty($rows1[0]))
{	
	foreach($rows1[0] as $k=>$v)
	{
		$subject .= '<dd><a href="'.apth_url('system/admin/index.php?act=information_on&id='.$v['titleid']).'">'.subString($v['body'],12).'</a></dd>';
	}
}		
		$subject .= '</dl>
		</div>';
	return $subject;
}
#应用中心(客户端)
function ApplicationCenter()
{
		$path = 'system/remotelink.php';
		$filename = SERVICE_LINK.$path;
		#查询数据,服务商
		$page = '';
		if($_GET['page']!='')
		{
			$page = '&page='.$_GET['page'];
		}
		$flag = '';
		if($_GET['flag']!='')
		{
			$flag = '&flag='.$_GET['flag'];
		}
		$v = '';
		if($_GET['v']!='')
		{
			$v = '&v='.$_GET['v'];
		}
		$s = '';
		if($_GET['s']!='')
		{
			$s = '&s='.$_GET['s'];
		}
		$json = vcurl($filename,'act=InDataInfo'.$page.$flag.$v.$s);
		$rows = json_decode($json,true);

		$subject = ApplicationCenterLeft();
		$subject .= '<div class="schlr schlrRight">';
if( !empty($rows[0]) )
{	
	foreach( $rows[0] as $k => $v )
	{	
		if( $v['price'] != '0' )
		{
			if( $v['flag'] == '0' )
			{
			$subject .= '<div class="ject-chjian" style="width:330px;">
				<div class="ject-chjian-in">
				<p style="background:#DDA0DD;"></p>&nbsp;
				<b>[PHP]</b> <a href="index.php?act=information&id='.$v['id'].'">'.$v['themeas'].'</a>
				<img style="float:right;" src="'.site_url('images/sell.png').'"/>
				</div>
				<div class="ject-chjian-box">
					<a href="index.php?act=information&id='.$v['id'].'"><img class="css_shadow" src="'.SERVICE_LINK.$v['themeimg'].'" align="absmiddle" width="180" height="136"/></a>
				</div>
				<div class="ject-chjian-ff">'.$v['description'].'</div>
			</div>';
			}
			else 
			{
			$subject .= '<div class="ject-chjian" style="width:330px;">
				<div class="ject-chjian-in">
				<p style="background:#DAA520;"></p>&nbsp;
				<b>[PHP]</b> <a href="index.php?act=information&id='.$v['id'].'">'.$v['themeas'].'</a>
				<img style="float:right;" src="'.site_url('images/sell.png').'"/>
				</div>
				<div class="ject-chjian-box">
					<a href="index.php?act=information&id='.$v['id'].'"><img class="css_shadow" src="'.SERVICE_LINK.$v['themeimg'].'" align="absmiddle" width="130" height="136"/></a>
				</div>
				<div class="ject-chjian-ff">'.$v['description'].'</div>
			</div>';
			}
		}
		else
		{	
			if( $v['flag'] == '0' )
			{
			$subject .= '<div class="ject-chjian" style="width:330px;">
				<div class="ject-chjian-in">
				<p style="background:#DDA0DD;"></p>&nbsp;
				<b>[PHP]</b> <a href="index.php?act=information_on&id='.$v['id'].'">'.$v['themeas'].'</a>
				
				</div>
				<div class="ject-chjian-box">
					<a href="index.php?act=information_on&id='.$v['id'].'"><img class="css_shadow" src="'.SERVICE_LINK.$v['themeimg'].'" align="absmiddle" width="180" height="136"/></a>
				</div>
				<div class="ject-chjian-ff">'.$v['description'].'</div>
			</div>';
			}
			else 
			{
			$subject .= '<div class="ject-chjian" style="width:330px;">
				<div class="ject-chjian-in">
				<p style="background:#DAA520;"></p>&nbsp;
				<b>[PHP]</b> <a href="index.php?act=information_on&id='.$v['id'].'">'.$v['themeas'].'</a>
				</div>
				<div class="ject-chjian-box">
					<a href="index.php?act=information_on&id='.$v['id'].'"><img class="css_shadow" src="'.SERVICE_LINK.$v['themeimg'].'" align="absmiddle" width="130" height="136"/></a>
				</div>
				<div class="ject-chjian-ff">'.$v['description'].'</div>
			</div>';
			}
		}
	}	
}else{
	$subject .= '<div class="ject-chjian" style="color:red;">暂时没有</div>';
}							
	$subject .= '</div>
	</div>';
	$subject .= '<div class="page-list-in">
			总数:'.$rows[1].'，当前'.$rows[2].'/'.$rows[3].'页 ';
if($rows[1]>10)
{	
	$subject .= '<a href="index.php?act=ApplicationCenter&page='.($rows[2]-1).'">上一页</a> | <a href="index.php?act=ApplicationCenter&page='.($rows[2]+1).'">下一页</a> 
			<input type="text" name="GO" value="" id="renyiCl" size="2"/>
			<input type="submit" value="GO" class="submitGo"/>';
}	
	$subject .= '</div>
	</div>
	<script>
	function conf()
	{
		var bl = window.confirm("是否要退出");
		if(bl)
		{
			location.href="handling_events.php?act=ApplicationCenterOut";
		}
	}
$(function(){
	$("#svds").click(function(){
		var s = $("[name=sv]").val();
		if( s != "" )
		{
			location.href="index.php?act=ApplicationCenter&s="+s;
		}
	});
	$("[value=GO]").click(function(){
		if($("#renyiCl").val()!="")
		{
			location.href="index.php?act=ApplicationCenter&page="+$("#renyiCl").val();
		}
	});
	});
	</script>';
	
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#用户仓库
function ApplicationRepository()
{
		#查询数据
		$sql = 'select * ';	
		$sql .= ' from '.PRE.'theme ';
		
		$rowsTotal = db()->query($sql)->array_nums();
		$showTotal = 10;
		$pageTotal = ceil($rowsTotal/$showTotal);
		$page = $_GET['page']==''?1:$_GET['page'];
		if($page>=$pageTotal){$page=$pageTotal;}
		if($page<=1){$page=1;}
		$offset = ($page-1)*$showTotal;
		$limit = $offset.','.$showTotal;
		$sql .= " order by publitime desc limit {$limit} ";
		$rows = db()->query($sql)->array_rows();

		$subject = ApplicationCenterLeft();
		$subject .= '<div class="schlr schlrRight">';
if( !empty($rows) )
{	
	foreach( $rows as $k => $v )
	{	
		if( $v['price'] != '0' )
		{
			if( $v['flag'] == '0' )
			{
			$subject .= '<div class="ject-chjian">
				<div class="ject-chjian-in">
				<p style="background:#DDA0DD;"></p>&nbsp;
				<b>[PHP]</b> <a href="javascript:;">'.$v['themeas'].'</a>
				<img style="float:right;" src="'.site_url('images/sell.png').'"/>
				</div>
				<div class="ject-chjian-box">
					<a href="javascript:;"><img class="css_shadow" src="'.SERVICE_LINK.$v['themeimg'].'" align="absmiddle" width="180" height="136"/></a>
				</div>
				<div class="ject-chjian-ff">'.$v['description'].'</div>
			</div>';
			}
			else 
			{
			$subject .= '<div class="ject-chjian">
				<div class="ject-chjian-in">
				<p style="background:#DAA520;"></p>&nbsp;
				<b>[PHP]</b> <a href="javascript:;">'.$v['themeas'].'</a>
				<img style="float:right;" src="'.site_url('images/sell.png').'"/>
				</div>
				<div class="ject-chjian-box">
					<a href="javascript:;"><img class="css_shadow" src="'.SERVICE_LINK.$v['themeimg'].'" align="absmiddle" width="130" height="136"/></a>
				</div>
				<div class="ject-chjian-ff">'.$v['description'].'</div>
			</div>';
			}
		}
		else
		{	
			if( $v['flag'] == '0' )
			{
			$subject .= '<div class="ject-chjian">
				<div class="ject-chjian-in">
				<p style="background:#DDA0DD;"></p>&nbsp;
				<b>[PHP]</b> <a href="javascript:;">'.$v['themeas'].'</a>
				
				</div>
				<div class="ject-chjian-box">
					<a href="javascript:;"><img class="css_shadow" src="'.SERVICE_LINK.$v['themeimg'].'" align="absmiddle" width="180" height="136"/></a>
				</div>
				<div class="ject-chjian-ff">'.$v['description'].'</div>
			</div>';
			}
			else 
			{
			$subject .= '<div class="ject-chjian">
				<div class="ject-chjian-in">
				<p style="background:#DAA520;"></p>&nbsp;
				<b>[PHP]</b> <a href="javascript:;">'.$v['themeas'].'</a>
				</div>
				<div class="ject-chjian-box">
					<a href="javascript:;"><img class="css_shadow" src="'.SERVICE_LINK.$v['themeimg'].'" align="absmiddle" width="130" height="136"/></a>
				</div>
				<div class="ject-chjian-ff">'.$v['description'].'</div>
			</div>';
			}
		}
	}	
}else{
	$subject .= '<div class="ject-chjian" style="color:red;">暂时没有</div>';
}							
	$subject .= '</div>
	</div>';
	$subject .= '<div class="page-list-in">
			总数:'.$rowsTotal.'，当前'.$page.'/'.$pageTotal.'页 ';
if($rowsTotal>$showTotal)
{	
	$subject .= '<a href="index.php?act=ApplicationCenter&page='.($page-1).'">上一页</a> | <a href="index.php?act=ApplicationCenter&page='.($page+1).'">下一页</a> 
			<input type="text" name="GO" value="" id="renyiCl" size="2"/>
			<input type="submit" value="GO" class="submitGo"/>';
}	
	$subject .= '</div>
	</div>
	<script>
	function conf()
	{
		var bl = window.confirm("是否要退出");
		if(bl)
		{
			location.href="handling_events.php?act=ApplicationCenterOut";
		}
	}
$(function(){
	$("#svds").click(function(){
		var s = $("[name=sv]").val();
		if( s != "" )
		{
			location.href="index.php?act=ApplicationCenter&s="+s;
		}
	});
	$("[value=GO]").click(function(){
		if($("#renyiCl").val()!="")
		{
			location.href="index.php?act=ApplicationCenter&page="+$("#renyiCl").val();
		}
	});
	});
	</script>';
	
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#应用更新
function ApplicationUpdate()
{
		$path = 'system/remotelink.php';
		$filename = SERVICE_LINK.$path;
		#查询数据,服务商
		$json = vcurl($filename,'act=InDataApplication');
		$rows = json_decode($json,true);
		
		$subject = ApplicationCenterLeft();
		$subject .= '<div class="schlr schlrRight">
		<div style="margin-top:25px;font-size:14px;line-height:25px;"><b>最近更新 <font color="red">'.count($rows[0]).'</font> 条：</b></div>
			<ul style="margin-top:5px;">';
if( !empty( $rows[0] ) )
{	
	foreach( $rows[0] as $k => $v )
	{	
		$subject .= '<li style="height:25px;font-size:14px;line-height:25px;">· <a href="index.php?act=information_on&id='.$v['id'].'">'.$v['title'].'</a></li>';
	}
}else{
	$subject .= '<li style="height:25px;font-size:14px;line-height:25px;">暂时没有</li>';
}
		$subject .= '</ul>
		</div>
	</div>';
$subject .= '</div>
	<script>
	function conf()
	{
		var bl = window.confirm("是否要退出");
		if(bl)
		{
			location.href="handling_events.php?act=ApplicationCenterOut";
		}
	}
$(function(){
	$("#svds").click(function(){
		var s = $("[name=sv]").val();
		if( s != "" )
		{
			location.href="index.php?act=ApplicationCenter&s="+s;
		}
	});
});
	</script>';
	
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#系统更新
function SystemUpdate()
{
		$path = 'system/remotelink.php';
		$filename = SERVICE_LINK.$path;
		#查询数据,服务商
		$json = vcurl($filename,'act=InDataSystem');
		$row = json_decode($json,true);
		
		$subject = ApplicationCenterLeft();

		$subject .= '<div class="schlr schlrRight">
		<div style="margin-top:25px;font-size:14px;line-height:25px;"><b>最近更新 <font color="red">'.count($row[0]).'</font> 条：</b></div>
			<div>'.($row[0]['body']==''?'暂时没有':$row[0]['body']).'</div>
		</div>
	</div>';
$subject .= '</div>
	<script>
	function conf()
	{
		var bl = window.confirm("是否要退出");
		if(bl)
		{
			location.href="handling_events.php?act=ApplicationCenterOut";
		}
	}
$(function(){
	$("#svds").click(function(){
		var s = $("[name=sv]").val();
		if( s != "" )
		{
			location.href="index.php?act=ApplicationCenter&s="+s;
		}
	});
});
	</script>';

	return str_replace(array("\n","\t"), array("",""), $subject);
}
#购买信息页面,付费
function information()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	if($id == '' )
	{
		echo '请选择相应的主题或插件';exit;
	}
	#查询数据
	$sql = 'select a.id,a.author,a.themename,a.homepage,a.themeas,a.description,a.themeimg,a.price,a.flag,b.pid,b.version,b.title,b.scoring,b.phpversion,b.systems,b.times,FROM_UNIXTIME(b.updatedate) as updatedate,b.details,b.about,b.guide,b.shelflag ';	
	$sql .= ' from '.PRE.'theme as a,'.PRE.'shelves as b where a.id=b.pid and a.id='.$id;
	#查找
	$row = db()->query($sql)->array_row();
	#平均得分
	$avg = db()->select('avg(scoring) as v')->from(PRE.'shelves')->where(array('pid'=>$id))->get()->array_row();
	#用户昵称
	$nickname = nickname(1);
	#评论设置
	$setcomment = db()->from(PRE.'review_up')->select('vifiy,colsecomment,talbox,qqbox,emailbox')->get()->array_row();
	#评论
	$review = db()->select('listtotal,sort')->from(PRE.'review_up')->get()->array_row();
	#开启时生效
	if( !empty($review['listtotal']) )
	{
		$limit = " limit 0,{$review['listtotal']} ";
	}
	$asc = 'desc';
	if( $review['sort'] == 'OFF' )
	{
		$asc = 'asc';
	}
	$sql = "select id,pid,likes,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state from ".PRE."review where state=0 and flag=1 and titleid={$id} order by  publitime {$asc} , likes {$asc} {$limit}";
	$rows = db()->query($sql)->array_rows();
	$all = $rows;
	foreach( $rows as $k => $v )
	{
		if($v['pid'] != 0 )
		{
			$sql = "select id,pid,likes,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state from ".PRE."review where state=0 and flag=1 and id={$v['pid']} ";
			$row = db()->query($sql)->array_row();
			$all[$k]['chill'] = $row;
		}
	}
	
	$subject = ApplicationCenterLeft();
	$subject .= '
	<link rel="stylesheet" rev="stylesheet" href="'.apth_url('subject/plugin/comment/css/global.css').'" type="text/css" media="all"/>
	<div class="schlr schlrRight">';
if($row['flag'] == 0)
{	
	$subject .= '<div class="mation-info">PHP主题</div>';
}else{	
	$subject .= '<div class="mation-info2">PHP插件</div>';
}	
	$subject .= '<div class="mation-box clear">
			<div class="mation-left clear" style="width:200px;height:150px;float:left;text-align:center;">';
	if($row['flag'] == 0)
	{		
	$subject .= '<a><img src="'.apth_url($row['themeimg']).'" width="180" height="136" align="absmiddle"></a>';
	}else{
	$subject .= '<a><img src="'.apth_url($row['themeimg']).'" width="130" height="136" align="absmiddle"></a>';	
	}	
	$subject .= '</div>
			<div class="mation-right" style="float:right;">
				<div class="mation-right-tile">
				<img style="float:left;" src="'.site_url('images/sell.png').'"/>
				<input style="float:right;text-align:center;padding:5px 10px;font-size:15px;font-weight:bold;" type="text" name="" value="购买应用" class="sub"/>
				&nbsp; <b>'.$row['themeas'].'</b><br/>
				&nbsp; <em style="color:#FF0000;font-size:13px;font-weight:bold;font-style:normal;">价格：'.$row['price'].'元</em>	
				</div>
				<table class="mation-right-table">
					<tr>
						<td width="70">主题ID:</td>
						<td>'.$row['themename'].'</td>
						<td width="70">系统要求:</td>
						<td>'.$row['systems'].'</td>
					</tr>
					<tr>
						<td width="70">版 本:</td>
						<td>'.$row['version'].'</td>
						<td width="70">更新日期:</td>
						<td>'.$row['updatedate'].'</td>
					</tr>
					<tr>
						<td width="70">作 者:</td>
						<td>'.$nickname.'</td>
						<td width="70">下载次数:</td>
						<td>'.$row['times'].'</td>
					</tr>
					<tr>
						<td width="70">评分情况：</td>
						<td colspan="3">
							<div id="star2">
								<dl>
									<dd></dd>
									<dd></dd>
									<dd></dd>
									<dd></dd>
									<dd></dd>
								</dl>	
							</div>					
						</td>
					</tr>
					<tr>
						<td width="70">PHP版本:</td>
						<td colspan="3">'.$row['phpversion'].'及更高 (当前系统为PHP '.PHP_VERSION.',支持该应用.)</td>
					</tr>
				</table>				
			</div>
			<div class="maty-fs clear">
				请选择支付方式：
				<input type="radio" name="radio" value="" id="r1"/><label for="r1"> 支付宝</label> &nbsp; &nbsp; 
				<input type="radio" name="radio" value="" id="r2"/><label for="r2"> 微信支付</label> &nbsp; &nbsp; 
				<input type="radio" name="radio" value="" id="r3"/><label for="r3"> 银联支付</label> &nbsp; &nbsp;  
			</div>
			<div class="mation-infos-in clear">
				<ul class="clear">
					<li class="mation-infos-action">详细介绍</li>
					<li>网友评论</li>
					<li>作者简介</li>
					<li>购买及下载指南</li>
				</ul>
			</div>
			<div class="mation-infos-in-list" >'.$row['details'].'</div>
			<div class="mation-infos-in-list" style="display:none;">
			<div style="margin-top:20px;line-height:23px;">欢迎发表评论并打分</div>
			<div id="star">
				<ul title="点击评分">
					<li class="activestar"></li>
					<li class="activestar"></li>
					<li class="activestar"></li>
					<li class="activestar"></li>
					<li class="activestar"></li>
				</ul>
				<div id="dv2"></div>
			</div>	
			
<div style="margin-top:10px;">
	<p class="posttop"><a name="comment"></a></p>
	<p>'.$nickname.'</p>
	<form id="frmSumbit" method="post" action="handling_events.php" >
	<input type="hidden" name="titleid" id="inpId" value="'.$id.'" />';
if($nickname == false)
{	
$subject .= '<p style="margin-top:10px;"><input type="text" name="name" id="inpRevID" value="" /> 名称(*)</p>	';
$subject .= '<p style="margin-top:10px;"><input type="text" name="email" id="inpEmail" value="" /> 邮箱(*)</p>';
}else{
$subject .= '<input type="hidden" name="name" id="inpRevID" value="'.$nickname.'" />';
$subject .= '<input type="hidden" name="email" id="inpEmail" value="" />';
}
$subject .= '<input type="hidden" name="tal" id="inpName" value="" />
	<input type="hidden" name="qq" id="inpEmail" value="" />
	<a rel="nofollow" id="cancel-reply" href="javascript:;" style="display:none;">
	<small>取消回复</small></a>
	</p>
	<p style="margin-top:10px;"><textarea name="body" id="txaArticle" class="text" cols="50" rows="4" tabindex="5" ></textarea></p>
	<p>
	<input type="hidden" name="pid" value="0" />
	<input type="hidden" name="flag" value="1" />
	<input type="hidden" name="scoring" value="5" id="scoring"/>
	<input type="hidden" name="dianzanUrl" value="'.apth_url('system/external_request.php').'" />
	<input type="hidden" name="act" value="review" />
	<input type="submit" tabindex="6" value="提交" class="button" />';
if( $setcomment['vifiy'] == "OFF" )
{
$subject .= '验证码　<input type="text" name="virify" size="2" id="inpHomePage" value="" /> <img src="'.apth_url('system/virify.php').'" alt="验证码" title="点击换下一张" align="absmiddle" onclick="vitifyfunc(this);"/>';
}
$subject .= '</p>
	</form>
	<p class="postbottom"></p>
	</div>	
			
		<div class="content-CommentPost">';
if(!empty($all))
{	
$subject .= '<div class="Comment-line"><a>热门评论:</a></div>';
	foreach($all as $k=>$v){
		if($v['pid'] != 0)
		{
$subject .= '<div class="Commentqu">
			<div class="Commentqu-ins Commentqu-ins-left" ><img src="'.$v['pic'].'" width="48" height="48"/></div>
			<div class="Commentqu-ins Commentqu-ins-right">
			<div class="CommentotherName">
			<div class="Comment-name-list1">'.$v['name'].'</div>
			<div class="Comment-name-list2">
				<dl>
					<dd id="Comment-dianzan"><span class="Comment-dianzan'.$v['id'].'" onclick="dianzan('.$v['id'].')" style="cursor:pointer;">点赞</span> <span class="Comment-dianzan-num'.$v['id'].'">'.$v['likes'].'</span></dd>
					<dd>
					<a href="#comment" class="comment-huifu-this">回复</a>
					<input type="hidden" class="comment-hidden-id" value="'.$v['id'].'"/>
					</dd>
					<dd><a href="javascript:;" onclick="report('.$v['id'].')">举报</a></dd>
				</dl>
			</div>
			<div style="clear:both;"></div>
			</div>
			<div class="CommentotherTime">'.$v['publitime'].'</div>
			<div class="Commentotherbody">
			<p><span class="Comment-huifu-ins">回复</span> <span class="Comment-huifu-ins">'.$v['chill']['name'].'：</span>'.$v['body'].'</p>
			<div class="Comment-name-huifu">
			<p><span class="Comment-huifu-ins">'.$v['chill']['name'].'：</span>'.$v['chill']['body'].'</p>
			</div>
			</div>
			</div>
			<div style="clear:both;"></div>
		</div>';

		}else{
		
$subject .= '<div class="Commentqu">
			<div class="Commentqu-ins Commentqu-ins-left" ><img src="'.$v['pic'].'" width="48" height="48"/></div>
			<div class="Commentqu-ins Commentqu-ins-right">
			<div class="CommentotherName">
			<div class="Comment-name-list1">'.$v['name'].'</div>
			<div class="Comment-name-list2">
				<dl>
					<dd id="Comment-dianzan"><span class="Comment-dianzan'.$v['id'].'" onclick="dianzan('.$v['id'].')" style="cursor:pointer;">点赞</span> <span class="Comment-dianzan-num'.$v['id'].'">'.$v['likes'].'</span></dd>
					<dd>
					<a href="#comment" class="comment-huifu-this">回复</a>
					<input type="hidden" class="comment-hidden-id" value="'.$v['id'].'"/>
					</dd>
					<dd><a href="javascript:;" onclick="report('.$v['id'].')">举报</a></dd>
				</dl>
			</div>
			<div style="clear:both;"></div>
			</div>
			<div class="CommentotherTime">'.$v['publitime'].'</div>
			<div class="Commentotherbody">
			<p>'.$v['body'].'</p>
			</div>
			</div>
			<div style="clear:both;"></div>
		</div>';
		}
	}
	$subject .= '<div style="text-align:center;display:none;" id="pulun-loading1"><img src="'.apth_url('subject/plugin/comment/images/loading-0.gif').'" alt="加载中..."/></div>';
	$subject .= '<div style="text-align:center;display:none;" id="pulun-loading2">加载完毕</div>';
}		
$subject .= '</div>	
			</div>
			<div class="mation-infos-in-list" style="display:none;">'.$row['about'].'</div>
			<div class="mation-infos-in-list" style="display:none;">'.$row['guide'].'</div>
		</div>
	</div>';
	$subject .= '
<script src="'.apth_url('subject/plugin/comment/js/resetform.js').'" type="text/javascript"></script>
	<script>
	function vitifyfunc(srcobj)
	{
		srcobj.src = srcobj.src+"?rand="+Math.random();
	}
	$(function(){
		var avg = '.ceil($avg['v']).';
		for(var i=0;i<avg;i++)
		{
			$("#star2 dd:eq("+i+")").addClass("activestar");
		}
		$(".mation-right-table tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".mation-right-table tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".mation-right-table tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	$(function(){
		$(".mation-infos-in li").each(function(index){
			$(this).click(function(){
				$(this).addClass("mation-infos-action").siblings().removeClass("mation-infos-action");
				$(".mation-infos-in-list").hide();
				$(".mation-infos-in-list:eq("+index+")").show();
			});
		});
	});
window.onload = function(){
	var oDiv = document.getElementById("star");
	var aLi = oDiv.getElementsByTagName("li");
	var oDiv2 = document.getElementById("dv2");
	var scoring = document.getElementById("scoring");
	var aText = ["很差","差","一般","好","很好"];
	for(var i=0; i<aLi.length; i++){
		aLi[i].index = i;
		aLi[i].onmouseover = function(){
			oDiv2.innerHTML = aText[this.index];
			for(var i=0; i<aLi.length; i++){
				aLi[i].className = "";
			}
			for(var i=0; i<=this.index; i++){
				aLi[i].className = "activestar";
			}
		}
		aLi[i].onmouseout = function(){
			for(var i=0; i<this.index; i++){
				aLi[i].className = "activestar";
			}
			scoring.value = this.index+1;
		}
		aLi[i].onclick = function(){
				scoring.value = this.index+1;
		}
	}
}

	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#购买信息页面,免费
function information_on()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));

	$path = 'system/remotelink.php';
	$filename = SERVICE_LINK.$path;
	#查询数据,服务商
	$json = vcurl($filename,'act=InDataTPInfo&id='.$id);
	$rows = json_decode($json,true);
	
	#查询数据
	$row = $rows[0];
	#平均得分
	$avg = $rows[1];
	#用户昵称
	$nickname = nickname2(1);
	#评论设置
	$setcomment = $rows[2];
	#评论
	$review = $rows[3];
	#开启时生效
	$all = $rows[4];
	
	$subject = ApplicationCenterLeft();
	$subject .= '
	<link rel="stylesheet" rev="stylesheet" href="'.apth_url('subject/plugin/comment/css/global.css').'" type="text/css" media="all"/>
	<div class="schlr schlrRight">';
if($row['flag'] == 0)
{	
	$subject .= '<div class="mation-info">PHP主题</div>';
}else{	
	$subject .= '<div class="mation-info2">PHP插件</div>';
}	
	$subject .= '<div class="mation-box clear">
			<div class="mation-left clear" style="width:200px;height:150px;float:left;text-align:center;">';
	if($row['flag'] == 0)
	{		
	$subject .= '<a><img src="'.SERVICE_LINK.$row['themeimg'].'" width="180" height="136" align="absmiddle"></a>';
	}else{
	$subject .= '<a><img src="'.SERVICE_LINK.$row['themeimg'].'" width="130" height="136" align="absmiddle"></a>';	
	}		
	$subject .= '</div>
			<div class="mation-right" style="float:right;">
				<div class="mation-right-tile">
				<input style="float:right;text-align:center;padding:5px 10px;font-size:15px;font-weight:bold;" type="text" onclick="GetLoad('.$id.')" value="获取应用" class="sub"/>
				&nbsp; <b>'.$row['themeas'].'<font color="red">(免费)</font></b><br/>
				<p>&nbsp; </p>
				</div>
				<table class="mation-right-table">
					<tr>
						<td width="70">主题ID:</td>
						<td>'.$row['themename'].'</td>
						<td width="70">系统要求:</td>
						<td>'.$row['systems'].'</td>
					</tr>
					<tr>
						<td width="70">版 本:</td>
						<td>'.$row['version'].'</td>
						<td width="70">更新日期:</td>
						<td>'.$row['updatedate'].'</td>
					</tr>
					<tr>
						<td width="70">作 者:</td>
						<td>'.$row['author'].'</td>
						<td width="70">下载次数:</td>
						<td>'.$row['times'].'</td>
					</tr>
					<tr>
						<td width="70">评分情况：</td>
						<td colspan="3">
							<div id="star2">
								<dl>
									<dd></dd>
									<dd></dd>
									<dd></dd>
									<dd></dd>
									<dd></dd>
								</dl>	
							</div>					
						</td>
					</tr>
					<tr>
						<td width="70">PHP版本:</td>
						<td colspan="3">'.$row['phpversion'].'及更高 (当前系统为PHP '.PHP_VERSION.',支持该应用.)</td>
					</tr>
				</table>				
			</div>
			<div class="mation-infos-in clear">
				<ul class="clear">
					<li class="mation-infos-action">详细介绍</li>
					<li>网友评论</li>
					<li>作者简介</li>';		
		$subject .= '</ul>
			</div>
			<div class="mation-infos-in-list" >'.$row['details'].'</div>
			<div class="mation-infos-in-list" style="display:none;">
			<div style="margin-top:20px;line-height:23px;">欢迎发表评论并打分</div>
			<div id="star">
				<ul title="点击评分">
					<li class="activestar"></li>
					<li class="activestar"></li>
					<li class="activestar"></li>
					<li class="activestar"></li>
					<li class="activestar"></li>
				</ul>
				<div id="dv2"></div>
			</div>	
			
<div style="margin-top:10px;">
	<p class="posttop"><a name="comment"></a></p>
	<p>'.$nickname.'</p>
	<form id="frmSumbit" method="post" action="'.SERVICE_LINK.'system/admin/handling_events.php" >
	<input type="hidden" name="titleid" id="inpId" value="'.$id.'" />';
if($nickname == false)
{	
$subject .= '<p style="margin-top:10px;"><input type="text" name="name" id="inpRevID" value="" /> 名称(*)</p>	';
$subject .= '<p style="margin-top:10px;"><input type="text" name="email" id="inpEmail" value="" /> 邮箱(*)</p>';
}else{
$subject .= '<input type="hidden" name="name" id="inpRevID" value="'.$nickname.'" />';
$subject .= '<input type="hidden" name="email" id="inpEmail" value="" />';
}
$subject .= '<input type="hidden" name="tal" id="inpName" value="" />
	<input type="hidden" name="qq" id="inpEmail" value="" />
	<a rel="nofollow" id="cancel-reply" href="javascript:;" style="display:none;">
	<small>取消回复</small></a>
	</p>
	<p style="margin-top:10px;"><textarea name="body" id="txaArticle" class="text" cols="50" rows="4" tabindex="5" ></textarea></p>
	<p>
	<input type="hidden" name="pid" value="0" />
	<input type="hidden" name="flag" value="1" />
	<input type="hidden" name="scoring" value="5" id="scoring"/>
	<input type="hidden" name="dianzanUrl" value="'.apth_url('system/external_request.php').'" />
	<input type="hidden" name="act" value="review" />
	<input type="submit" tabindex="6" value="提交" class="button" />';
if( $setcomment['vifiy'] == "OFF" )
{
$subject .= '验证码　<input type="text" name="virify" size="2" id="inpHomePage" value="" /> <img src="'.SERVICE_LINK.'/system/virify.php" alt="验证码" title="点击换下一张" align="absmiddle" onclick="vitifyfunc(this);"/>';
}
$subject .= '</p>
	</form>
	<p class="postbottom"></p>
	</div>	
			
		<div class="content-CommentPost">';
if(!empty($all))
{	
$subject .= '<div class="Comment-line"><a>热门评论:</a></div>';
	foreach($all as $k=>$v){
		if($v['pid'] != 0)
		{
$subject .= '<div class="Commentqu">
			<div class="Commentqu-ins Commentqu-ins-left" ><img src="'.$v['pic'].'" width="48" height="48"/></div>
			<div class="Commentqu-ins Commentqu-ins-right">
			<div class="CommentotherName">
			<div class="Comment-name-list1">'.$v['name'].'</div>
			<div class="Comment-name-list2">
				<dl>
					<dd id="Comment-dianzan"><span class="Comment-dianzan'.$v['id'].'" onclick="dianzan('.$v['id'].')" style="cursor:pointer;">点赞</span> <span class="Comment-dianzan-num'.$v['id'].'">'.$v['likes'].'</span></dd>
					<dd>
					<a href="#comment" class="comment-huifu-this">回复</a>
					<input type="hidden" class="comment-hidden-id" value="'.$v['id'].'"/>
					</dd>
					<dd><a href="javascript:;" onclick="report('.$v['id'].')">举报</a></dd>
				</dl>
			</div>
			<div style="clear:both;"></div>
			</div>
			<div class="CommentotherTime">'.$v['publitime'].'</div>
			<div class="Commentotherbody">
			<p><span class="Comment-huifu-ins">回复</span> <span class="Comment-huifu-ins">'.$v['chill']['name'].'：</span>'.$v['body'].'</p>
			<div class="Comment-name-huifu">
			<p><span class="Comment-huifu-ins">'.$v['chill']['name'].'：</span>'.$v['chill']['body'].'</p>
			</div>
			</div>
			</div>
			<div style="clear:both;"></div>
		</div>';

		}else{
		
$subject .= '<div class="Commentqu">
			<div class="Commentqu-ins Commentqu-ins-left" ><img src="'.$v['pic'].'" width="48" height="48"/></div>
			<div class="Commentqu-ins Commentqu-ins-right">
			<div class="CommentotherName">
			<div class="Comment-name-list1">'.$v['name'].'</div>
			<div class="Comment-name-list2">
				<dl>
					<dd id="Comment-dianzan"><span class="Comment-dianzan'.$v['id'].'" onclick="dianzan('.$v['id'].')" style="cursor:pointer;">点赞</span> <span class="Comment-dianzan-num'.$v['id'].'">'.$v['likes'].'</span></dd>
					<dd>
					<a href="#comment" class="comment-huifu-this">回复</a>
					<input type="hidden" class="comment-hidden-id" value="'.$v['id'].'"/>
					</dd>
					<dd><a href="javascript:;" onclick="report('.$v['id'].')">举报</a></dd>
				</dl>
			</div>
			<div style="clear:both;"></div>
			</div>
			<div class="CommentotherTime">'.$v['publitime'].'</div>
			<div class="Commentotherbody">
			<p>'.$v['body'].'</p>
			</div>
			</div>
			<div style="clear:both;"></div>
		</div>';
		}
	}
	$subject .= '<div style="text-align:center;display:none;" id="pulun-loading1"><img src="'.apth_url('subject/plugin/comment/images/loading-0.gif').'" alt="加载中..."/></div>';
	$subject .= '<div style="text-align:center;display:none;" id="pulun-loading2">加载完毕</div>';
}		
$subject .= '</div>		
	</div>
			<div class="mation-infos-in-list" style="display:none;">'.$row['about'].'</div>
		</div>
	</div>';
	$subject .= '
<script src="'.apth_url('subject/plugin/comment/js/resetform.js').'" type="text/javascript"></script>
	<script>
	function GetLoad(id)
	{
		location.href="handling_events.php?act=GetLoad&id="+id;
	}
	function vitifyfunc(srcobj)
	{
		srcobj.src = srcobj.src+"?rand="+Math.random();
	}
	$(function(){
		var avg = '.ceil($avg['v']).';
		for(var i=0;i<avg;i++)
		{
			$("#star2 dd:eq("+i+")").addClass("activestar");
		}
		$(".mation-right-table tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".mation-right-table tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".mation-right-table tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	$(function(){
		$(".mation-infos-in li").each(function(index){
			$(this).click(function(){
				$(this).addClass("mation-infos-action").siblings().removeClass("mation-infos-action");
				$(".mation-infos-in-list").hide();
				$(".mation-infos-in-list:eq("+index+")").show();
			});
		});
	});
window.onload = function(){
	var oDiv = document.getElementById("star");
	var aLi = oDiv.getElementsByTagName("li");
	var oDiv2 = document.getElementById("dv2");
	var scoring = document.getElementById("scoring");
	var aText = ["很差","差","一般","好","很好"];
	for(var i=0; i<aLi.length; i++){
		aLi[i].index = i;
		aLi[i].onmouseover = function(){
			oDiv2.innerHTML = aText[this.index];
			for(var i=0; i<aLi.length; i++){
				aLi[i].className = "";
			}
			for(var i=0; i<=this.index; i++){
				aLi[i].className = "activestar";
			}
		}
		aLi[i].onmouseout = function(){
			for(var i=0; i<this.index; i++){
				aLi[i].className = "activestar";
			}
			scoring.value = this.index+1;
		}
		aLi[i].onclick = function(){
				scoring.value = this.index+1;
		}
	}
}

	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#标签编辑
function TagEdt()
{	
	#当前模板
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$subject = '<div class="useredit">';	
	$subject .= '<div class="userheader" style="border:none;">标签编辑</div>
	<form action="handling_events.php" method="post" id="viri">
	<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="keywords" value="" class="input-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="tagas" value="" class="input-s"/></div>		
		<div class="userjibie">模板:</div>
		<div>
			<select name="templateid" class="input-x">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>		
		<div class="userjibie">摘要:</div>
		<div><textarea name="description" class="input-w"></textarea></div>
		<div class="userjibie clear">
		<span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid menuadd" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
		<input type="hidden" name="addmenu" value="ON" class="pagemenuadd"/>
		</div>		
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="act" value="TagEdt"/>
		<input type="submit" value="提交" class="sub"/>
		</div>
	</div></form>
	</div>';
	$subject .= '<script>
	$(function(){
		$(".menuadd").click(function(){
		if( $("[name=addmenu]").val() == "ON" || $("[name=addmenu]").val() == "" )
		{
			$(".pagemenuadd").val("OFF");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".pagemenuadd").val("ON");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}
		});
		$("#viri").submit(function(){
			if( $("[name=keywords]").val() == "" )
			{
				alert("标签名称未命名");
				return false;
			}
		});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#标签编辑
function TagEdt_phone()
{	
	#当前模板
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$subject = '<div class="useredit_phone">';	
	$subject .= '<div class="userheader2_phone"><i class="f7-icons size-22">tags_fill</i> 标签编辑</div>
	<form action="handling_events.php" method="post" id="viri">
	<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="keywords" value="" class="inputs-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="tagas" value="" class="inputs-s"/></div>		
		<div class="userjibie">模板:</div>
		<div>
			<select name="templateid" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>		
		<div class="userjibie">摘要:</div>
		<div><textarea name="description" class="input-w"></textarea></div>
		<div class="userjibie clear">
		<span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid menuadd" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
		<input type="hidden" name="addmenu" value="ON" class="pagemenuadd"/>
		</div>		
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="act" value="TagEdt"/>
		<input type="submit" value="提交" class="subClass"/>
		</div>
	</div></form>
	</div>';
	$subject .= '<script>
	$(function(){
		$(".menuadd").click(function(){
		if( $("[name=addmenu]").val() == "ON" || $("[name=addmenu]").val() == "" )
		{
			$(".pagemenuadd").val("OFF");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".pagemenuadd").val("ON");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}
		});
		$("#viri").submit(function(){
			if( $("[name=keywords]").val() == "" )
			{
				alert("标签名称未命名");
				return false;
			}
		});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#修改TAG
function TagEdtUp()
{	
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));	
	$row = db()->select('*')->from(PRE.'tag')->where(array('id'=>$id))->get()->array_row();
	#当前模板
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$subject = '<div class="useredit">';	
	$subject .= '<div class="userheader" style="border:none;">标签编辑</div>
	<form action="handling_events.php" method="post" id="viri">
	<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="keywords" value="'.$row['keywords'].'" class="input-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="tagas" value="'.$row['tagas'].'" class="input-s"/></div>		
		<div class="userjibie">模板:</div>
		<div>
			<select name="templateid" class="input-x">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>		
		<div class="userjibie">摘要:</div>
		<div><textarea name="description" class="input-w">'.$row['description'].'</textarea></div>
		<div class="userjibie clear">
		<span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid menuadd" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
		<input type="hidden" name="addmenu" value="'.($row['addmenu']==''?'ON':$row['addmenu']).'" class="pagemenuadd"/>
		</div>		
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="id" value="'.$id.'"/>
		<input type="hidden" name="act" value="TagEdtUp"/>
		<input type="submit" value="提交" class="sub"/>
		</div>
	</div></form>
	</div>';
	$subject .= '<script>
	$(function(){
		if( $("[name=addmenu]").val() == "OFF")
		{
			$(".menuadd").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".menuadd").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}
		$(".menuadd").click(function(){
		if( $("[name=addmenu]").val() == "ON" || $("[name=addmenu]").val() == "" )
		{
			$(".pagemenuadd").val("OFF");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".pagemenuadd").val("ON");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}
		});
		var clFlag = false;
		$("#viri").submit(function(){
			if( $("[name=keywords]").val() == "" )
			{
				alert("标签名称未命名");
				return false;
			}
		});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#修改TAG
function TagEdtUp_phone()
{	
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));	
	$row = db()->select('*')->from(PRE.'tag')->where(array('id'=>$id))->get()->array_row();
	#当前模板
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$subject = '<div class="useredit_phone">';	
	$subject .= '<div class="userheader2_phone"><i class="f7-icons size-22">tags_fill</i> 标签编辑</div>
	<form action="handling_events.php" method="post" id="viri">
	<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="keywords" value="'.$row['keywords'].'" class="inputs-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="tagas" value="'.$row['tagas'].'" class="inputs-s"/></div>		
		<div class="userjibie">模板:</div>
		<div>
			<select name="templateid" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>		
		<div class="userjibie">摘要:</div>
		<div><textarea name="description" class="input-w">'.$row['description'].'</textarea></div>
		<div class="userjibie clear">
		<span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid menuadd" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
		<input type="hidden" name="addmenu" value="'.($row['addmenu']==''?'ON':$row['addmenu']).'" class="pagemenuadd"/>
		</div>		
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="id" value="'.$id.'"/>
		<input type="hidden" name="act" value="TagEdtUp"/>
		<input type="submit" value="提交" class="subClass"/>
		</div>
	</div></form>
	</div>';
	$subject .= '<script>
	$(function(){
		if( $("[name=addmenu]").val() == "OFF")
		{
			$(".menuadd").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".menuadd").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}
		$(".menuadd").click(function(){
		if( $("[name=addmenu]").val() == "ON" || $("[name=addmenu]").val() == "" )
		{
			$(".pagemenuadd").val("OFF");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		}
		else
		{
			$(".pagemenuadd").val("ON");
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		}
		});
		var clFlag = false;
		$("#viri").submit(function(){
			if( $("[name=keywords]").val() == "" )
			{
				alert("标签名称未命名");
				return false;
			}
		});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#页面管理,新建主题
function Theme_edit()
{
	$subject = '<div class="useredit">';
	$subject .= '<div class="userheader" style="border:none;">页面管理－新建主题</div>
	<form action="handling_events.php" method="post" id="frm" enctype="multipart/form-data">
	<table class="tableBox" style="margin-top:8px;">	
		<tr>
			<td width="343"></td>
			<td></td>
		</tr>
		<tr>
			<td><b>· 主题ID</b><br/>主题ID为主题的目录名,且不能重复.ID名只能用字母数字和下划线的组合.</td>
			<td><input type="text" name="themename" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 主题名称</b></td>
			<td><input type="text" name="themeas" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 内置插件页面管理</b><br/>内置插件页面管理统一命名,main.php</td>
			<td><input type="text" name="autoplug1" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 内置插件嵌入页面</b><br/>内置插件嵌入页面统一命名,include.php</td>
			<td><input type="text" name="autoplug2" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 作者</b></td>
			<td><input type="text" name="author" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 作者主页</b></td>
			<td><input type="text" name="homepage" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 主题简介</b></td>
			<td><input type="text" name="description" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 主题图片</b><br/>只允许（jpeg、jpg、gif、png）类型图片上传，大小范围不超出2MB.</td>
			<td><input type="file" name="file" size="60" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 主题定价</b></td>
			<td><input type="text" name="price" value="0" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td colspan="2">
			<input type="hidden" name="act" value="Theme_edit" >
			<input type="submit" value="提交" class="sub">
			</td>
		</tr>
	</table>
	</form>
	</div>';
	$subject .= '<script>
	$(function(){
		var frmSubMit = false;
		$("#frm").submit(function(){
			if( $("[name=themename]").val() == "" )
			{
				alert("主题ID未命名");
				$("[name=themename]").focus();
				return false;
			}
			if(frmSubMit)
			{
				alert("主题ID已经存在");
				$("[name=themename]").focus();
				return false;
			}
			if( $("[name=themeas]").val() == "" )
			{
				alert("主题名称未命名");
				$("[name=themeas]").focus();
				return false;
			}
			if( $("[name=author]").val() == "" )
			{
				alert("作者不能留空");
				$("[name=author]").focus();
				return false;
			}
			if( $("[name=description]").val() == "" )
			{
				alert("主题简介不能留空");
				$("[name=description]").focus();
				return false;
			}
			if( $("[name=file]").val() == "" )
			{
				alert("主题图片不能留空");
				$("[name=file]").focus();
				return false;
			}
		});
		$("[name=themename]").blur(function(){
			$.post("../external_request.php",{act:"Theme_edit",val:$(this).val()},function(data){
				if(data == "ON")
				{		
					alert("主题ID已经存在");		
					frmSubMit = true;
				}
				else
				{
					frmSubMit = false;
				}
			});
		});
	});
	$(function(){
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#补充插件信息
function Plugins_add()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	#查询插件
	$row = db()->select('id,themename')->from(PRE.'theme')->where(array('id'=>$id))->get()->array_row();
	
	$subject = '<div class="useredit">';
	$subject .= '<div class="userheader" style="border:none;">页面管理－补充插件信息</div>
	<form action="handling_events.php" method="post" id="frm" enctype="multipart/form-data">
	<table class="tableBox" style="margin-top:8px;">	
		<tr>
			<td width="343"></td>
			<td></td>
		</tr>
		<tr>
			<td><b>· 插件ID</b><br/>插件ID为插件的目录名,且不能重复.ID名只能用字母数字和下划线的组合.</td>
			<td><input type="text" name="themename" value="'.$row['themename'].'" readonly="readonly" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 插件名称</b></td>
			<td><input type="text" name="themeas" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 内置插件页面管理</b><br/>内置插件页面管理统一命名,main.php</td>
			<td><input type="text" name="autoplug1" value="main.php" readonly="readonly" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 内置插件嵌入页面</b><br/>内置插件嵌入页面统一命名,include.php</td>
			<td><input type="text" name="autoplug2" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 作者</b></td>
			<td><input type="text" name="author" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 作者主页</b></td>
			<td><input type="text" name="homepage" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 插件简介</b></td>
			<td><input type="text" name="description" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 插件图片</b><br/>只允许（jpeg、jpg、gif、png）类型图片上传，大小范围不超出2MB.</td>
			<td><input type="file" name="file" size="60" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 插件定价</b></td>
			<td><input type="text" name="price" value="0" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td colspan="2">
			<input type="hidden" name="id" value="'.$row['id'].'" >
			<input type="hidden" name="act" value="Plugins_add" >
			<input type="submit" value="提交" class="sub">
			</td>
		</tr>
	</table>
	</form>
	</div>';
	$subject .= '<script>
	$(function(){
		$("#frm").submit(function(){		
			if( $("[name=themeas]").val() == "" )
			{
				alert("插件名称未命名");
				$("[name=themeas]").focus();
				return false;
			}
			if( $("[name=author]").val() == "" )
			{
				alert("作者不能留空");
				$("[name=author]").focus();
				return false;
			}
			if( $("[name=description]").val() == "" )
			{
				alert("插件简介不能留空");
				$("[name=description]").focus();
				return false;
			}
			if( $("[name=file]").val() == "" )
			{
				alert("插件图片不能留空");
				$("[name=file]").focus();
				return false;
			}
		});
	});
	$(function(){
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#补充主题信息
function Theme_add()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	$row = db()->select('themename')->from(PRE.'theme')->where(array('id'=>$id))->get()->array_row();
	
	$subject = '<div class="useredit">';
	$subject .= '<div class="userheader" style="border:none;">页面管理－补充信息</div>
	<form action="handling_events.php" method="post" id="frm" enctype="multipart/form-data">
	<table class="tableBox" style="margin-top:8px;">	
		<tr>
			<td width="343"></td>
			<td></td>
		</tr>
		<tr>
			<td><b>· 主题ID</b><br/>主题ID为主题的目录名,且不能重复.ID名只能用字母数字和下划线的组合.</td>
			<td><input type="text" name="themename" value="'.$row['themename'].'" readonly="readonly" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 主题名称</b></td>
			<td><input type="text" name="themeas" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 内置插件页面管理</b><br/>内置插件页面管理统一命名,main.php</td>
			<td><input type="text" name="autoplug1" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 内置插件嵌入页面</b><br/>内置插件嵌入页面理统一命名,include.php</td>
			<td><input type="text" name="autoplug2" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 作者</b></td>
			<td><input type="text" name="author" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 作者主页</b></td>
			<td><input type="text" name="homepage" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 主题简介</b></td>
			<td><input type="text" name="description" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 主题图片</b><br/>只允许（jpeg、jpg、gif、png）类型图片上传，大小范围不超出2MB.</td>
			<td><input type="file" name="file" size="60" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 主题定价</b></td>
			<td><input type="text" name="price" value="0" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td colspan="2">
			<input type="hidden" name="id" value="'.$id.'" >
			<input type="hidden" name="act" value="Theme_add" >
			<input type="submit" value="提交" class="sub">
			</td>
		</tr>
	</table>
	</form>
	</div>';
	$subject .= '<script>
	$(function(){
		$("#frm").submit(function(){			
			if( $("[name=themeas]").val() == "" )
			{
				alert("主题名称未命名");
				$("[name=themeas]").focus();
				return false;
			}
			if( $("[name=author]").val() == "" )
			{
				alert("作者不能留空");
				$("[name=author]").focus();
				return false;
			}
			if( $("[name=description]").val() == "" )
			{
				alert("主题简介不能留空");
				$("[name=description]").focus();
				return false;
			}
			if( $("[name=file]").val() == "" )
			{
				alert("主题图片不能留空");
				$("[name=file]").focus();
				return false;
			}
		});		
	});
	$(function(){
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#新建插件
function PlugIn_edit()
{
	$subject = '<div class="useredit">';
	$subject .= '<div class="userheader" style="border:none;">页面管理－新建插件</div>
	<form action="handling_events.php" method="post" id="frm" enctype="multipart/form-data">
	<table class="tableBox" style="margin-top:8px;">	
		<tr>
			<td width="343"></td>
			<td></td>
		</tr>
		<tr>
			<td><b>· 插件ID</b><br/>插件ID为插件的目录名,且不能重复.ID名只能用字母数字和下划线的组合.</td>
			<td><input type="text" name="themename" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 插件名称</b></td>
			<td><input type="text" name="themeas" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 内置插件页面管理</b><br/>内置插件页面管理统一命名,main.php</td>
			<td><input type="text" name="autoplug1" value="main.php" readonly="readonly" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 内置插件嵌入页面</b><br/>内置插件嵌入页面统一命名,include.php</td>
			<td><input type="text" name="autoplug2" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 作者</b></td>
			<td><input type="text" name="author" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 作者主页</b></td>
			<td><input type="text" name="homepage" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td><b>· 插件简介</b></td>
			<td><input type="text" name="description" class="inputs-s" style="width:598px;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 插件图片</b><br/>只允许（jpeg、jpg、gif、png）类型图片上传，大小范围不超出2MB.</td>
			<td><input type="file" name="file" size="60" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"> <font color="red">*</font></td>
		</tr>
		<tr>
			<td><b>· 插件定价</b></td>
			<td><input type="text" name="price" value="0" class="inputs-s" style="width:598px;"></td>
		</tr>
		<tr>
			<td colspan="2">
			<input type="hidden" name="act" value="PlugIn_edit" >
			<input type="submit" value="提交" class="sub">
			</td>
		</tr>
	</table>
	</form>
	</div>';
	$subject .= '<script>
	$(function(){
		var frmSubMit = false;
		$("#frm").submit(function(){
			if( $("[name=themename]").val() == "" )
			{
				alert("插件ID未命名");
				$("[name=themename]").focus();
				return false;
			}
			if(frmSubMit)
			{
				alert("插件ID已经存在");
				$("[name=themename]").focus();
				return false;
			}
			if( $("[name=themeas]").val() == "" )
			{
				alert("插件名称未命名");
				$("[name=themeas]").focus();
				return false;
			}
			if( $("[name=author]").val() == "" )
			{
				alert("作者不能留空");
				$("[name=author]").focus();
				return false;
			}
			if( $("[name=description]").val() == "" )
			{
				alert("插件简介不能留空");
				$("[name=description]").focus();
				return false;
			}
			if( $("[name=file]").val() == "" )
			{
				alert("插件图片不能留空");
				$("[name=file]").focus();
				return false;
			}
		});
		$("[name=themename]").blur(function(){
			$.post("../external_request.php",{act:"Theme_edit",val:$(this).val()},function(data){
				if(data == "ON")
				{		
					alert("插件ID已经存在");		
					frmSubMit = true;
				}
				else
				{
					frmSubMit = false;
				}
			});
		});
	});
	$(function(){
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#页面管理
function PageMng()
{
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>'0'))->get()->array_row();
	if( !empty($theme) )
	{
	#查询内容
	$rowstotal = db()->select('a.id,a.pid,a.name,a.module,a.addmenu,a.artrows,a.state,b.userName,c.themeas')->from(PRE.'template as a,'.PRE.'login as b,'.PRE.'theme as c')->where('a.userid=b.id and a.templateid=c.id and a.templateid='.$theme['id'])->get()->array_nums();
	$showtotal = $num;
	$pageTotal = ceil($rowstotal/$showtotal);
	$page = $_GET['page']==''?1:$_GET['page'];
	if($page>=$pageTotal){$page=$pageTotal;}
	if($page<=1||!is_numeric($page)){$page=1;}
	$offset = ($page-1)*$showtotal;
	$offset = $offset.','.$showtotal;

	$rows = db()->select('a.id,a.pid,a.name,a.module,a.addmenu,a.artrows,a.cover,a.state,b.userName,c.themeas,(select count(columnid) from '.PRE.'article where columnid=a.id) as newTotal')->from(PRE.'template as a,'.PRE.'login as b,'.PRE.'theme as c')->where('a.userid=b.id and a.templateid=c.id and a.templateid='.$theme['id'])->order_by('id desc')->limit($offset)->get()->array_rows();
	}
	#全局配置
	$review = db()->select('development')->from(PRE.'review_up')->get()->array_row();
	
	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader" style="border:none;">页面管理</div>
	<ul class="menuWeb clear">
	<li class="menuWebAction" style="margin-right:5px;"><a href="index.php?act=PageEdt">新建栏目</a></li>';
	if($review['development']=='OFF')
	{
	$subject .= '<li class="menuWebAction" style="margin-right:5px;"><a href="index.php?act=Theme_edit">新建主题</a></li>
	<li class="menuWebAction" style="margin-right:5px;"><a href="index.php?act=PlugIn_edit">新建插件</a></li>';	
	}
	$subject .= '</ul>
	<table class="tableBox" style="margin-top:8px;">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">PID</th>
				<th style="text-align:center;">栏目名称</th>
				<th style="text-align:center;">主题名称</th>
				<th style="text-align:center;">开发者</th>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;名称</th>
				<th style="text-align:center;">文章数</th>
				<th style="text-align:center;">加入导航</th>
				<th style="text-align:center;">状态</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if( !empty($rows) )
{	
	foreach($rows as $k=>$v)
	{		
	$subject .= '<tr style="text-align:center;">
				<td>'.$v['id'].'</td>
				<td>'.$v['pid'].'</td>
				<td>'.$v['name'].' &nbsp; ';
	if($v['cover'] !='' )
	{			
	$subject .= '<a href="index.php?act=PageEdtUp&id='.$v['id'].'&page='.$page.'" title="修改" class="hoverstyle">
				<img src="'.apth_url('system/admin/images/d05.png').'" align="absmiddle" width="16" height="16" title="修改栏目封面图片">
				</a>';
	}			
	$subject .= '</td>
				<td>'.$v['themeas'].'</td>
				<td>'.$v['userName'].'</td>
				<td><a href="'.apth_url('index.php?act=article_list&id='.$v['id']).'" target="_blank" title="新页面"><img src="'.site_url('images/link.png').'" align="absmiddle" /></a>&nbsp;'.$v['module'].'</td>
				<td>'.$v['newTotal'].'</td>
				<td>'.($v['addmenu']=='OFF'?'ON':'OFF').'</td>
				<td>'.($v['state']==0?'动态':'静态').'</td>
				<td style="text-align:center;">
					<a href="index.php?act=PageEdtUp&id='.$v['id'].'&page='.$page.'" title="修改" class="hoverstyle"><img src="'.site_url('images/page_edit.png').'" align="absmiddle"/></a>
					 &nbsp; &nbsp; 
					<a href="javascript:;" onclick="conf('.$v['id'].','.$page.')" title="清除" class="hoverstyle"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
			</tr>';	
	}		
}			
	$subject .= '<tr>
				<td colspan="10">
				<span style="font-size:15px;color:#666666;">总数:'.$rowstotal.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$pageTotal.'页</span>
				&nbsp; ';
if($rowstotal>$showtotal)
{				
	$subject .= '<a href="index.php?act=PageMng&page='.($page-1).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; 
				<a href="index.php?act=PageMng&page='.($page+1).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="GO" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" id="GO" value="GO" class="sub"/>
				</span>';
}				
	$subject .= '</td>
			</tr>
		</table></div>';
	$subject .= '<script>
	function conf(id,page)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=PageEdtDeletet&id="+id+"&page="+page;
		}
	}
	$(function(){
		$("#GO").click(function(){
			location.href="index.php?act=PageMng&page="+$("[name=GO]").val();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#页面管理
function PageMng_phone()
{
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>'0'))->get()->array_row();
	if( !empty($theme) )
	{
	#查询内容
	$rowstotal = db()->select('a.id,a.pid,a.name,a.module,a.addmenu,a.artrows,a.state,b.userName,c.themeas')->from(PRE.'template as a,'.PRE.'login as b,'.PRE.'theme as c')->where('a.userid=b.id and a.templateid=c.id and a.templateid='.$theme['id'])->get()->array_nums();
	$showtotal = $num;
	$pageTotal = ceil($rowstotal/$showtotal);
	$page = $_GET['page']==''?1:$_GET['page'];
	if($page>=$pageTotal){$page=$pageTotal;}
	if($page<=1||!is_numeric($page)){$page=1;}
	$offset = ($page-1)*$showtotal;
	$offset = $offset.','.$showtotal;

	$rows = db()->select('a.id,a.pid,a.name,a.module,a.addmenu,a.artrows,a.cover,a.state,b.userName,c.themeas,(select count(columnid) from '.PRE.'article where columnid=a.id) as newTotal')->from(PRE.'template as a,'.PRE.'login as b,'.PRE.'theme as c')->where('a.userid=b.id and a.templateid=c.id and a.templateid='.$theme['id'])->order_by('id desc')->limit($offset)->get()->array_rows();
	}
	$subject = '<div class="useredit_phone">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">calendar_fill</i> 栏目管理</div>
	<table class="tableBox" style="margin-top:8px;">
			<tr>
				<th style="text-align:center;">栏目名称</th>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;文件名称</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if( !empty($rows) )
{	
	foreach($rows as $k=>$v)
	{		
	$subject .= '<tr>
				<td style="text-align:center;">'.$v['name'].' &nbsp; ';
	if($v['cover'] !='' )
	{			
	$subject .= '<a href="index.php?act=PageEdtUp&id='.$v['id'].'&page='.$page.'" title="修改" class="hoverstyle">
				<img src="'.site_url('images/d05.png').'" align="absmiddle" width="16" height="16" title="修改栏目封面图片">
				</a>';
	}			
	$subject .= '</td>
				<td><a href="'.apth_url('index.php?act=article_list&id='.$v['id']).'" target="_blank" title="新页面"><img src="'.site_url('images/link.png').'" align="absmiddle" /></a>&nbsp;'.$v['module'].'</td>
				<td>
				<p class="art_floats">
					<a href="index.php?act=PageEdtUp_phone&id='.$v['id'].'&page='.$page.'" title="修改" class="hoverstyle"><img src="'.site_url('images/page_edit.png').'" align="absmiddle"/></a>
				</p>
				<p class="art_floats">
					<a href="javascript:;" onclick="conf('.$v['id'].','.$page.')" title="清除" class="hoverstyle"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</p>
				</td>
			</tr>';	
	}		
}			
	$subject .= '<tr>
				<p>
				<td colspan="3">
				<span style="font-size:15px;color:#666666;">总数:'.$rowstotal.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$pageTotal.'页</span>
				</p>';
if($rowstotal>$showtotal)
{				
	$subject .= '<a class="a_href" href="index.php?act=PageMng_phone&page='.($page-1).'">上一页</a> &nbsp; 
				<a class="a_href" href="index.php?act=PageMng_phone&page='.($page+1).'">下一页</a>
				&nbsp; 
				<span>
				<input type="text" name="GO" value="" class="renyiCl" style="width:50px;height:23px;"/>页 &nbsp; 
				<input type="submit" id="GO" value="GO"/>
				</span>';
}				
	$subject .= '</td>
			</tr>
		</table></div>';
	$subject .= '<script>
	function conf(id,page)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=PageEdtDeletet&id="+id+"&page="+page;
		}
	}
	$(function(){
		$("#GO").click(function(){
			location.href="index.php?act=PageMng_phone&page="+$("[name=GO]").val();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#修改,页面编辑
function PageEdtUp()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	#作者
	$rows = db()->select('*')->from(PRE.'login')->order_by('id desc')->get()->array_rows();
	#查询
	$row = db()->select('*')->from(PRE.'template')->where(array('id'=>$id))->get()->array_row();
	#查询父类栏目
	$datas = GetFenLai('0');
	#查询主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$subject = '<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit">
		<div class="userheader">页面编辑，修改</div>
		<div class="userjibie ujt">栏目名称</div>
		<div><input type="text" name="name" value="'.$row['name'].'" class="inputs-s" style="width:598px;"/>
		<span>自定义栏目名称，默认导行菜单栏显示</span>
		</div>
		<div class="userjibie ujt">页面名称</div>
		<div>
		<input type="text" name="module" value="'.$row['module'].'" readonly="readonly" class="inputs-s" style="width:598px;"/>
		<span>自定义名称，不需要添加后缀名，默认为.html文件</span>
		</div>
		<div class="userjibie ujt">排序</div>
		<div><input type="text" name="sort" value="'.$row['sort'].'" class="inputs-s" style="width:249px;"/>
		<span>自定义数字，排序从大到小</span>
		</div>
		<div class="userjibie ujt">添加子类栏目</div>
		<div>
			<select name="pid" class="selens" style="width:249px;">
				<option value="-1" selected="selected">不添加子类栏目</option>';
if(!empty($datas))
{	
	foreach($datas as $k=>$v)
	{			
		$subject .= '<option value="'.$v['id'].'" '.($v['id']==$row['pid']?'selected="selected"':'').'>'.$v['name'].'</option>';
	}
}				
	$subject .= '</select>
		</div>
		<div class="userjibie ujt">栏目封面图片</div>
		<div>
		<input type="file" name="cover" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"/>
		<span>上传最大2M，类型要求(jpeg，jpg，gif，png)</span>
		<div style="border:1px solid #999999;margin-top:2px;width:150px;">';
	if(strrpos($row['cover'], 'a-ettra01.jpg')||$row['cover']=='')
	{	
		$subject .= '<span>默认封面图片</span>
		<img src="'.apth_url('system/admin/pic/defualt/a-ettra01.jpg').'" width="150"/>
		<input type="hidden" name="srcpath" value="system/admin/pic/defualt/a-ettra01.jpg"/>';
	}
	else 
	{
		$subject .= '<span>封面图片</span>
		<img src="'.$row['cover'].'" width="150"/>
		<input type="hidden" name="srcpath" value="'.$row['cover'].'"/>';
	}	
	$subject .= '</div>
		</div>
		<div class="userjibie ujt">关键字</div>
		<div><input type="text" name="keywords" value="'.$row['keywords'].'" class="inputs-s" style="width:598px;"/>
		<span>多个关键字“，”号隔开</span>
		</div>
		<div class="userjibie ujt">摘要</div>
		<div><textarea name="description" class="input-w">'.$row['description'].'</textarea></div>
		
		</div>
		<ul class="buttom-girg">
			<li class="subClass1"></li>
			<li>
			<input type="hidden" name="id" value="'.$row['id'].'"/>
			<input type="hidden" name="page" value="'.$_GET['page'].'"/>
			<input type="hidden" name="act" value="PageEdtUp"/>
			<input type="submit" value="提交" class="subClass"/>
			</li>		
			<li class="clfl">状态&nbsp;</li>
			<li>
			<select name="state" class="selens">
				<option value="0" '.($row['state']==0?'selected="selected"':'').'>动态页面</option>
				<option value="1" '.($row['state']==1?'selected="selected"':'').'>静态页面</option>
			</select>
			</li>
			<li class="clfl">主题&nbsp;</li>
			<li>
			<select name="templateid" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
			</li>
			<li class="clfl">作者&nbsp;</li>
			<li>
			<select name="userid" class="selens">';
if(!empty($rows))
{	
	foreach($rows as $k=>$v)
	{
		$subject .= '<option value="'.$v['id'].'" '.($row['userid']==$v['id']?'selected="selected"':'').'>'.$v['userName'].'</option>';
	}
}		
		$subject .= '</select>
			</li>
			<li class="clfl"><input type="hidden" id="offon2" name="addmenu" value="'.$row['addmenu'].'"/></li>
			<li class="clens"><span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid offno2" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 0;"></span></li>			
			<li class="clfl"><input type="hidden" id="offon3" name="forbidden" value="'.$row['forbidden'].'"/></li>
			<li class="clens"><span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">禁止发布编辑</span><span class="clzhid offno50" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 0;"></span></li>
		</ul>
		</form>';
	$subject .= '
    <script type="text/javascript">    
$(function(){
	$("#frm").submit(function(){
		if( $("[name=name]").val() == "" )
		{
			alert("栏目名称未命名");
			$("[name=name]").focus();
			return false;
		}
		if( $("[name=module]").val() == "" )
		{
			alert("页面名称未命名");
			$("[name=module]").focus();
			return false;
		}
	});
	if( $("[name=addmenu]").val() == "OFF" )
	{
		$(".offno2").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
	else
	{
		$(".offno2").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
	if( $("[name=forbidden]").val() == "ON" )
	{
		$(".offno50").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
	else
	{
		$(".offno50").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
		$(".offno2").click(function(){
		if($("[name=addmenu]").val()=="ON")
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$("#offon2").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$("#offon2").val("ON");//关闭
		}
		});
		$(".offno50").click(function(){
		if($("[name=forbidden]").val()=="ON")
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$("#offon3").val("OFF");//关闭
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$("#offon3").val("ON");//打开
		}
		});
});
    </script>';	
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#修改,页面编辑
function PageEdtUp_phone()
{
	$id = trim(htmlspecialchars($_GET['id'],ENT_QUOTES));
	#作者
	$rows = db()->select('*')->from(PRE.'login')->order_by('id desc')->get()->array_rows();
	#查询
	$row = db()->select('*')->from(PRE.'template')->where(array('id'=>$id))->get()->array_row();
	#查询父类栏目
	$datas = GetFenLai('0');
	#查询主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$subject = '<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit_phone">
		<div class="userheader2_phone">页面编辑，修改</div>
		<div class="userjibie ujt">栏目名称</div>
		<div><input type="text" name="name" value="'.$row['name'].'" class="inputs-s" style="width:100%;"/>
		<span>自定义栏目名称，默认导行菜单栏显示</span>
		</div>
		<div class="userjibie ujt">页面名称</div>
		<div>
		<input type="text" name="module" value="'.$row['module'].'" readonly="readonly" class="inputs-s" style="width:100%;"/>
		<span>自定义名称，不需要添加后缀名，默认为.html文件</span>
		</div>
		<div class="userjibie ujt">排序</div>
		<div><input type="text" name="sort" value="'.$row['sort'].'" class="inputs-s" style="width:100%;"/>
		<span>自定义数字，排序从大到小</span>
		</div>
		<div class="userjibie ujt">添加子类栏目</div>
		<div>
			<select name="pid" class="selens" style="width:100%;">
				<option value="-1" selected="selected">不添加子类栏目</option>';
if(!empty($datas))
{	
	foreach($datas as $k=>$v)
	{			
		$subject .= '<option value="'.$v['id'].'" '.($v['id']==$row['pid']?'selected="selected"':'').'>'.$v['name'].'</option>';
	}
}				
	$subject .= '</select>
		</div>
		<div class="userjibie ujt">栏目封面图片</div>
		<div>
		<input type="file" name="cover" style="width:100%;border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;border-radius:5px 5px 5px 5px;-moz-border-radius:5px 5px 5px 5px;-webkit-border-radius:5px 5px 5px 5px;"/>
		<span>上传最大2M，类型要求(jpeg，jpg，gif，png)</span>
		<div style="border:1px solid #999999;margin-top:2px;width:150px;border-radius:5px 5px 5px 5px;-moz-border-radius:5px 5px 5px 5px;-webkit-border-radius:5px 5px 5px 5px;">';
	if(strrpos($row['cover'], 'a-ettra01.jpg')||$row['cover']=='')
	{	
		$subject .= '<span>默认封面图片</span>
		<img src="'.apth_url('system/admin/pic/defualt/a-ettra01.jpg').'" width="150"/>
		<input type="hidden" name="srcpath" value="system/admin/pic/defualt/a-ettra01.jpg"/>';
	}
	else 
	{
		$subject .= '<span>封面图片</span>
		<img src="'.$row['cover'].'" width="150"/>
		<input type="hidden" name="srcpath" value="'.$row['cover'].'"/>';
	}	
	$subject .= '</div>
		</div>
		<div class="userjibie ujt">关键字</div>
		<div><input type="text" name="keywords" value="'.$row['keywords'].'" class="inputs-s" style="width:100%;"/>
		<span>多个关键字“，”号隔开</span>
		</div>
		<div class="userjibie ujt">摘要</div>
		<div><textarea name="description" class="input-w">'.$row['description'].'</textarea></div>
		</div>
		<ul class="buttom-girg_phone">
			<li class="subClass1"></li>	
			<li class="clfl">状态&nbsp;</li>
			<li>
			<select name="state" class="selens">
				<option value="0" '.($row['state']==0?'selected="selected"':'').'>动态页面</option>
				<option value="1" '.($row['state']==1?'selected="selected"':'').'>静态页面</option>
			</select>
			</li>
			<li class="clfl">主题&nbsp;</li>
			<li>
			<select name="templateid" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
			</li>
			<li class="clfl">作者&nbsp;</li>
			<li>
			<select name="userid" class="selens">';
if(!empty($rows))
{	
	foreach($rows as $k=>$v)
	{
		$subject .= '<option value="'.$v['id'].'" '.($row['userid']==$v['id']?'selected="selected"':'').'>'.$v['userName'].'</option>';
	}
}		
		$subject .= '</select>
			</li>
			<li class="clfl"><input type="hidden" id="offon2" name="addmenu" value="'.$row['addmenu'].'"/></li>
			<li class="clens"><span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid offno2" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 0;"></span></li>			
			<li class="clfl"><input type="hidden" id="offon3" name="forbidden" value="'.$row['forbidden'].'"/></li>
			<li class="clens"><span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">禁止发布编辑</span><span class="clzhid offno50" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 0;"></span></li>
			<li style="height:30px;"></li>
			<li>
			<input type="hidden" name="id" value="'.$row['id'].'"/>
			<input type="hidden" name="page" value="'.$_GET['page'].'"/>
			<input type="hidden" name="act" value="PageEdtUp"/>
			<input type="submit" value="提交" class="subClass"/>
			</li>	
		</ul>
		</form>';
	$subject .= '
    <script type="text/javascript">    
$(function(){
	$("#frm").submit(function(){
		if( $("[name=name]").val() == "" )
		{
			alert("栏目名称未命名");
			$("[name=name]").focus();
			return false;
		}
		if( $("[name=module]").val() == "" )
		{
			alert("页面名称未命名");
			$("[name=module]").focus();
			return false;
		}
	});
	if( $("[name=addmenu]").val() == "OFF" )
	{
		$(".offno2").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
	else
	{
		$(".offno2").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
	if( $("[name=forbidden]").val() == "ON" )
	{
		$(".offno50").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
	else
	{
		$(".offno50").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
	
	
		$(".offno2").click(function(){
		if($("[name=addmenu]").val()=="ON")
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$("#offon2").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$("#offon2").val("ON");//关闭
		}
		});	
		$(".offno50").click(function(){
		if($("[name=forbidden]").val()=="ON")
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$("#offon3").val("OFF");//关闭
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$("#offon3").val("ON");//打开
		}
		});
});
    </script>';	
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#页面编辑
function PageEdt()
{
	session_start();
	#作者
	$rows = db()->select('*')->from(PRE.'login')->order_by('id desc')->get()->array_rows();
	#查询父类栏目
	$datas = GetFenLai('0');
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$subject = '<form action="handling_events.php" id="frm" method="post"  enctype="multipart/form-data">
		<div class="useredit">
		<div class="userheader">页面编辑－添加栏目</div>
		<div class="userjibie ujt">栏目名称</div>
		<div><input type="text" name="name" placeholder="未命名" class="inputs-s" style="width:598px;"/>
		<span>自定义栏目名称，默认导行菜单栏显示</span>
		</div>
		<div class="userjibie ujt">页面名称</div>
		<div>
		<input type="text" name="module" value="article_list" class="inputs-s" style="width:598px;"/>
		<span>自定义名称，不需要添加后缀名，默认为.html文件</span>
		<p style="line-height:25px;font-size:14px;color:#999999;">新建主题时默认创建，首页 index.html、栏目页面article_list.html、内容页面 article_content.html</p>
		</div>
		<div class="userjibie ujt">排序</div>
		<div><input type="text" name="sort" value="0" class="inputs-s" style="width:249px;"/>
		<span>自定义数字，排序从大到小</span>
		</div>
		<div class="userjibie ujt">添加子类栏目</div>
		<div>
			<select name="pid" class="selens" style="width:249px;">
				<option value="-1" selected="selected">不添加子类栏目</option>';
if(!empty($datas))
{	
	foreach($datas as $k=>$v)
	{			
		$subject .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
	}
}				
	$subject .= '</select>
		</div>
		<div class="userjibie ujt">栏目封面图片</div>
		<div>
		<input type="file" name="cover" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"/>
		<span>上传最大2M，类型要求(jpeg，jpg，gif，png)</span>
		<div style="border:1px solid #999999;margin-top:2px;width:150px;">
		<span>默认封面图片</span>
		<img src="'.apth_url('system/admin/pic/defualt/a-ettra01.jpg').'" width="150"/>
		</div>
		</div>
		<div class="userjibie ujt">关键字</div>
		<div><input type="text" name="keywords" value="" class="inputs-s" style="width:598px;"/>
		<span>多个关键字“，”号隔开</span>
		</div>
		<div class="userjibie ujt">摘要</div>
		<div><textarea name="description" class="input-w"></textarea></div>
		
		</div>
		<ul class="buttom-girg">
			<li class="subClass1"></li>
			<li>
			<input type="hidden" name="act" value="PageEdt"/>
			<input type="submit" value="提交" class="subClass"/>
			</li>		
			<li class="clfl">状态&nbsp;</li>
			<li>
			<select name="state" class="selens">
				<option value="0">动态页面</option>
			</select>
			</li>
			<li class="clfl">主题&nbsp;</li>
			<li>
			<select name="templateid" class="selens">';

		$subject .= '<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>';
			
		$subject .= '</select>
			</li>
			<li class="clfl">作者&nbsp;</li>
			<li>
			<select name="userid" class="selens">';
if(!empty($rows))
{	
	foreach($rows as $k=>$v)
	{
		$subject .= '<option value="'.$v['id'].'" '.($v['userName']==$_SESSION['username']?'selected="selected"':'').'>'.$v['userName'].'</option>';
	}
}		
		$subject .= '</select>
			</li>
			<li class="clfl"><input type="hidden" id="offon2" name="addmenu" value="OFF"/></li>
			<li class="clens"><span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">加入导航栏菜单</span><span class="clzhid offno2" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 0;"></span></li>			
			<li class="clfl"><input type="hidden" id="offon3" name="forbidden" value="ON"/></li>
			<li class="clens"><span class="clzhid" style="width:115px;font-weight:bold;font-size:15px;text-align:center;">禁止发布编辑</span><span class="clzhid offno3" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 0;"></span></li>
		</ul>
		</form>';
	$subject .= '
    <script type="text/javascript">    
$(function(){
	$("#frm").submit(function(){
		if( $("[name=name]").val() == "" )
		{
			alert("栏目名称未命名");
			$("[name=name]").focus();
			return false;
		}
		if( $("[name=module]").val() == "" )
		{
			alert("页面名称未命名");
			$("[name=module]").focus();
			return false;
		}
		if( $("[name=templateid]").val() == "-1" )
		{
			alert("未选择主题");
			$("[name=templateid]").focus();
			return false;
		}
	});
		$(".offno2").click(function(){
		if($("[name=addmenu]").val()=="ON")
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$("#offon2").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$("#offon2").val("ON");//关闭
		}
		});
		$(".offno3").click(function(){
		if($("[name=forbidden]").val()=="ON")
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$("#offon3").val("OFF");//关闭
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$("#offon3").val("ON");//打开
		}
		});
});
    </script>';	
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#文章管理
function ArticleMng()
{
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	#获取分类
	$clrows = db()->select('*')->from(PRE.'classified')->where(array('templateid'=>$theme['id']))->order_by('id desc')->get()->array_rows();	
	#所有栏目	
	$column = db()->select('id,name')->from(PRE.'template')->where(array('templateid'=>$theme['id']))->get()->array_rows();
	#获取文章	
	$sql = 'select a.id,a.author,a.imgurl,a.title,FROM_UNIXTIME(a.publitime) as publitime,a.top,a.state,b.classified,(select count(id) from '.PRE.'review where titleid=a.id) as revTotal from '.PRE.'article as a,'.PRE.'classified as b where a.cipid=b.id and a.templateid='.$theme['id'].' ';
	if($_GET['c']!='')
	{
		$sql .= ' and cipid='.$_GET['c'].' ';
	}
	if($_GET['k']!='')
	{
		$num = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];
		$sql .= ' and a.columnid='.$_GET['k'].' ';
	}
	if($_GET['t']!=0)
	{
		$sql .= ' and top in(101,102,103) ';
	}	
	if($_GET['s']!='')
	{
		$num = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];
		$sql .= ' and (title like "'.trim($_GET['s']).'%") ';
	}
	$totalrows = db()->query($sql)->array_nums();
	$showrow = $num;
	$totalpage = ceil($totalrows/$showrow);
	$page = $_GET['page']==''?1:$_GET['page'];
	if($page>=$totalpage){$page=$totalpage;}
	if($page<=1||!is_numeric($page)){$page=1;}
	$offset = ($page-1)*$showrow;	
	$sql .= 'order by id desc limit '.$offset.','.$showrow;
	$rows = db()->query($sql)->array_rows();
	
	$subject = '<div class="useredit">';	
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}	
	$subject .= '<div class="userheader" style="border:none;">文档管理</div>
		<div class="newsTsBox">
		<span>搜索: </span>
		<span>分类</span>
		<span>
			<select id="clAll" class="renyiCl">
				<option value="">所有分类</option>';
if(!empty($clrows))
{			
	foreach($clrows as $k=>$v)
	{	
		$subject .= '<option value="'.$v["id"].'" '.($_GET["c"]==$v["id"]?"selected=true":"").'>'.$v["classified"].'</option>';
	}
}
	$subject .= '<select>
		</span> &nbsp; 
		<span>栏目</span>
		<span>
			<select id="clkey" class="renyiCl">
				<option value="">所有栏目</option>';
if(!empty($column))
{				
	foreach($column as $k=>$v)
	{
		$subject .= '<option value="'.$v["id"].'" '.($_GET["k"]==$v["id"]?"selected=true":"").'>'.$v["name"].'</option>';
	}
}
	$subject .= '<select>
		</span> &nbsp; 
		<span><input type="checkbox" id="zding" value="'.($_GET["t"]==0?"0":"1").'" '.($_GET["t"]==0?"":"checked=checked").'/> <label for="zding">置顶</label></span> &nbsp; 
		<span><input type="text" name="art" value="" class="rinput"/></span> &nbsp; 
		<span><input type="submit" value="搜索" id="search" class="sub"/></span>
		</div>
		<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">分类</th>
				<th style="text-align:center;">作者</th>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;标题</th>
				<th style="text-align:center;">日期</th>
				<th style="text-align:center;">评论</th>
				<th style="text-align:center;">状态</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if(!empty($rows))
{		
	foreach($rows as $k=>$v)
	{	
	$subject .= '<tr>
				<td>'.$v["id"].'</td>
				<td>'.$v["classified"].'</td>
				<td>'.$v["author"].'</td>
				<td>
				<a href="'.apth_url('index.php?act=article_content&id='.$v["id"]).'" class="hoverstyle" target="_blank" title="内容页面"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>
				&nbsp;'.subString($v["title"],25).'';
	if($v['imgurl']!='null')
	{			
		$subject .= ' &nbsp; <img src="'.site_url('images/d05.png').'" align="absmiddle" width="16" height="16" title="图文"/>';
	}			
	$subject .= '</td>
				<td>'.$v["publitime"].'</td>
				<td>'.$v["revTotal"].'</td>
				<td>'.topFormat($v["top"]).sateFormat($v["state"]).'</td>
				<td style="text-align:center;">
					<a href="index.php?act=ArticleUp&id='.$v["id"].'&page='.$page.'" class="hoverstyle" title="修改"><img src="'.site_url('images/page_edit.png').'" align="absmiddle"/></a>
					 &nbsp; &nbsp; 
					<a href="javascript:;" onclick="conf('.$v["id"].','.$page.')" class="hoverstyle" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
			</tr>';
	}
}						
	$subject .= '<tr>
				<td colspan="8">
				<span style="font-size:15px;color:#666666;">总数:'.$totalrows.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$totalpage.'页</span>
				&nbsp; ';
if( $totalrows >= $showrow )
{				
	$subject .= '<a href="index.php?act=ArticleMng&page='.($page-1).'&c='.$_GET['c'].'&k='.$_GET['k'].'&t='.$_GET['t'].'&s='.$_GET['s'].'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; 
				<a href="index.php?act=ArticleMng&page='.($page+1).'&c='.$_GET['c'].'&k='.$_GET['k'].'&t='.$_GET['t'].'&s='.$_GET['s'].'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="GO" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" id="GO" class="sub"/>
				</span>';
}				
	$subject .= '</td>
			</tr>
		</table>
		</div>';
	$subject .= '<script>
	function conf(id,page)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=ArticleDelete&id="+id+"&page="+page;
		}
	}
	$(function(){
		$("#clkey").change(function(){
			location.href="index.php?act=ArticleMng&c="+$("#clAll").val()+"&k="+$(this).val()+"&t="+$("#zding").val()+"&s="+$("[name=art]").val();
		});
		$("#clAll").change(function(){
			location.href="index.php?act=ArticleMng&c="+$(this).val()+"&k="+$("#clkey").val()+"&t="+$("#zding").val()+"&s="+$("[name=art]").val();
		});
		$("#search").click(function(){
			var art = $("[name=art]").val();
			location.href="index.php?act=ArticleMng&c="+$("#clAll").val()+"&k="+$("#clkey").val()+"&t="+$("#zding").val()+"&s="+art;
		});
		$("#zding").click(function(){
			if($(this).is(":checked"))
			{
				$(this).val(1);
				$(this).attr("checked","true");
				location.href="index.php?act=ArticleMng&c="+$("#clAll").val()+"&k="+$("#clkey").val()+"&t="+$(this).val()+"&s="+$("[name=art]").val();
			}
			else
			{
				$(this).val(0);
				$(this).removeAttr("checked");
				location.href="index.php?act=ArticleMng&c="+$("#clAll").val()+"&k="+$("#clkey").val()+"&t="+$(this).val()+"&s="+$("[name=art]").val();
			}
		});
		$("#GO").click(function(){
			location.href="index.php?act=ArticleMng&page="+$("[name=GO]").val()+"&c="+$("#clAll").val()+"&k="+$("#clkey").val()+"&t="+$("#zding").val()+"&s="+$("[name=art]").val();;
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
	$subject .= '$.post("handling_events.php",{act:"setting_staitc"},function(d){});';		
	}
	$subject .= '</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#文章管理
function ArticleMng_phone()
{
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	#获取分类
	$clrows = db()->select('*')->from(PRE.'classified')->where(array('templateid'=>$theme['id']))->order_by('id desc')->get()->array_rows();
	#所有栏目	
	$column = db()->select('id,name')->from(PRE.'template')->where(array('templateid'=>$theme['id']))->get()->array_rows();
	#获取文章	
	$sql = 'select a.id,a.author,a.imgurl,a.title,FROM_UNIXTIME(a.publitime) as publitime,a.top,a.state,b.classified,(select count(id) from '.PRE.'review where titleid=a.id) as revTotal from '.PRE.'article as a,'.PRE.'classified as b where a.cipid=b.id and a.templateid='.$theme['id'].' ';
	if($_GET['c']!='')
	{
		$sql .= ' and cipid='.$_GET['c'].' ';
	}
	if($_GET['k']!='')
	{
		$num = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];
		$sql .= ' and a.columnid='.$_GET['k'].' ';
	}
	if($_GET['t']!=0)
	{
		$sql .= ' and top in(101,102,103) ';
	}	
	if($_GET['s']!='')
	{
		$num = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];
		$sql .= ' and (title like "'.trim($_GET['s']).'%") ';
	}
	$totalrows = db()->query($sql)->array_nums();
	$showrow = $num;
	$totalpage = ceil($totalrows/$showrow);
	$page = $_GET['page']==''?1:$_GET['page'];
	if($page>=$totalpage){$page=$totalpage;}
	if($page<=1||!is_numeric($page)){$page=1;}
	$offset = ($page-1)*$showrow;	
	$sql .= ' order by id desc limit '.$offset.','.$showrow;
	$rows = db()->query($sql)->array_rows();
	
	$subject = '<div class="useredit_phone">';	
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}	
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">document_fill</i> 文档管理</div>
		<div class="newsTsBox_phone">
		<p>
		<span>
			<select id="clAll" class="renyiCl">
				<option value="">所有分类</option>';
if(!empty($clrows))
{			
	foreach($clrows as $k=>$v)
	{	
		$subject .= '<option value="'.$v["id"].'" '.($_GET["c"]==$v["id"]?"selected=true":"").'>'.$v["classified"].'</option>';
	}
}
	$subject .= '<select>
		</span>
		</p> 
		<p>
		<span>
			<select id="clkey" class="renyiCl">
				<option value="">所有栏目</option>';
if(!empty($column))
{				
	foreach($column as $k=>$v)
	{
		$subject .= '<option value="'.$v["id"].'" '.($_GET["k"]==$v["id"]?"selected=true":"").'>'.$v["name"].'</option>';
	}
}
	$subject .= '<select>
		</span> 
		</p>
		<p>
		<span><input type="checkbox" id="zding" value="'.($_GET["t"]==0?"0":"1").'" '.($_GET["t"]==0?"":"checked=checked").'/> <label for="zding">置顶</label></span> 
		</p>
		<p>
		<span><input type="text" name="art" value="" class="inputs-s"/></span> 
		</p>
		<p>
		<span><input type="submit" value="搜索" id="search" class="subClass"/></span>
		</p>
		</div>
		<table class="tableBox">
			<tr>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;标题</th>
				<th style="text-align:center;">评论</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if(!empty($rows))
{		
	foreach($rows as $k=>$v)
	{	
	$subject .= '<tr>
				<td>
				<a href="'.apth_url('index.php?act=article_content&id='.$v["id"]).'" class="hoverstyle" target="_blank" title="内容页面"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>
				&nbsp;'.subString($v["title"],25).'';
	if($v['imgurl']!='null')
	{			
		$subject .= ' &nbsp; <img src="'.site_url('images/d05.png').'" align="absmiddle" width="16" height="16" title="图文"/>';
	}			
	$subject .= '</td>
				<td>'.$v["revTotal"].'</td>
				<td style="text-align:center;">
					<p class="art_floats art_floats_d">
					<a href="index.php?act=ArticleUp_phone&id='.$v["id"].'&page='.$page.'" class="hoverstyle" title="修改"><img src="'.site_url('images/page_edit.png').'" align="absmiddle"/></a>
					</p>
					<p class="art_floats">
					<a href="javascript:;" onclick="conf('.$v["id"].','.$page.')" class="hoverstyle" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
					</p>
				</td>
			</tr>';
	}
}						
	$subject .= '<tr>
				<p>
				<td colspan="3">
				<span style="font-size:15px;color:#666666;">总数:'.$totalrows.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$totalpage.'页</span>
				</p>';
if( $totalrows >= $showrow )
{				
	$subject .= '<p><a class="a_href" href="index.php?act=ArticleMng_phone&page='.($page-1).'&c='.$_GET['c'].'&k='.$_GET['k'].'&t='.$_GET['t'].'&s='.$_GET['s'].'">上一页</a>  
				<a class="a_href" href="index.php?act=ArticleMng_phone&page='.($page+1).'&c='.$_GET['c'].'&k='.$_GET['k'].'&t='.$_GET['t'].'&s='.$_GET['s'].'">下一页</a>
				<span>
				<input type="text" name="GO" value="" class="renyiCl" style="width:50px;height:23px;"/>页 &nbsp; 
				<input type="submit" value="GO" id="GO" />
				</span></p>';
}				
	$subject .= '</td>
			</tr>
		</table>
		</div>';
	$subject .= '<script>
	function conf(id,page)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=ArticleDelete&id="+id+"&page="+page;
		}
	}
	$(function(){
		$("#clkey").change(function(){
			location.href="index.php?act=ArticleMng_phone&c="+$("#clAll").val()+"&k="+$(this).val()+"&t="+$("#zding").val()+"&s="+$("[name=art]").val();
		});
		$("#clAll").change(function(){
			location.href="index.php?act=ArticleMng_phone&c="+$(this).val()+"&k="+$("#clkey").val()+"&t="+$("#zding").val()+"&s="+$("[name=art]").val();
		});
		$("#search").click(function(){
			var art = $("[name=art]").val();
			location.href="index.php?act=ArticleMng_phone&c="+$("#clAll").val()+"&k="+$("#clkey").val()+"&t="+$("#zding").val()+"&s="+art;
		});
		$("#zding").click(function(){
			if($(this).is(":checked"))
			{
				$(this).val(1);
				$(this).attr("checked","true");
				location.href="index.php?act=ArticleMng_phone&c="+$("#clAll").val()+"&k="+$("#clkey").val()+"&t="+$(this).val()+"&s="+$("[name=art]").val();
			}
			else
			{
				$(this).val(0);
				$(this).removeAttr("checked");
				location.href="index.php?act=ArticleMng_phone&c="+$("#clAll").val()+"&k="+$("#clkey").val()+"&t="+$(this).val()+"&s="+$("[name=art]").val();
			}
		});
		$("#GO").click(function(){
			location.href="index.php?act=ArticleMng_phone&page="+$("[name=GO]").val()+"&c="+$("#clAll").val()+"&k="+$("#clkey").val()+"&t="+$("#zding").val()+"&s="+$("[name=art]").val();;
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});';	
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
	$subject .= '$.post("handling_events.php",{act:"setting_staitc"},function(d){});';		
	}
	$subject .= '</script>';	
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#修改文章内容
function ArticleUp()
{
	session_start();	
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$id = mysql_escape_string($_GET['id']);	
	$row = db()->select('id,author,cipid,state,stage,top,nocomment,templateid,title,alias,keywords,description,body,columnid,posflag,price,orprice,Sales,chain,sizetype')->from(PRE.'article')->where(array('id'=>$id))->get()->array_row();
			
	#作者
	$author = db()->select('userName')->from(PRE.'login')->get()->array_rows();	
	#所有栏目
	$column = db()->select('id,name')->from(PRE.'template')->where(array('templateid'=>$theme['id']))->get()->array_rows();
	#分类
	$rows = db()->select('id,classified')->from(PRE.'classified')->where(array('templateid'=>$theme['id']))->order_by('sort desc')->get()->array_rows();
	#标签
	$tags = db()->select('id,keywords')->from(PRE.'tag')->where(array('templateid'=>$theme['id']))->order_by('id desc')->limit('0,50')->get()->array_rows();
	#获取往期
	$stage = db()->select('stage')->from(PRE.'article')->order_by('stage desc')->limit('0,5')->get()->array_rows();
	
	$subject = '<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit">
		<div class="userheader">文档编辑</div>
		<div class="userjibie ujt">标题</div>
		<div><input type="text" name="title" value="'.$row['title'].'" class="inputs-s"/></div>
		<div class="userjibie ujt">正文</div>
		<div><script id="container" name="body" type="text/plain" style="width:90%;height:309px;">'.$row['body'].'</script></div>
		<div class="userjibie ujt">别名 <input type="text" name="alias" value="'.$row['alias'].'" class="inputs-w"/></div>
		<div class="ujt" style="font-weight:bold;">
		   标签
		 <input type="text" name="keywords" value="'.$row['keywords'].'" class="inputs-w"/>
		  <span style="font-weight:normal;font-size:13px;">(逗号分割) <a href="javascript:;" id="showClBox" style="color:#1D4C7D;">显示常用标签</a></span>
		  <dl id="listDls" class="clear">';
if(!empty($tags))
{		  
	foreach($tags as $k=>$v)
	{
		$subject .= '<dd>'.$v["keywords"].'</dd>';		
	}	
}		  	
	$subject .= ' </dl>
		 </div>
		<div class="userjibie ujt">摘要</div>
		<div style="font-size:15px;color:#333333;margin-bottom:2px;">* 在正文插入首条分隔符，则分隔符以上的内容将作为摘要。您也可以<font class="D4C7D">[自动生成摘要]</font></div>
		<div class="showZhaiYao">
			<div style="margin-bottom:10px;"><script id="at" name="description" type="text/plain" style="width:90%;height:100px;">'.$row['description'].'</script></div>
		</div>
		</div>
		
		<div class="userjibie ujt">
			期数: # 第 <input type="text" name="stage" value="'.$row['stage'].'" class="inputs-w" style="text-align:center;width:45px;"/> 期 #
			&nbsp; &nbsp; &nbsp; &nbsp; 
			上一期:
			<select>';
if(!empty($stage))
{	
	foreach( $stage as $k=>$v )
	{
		$subject .= ' <option>'.$v['stage'].'</option>';
	}
}		
		$subject .= ' </select>
		</div>
		
		<div class="userjibie ujt">内容封面图片</div>
		<div>
		<input type="file" name="cover" size="60" onchange="previewImage(this);" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;">
		<font color="#555555">上传最大2M，类型要求(jpeg，jpg，gif，png)</font>';
	if(strrpos($row['cover'], 'a-ettra01.jpg') || $row['cover'] == '')
	{	
		$subject .= '<div style="border:1px solid #999999;margin-top:2px;width:98px;">
		<span>默认封面图片</span>
		<img src="'.apth_url('system/admin/pic/defualt/a-ettra01.jpg').'"  width="98" height="77" id="img_url">
		</div>
		<input type="hidden" name="srcpath" value="system/admin/pic/defualt/a-ettra01.jpg"/>';
	}
	else 
	{
		$subject .= '<div style="border:1px solid #999999;margin-top:2px;width:150px;">
		<span>封面图片</span>
		<img src="'.$row['cover'].'"  width="98" height="77" id="img_url">
		</div>
		<input type="hidden" name="srcpath" value="'.$row['cover'].'"/>';
	}	
	$subject .= '</div>
		<div style="height:5px;"></div>
		<div class="userjibie ujt">辅助信息</div>
		<div class="userjibie ujt">售价/工资/范围 <input type="text" name="price" value="'.$row['price'].'" class="inputs-w"/> <span style="font-weight:normal;font-size:13px;color:#666666;">&yen;/$</span></div>
		<div class="userjibie ujt">原价/工资/范围 <input type="text" name="orprice" value="'.$row['orprice'].'" class="inputs-w"/> <span style="font-weight:normal;font-size:13px;color:#666666;">&yen;/$</span></div>
		<div class="userjibie ujt">销量/数量/人数 <input type="text" name="Sales" value="'.$row['Sales'].'" class="inputs-w"/> </div>
		<div class="userjibie ujt">链接/文字/其它 <input type="text" name="chain" value="'.$row['chain'].'" class="inputs-w"/> <span style="font-weight:normal;font-size:13px;color:#666666;">*+</span></div>
		<div class="userjibie ujt">
		类型 &nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="1" id="s2" '.($row['sizetype']==1?'checked="checked"':'').'/>
		<label for="s2" style="color:#666666;">热销</label>  
		&nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="2" id="s3" '.($row['sizetype']==2?'checked="checked"':'').'/>
		<label for="s3" style="color:#666666;">新品</label>  
		&nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="3" id="s4" '.($row['sizetype']==3?'checked="checked"':'').'/>
		<label for="s4" style="color:#666666;">流行</label>  
		&nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="4" id="s5" '.($row['sizetype']==4?'checked="checked"':'').'/>
		<label for="s5" style="color:#666666;">时尚</label>   
		&nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="5" id="s6" '.($row['sizetype']==5?'checked="checked"':'').'/>
		<label for="s6" style="color:#666666;">推荐</label>  
		&nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="6" id="s7" '.($row['sizetype']==6?'checked="checked"':'').'/>
		<label for="s7" style="color:#666666;">其它</label></div>
		<div style="height:50px;"></div>
		';

	$subject .= '<input type="hidden" name="posflag" value="'.$row['posflag'].'"/>'; 
	
	$subject .= '<ul class="buttom-girg">
			<li class="subClass1"></li><li>
			<input type="hidden" name="page" value="'.$_GET['page'].'"/>
			<input type="hidden" name="id" value="'.$row['id'].'"/>
			<input type="hidden" name="act" value="ArticleUp"/>
			<input type="submit" value="提交" class="subClass"/>
			</li>
			<li class="clfl">栏目&nbsp;</li>
			<li>
			<select name="columnid" class="selens">
			<option value="0">所有栏目</option>';
if(!empty($column))
{    
	foreach($column as $v)
	{
    $subject .= '<option value="'.$v['id'].'" '.($v['id']==$row['columnid']?'selected="selected"':'').'>'.$v['name'].'</option>';
	}
}    
    $subject .= '</select></li>
			<li class="clfl">分类&nbsp;</li>
			<li>
			<select name="cipid" class="selens">';
if(!empty($rows))
{
	foreach($rows as $k=>$v)
	{			
		$subject .= '<option value="'.$v["id"].'" '.($row['cipid']==$v['id']?'selected="selected"':'').'>'.$v["classified"].'</option>';
	}
}
else 
{
	$subject .= '<option value="1">未分类</option>';	
}
    $subject .= '</select>
			</li>
			<li class="clfl">状态&nbsp;</li>
			<li>
			<select name="state" class="selens">
				<option value="0" '.($row['state']=='0'?'selected="selected"':'').'>公开</option>
				<option value="1" '.($row['state']=='1'?'selected="selected"':'').'>草稿</option>
				<option value="2" '.($row['state']=='2'?'selected="selected"':'').'>审核</option>
			</select>
			</li>
			<li class="clfl">模板&nbsp;</li>
			<li>
			<select name="templateid" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
			</li>
			<li class="clfl">作者&nbsp;</li>
			<li>
			<select name="author" class="selens">';
if(!empty($author))
{	
	foreach($author as $k=>$v)
	{		
		$subject .= '<option value="'.$v["userName"].'" '.($row['author']==$v["userName"]?'selected="selected"':'').'>'.$v["userName"].'</option>';
	}
}
else 
{
	$subject .= '<option value="'.$_SESSION["username"].'">'.$_SESSION["username"].'</option>';
}				
	$subject .= '</select>
			</li>
			<li class="clfl">定时&nbsp;</li>
			<li><input type="text" id="edtDateTime" name="timerel" value="" class="selens1"/></li>
			<li class="clfl"><input type="hidden" id="offon1" name="offon1" value="ON"/></li>
			<li class="clens">
			<span class="clzhid" style="width:40px;font-weight:bold;font-size:15px;text-align:center;">置顶</span>
			<span class="clzhid offno1" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
			<span class="clzhid showcl" style="width:60px;text-align:center;display:none;">
			<select name="top">
			<option value="101" '.($row['top']=='101'?'selected="selected"':'').'>首页</option>
			<option value="102" '.($row['top']=='102'?'selected="selected"':'').'>全部</option>
			<option value="103" '.($row['top']=='103'?'selected="selected"':'').'>分页</option>
			</select>
			</span>
			</li>
			<li class="clfl"><input type="hidden" id="offon2" name="nocomment" value="'.$row['nocomment'].'"/></li>
			<li class="clens"><span class="clzhid" style="width:75px;font-weight:bold;font-size:15px;text-align:center;">禁止评论</span><span class="clzhid offno2" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span></li>			
		</ul>
		</form>';
	$subject .= '
    <script type="text/javascript" src="plugin/UEditor/ueditor.config.js"></script>
    <script type="text/javascript" src="plugin/UEditor/ueditor.all.js"></script>
    <script type="text/javascript" src="plugin/UEditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript">
        var ue1 = UE.getEditor("container");       
        var ue2 = UE.getEditor("at",{
            //这里可以选择自己需要的工具按钮名称,此处仅选择如下五个
            toolbars:[["Undo","Redo","bold","italic","underline","forecolor", "backcolor","indent","justifyleft", "justifycenter", "justifyright","lineheight","fontfamily", "fontsize","emotion","link","horizontal","inserttable","spechars"]],
            //focus时自动清空初始化时的内容
            autoClearinitialContent:false,
            //关闭字数统计
            wordCount:true,
            //关闭elementPath
            elementPathEnabled:false,
            //默认的编辑区域高度
            initialFrameHeight:120
            //更多其他参数，请参考ueditor.config.js中的配置项
           // serverUrl: "/server/ueditor/controller.php"
        }); 
   
        $("#frm").submit(function(){
			if($("[name=title]").val() == "")
			{
				alert("标题未命名");
				$("[name=title]").focus();
				return false;
			}
			var ue1Str = ue1.getContentTxt();
			if(ue1Str == "")
			{
				alert("正文为空");
				return false;
			}
		});   
        $(function(){
        $("#showClBox").click(function(){
		if($("#listDls").is(":hidden"))
		{
			$("#listDls").show();
		}
		else
		{
			$("#listDls").hide();
		}
});
        var listDlsstr = "";
        $("#listDls dd").click(function(){   
        listDlsstr += (listDlsstr==""?"":",")+$(this).html();
		$("[name=keywords]").val(listDlsstr);
});
        $("#showClBox").hover(function(){
$(this).css({"color":"#FF0000"});
},function(){
$(this).css({"color":"#1D4C7D"});
});
	$(".showZhaiYao").show();
	$(".D4C7D").click(function(){
		$(".showZhaiYao").show();
		var ue1Str = ue1.getContentTxt();
		ue1Str = ue1Str.substring(0,500);
		ue2.setContent(ue1Str);
		var scrollBottom = $(window).scrollTop() + $(window).height();
		$("body,html").animate({scrollTop:scrollBottom},400);
});
		var i=0;
		$(".offno1").click(function(){
		if(i%2==0)
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$(".showcl").show();
			$("#offon1").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$(".showcl").hide();
			$("#offon1").val("ON");//关闭
		}
		i++;
});
var y=0;
		$(".offno2").click(function(){
		if(y%2==0)
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$("#offon2").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$("#offon2").val("ON");//关闭
		}
		y++;
});
});
$(function(){
	var a5 = '.$row["top"].';
	if( a5 !=0 )
	{
		$(".offno1").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		$(".showcl").show();
		$("#offon1").val("OFF");//打开
	}
	else
	{
		$(".offno1").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		$(".showcl").hide();
		$("#offon1").val("ON");//关闭
	}
	if( $("#offon2").val() != "ON")
	{
		$(".offno2").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
	else
	{
		$(".offno2").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
});
$(function(){
//日期时间控件
var dt = new Date();
$("#edtDateTime").val(dt.getFullYear()+"-"+getInts(dt.getMonth()+1)+"-"+getInts(dt.getDate())+" "+getInts(dt.getHours())+":"+getInts(dt.getMinutes())+":"+getInts(dt.getSeconds()));
function getInts(int){if(int<10){var int = "0"+int;} return int;};
$.datepicker.regional["zh-CN"] = {
  closeText: "完成",
  prevText: "上个月",
  nextText: "下个月",
  currentText: "现在",
  monthNames: ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
  monthNamesShort: ["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"],
  dayNames: ["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
  dayNamesShort: ["周日","周一","周二","周三","周四","周五","周六"],
  dayNamesMin: ["日","一","二","三","四","五","六"],
  weekHeader: "周",
  dateFormat: "yy-mm-dd",
  firstDay: 1,
  isRTL: false,
  showMonthAfterYear: true,
  yearSuffix: " 年  "
};
$.datepicker.setDefaults($.datepicker.regional["zh-CN"]);
$.timepicker.regional["zh-CN"] = {
  timeOnlyTitle: "时间",
  timeText: "时间",
  hourText: "小时",
  minuteText: "分钟",
  secondText: "秒钟",
  millisecText: "毫秒",
  currentText: "现在",
  closeText: "完成",
  timeFormat: "HH:mm:ss",
  ampm: false
};
$.timepicker.setDefaults($.timepicker.regional["zh-CN"]);
$("#edtDateTime").datetimepicker({
  showSecond: true
  //changeMonth: true,
  //changeYear: true
});
});
function previewImage(file){	
		if(file.files && file.files[0]){
			var img = $("#img_url")[0];
			var reader = new FileReader(),rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;;  				
			
			var size = file.size;
			var Max_Size = 2000*1024;
			var width,height;
			var image = new Image();
					
			reader.onload = function(evt){
				img.src=evt.target.result;
				
				var data = evt.target.result;
				 image.onload=function(){
					width = image.width;
					height = image.height;	
					/*
					if(width>img.width && height>img.height){ 
						alert("图片宽高不符合要求，请上传宽高"+img.width+"*"+img.height+"像素图片");
					}*/					
				};
				image.src= data;			 
			} 		
				
		    if(!rFilter.test(file.files[0].type)) { alert("文件类型不正确 ");return; }	
		    if(size>Max_Size){ alert("文件大小不能超出2M");return; }
		    
		    reader.readAsDataURL(file.files[0]);	
	    }	    
	}
    </script>';	
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#修改文章内容
function ArticleUp_phone()
{
	session_start();	
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	$id = mysql_escape_string($_GET['id']);	
	$row = db()->select('id,author,cipid,state,top,nocomment,templateid,title,alias,keywords,description,body,columnid,posflag,price,cover,orprice,Sales,chain,sizetype,FROM_UNIXTIME(timerel,"%Y-%m-%d %H:%i:%s") as timerel')->from(PRE.'article')->where(array('id'=>$id))->get()->array_row();
			
	#作者
	$author = db()->select('userName')->from(PRE.'login')->get()->array_rows();
	#所有栏目
	$column = db()->select('id,name')->from(PRE.'template')->where(array('templateid'=>$theme['id']))->get()->array_rows();
	#分类
	$rows = db()->select('id,classified')->from(PRE.'classified')->where(array('templateid'=>$theme['id']))->order_by('sort desc')->get()->array_rows();
	#标签
	$tags = db()->select('id,keywords')->from(PRE.'tag')->where(array('templateid'=>$theme['id']))->order_by('id desc')->limit('0,50')->get()->array_rows();

	$subject = '<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit_phone">
		<div class="userheader2_phone"><i class="f7-icons size-22">compose_fill</i> 修改编辑</div>
		<div class="userjibie ujt">标题</div>
		<div><input type="text" name="title" value="'.$row['title'].'" class="inputs-s"/></div>
		<div class="userjibie ujt">正文</div>
		<div>
			<textarea name="body" id="container_phone">'.$row['body'].'</textarea>
		</div>
		<div class="userjibie ujt">
		 <p>别名 </p>
		<input type="text" name="alias" value="'.$row['alias'].'" class="inputs-s"/></div>
		<div class="ujt" style="font-weight:bold;">
		<p>标签  <a href="javascript:;" id="showClBox" style="color:#1D4C7D;">[点击显示常用标签]</a></p>
		<input type="text" name="keywords" value="'.$row['keywords'].'" class="inputs-s"/>		  
		  <dl id="listDls" class="clear">';
if(!empty($tags))
{		  
	foreach($tags as $k=>$v)
	{
		$subject .= '<dd>'.$v["keywords"].'</dd>';		
	}	
}		  	
	$subject .= ' </dl>
		 </div>
		<div class="userjibie ujt">摘要</div>
		<div class="showZhaiYao" style="display: block;">
			<div style="margin-bottom:10px;">
			<textarea name="description" class="input-w">'.$row['description'].'</textarea>
			</div>
		</div>		
		</div>
		<div class="userjibie ujt">内容封面图片</div>
		<div>
		<input type="file" name="cover" size="60" style="width:100%;border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;border-radius:5px 5px 5px 5px;-moz-border-radius:5px 5px 5px 5px;-webkit-border-radius:5px 5px 5px 5px;">
		<font color="#555555">上传最大2M，类型要求(jpeg，jpg，gif，png)</font>';
	if(strrpos($row['cover'], 'a-ettra01.jpg') || $row['cover'] == '')
	{	
		$subject .= '<div style="border:1px solid #999999;margin-top:2px;width:150px;border-radius:5px 5px 5px 5px;-moz-border-radius:5px 5px 5px 5px;-webkit-border-radius:5px 5px 5px 5px;">
		<span>默认封面图片</span>
		<img src="'.apth_url('system/admin/pic/defualt/a-ettra01.jpg').'" width="150">
		</div>
		<input type="hidden" name="srcpath" value="system/admin/pic/defualt/a-ettra01.jpg"/>';
	}
	else 
	{
		$subject .= '<div style="border:1px solid #999999;margin-top:2px;width:150px;border-radius:5px 5px 5px 5px;-moz-border-radius:5px 5px 5px 5px;-webkit-border-radius:5px 5px 5px 5px;">
		<span>封面图片</span>
		<img src="'.$row['cover'].'" width="150">
		</div>
		<input type="hidden" name="srcpath" value="'.$row['cover'].'"/>';
	}	
	$subject .= '</div>
		<div style="height:5px;"></div>
		<div class="userjibie ujt">
		<p>售价/工资/范围 </p>
		<input type="text" name="price" value="'.$row['price'].'" class="inputs-s"/></div>
		<div class="userjibie ujt">
		<p>原价/工资/范围  </p>
		<input type="text" name="orprice" value="'.$row['orprice'].'" class="inputs-s"/></div>
		<div class="userjibie ujt">
		<p>销量/数量/人数  </p>
		<input type="text" name="Sales" value="'.$row['Sales'].'" class="inputs-s"/></div>
		<div class="userjibie ujt">
		<p>链接/文字/其它  </p>
		<input type="text" name="chain" value="'.$row['chain'].'" class="inputs-s"/></div>
		<div class="userjibie ujt">
		<p>类型 </p> 
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="1" id="s2" '.($row['sizetype']==1?'checked="checked"':'').' />
		<label for="s2" style="color:#666666;">热销</label>  
		</p>
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="2" id="s3" '.($row['sizetype']==2?'checked="checked"':'').' />
		<label for="s3" style="color:#666666;">新品</label>  
		</p>
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="3" id="s4" '.($row['sizetype']==3?'checked="checked"':'').' />
		<label for="s4" style="color:#666666;">流行</label>  
		</p>
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="4" id="s5" '.($row['sizetype']==4?'checked="checked"':'').' />
		<label for="s5" style="color:#666666;">时尚</label>   
		</p> 
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="5" id="s6" '.($row['sizetype']==5?'checked="checked"':'').' />
		<label for="s6" style="color:#666666;">推荐</label>  
		</p> 
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="6" id="s7" '.($row['sizetype']==6?'checked="checked"':'').' />
		<label for="s7" style="color:#666666;">其它</label>
		</p>
		</div>
		<div style="height:50px;"></div>
		';

	$subject .= '<input type="hidden" name="posflag" value="'.$row['posflag'].'"/>'; 
	
	$subject .= '<ul class="buttom-girg_phone">
			<li class="subClass1"></li>
			<li class="clfl">栏目&nbsp;</li>
			<li>
			<select name="columnid" class="selens">
			<option value="0">所有栏目</option>';
if(!empty($column))
{    
	foreach($column as $v)
	{
    $subject .= '<option value="'.$v['id'].'" '.($v['id']==$row['columnid']?'selected="selected"':'').'>'.$v['name'].'</option>';
	}
}    
    $subject .= '</select></li>
			<li class="clfl">分类&nbsp;</li>
			<li>
			<select name="cipid" class="selens">';
if(!empty($rows))
{
	foreach($rows as $k=>$v)
	{			
		$subject .= '<option value="'.$v["id"].'" '.($row['cipid']==$v['id']?'selected="selected"':'').'>'.$v["classified"].'</option>';
	}
}
else 
{
	$subject .= '<option value="0">未分类</option>';	
}
    $subject .= '</select>
			</li>
			<li class="clfl">状态&nbsp;</li>
			<li>
			<select name="state" class="selens">
				<option value="0" '.($row['state']=='0'?'selected="selected"':'').'>公开</option>
				<option value="1" '.($row['state']=='1'?'selected="selected"':'').'>草稿</option>
				<option value="2" '.($row['state']=='2'?'selected="selected"':'').'>审核</option>
			</select>
			</li>
			<li class="clfl">模板&nbsp;</li>
			<li>
			<select name="templateid" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
			</li>
			<li class="clfl">作者&nbsp;</li>
			<li>
			<select name="author" class="selens">';
if(!empty($author))
{	
	foreach($author as $k=>$v)
	{		
		$subject .= '<option value="'.$v["userName"].'" '.($row['author']==$v["userName"]?'selected="selected"':'').'>'.$v["userName"].'</option>';
	}
}
else 
{
	$subject .= '<option value="'.$_SESSION["username"].'">'.$_SESSION["username"].'</option>';
}				
	$subject .= '</select>
			</li>
			<li class="clfl">定时&nbsp;</li>
			<li><input type="text" id="edtDateTime" name="timerel" value="'.$row['timerel'].'" class="selens1"/></li>
			<li class="clfl"><input type="hidden" id="offon1" name="offon1" value="ON"/></li>
			<li class="clens">
			<span class="clzhid" style="width:40px;font-weight:bold;font-size:15px;text-align:center;">置顶</span>
			<span class="clzhid offno1" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
			<span class="clzhid showcl" style="width:60px;text-align:center;display:none;">
			<select name="top">
			<option value="101" '.($row['top']=='101'?'selected="selected"':'').'>首页</option>
			<option value="102" '.($row['top']=='102'?'selected="selected"':'').'>全部</option>
			<option value="103" '.($row['top']=='103'?'selected="selected"':'').'>分页</option>
			<option value="104" '.($row['top']=='104'?'selected="selected"':'').'>系统更新</option>
			<option value="105" '.($row['top']=='105'?'selected="selected"':'').'>最新动态</option>
			</select>
			</span>
			</li>
			<li class="clfl"><input type="hidden" id="offon2" name="nocomment" value="'.$row['nocomment'].'"/></li>
			<li class="clens"><span class="clzhid" style="width:75px;font-weight:bold;font-size:15px;text-align:center;">禁止评论</span><span class="clzhid offno2" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span></li>			
			<li style="height:30px;"></li>
			<li>
			<input type="hidden" name="page" value="'.$_GET['page'].'"/>
			<input type="hidden" name="id" value="'.$row['id'].'"/>
			<input type="hidden" name="act" value="ArticleUp"/>
			<input type="submit" value="提交" class="subClass"/>
			</li>
		</ul>
		</form>';
	$subject .= '
     <script src="'.site_url('plugin/froala_editor/js/libs/jquery-1.11.1.min.js').'"></script>
 	<script src="'.site_url('plugin/froala_editor/js/froala_editor.min.js').'"></script>
  <!--[if lt IE 9]>
    <script src="'.site_url('plugin/froala_editor/js/froala_editor_ie8.min.js').'"></script>
  <![endif]-->
  <script src="'.site_url('plugin/froala_editor/js/plugins/tables.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/lists.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/colors.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/media_manager.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/font_family.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/font_size.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/block_styles.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/video.min.js').'"></script>
    <script type="text/javascript">
         $(function () {
            $("#container_phone").editable({
                inlineMode: false,
                alwaysBlank: true,
                language: "zh_cn",
                direction: "ltr",
                allowedImageTypes: ["jpeg", "jpg", "png", "gif"],
                autosave: true,
                autosaveInterval: 2500,
                saveURL: "imgupload.php",
                saveParams: { postId: "123" },
                spellcheck: true,
                plainPaste: true,
                imageButtons: ["floatImageLeft", "floatImageNone", "floatImageRight", "linkImage", "replaceImage", "removeImage"],
                imageUploadURL: "imgupload.php",
                imageParams: { postId: "123" },
                enableScript: false
 
            })
     	});
   	$(function(){
		$(".froala-box [data-name=color]").hide();
		$(".froala-box [data-name=table]").hide();
		$(".froala-box [data-cmd=html]").hide();
		$(".froala-box [data-cmd=strikeThrough]").hide();
		$(".froala-box [data-cmd=underline]").hide();
		$(".froala-box [data-cmd=insertVideo]").hide();
		$(".froala-box [data-name=formatBlock]").hide();
	 });
        $("#frm").submit(function(){
			if($("[name=title]").val() == "")
			{
				alert("标题未命名");
				$("[name=title]").focus();
				return false;
			}
			var ue1Str = ue1.getContentTxt();
			if(ue1Str == "")
			{
				alert("正文为空");
				return false;
			}
		});   
        $(function(){
        $("#showClBox").click(function(){
		if($("#listDls").is(":hidden"))
		{
			$("#listDls").show();
		}
		else
		{
			$("#listDls").hide();
		}
});
        var listDlsstr = "";
        $("#listDls dd").click(function(){   
        listDlsstr += (listDlsstr==""?"":",")+$(this).html();
		$("[name=keywords]").val(listDlsstr);
});
        $("#showClBox").hover(function(){
$(this).css({"color":"#FF0000"});
},function(){
$(this).css({"color":"#1D4C7D"});
});
	$(".showZhaiYao").show();
	$(".D4C7D").click(function(){
		$(".showZhaiYao").show();
		var ue1Str = ue1.getContentTxt();
		ue1Str = ue1Str.substring(0,500);
		ue2.setContent(ue1Str);
		var scrollBottom = $(window).scrollTop() + $(window).height();
		$("body,html").animate({scrollTop:scrollBottom},400);
});
		var i=0;
		$(".offno1").click(function(){
		if(i%2==0)
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$(".showcl").show();
			$("#offon1").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$(".showcl").hide();
			$("#offon1").val("ON");//关闭
		}
		i++;
});
var y=0;
		$(".offno2").click(function(){
		if(y%2==0)
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$("#offon2").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$("#offon2").val("ON");//关闭
		}
		y++;
});
});
$(function(){
	var a5 = '.$row["top"].';
	if( a5 !=0 )
	{
		$(".offno1").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
		$(".showcl").show();
		$("#offon1").val("OFF");//打开
	}
	else
	{
		$(".offno1").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
		$(".showcl").hide();
		$("#offon1").val("ON");//关闭
	}
	if( $("#offon2").val() != "ON")
	{
		$(".offno2").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
	else
	{
		$(".offno2").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
	}
});
    </script>';	
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################

###################################################################################
#网站设置
function SettingMng()
{
	session_start();
	
	#基础设置
	$row = db()->select('*')->from(PRE.'setting')->get()->array_row();
	#评论设置
	$review = db()->select('*')->from(PRE.'review_up')->get()->array_row();
	#插件
	$chajian = db()->select('addmenu,themename')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>1,'themename'=>'comment'))->get()->array_row();
	
	$subject = '<div class="useredit">';
	
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
		
	$subject .= '<div class="userheader3">网站设置</div>
		<ul class="menuWeb">
		<li class="menuWebAction">其础设置</li>
		<li>全局设置</li>
		<li>页面设置</li>';
if( $chajian['addmenu'] == 'OFF' && $chajian['themename'] == 'comment')
{#评论插件	
	$subject .= '<li>评论设置</li>';
}	
	$subject .= '</ul>
		<form action="handling_events.php" method="post">
		<table class="tableBox">
			<tr>
				<td width="320"><b>网站地址</b><br/>
默认自动读取当前网址，如需固定网站域名请点击按钮并输入域名。</td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;"><input type="text" name="link" value="'.$row['link'].'" disabled="disabled" style="cursor:not-allowed" class="input-web urlPath"/></p>
				<p style="position:relative;">
				<span class="clzhid guding" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
				<span class="clzhid" style="width:100px;margin-left:2px;">固定网站域名</span>
				<input type="hidden" name="addmenu" value="'.$row['addmenu'].'"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>网站标题</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;"><input type="text" name="title" value="'.$row['title'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>网站副标题</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;"><input type="text" name="alias" value="'.$row['alias'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>关键字</b><br/>SEO关键字，多个关键字用“,”隔开</td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;"><input type="text" name="keywords" value="'.$row['keywords'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>网站描述语</b><br/>描述网页或文章内容，有利于SEO搜引擎搜录</td>
				<td>
				<textarea name="description" class="text-web">'.$row['description'].'</textarea>
				</td>
			</tr>
			<tr>
				<td width="320"><b>版权说明</b><br/>可以填入网站统计、备案号等内容。</td>
				<td>
				<textarea name="copyright" class="text-web">'.$row['copyright'].'</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<input type="hidden" name="act" value="SettingMngBasic"/>
				<input type="submit" value="提交" class="sub"/>
<script>
$(".showerror").hide(2000);
if($("[name=addmenu]").val()=="OFF")
{
	$(".urlPath").removeAttr("disabled").css({"cursor":"auto"});
	$(".guding").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
else
{
	$(".urlPath").attr("disabled","disabled").css({"cursor":"not-allowed"});
	$(".guding").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
}
$(".guding").click(function(){
if($("[name=addmenu]").val()=="OFF" || $("[name=addmenu]").val()=="")
{
	$("[name=addmenu]").val("ON");
	$(".urlPath").attr("disabled","disabled").css({"cursor":"not-allowed"});
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$("[name=addmenu]").val("OFF");
	$(".urlPath").removeAttr("disabled").css({"cursor":"auto"});
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});
</script>
				</td>
			</tr>
		</table></form>
	<form action="handling_events.php" method="post">	
		<table class="tableBox" style="display:none;">
			<tr style="display:none;"><td></td></tr>
			<tr>
				<td width="320"><b>网站时区</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<select name="sitetimezone" class="input-web">
					<option value="Etc/GMT+12">Etc/GMT+12 -12:00</option>
					<option value="Pacific/Midway">Pacific/Midway -11:00</option>
					<option value="Pacific/Honolulu">Pacific/Honolulu -10:00</option>
					<option value="America/Anchorage">America/Anchorage -09:00</option>
					<option value="America/Los_Angeles">America/Los_Angeles -08:00</option>
					<option value="America/Denver">America/Denver -07:00</option>
					<option value="America/Tegucigalpa">America/Tegucigalpa -06:00</option>
					<option value="America/New_York">America/New_York -05:00</option>
					<option value="America/Halifax">America/Halifax -04:00</option>
					<option value="America/Argentina/Buenos_Aires">America/Argentina/Buenos_Aires -03:00</option>
					<option value="Atlantic/South_Georgia">Atlantic/South_Georgia -02:00</option>
					<option value="Atlantic/Azores">Atlantic/Azores -01:00</option>
					<option value="UTC">UTC 00:00</option>
					<option value="Europe/Berlin">Europe/Berlin +01:00</option>
					<option value="Europe/Sofia">Europe/Sofia +02:00</option>
					<option value="Africa/Nairobi">Africa/Nairobi +03:00</option>
					<option value="Europe/Moscow">Europe/Moscow +04:00</option>
					<option value="Asia/Karachi">Asia/Karachi +05:00</option>
					<option value="Asia/Dhaka">Asia/Dhaka +06:00</option>
					<option value="Asia/Bangkok">Asia/Bangkok +07:00</option>
					<option value="Asia/Shanghai" selected="selected">Asia/Shanghai +08:00</option>
					<option value="Asia/Tokyo">Asia/Tokyo +09:00</option>
					<option value="Pacific/Guam">Pacific/Guam +10:00</option>
					<option value="Australia/Sydney">Australia/Sydney +11:00</option>
					<option value="Pacific/Fiji">Pacific/Fiji +12:00</option>
					<option value="Pacific/Tongatapu">Pacific/Tongatapu +13:00</option>
					</select>
				</p>				
				</td>
			</tr>
			<tr>
				<td width="320"><b>网站语言</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<select name="weblanguage" class="input-web">
					<option value="zh-cn" selected="selected">简体中文 (zh-cn)</option>
				</select>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>允许上传文件的类型</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="filestyle" value="'.$review['filestyle'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>允许上传文件的大小(单位MB)</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="updatemaxsize" value="'.$review['updatemaxsize'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>启用文章内容图片缩略图</b><br/>.启用后所有抓取到的内容图片，都产生缩略图.</td>
				<td>
				<p style="position:relative;">
				<span class="clzhid thumbnail" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="thumbnail" value="'.$review['thumbnail'].'"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>文章图片缩略图大小设置</b><br/>.启用文章内容图片缩略图，立即生效.</td>
				<td>
				<p style="position:relative;">
				宽度 <input type="text" name="tbwidth" size="2" value="'.$review['tbwidth'].'"/>（单位：px）
				 &nbsp; &nbsp; 
				高度 <input type="text" name="tbheight" size="2" value="'.$review['tbheight'].'"/>（单位：px）
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>首页缓存</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid page" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;">页面缓存</span>
				<input type="hidden" name="pagcache" value="'.$review['pagcache'].'" class="pagedata"/>
				设置缓存时间：<input type="text" name="setTime" size="2" value="'.$review['setTime'].'"/>秒
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>启用开发模式</b><br/>.建议不开启.</td>
				<td>
				<p style="position:relative;">
				<span class="clzhid development" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="development" value="'.$review['development'].'"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>关闭网站</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid colses" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="closesite" value="'.$review['closesite'].'" class="pagecolses"/>
				</p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<input type="hidden" name="flag" value="1"/>
				<input type="hidden" name="act" value="SetGlobal"/>
				<input type="submit" value="提交" class="sub"/>
<script>
if( $("[name=development]").val() == "ON" )
{
	$(".development").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".development").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".development").click(function(){
if( $("[name=development]").val() == "OFF" )
{
	$("[name=development]").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$("[name=development]").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if( $("[name=thumbnail]").val() == "ON" )
{
	$(".thumbnail").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".thumbnail").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".thumbnail").click(function(){
if( $("[name=thumbnail]").val() == "OFF" )
{
	$("[name=thumbnail]").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$("[name=thumbnail]").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if( $(".pagedata").val() == "ON" )
{
	$(".page").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".page").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".page").click(function(){
if( $(".pagedata").val() == "OFF" )
{
	$(".pagedata").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pagedata").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if( $(".pagecolses").val() == "ON" )
{
	$(".colses").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".colses").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".colses").click(function(){
if( $(".pagecolses").val() == "OFF" )
{
	$(".pagecolses").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pagecolses").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});
</script>				
				</td>
			</tr>
		</table></form>
		<form action="handling_events.php" method="post">
		<table class="tableBox" style="display:none;">
			<tr>
				<td width="320"><b>列表页显示文章的数量</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="listtotal" value="'.$review['listtotal'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>允许搜索返回文章的最大数量</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="searchmaxtotal" value="'.$review['searchmaxtotal'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>后台每页文章显示数量</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="rowstotal" value="'.$review['rowstotal'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<input type="hidden" name="flag" value="2"/>
				<input type="hidden" name="act" value="SetPageTerm"/>
				<input type="submit" value="提交" class="sub"/>				
				</td>
			</tr>
		</table>
		</form>
			<form action="handling_events.php" method="post">
	<table class="tableBox">
			<tr>
				<td width="320"><b>启用评论过滤器功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid filter" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="filter" value="'.$review['filter'].'" />
				</td>
			</tr>
			<tr>
				<td width="320"><b>黑词列表</b><br>· 使用正则表达式，最后一个字符不能是“|”<br>· 启用评论过滤器功能，立即生效。</td>
				<td>
				<textarea name="blacklist" class="text-web">'.$review['blacklist'].'</textarea>
				</td>
			</tr>
			<tr>
				<td width="320"><b>敏感词列表 </b><br>· 使用正则表达式，最后一个字符不能是“|”<br>· 启用评论过滤器功能，立即生效。</td>
				<td>
				<textarea name="sensitivelist" class="text-web">'.$review['sensitivelist'].'</textarea>
				</td>
			</tr>
			<tr>
				<td width="320"><b>启用评论拦截IP功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid stopped" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="stopped" value="'.$review['stopped'].'"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>IP过滤列表</b><br>· 用|分隔，支持*屏蔽IP段<br>· 启用评论拦截IP功能，立即生效。</td>
				<td>
				<textarea name="ipfilterlist" class="text-web">'.$review['ipfilterlist'].'</textarea>
				</td>
			</tr>
			<tr>
				<td width="320"><b>关闭评论功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid pinglun" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="colsecomment" value="'.$review['colsecomment'].'" class="pagepl"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>审核评论</b><br/>打开后发布的评论都将进入审核状态</td>
				<td>
				<p style="position:relative;">
				<span class="clzhid shenhe" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="moderation" value="'.$review['moderation'].'" class="pageshenhe"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>启用评论倒序输出</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid pldese" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="sort" value="'.$review['sort'].'" class="pagepldese"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>启用评论验证码功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid virify" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="vifiy" value="'.$review['vifiy'].'" class="pagevirify"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>每页输出评论数量</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="listtotal" value="'.$review['listtotal'].'" class="input-web"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>非登录用户信息项</b></td>
				<td>
				<p style="">
				<input type="checkbox" name="talbox" value="1" ".$review["talbox"]==0?"":"checked=checked"." id="bo1"/>
				<label for="bo1">显示手机框(Tal)</label>				
				 &nbsp; &nbsp; &nbsp; 
				 <input type="checkbox" name="emailbox" ".$review["emailbox"]==0?"":"checked=checked"." value="1" id="bo2"/>
				<label for="bo2">显示邮箱框(email)</label>				
				 &nbsp; &nbsp; &nbsp; 
				 <input type="checkbox" name="qqbox" ".$review["qqbox"]==0?"":"checked=checked"." value="1" id="bo3"/>
				<label for="bo3">显示QQ框(QQ)</label>				
				</p>
				</td>
			</tr>		
			
			<tr>
				<td colspan="2">
				<input type="hidden" name="flag" value="3"/>
				<input type="hidden" name="act" value="ReviewMain"/>
				<input type="submit" value="提交" class="sub"/>
<script>
if( $("[name=colsecomment]").val() == "ON" )
{
	$(".pinglun").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pinglun").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}	
$(".pinglun").click(function(){
if( $("[name=colsecomment]").val() == "OFF" )
{
	$("[name=colsecomment]").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$("[name=colsecomment]").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if( $(".pageshenhe").val() == "ON" )
{
	$(".shenhe").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".shenhe").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".shenhe").click(function(){
if( $(".pageshenhe").val() == "OFF" )
{
	$(".pageshenhe").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pageshenhe").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if( $(".pagepldese").val() == "ON" )
{
	$(".pldese").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pldese").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".pldese").click(function(){
if( $(".pagepldese").val() == "OFF" )
{
	$(".pagepldese").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pagepldese").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if($("[name=stopped]").val()=="ON")
{
	$(".stopped").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".stopped").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".stopped").click(function(){
	if($("[name=stopped]").val()=="OFF")
	{
		$("[name=stopped]").val("ON");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
	}
	else
	{
		$("[name=stopped]").val("OFF");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
});

if($("[name=filter]").val()=="ON")
{
	$(".filter").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".filter").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".filter").click(function(){
	if($("[name=filter]").val()=="OFF")
	{
		$("[name=filter]").val("ON");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
	}
	else
	{
		$("[name=filter]").val("OFF");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
});

if( $(".pagevirify").val() == "ON" )
{
	$(".virify").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".virify").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".virify").click(function(){
if( $(".pagevirify").val() == "OFF" )
{
	$(".pagevirify").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pagevirify").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});
$(function(){
$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
$(".tableBox tr").hover(function(){
$(this).css({"background":"#FFFFDD"});
},function(){
$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
});
</script>				
				</td>
			</tr>
		</table>
		</form>
		
		</div>';
	$subject .= '<script>
	$(".tableBox").hide();
	var falgvals = "'.($_GET['flag']==''?0:$_GET['flag']).'";
	$(".tableBox:eq("+falgvals+")").show();
	$(".menuWeb li").removeClass("menuWebAction");
	$(".menuWeb li:eq("+falgvals+")").addClass("menuWebAction");
	$(function(){
		$(".menuWeb li").each(function(index){
		$(this).click(function(){
	$(this).addClass("menuWebAction").siblings().removeClass("menuWebAction");
	$(".tableBox").hide();
	$(".tableBox:eq("+index+")").show();
	$("[name=flag]:eq("+index+")").val(index);
});
});
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#网站设置
function SettingMng_phone()
{
	session_start();
	
	#基础设置
	$row = db()->select('*')->from(PRE.'setting')->get()->array_row();
	#评论设置
	$review = db()->select('*')->from(PRE.'review_up')->get()->array_row();
	#插件
	$chajian = db()->select('addmenu,themename')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>1,'themename'=>'comment'))->get()->array_row();
	
	$subject = '<div class="useredit_phone">';
	
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
		
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">gear_fill</i> 网站设置</div>
		<ul class="menuWeb">
		<li class="menuWebAction">其础设置</li>
		<li>全局设置</li>
		<li>页面设置</li>';
if( $chajian['addmenu'] == 'OFF' && $chajian['themename'] == 'comment')
{#评论插件	
	$subject .= '<li>评论设置</li>';
}	
	$subject .= '</ul>
		<form action="handling_events.php" method="post">
		<table class="tableBox">
			<tr>
				<td><b>网站地址</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;"><input type="text" name="link" value="'.$row['link'].'" disabled="disabled" style="cursor:not-allowed" class="input-web urlPath"/></p>
				<p style="position:relative;">
				<span class="clzhid guding" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
				<span class="clzhid" style="width:100px;margin-left:2px;">固定网站域名</span>
				<input type="hidden" name="addmenu" value="'.$row['addmenu'].'"/>
				</p>
				</td>
			</tr>
			<tr>
				<td><b>网站标题</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;"><input type="text" name="title" value="'.$row['title'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td><b>网站副标题</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;"><input type="text" name="alias" value="'.$row['alias'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td><b>关键字</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;"><input type="text" name="keywords" value="'.$row['keywords'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td><b>网站描述语</b></td>
				<td>
				<textarea name="description" class="text-web">'.$row['description'].'</textarea>
				</td>
			</tr>
			<tr>
				<td><b>版权说明</b></td>
				<td>
				<textarea name="copyright" class="text-web">'.$row['copyright'].'</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<input type="hidden" name="act" value="SettingMngBasic"/>
				<input type="submit" value="提交" class="sub"/>
<script>
$(".showerror").hide(2000);
if($("[name=addmenu]").val()=="OFF")
{
	$(".urlPath").removeAttr("disabled").css({"cursor":"auto"});
	$(".guding").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
else
{
	$(".urlPath").attr("disabled","disabled").css({"cursor":"not-allowed"});
	$(".guding").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
}
$(".guding").click(function(){
if($("[name=addmenu]").val()=="OFF" || $("[name=addmenu]").val()=="")
{
	$("[name=addmenu]").val("ON");
	$(".urlPath").attr("disabled","disabled").css({"cursor":"not-allowed"});
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$("[name=addmenu]").val("OFF");
	$(".urlPath").removeAttr("disabled").css({"cursor":"auto"});
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});
</script>
				</td>
			</tr>
		</table></form>
	<form action="handling_events.php" method="post">	
		<table class="tableBox" style="display:none;">
			<tr style="display:none;"><td></td></tr>
			<tr>
				<td><b>网站时区</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<select name="sitetimezone" class="input-web">
					<option value="Etc/GMT+12">Etc/GMT+12 -12:00</option>
					<option value="Pacific/Midway">Pacific/Midway -11:00</option>
					<option value="Pacific/Honolulu">Pacific/Honolulu -10:00</option>
					<option value="America/Anchorage">America/Anchorage -09:00</option>
					<option value="America/Los_Angeles">America/Los_Angeles -08:00</option>
					<option value="America/Denver">America/Denver -07:00</option>
					<option value="America/Tegucigalpa">America/Tegucigalpa -06:00</option>
					<option value="America/New_York">America/New_York -05:00</option>
					<option value="America/Halifax">America/Halifax -04:00</option>
					<option value="America/Argentina/Buenos_Aires">America/Argentina/Buenos_Aires -03:00</option>
					<option value="Atlantic/South_Georgia">Atlantic/South_Georgia -02:00</option>
					<option value="Atlantic/Azores">Atlantic/Azores -01:00</option>
					<option value="UTC">UTC 00:00</option>
					<option value="Europe/Berlin">Europe/Berlin +01:00</option>
					<option value="Europe/Sofia">Europe/Sofia +02:00</option>
					<option value="Africa/Nairobi">Africa/Nairobi +03:00</option>
					<option value="Europe/Moscow">Europe/Moscow +04:00</option>
					<option value="Asia/Karachi">Asia/Karachi +05:00</option>
					<option value="Asia/Dhaka">Asia/Dhaka +06:00</option>
					<option value="Asia/Bangkok">Asia/Bangkok +07:00</option>
					<option value="Asia/Shanghai" selected="selected">Asia/Shanghai +08:00</option>
					<option value="Asia/Tokyo">Asia/Tokyo +09:00</option>
					<option value="Pacific/Guam">Pacific/Guam +10:00</option>
					<option value="Australia/Sydney">Australia/Sydney +11:00</option>
					<option value="Pacific/Fiji">Pacific/Fiji +12:00</option>
					<option value="Pacific/Tongatapu">Pacific/Tongatapu +13:00</option>
					</select>
				</p>				
				</td>
			</tr>
			<tr>
				<td><b>网站语言</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<select name="weblanguage" class="input-web">
					<option value="zh-cn" selected="selected">简体中文 (zh-cn)</option>
				</select>
				</p>
				</td>
			</tr>
			<tr>
				<td><b>允许上传文件的类型</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="filestyle" value="'.$review['filestyle'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td><b>允许上传文件的大小(单位MB)</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="updatemaxsize" value="'.$review['updatemaxsize'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td><b>启用文章内容图片缩略图</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid thumbnail" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="thumbnail" value="'.$review['thumbnail'].'"/>
				</p>
				</td>
			</tr>
			<tr>
				<td><b>文章图片缩略图大小设置</b></td>
				<td>
				<p>宽度 <input type="text" name="tbwidth" size="2" value="'.$review['tbwidth'].'"/>（单位：px）</p>
				<p>高度 <input type="text" name="tbheight" size="2" value="'.$review['tbheight'].'"/>（单位：px）</p>
				</td>
			</tr>
			<tr>
				<td><b>首页缓存</b></td>
				<td>
				<p>
				<span class="clzhid page" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;">页面缓存</span>
				<input type="hidden" name="pagcache" value="'.$review['pagcache'].'" class="pagedata"/></p>
				<p>设置缓存时间：<input type="text" name="setTime" size="2" value="'.$review['setTime'].'"/>秒</p>
				</td>
			</tr>
			<tr>
				<td><b>启用开发模式</b><br/>.建议不开启.</td>
				<td>
				<span class="clzhid development" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="development" value="'.$review['development'].'"/>
				</td>
			</tr>
			<tr>
				<td><b>关闭网站</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid colses" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="closesite" value="'.$review['closesite'].'" class="pagecolses"/>
				</p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<input type="hidden" name="flag" value="1"/>
				<input type="hidden" name="act" value="SetGlobal"/>
				<input type="submit" value="提交" class="sub"/>
<script>
if( $("[name=development]").val() == "ON" )
{
	$(".development").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".development").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".development").click(function(){
if( $("[name=development]").val() == "OFF" )
{
	$("[name=development]").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$("[name=development]").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if( $("[name=thumbnail]").val() == "ON" )
{
	$(".thumbnail").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".thumbnail").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".thumbnail").click(function(){
if( $("[name=thumbnail]").val() == "OFF" )
{
	$("[name=thumbnail]").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$("[name=thumbnail]").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if( $(".pagedata").val() == "ON" )
{
	$(".page").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".page").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".page").click(function(){
if( $(".pagedata").val() == "OFF" )
{
	$(".pagedata").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pagedata").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if( $(".pagecolses").val() == "ON" )
{
	$(".colses").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".colses").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".colses").click(function(){
if( $(".pagecolses").val() == "OFF" )
{
	$(".pagecolses").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pagecolses").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});
</script>				
				</td>
			</tr>
		</table></form>
		<form action="handling_events.php" method="post">
		<table class="tableBox" style="display:none;">
			<tr>
				<td><b>列表页显示文章的数量</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="listtotal" value="'.$review['listtotal'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td><b>允许搜索返回文章的最大数量</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="searchmaxtotal" value="'.$review['searchmaxtotal'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td><b>后台每页文章显示数量</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="rowstotal" value="'.$review['rowstotal'].'" class="input-web"/></p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<input type="hidden" name="flag" value="2"/>
				<input type="hidden" name="act" value="SetPageTerm"/>
				<input type="submit" value="提交" class="sub"/>				
				</td>
			</tr>
		</table>
		</form>
			<form action="handling_events.php" method="post">
	<table class="tableBox">
			<tr>
				<td><b>启用评论过滤器功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid filter" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="filter" value="'.$review['filter'].'" />
				</td>
			</tr>
			<tr>
				<td><b>黑词列表</b></td>
				<td>
				<textarea name="blacklist" class="text-web">'.$review['blacklist'].'</textarea>
				</td>
			</tr>
			<tr>
				<td><b>敏感词列表 </b></td>
				<td>
				<textarea name="sensitivelist" class="text-web">'.$review['sensitivelist'].'</textarea>
				</td>
			</tr>
			<tr>
				<td><b>启用评论拦截IP功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid stopped" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="stopped" value="'.$review['stopped'].'"/>
				</p>
				</td>
			</tr>
			<tr>
				<td><b>IP过滤列表</b></td>
				<td>
				<textarea name="ipfilterlist" class="text-web">'.$review['ipfilterlist'].'</textarea>
				</td>
			</tr>
			<tr>
				<td><b>关闭评论功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid pinglun" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="colsecomment" value="'.$review['colsecomment'].'" class="pagepl"/>
				</p>
				</td>
			</tr>
			<tr>
				<td><b>审核评论</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid shenhe" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="moderation" value="'.$review['moderation'].'" class="pageshenhe"/>
				</p>
				</td>
			</tr>
			<tr>
				<td><b>启用评论倒序输出</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid pldese" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="sort" value="'.$review['sort'].'" class="pagepldese"/>
				</p>
				</td>
			</tr>
			<tr>
				<td><b>启用评论验证码功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid virify" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="vifiy" value="'.$review['vifiy'].'" class="pagevirify"/>
				</p>
				</td>
			</tr>
			<tr>
				<td><b>每页输出评论数量</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="listtotal" value="'.$review['listtotal'].'" class="input-web"/>
				</p>
				</td>
			</tr>
			<tr>
				<td><b>非登录用户信息项</b></td>
				<td>
				<p>
				<input type="checkbox" name="talbox" value="1" ".$review["talbox"]==0?"":"checked=checked"." id="bo1"/>
				<label for="bo1">显示手机框(Tal)</label>	
				</p>			
				<p>
				 <input type="checkbox" name="emailbox" ".$review["emailbox"]==0?"":"checked=checked"." value="1" id="bo2"/>
				<label for="bo2">显示邮箱框(email)</label>	
				</p>			
				<p> 
				 <input type="checkbox" name="qqbox" ".$review["qqbox"]==0?"":"checked=checked"." value="1" id="bo3"/>
				<label for="bo3">显示QQ框(QQ)</label>				
				</p>
				</td>
			</tr>		
			
			<tr>
				<td colspan="2">
				<input type="hidden" name="flag" value="3"/>
				<input type="hidden" name="act" value="ReviewMain"/>
				<input type="submit" value="提交" class="sub"/>
<script>
if( $("[name=colsecomment]").val() == "ON" )
{
	$(".pinglun").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pinglun").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}	
$(".pinglun").click(function(){
if( $("[name=colsecomment]").val() == "OFF" )
{
	$("[name=colsecomment]").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$("[name=colsecomment]").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if( $(".pageshenhe").val() == "ON" )
{
	$(".shenhe").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".shenhe").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".shenhe").click(function(){
if( $(".pageshenhe").val() == "OFF" )
{
	$(".pageshenhe").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pageshenhe").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if( $(".pagepldese").val() == "ON" )
{
	$(".pldese").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pldese").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".pldese").click(function(){
if( $(".pagepldese").val() == "OFF" )
{
	$(".pagepldese").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pagepldese").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});

if($("[name=stopped]").val()=="ON")
{
	$(".stopped").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".stopped").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".stopped").click(function(){
	if($("[name=stopped]").val()=="OFF")
	{
		$("[name=stopped]").val("ON");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
	}
	else
	{
		$("[name=stopped]").val("OFF");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
});

if($("[name=filter]").val()=="ON")
{
	$(".filter").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".filter").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".filter").click(function(){
	if($("[name=filter]").val()=="OFF")
	{
		$("[name=filter]").val("ON");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
	}
	else
	{
		$("[name=filter]").val("OFF");
		$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
	}
});

if( $(".pagevirify").val() == "ON" )
{
	$(".virify").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".virify").css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
$(".virify").click(function(){
if( $(".pagevirify").val() == "OFF" )
{
	$(".pagevirify").val("ON");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});	
}
else
{
	$(".pagevirify").val("OFF");
	$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
}
});
$(function(){
$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
$(".tableBox tr").hover(function(){
$(this).css({"background":"#FFFFDD"});
},function(){
$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
});
</script>				
				</td>
			</tr>
		</table>
		</form>
		
		</div>';
	$subject .= '<script>
	$(".tableBox").hide();
	var falgvals = "'.($_GET['flag']==''?0:$_GET['flag']).'";
	$(".tableBox:eq("+falgvals+")").show();
	$(".menuWeb li").removeClass("menuWebAction");
	$(".menuWeb li:eq("+falgvals+")").addClass("menuWebAction");
	$(function(){
		$(".menuWeb li").each(function(index){
		$(this).click(function(){
	$(this).addClass("menuWebAction").siblings().removeClass("menuWebAction");
	$(".tableBox").hide();
	$(".tableBox:eq("+index+")").show();
	$("[name=flag]:eq("+index+")").val(index);
});
});
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#用户编辑
function UserEdit()
{
		session_start();
		$userName = $_SESSION['username'];
		$row = db()->select('id,level,state,userName,alias,email,homepage,abst,Template,pic')->from(PRE.'login')->where(array('userName'=>mysql_escape_string($userName)))->get()->array_row();		
		#当前模板
		$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
		$subject = '
		<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit">
		<div class="userheader">用户编辑</div>
		<div class="userjibie">用户级别:</div>
		<div>
			<select name="level" class="input-x">
				<option value="0" '.($row['level']=='0'?'selected="selected"':'').'>管理员</option>
				<option value="1" '.($row['level']=='1'?'selected="selected"':'').'>网站编辑</option>
				<option value="2" '.($row['level']=='2'?'selected="selected"':'').'>作者</option>
				<option value="3" '.($row['level']=='3'?'selected="selected"':'').'>协作者</option>
				<option value="4" '.($row['level']=='4'?'selected="selected"':'').'>评论员</option>
				<option value="5" '.($row['level']=='5'?'selected="selected"':'').'>游客</option>
			</select>
			&nbsp;
			<span style="font-size:14px;">( <b>状态:</b>
			<input type="radio" name="state" value="0" '.($row['state']==0?'checked="checked"':'').' id="radio1"/> <label for="radio1">正常<label> &nbsp;
			<input type="radio" name="state" value="1" '.($row['state']==1?'checked="checked"':'').' id="radio2"/> <label for="radio2">审核<label> &nbsp;
			<input type="radio" name="state" value="2" '.($row['state']==2?'checked="checked"':'').' id="radio3"/> <label for="radio3">禁止<label> )
			</span>
		</div>
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="userName" value="'.$row['userName'].'" readonly="readonly"  class="input-s" style="background:#EEEEEE;"/></div>
		<div class="userjibie">密码: </div>
		<div><input type="password" name="pwd" value="" class="input-s"/></div>
		<div class="userjibie">确认密码: </div>
		<div><input type="password" name="pwd2" value="" class="input-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="alias" value="'.$row['alias'].'" class="input-s"/></div>
		<div class="userjibie">邮箱: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="email" value="'.$row['email'].'" class="input-s"/></div>
		<div class="userjibie">主页:</div>
		<div><input type="text" name="homepage" value="'.$row['homepage'].'" class="input-s"/></div>
		<div class="userjibie">摘要:</div>
		<div><textarea name="abst" class="input-w">'.$row['abst'].'</textarea></div>
		<div class="userjibie">模板:</div>
		<div>
			<select name="Template" class="input-x">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>
		<div class="userjibie">默认头像:</div>
		<div class="clear">
		<div class="touxiang1">
		<img src="'.($row['pic']==''?site_url('header/0.png'):site_url($row['pic'])).'" width="40" height="40"/>
		</div>
		<div class="touxiang2">本地更换</div> &nbsp; <span id="WenPic" style="color:#FF0000;"></span>
		<input type="file" id="tou_file" name="pic" style="display:none">
		</div>
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="id" value="'.$row['id'].'"/>
		<input type="hidden" name="act" value="MemberEdt"/>
		<input type="submit" value="提交" class="sub"/>
		</div>
	</div></form>';
	$subject .= '<script>
$(function(){
	$(".touxiang2").click(function(){
		document.getElementById("tou_file").click();	
	});
	$("body").mousemove(function(){
		if( $("[name=pic]").val() != "" )
		{
			$("#WenPic").html($("[name=pic]").val());
		}
		else
		{
			$("#WenPic").html("");
		}
	});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#用户编辑
function UserEdit_phone()
{
		session_start();
		$userName = $_SESSION['username'];
		$row = db()->select('id,level,state,userName,alias,email,homepage,abst,Template,pic')->from(PRE.'login')->where(array('userName'=>mysql_escape_string($userName)))->get()->array_row();		
		#当前模板
		$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
		$subject = '
		<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit_phone">
		<div class="userheader2_phone"><i class="f7-icons size-22">person_fill</i> 用户编辑</div>
		<div class="userjibie">用户级别:</div>
		<div>
			<select name="level" class="selens">
				<option value="0" '.($row['level']=='0'?'selected="selected"':'').'>管理员</option>
				<option value="1" '.($row['level']=='1'?'selected="selected"':'').'>网站编辑</option>
				<option value="2" '.($row['level']=='2'?'selected="selected"':'').'>作者</option>
				<option value="3" '.($row['level']=='3'?'selected="selected"':'').'>协作者</option>
				<option value="4" '.($row['level']=='4'?'selected="selected"':'').'>评论员</option>
				<option value="5" '.($row['level']=='5'?'selected="selected"':'').'>游客</option>
			</select>
			&nbsp;
			<span style="font-size:14px;">( <b>状态:</b>
			<input type="radio" name="state" value="0" '.($row['state']==0?'checked="checked"':'').' id="radio1"/> <label for="radio1">正常<label> &nbsp;
			<input type="radio" name="state" value="1" '.($row['state']==1?'checked="checked"':'').' id="radio2"/> <label for="radio2">审核<label> &nbsp;
			<input type="radio" name="state" value="2" '.($row['state']==2?'checked="checked"':'').' id="radio3"/> <label for="radio3">禁止<label> )
			</span>
		</div>
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="userName" value="'.$row['userName'].'" readonly="readonly"  class="inputs-s" style="background:#EEEEEE;"/></div>
		<div class="userjibie">密码: </div>
		<div><input type="password" name="pwd" value="" class="inputs-s"/></div>
		<div class="userjibie">确认密码: </div>
		<div><input type="password" name="pwd2" value="" class="inputs-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="alias" value="'.$row['alias'].'" class="inputs-s"/></div>
		<div class="userjibie">邮箱: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="email" value="'.$row['email'].'" class="inputs-s"/></div>
		<div class="userjibie">主页:</div>
		<div><input type="text" name="homepage" value="'.$row['homepage'].'" class="inputs-s"/></div>
		<div class="userjibie">摘要:</div>
		<div><textarea name="abst" class="input-w">'.$row['abst'].'</textarea></div>
		<div class="userjibie">模板:</div>
		<div>
			<select name="Template" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>
		<div class="userjibie">默认头像:</div>
		<div class="clear">
		<div class="touxiang1">
		<img src="'.($row['pic']==''?site_url('pic/defualt/0.png'):$row['pic']).'" width="40" height="40"/>
		</div>
		<div class="touxiang2">本地更换</div> &nbsp; <span id="WenPic" style="color:#FF0000;"></span>
		<input type="file" id="tou_file" name="pic" style="display:none">
		</div>
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="id" value="'.$row['id'].'"/>
		<input type="hidden" name="act" value="MemberEdt"/>
		<input type="submit" value="提交" class="subClass"/>
		</div>
	</div></form>';
	$subject .= '<script>
$(function(){
	$(".touxiang2").click(function(){
		document.getElementById("tou_file").click();	
	});
	$("body").mousemove(function(){
		if( $("[name=pic]").val() != "" )
		{
			$("#WenPic").html($("[name=pic]").val());
		}
		else
		{
			$("#WenPic").html("");
		}
	});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#后台导行组件
function CreateTopBar_parts_c()
{	
	session_start();
	
	$colorval = 'no';
	if(file_exists('MenuXml.xml')){
		$xml = simplexml_load_file("MenuXml.xml");
		$colorval = (string)$xml->vals;
	}
	#匹配权限
	if( isset($_SESSION['username']) && $_SESSION['username'] != null )
	{
		$row = db()->select('level')->from(PRE.'login')->where(array('userName'=>$_SESSION['username']))->get()->array_row();
	}
	$subject = '<ul class="menu_parts">
	<li class="kuozhao">
	<a href="index.php?act=admin">后台首页</a>
	</li>';
	if(!($row['level']==2||$row['level']==3||$row['level']==4||$row['level']==5))
	{
	$subject .= '<li class="kuozhao">
	<a href="index.php?act=SettingMng">网站设置</a>
	</li>';
	}
	$subject .= '<li><a href="'.apth_url('index.php').'" target="_blank">网站首页</a></li>
	</ul>';
	$subject .= '<script>
$(function(){
		var b ="'.$colorval.'";
		if(b != "no" )
		{
			$(".kuozhao:eq("+b+")").addClass("menu_action");
		}
		$(".kuozhao").each(function(index){
			$(this).click(function(){
				$.post("../external_request.php",{vals:index,act:"MenuStyle"},function(data){});
			});
		});	
		$(".menu_parts li").click(function(){
			$.post("../external_request.php",{vals:"no",act:"ChangeStyle"},function(data){});
		});
});
	</script>';
	return $subject;
}
###################################################################################
#后台导行菜单组件
function MenuComponent()
{
	session_start();
	
	$colorval = 'no';
	if(file_exists('ColorXml.xml')){
		$xml = simplexml_load_file("ColorXml.xml");
		$colorval = (string)$xml->vals;
	}
	#匹配权限
	if( isset($_SESSION['username']) && $_SESSION['username'] != null )
	{
		$row = db()->select('level')->from(PRE.'login')->where(array('userName'=>$_SESSION['username']))->get()->array_row();
	}
	$subject .= '<div class="menuc"><ul class="menucomponent">';
	if(!($row['level']==4||$row['level']==5))
	{
	$subject .= '<li><a href="index.php?act=ArticleEdt" style="background:url('.site_url('images/new_1.png').') no-repeat 20px 8px;">发布编辑</a></li>
		<li><a href="index.php?act=ArticleMng" style="background:url('.site_url('images/article_1.png').') no-repeat 20px 8px;">文档管理</a></li>';
	}
	if(!($row['level']==2||$row['level']==3||$row['level']==4||$row['level']==5))
	{
	$subject .= '<li><a href="index.php?act=PageMng" style="background:url('.site_url('images/page_1.png').') no-repeat 20px 8px;">栏目管理</a></li>';
	}
	$subject .= '</ul>';
	$subject .= '<ul class="menucomponent">';
	if(!($row['level']==4||$row['level']==5))
	{
	$subject .= '<li><a href="index.php?act=CategoryMng" style="background:url('.site_url('images/category_1.png').') no-repeat 20px 8px;">分类管理</a></li>
		<li><a href="index.php?act=TagMng" style="background:url('.site_url('images/tags_1.png').') no-repeat 20px 8px;">标签管理</a></li>';
	}
	if(!($row['level']==5))
	{
	$subject .= '<li><a href="index.php?act=CommentMng" style="background:url('.site_url('images/comments_1.png').') no-repeat 20px 8px;">评论管理</a></li>';
	}
	if(!($row['level']==2||$row['level']==3||$row['level']==4||$row['level']==5))
	{
	$subject .= '<li><a href="index.php?act=UploadMng" style="background:url('.site_url('images/accessories_1.png').') no-repeat 20px 8px;">附件管理</a></li>';
	}
	if(!($row['level']==1||$row['level']==2||$row['level']==3||$row['level']==4||$row['level']==5))
	{
		$subject .= '<li><a href="index.php?act=MemberMng" style="background:url('.site_url('images/user_1.png').') no-repeat 20px 8px;">用户管理</a></li>';
		$subject .= '<li><a href="index.php?act=OrderMng" style="background:url('.site_url('images/orders_1.png').') no-repeat 20px 8px;">客户管理</a></li>';
		$subject .= '<li><a href="index.php?act=Resources" style="background:url('.site_url('images/resources_1.png').') no-repeat 20px 8px;">资源管理</a></li>';
	}
	$subject .= '</ul>';
	$subject .= '<ul class="menucomponent">';
	if(!($row['level']==2||$row['level']==3||$row['level']==4||$row['level']==5))
	{
	$subject .= '<li><a href="index.php?act=ThemeMng" style="background:url('.site_url('images/themes_1.png').') no-repeat 20px 8px;">主题管理</a></li>';
	}
	if(!($row['level']==2||$row['level']==3||$row['level']==4||$row['level']==5))
	{
	$subject .= '<li><a href="index.php?act=ModuleMng" style="background:url('.site_url('images/link_1.png').') no-repeat 20px 8px;">模块管理</a></li>
		<li><a href="index.php?act=PluginMng" style="background:url('.site_url('images/plugin_1.png').') no-repeat 20px 8px;">插件管理</a></li>
		<li><a href="index.php?act=ApplicationCenter" style="background:url('.site_url('images/Cube1_1.png').') no-repeat 20px 8px;">应用中心</a></li>';
	}
	$subject .= '</ul></div>';
	$subject .= '<script>
		$(function(){
		var imgName = new Array("new","article","page","category","tags","comments","accessories","user","orders","resources","themes","link","plugin","Cube1");		
		var i="'.$colorval.'";
		if(i != "no")
		{
			$(".menuc li:eq("+i+")").addClass("mcAtion").siblings().removeClass("mcAtion");
			$(".menuc a:eq("+i+")").css({"color":"#FFFFFF","background":"url('.site_url('images/"+imgName[i]+"_2.png').') no-repeat 20px 8px"});	
			$(".userheader").css({"background":"url('.site_url('images/"+imgName[i]+"_32.png').') no-repeat 5px 12px"});
		}
		$(".menuc li").each(function(index){
	$(this).click(function(){
		$.post("../external_request.php",{vals:index,act:"ChangeStyle"},function(data){});
		$.post("../external_request.php",{vals:"no",act:"MenuStyle"},function(data){});
});
});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#外边框架T形框架
function OuterFrame($top,$left,$right)
{
	$data = file_get_contents('../pingmupixels.xml');
	$pixels = simplexml_load_string($data);
	
	$w = (int)$pixels->pixels;
	$act = (string)$pixels->call;
	$act = explode('_', $act);
	
	echo '<div style="border-bottom:0px solid red;position:absolute;top:0;left:0;width:100%;" id="topBox">'.$top.'</div>';
	echo '<div style="position:absolute;top:85px;left:0;width:140px;" id="leftBox">'.$left.'</div>';
	echo '<div id="leftBox_phone">
			<ul class="clear">
				<li><a href="'.apth_url('index.php').'"><i class="f7-icons size-29" class="icons_block">paper_plane</i><small class="icons_block">网站首页</small></a></li>
				<li><a href="index.php?act=admin_phone"><i class="f7-icons size-29" class="icons_block">home</i><small class="icons_block">后台首面</small></a></li>
				<li><a href="index.php?act=SettingMng_phone"><i class="f7-icons size-29" class="icons_block">gear</i><small class="icons_block">网站设置</small></a></li>		
				<li><a href="index.php?act=ArticleEdt_phone"><i class="f7-icons size-29" class="icons_block">compose</i><small class="icons_block">发布编辑</small></a></li>
				<li><a href="index.php?act=ArticleMng_phone"><i class="f7-icons size-29" class="icons_block">document</i><small class="icons_block">文档管理</small></a></li>
				<li><a href="index.php?act=PageMng_phone"><i class="f7-icons size-29" class="icons_block">calendar</i><small class="icons_block">栏目管理</small></a></li>
				<li><a href="index.php?act=CategoryMng_phone"><i class="f7-icons size-29" class="icons_block">folder</i><small class="icons_block">分类管理</small></a></li>
				<li><a href="index.php?act=TagMng_phone"><i class="f7-icons size-29" class="icons_block">tags</i><small class="icons_block">标签管理</small></a></li>
				<li><a href="index.php?act=CommentMng_phone"><i class="f7-icons size-29" class="icons_block">chat</i><small class="icons_block">评论管理</small></a></li>
				<li><a href="index.php?act=UploadMng_phone"><i class="f7-icons size-29" class="icons_block">drawers</i><small class="icons_block">附件管理</small></a></li>
				<li><a href="index.php?act=MemberMng_phone"><i class="f7-icons size-29" class="icons_block">person</i><small class="icons_block">用户管理</small></a></li>			
				<li><a href="index.php?act=OrderMng_phone"><i class="f7-icons size-29" class="icons_block">persons</i><small class="icons_block">客户管理</small></a></li>
				<li><a href="index.php?act=ThemeMng_phone"><i class="f7-icons size-29" class="icons_block">collection</i><small class="icons_block">主题管理</small></a></li>
				<li><a href="index.php?act=PluginMng_phone"><i class="f7-icons size-29" class="icons_block">ticket</i><small class="icons_block">插件管理</small></a></li>
			</ul>
		</div>';
	if( $w > 1250 )
	{
		echo '<div style="border-left:0px solid red;position:absolute;top:85px;left:145px;width:88%;" id="RightBox">'.$right.'</div>';
	}
	else
	{
		echo '<div id="RightBox_phone">'.$right.'</div>';
	}
	echo '<script>
	var glos_alls = "";
	var BodyWidth = "";
	if( glos_alls == "" ){
		BodyWidth = $("body").width();
		$.post("../external_request.php",{act:"pingmupixels",pixels:BodyWidth,call:"'.$_GET['act'].'"},function(d){});	
	}
	window.onresize = function(){	
		BodyWidth = $("body").width();
		//var LeftWidth = $("#leftBox").width();
		//var RightWidth = (BodyWidth-LeftWidth)-50;
		//$("#topBox").css({"width":BodyWidth+"px"});
		//$("#RightBox").css({"width":RightWidth+"px"});
		$.post("../external_request.php",{act:"pingmupixels",pixels:BodyWidth,call:"'.$_GET['act'].'"},function(d){});
		glos_alls = "onresize";	
	}	
	</script>';
}
###################################################################################
#评论管理
function CommentMng()
{	
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	
	#当前模板
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
		
	if( $_GET['ischecking'] != '1' )//已审核
	{
		$sql = 'select id,pid,likes,report,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state,stopped ';
		$sql .= ' from '.PRE.'review ';
		$sql .= ' where state=0 and templateid='.$theme['id'].' '; 
		if( $_GET['s'] !='' )
		{
			$num = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];
			$sql .= ' and name like "%'.$_GET['s'].'%" ';
		}
		$rowsTotal = db()->query($sql)->array_nums();
		$showTotal = $num;
		$pageTotal = ceil($rowsTotal/$showTotal);
		$page = $_GET['page']==''?'1':$_GET['page'];
		if($page>=$pageTotal){$page=$pageTotal;}
		if($page<=1||!is_numeric($page)){$page=1;}
		$offset = ($page-1)*$showTotal;
		$offset = $offset.','.$showTotal;
		
		$sql .= " order by publitime desc limit {$offset} ";
		$rows1 = db()->query($sql)->array_rows();
	}
	else 
	{
		$sql = 'select id,pid,likes,report,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state,stopped ';
		$sql .= ' from '.PRE.'review ';
		$sql .= ' where state=1 and templateid='.$theme['id'].' '; 
		if( $_GET['s'] !='' )
		{
			$sql .= ' and name like "%'.$_GET['s'].'%" ';
		}
		$rowsTotal = db()->query($sql)->array_nums();
		$showTotal = 12;
		$pageTotal = ceil($rowsTotal/$showTotal);
		$page = $_GET['page']==''?'1':$_GET['page'];
		if($page>=$pageTotal){$page=$pageTotal;}
		if($page<=1||!is_numeric($page)){$page=1;}
		$offset = ($page-1)*$showTotal;
		$offset = ($page-1)*$showTotal;
		$offset = $offset.','.$showTotal;
		
		$sql .= " order by publitime desc limit {$offset} ";
		$rows2 = db()->query($sql)->array_rows();
	}
	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}		
	$subject .= '<div class="userheader" style="border:none;">评论管理</div>
		<ul class="menuWeb clear">
		<li class="menuWebAction" style="margin-right:5px;"><a href="index.php?act=CommentMng">'.($rows1==''?'已审核评论':'已审核评论('.count($rows1).')').'</a></li>
		<li class="menuWebAction"><a href="index.php?act=CommentMng&ischecking=1">'.($rows2==''?'未审核评论':'未审核评论('.count($rows2).')').'</a></li>
		</ul>
		<div class="newsTsBox" style="margin-top:8px;">
		<span>搜索: </span>
		&nbsp; 
		<span><input type="text" name="s" value="" class="rinput" style="width:450px;"/></span> &nbsp; &nbsp;
		<span>
		<input type="submit" value="搜索" class="sub SearCh"/>
		</span>
		</div>';
	if( $_GET['ischecking'] != '1' )//已审核
	{	
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">上层ID</th>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;正文</th>
				<th style="text-align:center;">点赞</th>
				<th style="text-align:center;">举报</th>
				<th style="text-align:center;">日期</th>
				<th style="text-align:center;">文章</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
			</tr><form action="handling_events.php?act=CommentMngUplist" name="frm" method="post" class="frm">';
if( !empty( $rows1 ) )
{	
	foreach( $rows1 as $k => $v )
	{		
		$subject .= '<tr>
				<td width="60">'.$v['id'].'</td>
				<td width="60">'.$v['pid'].'</td>
				<td width="80">'.$v['name'].'</td>
				<td width="400"><a href="'.apth_url('?act=article_content&id='.$v['titleid']).'" target="_blank" class="hoverstyle"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.subString($v['body'],25).'</td>				
				<td width="40">'.$v['likes'].'</td>
				<td width="40">'.$v['report'].'</td>
				<td width="140">'.$v['publitime'].'</td>
				<td width="50">'.$v['titleid'].'</td>		
				<td style="text-align:center;">
					<a href="javascript:;" onclick="conf('.$v['id'].');" class="hoverstyle" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
					 &nbsp; &nbsp; 
					<a href="handling_events.php?act=CommentMngUp&id='.$v['id'].'" class="hoverstyle" title="审核"><img src="'.site_url('images/minus-shield.png').'" align="absmiddle"/></a>
					 &nbsp; &nbsp; 
					<a href="handling_events.php?act=CommentMngIp&id='.$v['id'].'" class="hoverstyle" title="拦截IP-'.($v['stopped']=='0'?'未拦截':'已拦截').'"><img src="'.site_url('images/exclamation.png').'" align="absmiddle"/></a>
				</td>
				<td width="80" style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}	
}			
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
		</form><tr>
				<td colspan="10">
				<span style="font-size:15px;color:#666666;">总数:'.$rowsTotal.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$pageTotal.'页</span>
				&nbsp; ';
if( $rowsTotal >= $showTotal )
{		
		$subject .= '<a href="index.php?act=CommentMng&page='.($page-1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; <a href="index.php?act=CommentMng&page='.($page+1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub subgoto1"/>';
}		
		$subject .= '</span>
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/> &nbsp; &nbsp; <input type="submit" value="审核选项" class="sub shencha1"/></span>';
		
		$subject .= '</td>
			</tr>';
		
		$subject .= '</table>';
	}
	else //未审核
	{
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">上层ID</th>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;正文</th>
				<th style="text-align:center;">文章</th>
				<th style="text-align:center;">点赞</th>
				<th style="text-align:center;">举报</th>
				<th style="text-align:center;">日期</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
			</tr><form action="handling_events.php?act=CommentMngUplist" name="frm" method="post" class="frm">';
if( !empty( $rows2 ) )
{	
	foreach( $rows2 as $k => $v )
	{			
	$subject .= '<tr>
				<td width="60">'.$v['id'].'</td>
				<td width="60">'.$v['pid'].'</td>
				<td width="80">'.$v['name'].'</td>
				<td width="400"><a href="#"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.$v['body'].'</td>
				<td width="50">'.$v['titleid'].'</td>
				<td width="40">'.$v['likes'].'</td>
				<td width="40">'.$v['report'].'</td>
				<td width="140">'.$v['publitime'].'</td>		
				<td style="text-align:center;">
					<a href="javascript:;" onclick="conf('.$v['id'].')" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
					 &nbsp; &nbsp; 
					<a href="handling_events.php?act=CommentMngUp&id='.$v['id'].'&ischecking=1"  title="审核"><img src="'.site_url('images/ok.png').'" align="absmiddle"/></a>
					 &nbsp; &nbsp; 
					<a href="handling_events.php?act=CommentMngIp&id='.$v['id'].'&ischecking=1"  title="拦截IP-'.($v['stopped']=='0'?'未拦截':'已拦截').'"><img src="'.site_url('images/exclamation.png').'" align="absmiddle"/></a>
				</td>
				<td width="80" style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}
}			
			
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
				<input type="hidden" name="ischecking" value="1"/>
		</form><tr>
				<td colspan="10">
				<span style="font-size:15px;color:#666666;">总页数:'.$pageTotal.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$pageTotal.'页</span>
				&nbsp; ';
if( $rowsTotal >= $showTotal )
{		
		$subject .= '<a href="index.php?act=CommentMng&ischecking=1&page='.($page-1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; <a href="index.php?act=CommentMng&ischecking=1&page='.($page+1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub subgoto1"/>';
}		
		$subject .= '</span>
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/> &nbsp; &nbsp; <input type="submit" value="审核选项" class="sub shencha1"/></span>';
		
		$subject .= '</td>
			</tr>';
		
	$subject .= '</table>';
	}	
	$subject .= '</div>';
	$subject .= '<script>
	function conf(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=CommentMngDelete&id="+id;
		}
	}
	var fs = "'.($_GET['ischecking']==''?'':$_GET['ischecking']).'";
	$(".SearCh").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=CommentMng&s="+$("[name=s]").val();
		}
		else
		{
			location.href="index.php?act=CommentMng&ischecking=1&s="+$("[name=s]").val();
		}
	});
	var s = "'.($_GET['s']==''?'':'&s='.$_GET['s']).'";
	$(".subgoto1").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=CommentMng&page="+$("[name=page]").val()+s;
		}
		else
		{
			location.href="index.php?act=CommentMng&ischecking=1&page="+$("[name=page]").val()+s;
		}
	});
	$(function(){
		$(".shencha1").click(function(){
			$("[name=flag3]").val("3");
			document.frm.submit();
		});
		$(".shencha2").click(function(){
			$("[name=flag3]").val("2");
			document.frm.submit();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	$(function(){
$(".selcetAll").click(function(){
if(!$(".selCheck").is(":checked"))
{
	$(".selCheck").attr({"checked":"true"});
}
else
{
	$(".selCheck").removeAttr("checked");
}
});
});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#评论管理
function CommentMng_phone()
{	
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	
	#当前模板
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	
	if( $_GET['ischecking'] != '1' )//已审核
	{
		$sql = 'select id,pid,likes,report,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state,stopped ';
		$sql .= ' from '.PRE.'review ';
		$sql .= ' where state=0 and templateid='.$theme['id'].' '; 
		if( $_GET['s'] !='' )
		{
			$num = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];
			$sql .= ' and name like "%'.$_GET['s'].'%" ';
		}
		$rowsTotal = db()->query($sql)->array_nums();
		$showTotal = $num;
		$pageTotal = ceil($rowsTotal/$showTotal);
		$page = $_GET['page']==''?'1':$_GET['page'];
		if($page>=$pageTotal){$page=$pageTotal;}
		if($page<=1||!is_numeric($page)){$page=1;}
		$offset = ($page-1)*$showTotal;
		$offset = $offset.','.$showTotal;
		
		$sql .= " order by publitime desc limit {$offset} ";
		$rows1 = db()->query($sql)->array_rows();
	}
	else 
	{
		$sql = 'select id,pid,likes,report,name,tal,email,qq,body,pic,FROM_UNIXTIME(publitime) as publitime,visitorip,titleid,vifiy,filter,state,stopped ';
		$sql .= ' from '.PRE.'review ';
		$sql .= ' where state=1 and templateid='.$theme['id'].' '; 
		if( $_GET['s'] !='' )
		{
			$sql .= ' and name like "%'.$_GET['s'].'%" ';
		}
		$rowsTotal = db()->query($sql)->array_nums();
		$showTotal = $num;
		$pageTotal = ceil($rowsTotal/$showTotal);
		$page = $_GET['page']==''?'1':$_GET['page'];
		if($page>=$pageTotal){$page=$pageTotal;}
		if($page<=1||!is_numeric($page)){$page=1;}
		$offset = ($page-1)*$showTotal;
		$offset = ($page-1)*$showTotal;
		$offset = $offset.','.$showTotal;
		
		$sql .= " order by publitime desc limit {$offset} ";
		$rows2 = db()->query($sql)->array_rows();
	}
	$subject = '<div class="useredit_phone">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}		
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">chat_fill</i> 评论管理</div>
		<ul class="menuWeb clear">
		<li class="menuWebAction" style="margin-right:5px;"><a href="index.php?act=CommentMng_phone">'.($rows1==''?'已审核评论':'已审核评论('.count($rows1).')').'</a></li>
		<li class="menuWebAction"><a href="index.php?act=CommentMng_phone&ischecking=1">'.($rows2==''?'未审核评论':'未审核评论('.count($rows2).')').'</a></li>
		</ul>
		<div class="newsTsBox_phone" style="margin-top:8px;">
		<p>
		<span><input type="text" name="s" value="" class="rinput" style="width:100%;"/></span>
		</p>
		<p>
		<span>
		<input type="submit" value="搜索" class="subClass SearCh"/>
		</span>
		</p>
		</div>';
	if( $_GET['ischecking'] != '1' )//已审核
	{	
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;正文</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
			</tr><form action="handling_events.php?act=CommentMngUplist" name="frm" method="post" class="frm">';
if( !empty( $rows1 ) )
{	
	foreach( $rows1 as $k => $v )
	{		
		$subject .= '<tr>
				<td><a href="'.apth_url('?act=article_content&id='.$v['titleid']).'" target="_blank" class="hoverstyle"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.subString($v['body'],25).'</td>					
				<td style="text-align:center;">
				<p class="art_floats">
					<a href="javascript:;" onclick="conf('.$v['id'].');" class="hoverstyle" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</p>
				<p class="art_floats">	
					<a href="handling_events.php?act=CommentMngUp&id='.$v['id'].'" class="hoverstyle" title="审核"><img src="'.site_url('images/minus-shield.png').'" align="absmiddle"/></a>
				</p>
				<p class="art_floats">	 
					<a href="handling_events.php?act=CommentMngIp&id='.$v['id'].'" class="hoverstyle" title="拦截IP-'.($v['stopped']=='0'?'未拦截':'已拦截').'"><img src="'.site_url('images/exclamation.png').'" align="absmiddle"/></a>
				</p>
				</td>
				<td style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}	
}			
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
		</form><tr>
				<p>
				<td colspan="3">
				<span style="font-size:15px;color:#666666;">总数:'.$rowsTotal.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$pageTotal.'页</span>
				</p>';
if( $rowsTotal >= $showTotal )
{		
		$subject .= '<p style="height:50px;line-height:50px;"><a class="a_href" href="index.php?act=CommentMng_phone&page='.($page-1).($_GET['s']==''?'':'&s='.$_GET['s']).'">上一页</a>  
				<a class="a_href" href="index.php?act=CommentMng_phone&page='.($page+1).($_GET['s']==''?'':'&s='.$_GET['s']).'">下一页</a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px;height:25px;"/>页 &nbsp; 
				<input type="submit" value="GO" id="GO" class="subgoto1"/></p>';
}		
		$subject .= '</span><p style="height:50px;line-height:50px;">
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/> &nbsp; &nbsp; <input type="submit" value="审核选项" class="sub shencha1"/></span>';
		
		$subject .= '</p></td>
			</tr>';
		
		$subject .= '</table>';
	}
	else //未审核
	{
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;正文</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
			</tr><form action="handling_events.php?act=CommentMngUplist" name="frm" method="post" class="frm">';
if( !empty( $rows2 ) )
{	
	foreach( $rows2 as $k => $v )
	{			
	$subject .= '<tr>
				<td><a href="#"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.$v['body'].'</td>	
				<td style="text-align:center;">
					<p class="art_floats">
					<a class="a_href" href="javascript:;" onclick="conf('.$v['id'].')" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
					</p>
					<p class="art_floats">
					<a class="a_href" href="handling_events.php?act=CommentMngUp&id='.$v['id'].'&ischecking=1"  title="审核"><img src="'.site_url('images/ok.png').'" align="absmiddle"/></a>
					</p>
					<p class="art_floats"> 
					<a class="a_href" href="handling_events.php?act=CommentMngIp&id='.$v['id'].'&ischecking=1"  title="拦截IP-'.($v['stopped']=='0'?'未拦截':'已拦截').'"><img src="'.site_url('images/exclamation.png').'" align="absmiddle"/></a>
					</p>
				</td>
				<td style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}
}			
			
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
				<input type="hidden" name="ischecking" value="1"/>
		</form><tr>
				<td colspan="3">
				<p>
				<span style="font-size:15px;color:#666666;">总页数:'.$rowsTotal.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$pageTotal.'页</span>
				</p>';
if( $rowsTotal >= $showTotal )
{		
		$subject .= '<p><a class="a_href" href="index.php?act=CommentMng_phone&ischecking=1&page='.($page-1).($_GET['s']==''?'':'&s='.$_GET['s']).'">上一页</a> 
					<a class="a_href" href="index.php?act=CommentMng_phone&ischecking=1&page='.($page+1).($_GET['s']==''?'':'&s='.$_GET['s']).'">下一页</a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px;height:25px;"/>页 &nbsp; 
				<input type="submit" value="GO" id="GO" class="subgoto1"/></p>';
}		
		$subject .= '</span><p style="height:50px;line-height:50px;">
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/> &nbsp; &nbsp; <input type="submit" value="审核选项" class="sub shencha1"/></span>';
		
		$subject .= '</p></td>
			</tr>';
		
	$subject .= '</table>';
	}	
	$subject .= '</div>';
	$subject .= '<script>
	function conf(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=CommentMngDelete&id="+id;
		}
	}
	var fs = "'.($_GET['ischecking']==''?'':$_GET['ischecking']).'";
	$(".SearCh").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=CommentMng_phone&s="+$("[name=s]").val();
		}
		else
		{
			location.href="index.php?act=CommentMng_phone&ischecking=1&s="+$("[name=s]").val();
		}
	});
	var s = "'.($_GET['s']==''?'':'&s='.$_GET['s']).'";
	$(".subgoto1").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=CommentMng_phone&page="+$("[name=page]").val()+s;
		}
		else
		{
			location.href="index.php?act=CommentMng_phone&ischecking=1&page="+$("[name=page]").val()+s;
		}
	});
	$(function(){
		$(".shencha1").click(function(){
			$("[name=flag3]").val("3");
			document.frm.submit();
		});
		$(".shencha2").click(function(){
			$("[name=flag3]").val("2");
			document.frm.submit();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	$(function(){
$(".selcetAll").click(function(){
if(!$(".selCheck").is(":checked"))
{
	$(".selCheck").attr({"checked":"true"});
}
else
{
	$(".selCheck").removeAttr("checked");
}
});
});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
function get_back_all($back,$key_val)
{#补助功能
	if( strrpos($back, '?') != false )
	{
		$url = $back.'&'.$key_val;
	}
	else 
	{
		$url = $back.'?'.$key_val;
	}
	return $url;
}
#客户管理
function OrderMng()
{
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	
	if( $_GET['ischecking'] != '1' )//订单管理
	{
		if( strlen($_GET['s'])==11 && preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_GET['s']))
		{#手机搜索
			$phone = $_GET['s'];
		}
		elseif(strrpos($_GET['s'], '@')!==false)
		{
			$email = $_GET['s'];
		}
		else 
		{
			$ordernumber = $_GET['s'];
		}
		$array = array(
			'phone'=>$phone,
			'email'=>$email,
			'ordernumber'=>$ordernumber
		);
		$getOrderInfo =  getOrderInfo(null,true,$page,$array);	
	}
	else 
	{#留言管理
		if( strlen($_GET['s'])==11 && preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_GET['s']))
		{#手机搜索
			$phone = $_GET['s'];
		}
		elseif(strrpos($_GET['s'], '@')!==false)
		{
			$email = $_GET['s'];
		}
		else 
		{
			$name = $_GET['s'];
		}
		$array = array(
			'phone'=>$phone,
			'email'=>$email,
			'name'=>$name
		);
		$page = $_GET['page']==''?1:$_GET['page'];
		$MessageInfo = getMessageInfo(null,true,$page,$array);	
	}
	
	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}		
	$subject .= '<div class="userheader" style="border:none;">客户管理</div>
		<ul class="menuWeb clear">
		<li class="menuWebAction" style="margin-right:5px;"><a href="index.php?act=OrderMng">订单管理'.($getOrderInfo['totaRrows']==''?'':'('.$getOrderInfo['totaRrows'].')').'</a></li>
		<li class="menuWebAction"><a href="index.php?act=OrderMng&ischecking=1">留言管理'.($MessageInfo['totaRrows']==''?'':'('.$MessageInfo['totaRrows'].')').'</a></li>
		</ul>
		<div class="newsTsBox" style="margin-top:8px;">
		<span>'.($_GET["ischecking"]==1?'<font color="#999999">姓名/手机/邮箱</font>':'<font color="#999999">订单号/手机/邮箱</font>').' 搜索: </span>
		&nbsp; 
		<span><input type="text" name="s" value="" class="rinput" style="width:450px;"/></span> &nbsp; &nbsp;
		<span>
		<input type="submit" value="搜索" class="sub SearCh"/>
		</span>
		</div>';
	if( $_GET['ischecking'] != '1' )//已审核
	{	
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">商品名称</th>			
				<th style="text-align:center;">订单号</th>
				<th style="text-align:center;">应付金额</th>
				<th style="text-align:center;">手机&邮箱</th>
				<th style="text-align:center;">订单日期</th>
				<th style="text-align:center;">邮寄地址</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
				</tr>
				
				<form action="handling_events.php?act=MessageInfolist" name="frm" method="post" class="frm">';
if(!empty($getOrderInfo['data']))
{		
	foreach($getOrderInfo['data'] as $k=>$v)
	{
		$subject .= '<tr>
				<td>'.$v['id'].'</td>
				<td>'.$v['commodity'].'</td>			
				<td>'.$v['ordernumber'].'</td>
				<td>'.$v['money'].'</td>
				<td>'.$v['phone'].($v['email']==''?'':' / '.$v['email']).'</td>
				<td>'.$v['publitime'].'</td>
				<td>'.$v['address'].'</td>			
				<td style="text-align:center;">
					<a href="javascript:;" onclick="conf1('.$v['id'].');" class="hoverstyle" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
				<td width="80" style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}	
}		
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
		</form><tr>
				<td colspan="10">
				<span style="font-size:15px;color:#666666;">总数:'.$getOrderInfo['totaRrows'].'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$getOrderInfo['page'].'/'.$getOrderInfo['totalpage'].'页</span>
				&nbsp; ';
if( $getOrderInfo['totaRrows'] > $getOrderInfo['totalshow'] )
{	
		$subject .= '<a href="index.php?act=OrderMng&page='.($getOrderInfo['page']-1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; <a href="index.php?act=OrderMng&page='.($getOrderInfo['page']+1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub subgoto1"/>';
}		
		$subject .= '</span>
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/></span>';
		
		$subject .= '</td>
			</tr>';
		
		$subject .= '</table>';
	}
	else //未审核
	{
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">PID</th>
				<th style="text-align:center;">name</th>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;正文</th>
				<th style="text-align:center;">手机</th>
				<th style="text-align:center;">邮箱</th>
				<th style="text-align:center;">日期</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
			</tr>
<form action="handling_events.php?act=MessageInfolist" name="frm" method="post" class="frm">';
if(!empty($MessageInfo['data'])){	
	foreach($MessageInfo['data'] as $k=>$v){	
	$subject .= '<tr>
				<td>'.$v['id'].'</td>
				<td>'.$v['pid'].'</td>
				<td>'.$v['name'].'</td>
				<td><a href="'.get_back_all($v['back'],'userid='.$v['id']).'" target="_blank"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.$v['body'].'</td>
				<td>'.$v['phone'].'</td>
				<td>'.$v['email'].'</td>
				<td>'.$v['publitime'].'</td>	
				<td style="text-align:center;">
					<a href="javascript:;" onclick="conf('.$v['id'].')" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
				<td width="80" style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}		
}			
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
				<input type="hidden" name="ischecking" value="1"/>
		</form><tr>
				<td colspan="10">
				<span style="font-size:15px;color:#666666;">总页数:'.$MessageInfo['totaRrows'].'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$MessageInfo['page'].'/'.$MessageInfo['totalpage'].'页</span>
				&nbsp; ';
if( $MessageInfo['totaRrows'] > $MessageInfo['totalshow'] )
{		
		$subject .= '<a href="index.php?act=OrderMng&ischecking=1&page='.($MessageInfo['page']-1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; <a href="index.php?act=OrderMng&ischecking=1&page='.($MessageInfo['page']+1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub subgoto1"/>';
}		
		$subject .= '</span>
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/></span>';
		
		$subject .= '</td>
			</tr>';
		
	$subject .= '</table>';
	}	
	$subject .= '</div>';
	$subject .= '<script>
	function conf(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=MessageInfoDelete&id="+id;
		}
	}
	function conf1(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=OrderInfoDelete&id="+id;
		}
	}
	var fs = "'.($_GET['ischecking']==''?'':$_GET['ischecking']).'";
	$(".SearCh").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=OrderMng&s="+$("[name=s]").val();
		}
		else
		{
			location.href="index.php?act=OrderMng&ischecking=1&s="+$("[name=s]").val();
		}
	});
	var s = "'.($_GET['s']==''?'':'&s='.$_GET['s']).'";
	$(".subgoto1").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=OrderMng&page="+$("[name=page]").val()+s;
		}
		else
		{
			location.href="index.php?act=OrderMng&ischecking=1&page="+$("[name=page]").val()+s;
		}
	});
	$(function(){
		$(".shencha1").click(function(){
			$("[name=flag3]").val("3");
			document.frm.submit();
		});
		$(".shencha2").click(function(){
			$("[name=flag3]").val("2");
			document.frm.submit();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	$(function(){
$(".selcetAll").click(function(){
if(!$(".selCheck").is(":checked"))
{
	$(".selCheck").attr({"checked":"true"});
}
else
{
	$(".selCheck").removeAttr("checked");
}
});
});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#客户管理
function OrderMng_phone()
{
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	
	if( $_GET['ischecking'] != '1' )//订单管理
	{
		if( strlen($_GET['s'])==11 && preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_GET['s']))
		{#手机搜索
			$phone = $_GET['s'];
		}
		elseif(strrpos($_GET['s'], '@')!==false)
		{
			$email = $_GET['s'];
		}
		else 
		{
			$ordernumber = $_GET['s'];
		}
		$array = array(
			'phone'=>$phone,
			'email'=>$email,
			'ordernumber'=>$ordernumber
		);
		$getOrderInfo =  getOrderInfo(null,true,$page,$array);	
	}
	else 
	{#留言管理
		if( strlen($_GET['s'])==11 && preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_GET['s']))
		{#手机搜索
			$phone = $_GET['s'];
		}
		elseif(strrpos($_GET['s'], '@')!==false)
		{
			$email = $_GET['s'];
		}
		else 
		{
			$name = $_GET['s'];
		}
		$array = array(
			'phone'=>$phone,
			'email'=>$email,
			'name'=>$name
		);
		$page = $_GET['page']==''?1:$_GET['page'];
		$MessageInfo = getMessageInfo(null,true,$page,$array);	
	}
	
	$subject = '<div class="useredit_phone">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}		
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">persons_fill</i> 客户管理</div>
		<ul class="menuWeb clear">
		<li class="menuWebAction" style="margin-right:5px;"><a href="index.php?act=OrderMng_phone">订单管理'.($getOrderInfo['totaRrows']==''?'':'('.$getOrderInfo['totaRrows'].')').'</a></li>
		<li class="menuWebAction"><a href="index.php?act=OrderMng_phone&ischecking=1">留言管理'.($MessageInfo['totaRrows']==''?'':'('.$MessageInfo['totaRrows'].')').'</a></li>
		</ul>
		<div class="newsTsBox_phone"><p>
		<span>'.($_GET["ischecking"]==1?'<font color="#999999">姓名/手机/邮箱</font>':'<font color="#999999">订单号/手机/邮箱</font>').' 搜索: </span>
		</p>
		<p>
		<span><input type="text" name="s" value="" class="rinput" style="width:100%;"/></span>
		</p>
		<p>
		<span>
		<input type="submit" value="搜索" class="subClass subgoto1"/>
		</span>
		</p>
		</div>';
	if( $_GET['ischecking'] != '1' )//已审核
	{	
		$subject .= '<table class="tableBox">
			<tr>		
				<th style="text-align:center;">订单号</th>
				<th style="text-align:center;">应付金额</th>
				<th style="text-align:center;">订单日期</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
				</tr>
				
				<form action="handling_events.php?act=MessageInfolist" name="frm" method="post" class="frm">';
if(!empty($getOrderInfo['data']))
{		
	foreach($getOrderInfo['data'] as $k=>$v)
	{
		$subject .= '<tr>		
				<td>'.$v['ordernumber'].'</td>
				<td>'.$v['money'].'</td>
				<td>'.$v['publitime'].'</td>		
				<td style="text-align:center;">
					<p class="art_floats">
					<a href="javascript:;" onclick="conf1('.$v['id'].');" class="hoverstyle" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
					</p>
				</td>
				<td width="80" style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}	
}		
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
		</form><tr>
				<p>
				<td colspan="10">
				<span style="font-size:15px;color:#666666;">总数:'.$getOrderInfo['totaRrows'].'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$getOrderInfo['page'].'/'.$getOrderInfo['totalpage'].'页</span>
				</p>';
if( $getOrderInfo['totaRrows'] > $getOrderInfo['totalshow'] )
{	
		$subject .= '<p style="height:50px;line-height:50px;">
		<a class="a_href" href="index.php?act=OrderMng_phone&page='.($getOrderInfo['page']-1).($_GET['s']==''?'':'&s='.$_GET['s']).'">上一页</a> 
		<a class="a_href" href="index.php?act=OrderMng_phone&page='.($getOrderInfo['page']+1).($_GET['s']==''?'':'&s='.$_GET['s']).'">下一页</a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px;height:25px;"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub subgoto1"/></p>';
}		
		$subject .= '</span><p style="height:50px;line-height:50px;">
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/></span></p>';
		
		$subject .= '</td>
			</tr>';
		
		$subject .= '</table>';
	}
	else //未审核
	{
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;正文</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
			</tr>
<form action="handling_events.php?act=MessageInfolist" name="frm" method="post" class="frm">';
if(!empty($MessageInfo['data'])){	
	foreach($MessageInfo['data'] as $k=>$v){	
	$subject .= '<tr>
				<td><a href="'.get_back_all($v['back'],'userid='.$v['id']).'" target="_blank"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.$v['body'].'</td>
				<td style="text-align:center;">
					<p class="art_floats">
					<a href="javascript:;" onclick="conf('.$v['id'].')" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
					</p>
				</td>
				<td width="80" style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}		
}			
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
				<input type="hidden" name="ischecking" value="1"/>
		</form><tr>
				<td colspan="10"><p>
				<span style="font-size:15px;color:#666666;">总页数:'.$MessageInfo['totaRrows'].'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$MessageInfo['page'].'/'.$MessageInfo['totalpage'].'页</span>
				</p>';
if( $MessageInfo['totaRrows'] > $MessageInfo['totalshow'] )
{		
		$subject .= '<p style="height:50px;line-height:50px;">
				<a class="a_href" href="index.php?act=OrderMng_phone&ischecking=1&page='.($MessageInfo['page']-1).($_GET['s']==''?'':'&s='.$_GET['s']).'">上一页</a> 
				<a class="a_href" href="index.php?act=OrderMng_phone&ischecking=1&page='.($MessageInfo['page']+1).($_GET['s']==''?'':'&s='.$_GET['s']).'">下一页</a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px;height:25px;"/>页 &nbsp; 
				<input type="submit" value="GO" class="subClass subgoto1"/></p>';
}		
		$subject .= '</span><p style="height:50px;line-height:50px;">
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/></span></p>';
		
		$subject .= '</td>
			</tr>';
		
	$subject .= '</table>';
	}	
	$subject .= '</div>';
	$subject .= '<script>
	function conf(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=MessageInfoDelete&id="+id;
		}
	}
	function conf1(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=OrderInfoDelete&id="+id;
		}
	}
	var fs = "'.($_GET['ischecking']==''?'':$_GET['ischecking']).'";
	$(".SearCh").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=OrderMng_phone&s="+$("[name=s]").val();
		}
		else
		{
			location.href="index.php?act=OrderMng_phone&ischecking=1&s="+$("[name=s]").val();
		}
	});
	var s = "'.($_GET['s']==''?'':'&s='.$_GET['s']).'";
	$(".subgoto1").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=OrderMng_phone&page="+$("[name=page]").val()+s;
		}
		else
		{
			location.href="index.php?act=OrderMng_phone&ischecking=1&page="+$("[name=page]").val()+s;
		}
	});
	$(function(){
		$(".shencha1").click(function(){
			$("[name=flag3]").val("3");
			document.frm.submit();
		});
		$(".shencha2").click(function(){
			$("[name=flag3]").val("2");
			document.frm.submit();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	$(function(){
$(".selcetAll").click(function(){
if(!$(".selCheck").is(":checked"))
{
	$(".selCheck").attr({"checked":"true"});
}
else
{
	$(".selCheck").removeAttr("checked");
}
});
});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#资源管理
function Resources()
{
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	
	if( $_GET['ischecking'] != '1' )//订单管理
	{
		if( strlen($_GET['s'])==11 && preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_GET['s']))
		{#手机搜索
			$phone = $_GET['s'];
		}
		elseif(strrpos($_GET['s'], '@')!==false)
		{
			$email = $_GET['s'];
		}
		else 
		{
			$ordernumber = $_GET['s'];
		}
		$array = array(
			'phone'=>$phone,
			'email'=>$email,
			'ordernumber'=>$ordernumber
		);
		$getOrderInfo =  getOrderInfo(null,true,$page,$array);	
	}
	else 
	{#留言管理
		if( strlen($_GET['s'])==11 && preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_GET['s']))
		{#手机搜索
			$phone = $_GET['s'];
		}
		elseif(strrpos($_GET['s'], '@')!==false)
		{
			$email = $_GET['s'];
		}
		else 
		{
			$name = $_GET['s'];
		}
		$array = array(
			'phone'=>$phone,
			'email'=>$email,
			'name'=>$name
		);
		$page = $_GET['page']==''?1:$_GET['page'];
		$MessageInfo = getMessageInfo(null,true,$page,$array);	
	}
	
	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}		
	$subject .= '<div class="userheader" style="border:none;">资源管理</div>
		<ul class="menuWeb clear">
		<li class="menuWebAction" style="margin-right:5px;"><a href="index.php?act=Tariff">资费管理'.($getOrderInfo['totaRrows']==''?'':'('.$getOrderInfo['totaRrows'].')').'</a></li>
		<li class="menuWebAction"><a href="index.php?act=Surname">姓氏管理'.($MessageInfo['totaRrows']==''?'':'('.$MessageInfo['totaRrows'].')').'</a></li>
		</ul>
		<div class="newsTsBox" style="margin-top:8px;">
		<span>'.($_GET["ischecking"]==1?'<font color="#999999">姓名/手机/邮箱</font>':'<font color="#999999">订单号/手机/邮箱</font>').' 搜索: </span>
		&nbsp; 
		<span><input type="text" name="s" value="" class="rinput" style="width:450px;"/></span> &nbsp; &nbsp;
		<span>
		<input type="submit" value="搜索" class="sub SearCh"/>
		</span>
		</div>';
	if( $_GET['ischecking'] != '1' )//已审核
	{	
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">商品名称</th>			
				<th style="text-align:center;">订单号</th>
				<th style="text-align:center;">应付金额</th>
				<th style="text-align:center;">手机&邮箱</th>
				<th style="text-align:center;">订单日期</th>
				<th style="text-align:center;">邮寄地址</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
				</tr>
				
				<form action="handling_events.php?act=MessageInfolist" name="frm" method="post" class="frm">';
if(!empty($getOrderInfo['data']))
{		
	foreach($getOrderInfo['data'] as $k=>$v)
	{
		$subject .= '<tr>
				<td>'.$v['id'].'</td>
				<td>'.$v['commodity'].'</td>			
				<td>'.$v['ordernumber'].'</td>
				<td>'.$v['money'].'</td>
				<td>'.$v['phone'].($v['email']==''?'':' / '.$v['email']).'</td>
				<td>'.$v['publitime'].'</td>
				<td>'.$v['address'].'</td>			
				<td style="text-align:center;">
					<a href="javascript:;" onclick="conf1('.$v['id'].');" class="hoverstyle" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
				<td width="80" style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}	
}		
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
		</form><tr>
				<td colspan="10">
				<span style="font-size:15px;color:#666666;">总数:'.$getOrderInfo['totaRrows'].'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$getOrderInfo['page'].'/'.$getOrderInfo['totalpage'].'页</span>
				&nbsp; ';
if( $getOrderInfo['totaRrows'] > $getOrderInfo['totalshow'] )
{	
		$subject .= '<a href="index.php?act=OrderMng&page='.($getOrderInfo['page']-1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; <a href="index.php?act=OrderMng&page='.($getOrderInfo['page']+1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub subgoto1"/>';
}		
		$subject .= '</span>
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/></span>';
		
		$subject .= '</td>
			</tr>';
		
		$subject .= '</table>';
	}
	else //未审核
	{
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">PID</th>
				<th style="text-align:center;">name</th>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;正文</th>
				<th style="text-align:center;">手机</th>
				<th style="text-align:center;">邮箱</th>
				<th style="text-align:center;">日期</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
			</tr>
<form action="handling_events.php?act=MessageInfolist" name="frm" method="post" class="frm">';
if(!empty($MessageInfo['data'])){	
	foreach($MessageInfo['data'] as $k=>$v){	
	$subject .= '<tr>
				<td>'.$v['id'].'</td>
				<td>'.$v['pid'].'</td>
				<td>'.$v['name'].'</td>
				<td><a href="'.get_back_all($v['back'],'userid='.$v['id']).'" target="_blank"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.$v['body'].'</td>
				<td>'.$v['phone'].'</td>
				<td>'.$v['email'].'</td>
				<td>'.$v['publitime'].'</td>	
				<td style="text-align:center;">
					<a href="javascript:;" onclick="conf('.$v['id'].')" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
				<td width="80" style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}		
}			
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
				<input type="hidden" name="ischecking" value="1"/>
		</form><tr>
				<td colspan="10">
				<span style="font-size:15px;color:#666666;">总页数:'.$MessageInfo['totaRrows'].'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$MessageInfo['page'].'/'.$MessageInfo['totalpage'].'页</span>
				&nbsp; ';
if( $MessageInfo['totaRrows'] > $MessageInfo['totalshow'] )
{		
		$subject .= '<a href="index.php?act=OrderMng&ischecking=1&page='.($MessageInfo['page']-1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; <a href="index.php?act=OrderMng&ischecking=1&page='.($MessageInfo['page']+1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub subgoto1"/>';
}		
		$subject .= '</span>
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/></span>';
		
		$subject .= '</td>
			</tr>';
		
	$subject .= '</table>';
	}	
	$subject .= '</div>';
	$subject .= '<script>
	function conf(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=MessageInfoDelete&id="+id;
		}
	}
	function conf1(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=OrderInfoDelete&id="+id;
		}
	}
	var fs = "'.($_GET['ischecking']==''?'':$_GET['ischecking']).'";
	$(".SearCh").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=OrderMng&s="+$("[name=s]").val();
		}
		else
		{
			location.href="index.php?act=OrderMng&ischecking=1&s="+$("[name=s]").val();
		}
	});
	var s = "'.($_GET['s']==''?'':'&s='.$_GET['s']).'";
	$(".subgoto1").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=OrderMng&page="+$("[name=page]").val()+s;
		}
		else
		{
			location.href="index.php?act=OrderMng&ischecking=1&page="+$("[name=page]").val()+s;
		}
	});
	$(function(){
		$(".shencha1").click(function(){
			$("[name=flag3]").val("3");
			document.frm.submit();
		});
		$(".shencha2").click(function(){
			$("[name=flag3]").val("2");
			document.frm.submit();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	$(function(){
$(".selcetAll").click(function(){
if(!$(".selCheck").is(":checked"))
{
	$(".selCheck").attr({"checked":"true"});
}
else
{
	$(".selCheck").removeAttr("checked");
}
});
});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#资费管理
function Tariff()
{
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	
	if( $_GET['ischecking'] != '1' )//订单管理
	{
		if( strlen($_GET['s'])==11 && preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_GET['s']))
		{#手机搜索
			$phone = $_GET['s'];
		}
		elseif(strrpos($_GET['s'], '@')!==false)
		{
			$email = $_GET['s'];
		}
		else 
		{
			$ordernumber = $_GET['s'];
		}
		$array = array(
			'phone'=>$phone,
			'email'=>$email,
			'ordernumber'=>$ordernumber
		);
		$getOrderInfo =  getOrderInfo(null,true,$page,$array);	
	}
	else 
	{#留言管理
		if( strlen($_GET['s'])==11 && preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_GET['s']))
		{#手机搜索
			$phone = $_GET['s'];
		}
		elseif(strrpos($_GET['s'], '@')!==false)
		{
			$email = $_GET['s'];
		}
		else 
		{
			$name = $_GET['s'];
		}
		$array = array(
			'phone'=>$phone,
			'email'=>$email,
			'name'=>$name
		);
		$page = $_GET['page']==''?1:$_GET['page'];
		$MessageInfo = getMessageInfo(null,true,$page,$array);	
	}
	
	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}		
	$subject .= '<div class="userheader" style="border:none;">资费管理</div>
		<ul class="menuWeb clear">
		<li class="menuWebAction" style="margin-right:5px;"><a href="index.php?act=Tariff">资费管理'.($getOrderInfo['totaRrows']==''?'':'('.$getOrderInfo['totaRrows'].')').'</a></li>
		<li class="menuWebAction"><a href="index.php?act=Surname">姓氏管理'.($MessageInfo['totaRrows']==''?'':'('.$MessageInfo['totaRrows'].')').'</a></li>
		</ul>
		<div class="newsTsBox" style="margin-top:8px;">
		<span>'.($_GET["ischecking"]==1?'<font color="#999999">姓名/手机/邮箱</font>':'<font color="#999999">订单号/手机/邮箱</font>').' 搜索: </span>
		&nbsp; 
		<span><input type="text" name="s" value="" class="rinput" style="width:450px;"/></span> &nbsp; &nbsp;
		<span>
		<input type="submit" value="搜索" class="sub SearCh"/>
		</span>
		</div>';
	if( $_GET['ischecking'] != '1' )//已审核
	{	
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">商品名称</th>			
				<th style="text-align:center;">订单号</th>
				<th style="text-align:center;">应付金额</th>
				<th style="text-align:center;">手机&邮箱</th>
				<th style="text-align:center;">订单日期</th>
				<th style="text-align:center;">邮寄地址</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
				</tr>
				
				<form action="handling_events.php?act=MessageInfolist" name="frm" method="post" class="frm">';
if(!empty($getOrderInfo['data']))
{		
	foreach($getOrderInfo['data'] as $k=>$v)
	{
		$subject .= '<tr>
				<td>'.$v['id'].'</td>
				<td>'.$v['commodity'].'</td>			
				<td>'.$v['ordernumber'].'</td>
				<td>'.$v['money'].'</td>
				<td>'.$v['phone'].($v['email']==''?'':' / '.$v['email']).'</td>
				<td>'.$v['publitime'].'</td>
				<td>'.$v['address'].'</td>			
				<td style="text-align:center;">
					<a href="javascript:;" onclick="conf1('.$v['id'].');" class="hoverstyle" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
				<td width="80" style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}	
}		
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
		</form><tr>
				<td colspan="10">
				<span style="font-size:15px;color:#666666;">总数:'.$getOrderInfo['totaRrows'].'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$getOrderInfo['page'].'/'.$getOrderInfo['totalpage'].'页</span>
				&nbsp; ';
if( $getOrderInfo['totaRrows'] > $getOrderInfo['totalshow'] )
{	
		$subject .= '<a href="index.php?act=OrderMng&page='.($getOrderInfo['page']-1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; <a href="index.php?act=OrderMng&page='.($getOrderInfo['page']+1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub subgoto1"/>';
}		
		$subject .= '</span>
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/></span>';
		
		$subject .= '</td>
			</tr>';
		
		$subject .= '</table>';
	}
	else //未审核
	{
		$subject .= '<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">PID</th>
				<th style="text-align:center;">name</th>
				<th style="text-align:center;"><img src="'.site_url('images/link.png').'" align="absmiddle"/>&nbsp;正文</th>
				<th style="text-align:center;">手机</th>
				<th style="text-align:center;">邮箱</th>
				<th style="text-align:center;">日期</th>
				<th style="text-align:center;">操作</th>
				<th style="text-align:center;"><a href="javascript:;" class="selcetAll">全选</a></th>
			</tr>
<form action="handling_events.php?act=MessageInfolist" name="frm" method="post" class="frm">';
if(!empty($MessageInfo['data'])){	
	foreach($MessageInfo['data'] as $k=>$v){	
	$subject .= '<tr>
				<td>'.$v['id'].'</td>
				<td>'.$v['pid'].'</td>
				<td>'.$v['name'].'</td>
				<td><a href="'.get_back_all($v['back'],'userid='.$v['id']).'" target="_blank"><img src="'.site_url('images/link.png').'" align="absmiddle"/></a>&nbsp;'.$v['body'].'</td>
				<td>'.$v['phone'].'</td>
				<td>'.$v['email'].'</td>
				<td>'.$v['publitime'].'</td>	
				<td style="text-align:center;">
					<a href="javascript:;" onclick="conf('.$v['id'].')" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
				<td width="80" style="text-align:center;"><input type="checkbox" name="id[]" value="'.$v['id'].'" class="selCheck"/></td>
			</tr>';
	}		
}			
		$subject .= '
		<input type="hidden" name="flag3" value="3"/>
				<input type="hidden" name="ischecking" value="1"/>
		</form><tr>
				<td colspan="10">
				<span style="font-size:15px;color:#666666;">总页数:'.$MessageInfo['totaRrows'].'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$MessageInfo['page'].'/'.$MessageInfo['totalpage'].'页</span>
				&nbsp; ';
if( $MessageInfo['totaRrows'] > $MessageInfo['totalshow'] )
{		
		$subject .= '<a href="index.php?act=OrderMng&ischecking=1&page='.($MessageInfo['page']-1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; <a href="index.php?act=OrderMng&ischecking=1&page='.($MessageInfo['page']+1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub subgoto1"/>';
}		
		$subject .= '</span>
				<span style="float:right;">
				<input type="submit" value="删除选项" class="sub shencha2"/></span>';
		
		$subject .= '</td>
			</tr>';
		
	$subject .= '</table>';
	}	
	$subject .= '</div>';
	$subject .= '<script>
	function conf(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=MessageInfoDelete&id="+id;
		}
	}
	function conf1(id)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=OrderInfoDelete&id="+id;
		}
	}
	var fs = "'.($_GET['ischecking']==''?'':$_GET['ischecking']).'";
	$(".SearCh").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=OrderMng&s="+$("[name=s]").val();
		}
		else
		{
			location.href="index.php?act=OrderMng&ischecking=1&s="+$("[name=s]").val();
		}
	});
	var s = "'.($_GET['s']==''?'':'&s='.$_GET['s']).'";
	$(".subgoto1").click(function(){
		if( fs == "" )
		{
			location.href="index.php?act=OrderMng&page="+$("[name=page]").val()+s;
		}
		else
		{
			location.href="index.php?act=OrderMng&ischecking=1&page="+$("[name=page]").val()+s;
		}
	});
	$(function(){
		$(".shencha1").click(function(){
			$("[name=flag3]").val("3");
			document.frm.submit();
		});
		$(".shencha2").click(function(){
			$("[name=flag3]").val("2");
			document.frm.submit();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	$(function(){
$(".selcetAll").click(function(){
if(!$(".selCheck").is(":checked"))
{
	$(".selCheck").attr({"checked":"true"});
}
else
{
	$(".selCheck").removeAttr("checked");
}
});
});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#姓氏管理
function Surname()
{
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	
	$sql = 'select id,xing,pinyin,bushou,bihua,wubi,title,meaning,flag from '.PRE.'surname ';	
	$s = $_GET['s'];
	if($s != null)
	{
		$sql .= ' where xing="'.$s.'" ';
	}
	
	$rowsTotal = db()->query($sql)->array_nums();
	
	$showTotal = $num;
	$pageTotal = ceil($rowsTotal/$showTotal);
	$page = $_GET['page']==null?1:$_GET['page'];
	if($page<1 || !is_numeric($page)){$page=1;}
	if($page>=$pageTotal){$page=$pageTotal;}
	$offset = ($page-1)*$showTotal;
	
	$sql .= ' limit '.$offset.','.$showTotal.' ';
	$rows = db()->query($sql)->array_rows();
	
	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}		
	$subject .= '<div class="userheader" style="border:none;">姓氏管理</div>
		<ul class="menuWeb clear">
		<li class="menuWebAction" style="margin-right:5px;"><a href="index.php?act=Tariff">资费管理</a></li>
		<li class="menuWebAction"><a href="index.php?act=Surname">姓氏管理</a></li>
		</ul>
		<div class="newsTsBox" style="margin-top:8px;">
		<span>姓氏 搜索: </span>
		&nbsp; 
		<span><input type="text" name="s" value="" class="rinput" style="width:450px;"/></span> &nbsp; &nbsp;
		<span>
		<input type="submit" value="搜索" class="sub SearCh"/>
		</span>
		</div>';
		$subject .= '<table class="tableBox" style="text-align:center;">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">姓氏</th>			
				<th style="text-align:center;">拼音</th>
				<th style="text-align:center;">部首</th>
				<th style="text-align:center;">笔画</th>
				<th style="text-align:center;">五笔</th>
				<th style="text-align:center;">标题</th>
				<th style="text-align:center;">操作</th>
				</tr>';
if(!empty($rows))
{		
	foreach($rows as $k=>$v)
	{
		$subject .= '<tr>
				<td>'.$v['id'].'</td>
				<td>'.$v['xing'].'</td>			
				<td>'.$v['pinyin'].'</td>
				<td>'.$v['bushou'].'</td>
				<td>'.$v['bihua'].'</td>
				<td>'.$v['wubi'].'</td>
				<td>'.$v['title'].'</td>			
				<td style="text-align:center;">
					<a href="index.php?act=SurnameUpdate&id='.$v['id'].'&page='.$page.'" class="hoverstyle" title="修改"><img src="'.site_url('images/page_edit.png').'" align="absmiddle"/></a>
				</td>
			</tr>';
	}	
}		
		$subject .= '<tr>
				<td colspan="8">
				<span style="font-size:15px;color:#666666;">总数:'.$rowsTotal.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$pageTotal.'页</span>
				&nbsp; ';
if( $rowsTotal > $showTotal )
{	
		$subject .= '<a href="index.php?act=Surname&page='.($page-1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; <a href="index.php?act=Surname&page='.($page+1).($_GET['s']==''?'':'&s='.$_GET['s']).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="page" value="" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub subgoto1"/>';
}				
		$subject .= '</td>
			</tr>';		
	$subject .= '</table>';
	$subject .= '<script>
	$(function(){
	$(".subgoto1").click(function(){
		var page = $("[name=page]").val();
		location.href="index.php?act=Surname&page="+page;
	});
	$(".SearCh").click(function(){
		var s = $("[name=s]").val();
		if(s!="")
		{
			location.href="index.php?act=Surname&s="+s;
		}
		else
		{
			location.href="index.php?act=Surname";
		}
	});
	$(".showerror").hide(2000);
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
	$(".tableBox tr").hover(function(){
	$(this).css({"background":"#FFFFDD"});
	},function(){
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
	});
	});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#修改姓氏分类
function SurnameUpdate()
{
	$id = $_GET['id'];
	$page = $_GET['page'];
	
	$row = db()->select('*')->from(PRE.'surname')->where(array('id'=>$id))->get()->array_row();
	
	$subject = '<div class="useredit">
	<form action="handling_events.php" method="post" id="viri">
	<div class="userheader">编辑姓氏</div>	
		<div class="userjibie">姓氏: </div>
		<div><input type="text" name="xing" value="'.$row['xing'].'" class="input-s"/></div>
		<div class="userjibie">拼音: </div>
		<div><input type="text" name="pinyin" value="'.$row['pinyin'].'" class="input-s"/></div>
		<div class="userjibie">部首:</div>
		<div><input type="text" name="bushou" value="'.$row['bushou'].'" class="input-s"/></div>
		<div class="userjibie">笔画:</div>
		<div><input type="text" name="bihua" value="'.$row['bihua'].'" class="input-s"/></div>
		<div class="userjibie">五笔:</div>
		<div><input type="text" name="wubi" value="'.$row['wubi'].'" class="input-s"/></div>
		<div class="userjibie">标题:</div>
		<div><input type="text" name="title" value="'.$row['title'].'" class="input-s"/></div>
		<div class="userjibie">备用:</div>
		<div><input type="text" name="flag" value="'.$row['flag'].'" class="input-s"/></div>';
	
	$subject .= '<div class="userjibie">来源、由来、含义:</div>
		<div><script id="container" name="meaning" type="text/plain" style="width:90%;height:309px;">'.$row['meaning'].'</script></div>
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="id" value="'.$id.'"/>
		<input type="hidden" name="page" value="'.$page.'"/>
		<input type="hidden" name="act" value="SurnameUpdate"/>
		<input type="submit" value="提交" class="sub"/>
		</div>
	</div></form>
	</div>';
	$subject .= '
	<script type="text/javascript" src="plugin/UEditor/ueditor.config.js"></script>
    <script type="text/javascript" src="plugin/UEditor/ueditor.all.js"></script>
    <script type="text/javascript" src="plugin/UEditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript">
        var ue1 = UE.getEditor("container");       
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#用户管理
function MemberMng()
{
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	#分页
	$sql = 'select * from '.PRE.'login ';
	if($_GET['level']!=''){
		$num = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];
		$sql .= ' where level= "'.$_GET['level'].'" ';
	}
	$search = $_GET['search'];
	if( $search != '' ){
		$num = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];
		$sql .= ' and (userName="'.$search.'" or alias="'.$search.'") ';
	}	
	$sql .= ' order by id desc ';
	
	$result = db()->query($sql);	
	$totalrows = $result->array_nums();
	
	$showpage = $num;
	$totalpage = ceil($totalrows/$showpage);
	$page = $_GET['page']==''?'1':$_GET['page'];
	if($page>=$totalpage){$page=$totalpage;}
	if($page<=1||!is_numeric($page)){$page=1;}
	$offset = ($page-1)*$showpage;
	
	$sql = 'select id,level,userName,alias,(select count(author) from '.PRE.'article where author=userName) as newTotal,(select count(userid) from '.PRE.'template where userid=a.id) as temTotal,(select count(id) from '.PRE.'review where name=a.userName) as revToal,(select count(id) from '.PRE.'fileupload where username=a.userName) as fjToal from '.PRE.'login as a ';
	if($_GET['level']!=''){
		$sql .= ' where level= "'.$_GET['level'].'" ';
	}
	$search = $_GET['search'];
	if( $search != '' ){
		$sql .= ' and (userName="'.$search.'" or alias="'.$search.'") ';
	}	
	$sql .= ' limit '.$offset.','.$showpage.' ';
	$result = db()->query($sql);
	$rows = $result->array_rows();

	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader" style="border:none;">用户管理</div>
		<ul class="menuWeb clear"><li class="menuWebAction"><a href="index.php?act=MemberNew">新建用户</a></li></ul>
		<div class="newsTsBox" style="margin-top:8px;">
		<span>搜索: </span> &nbsp; 
		<span>用户级别</span>
		<span>
			<select id="lv" class="renyiCl">
				<option value="0" '.($_GET['level']=='0'?'selected="selected"':'').'>管理员</option>
				<option value="1" '.($_GET['level']=='1'?'selected="selected"':'').'>网站编辑</option>
				<option value="2" '.($_GET['level']=='2'?'selected="selected"':'').'>作者</option>
				<option value="3" '.($_GET['level']=='3'?'selected="selected"':'').'>协作者</option>
				<option value="4" '.($_GET['level']=='4'?'selected="selected"':'').'>评论员</option>
				<option value="5" '.($_GET['level']=='5'?'selected="selected"':'').'>游客</option>
			<select>
		</span> &nbsp; 		
		<span><input type="text" name="search" value="" class="rinput"/></span> &nbsp; 
		<span><input type="submit" id="sh" value="搜索" class="sub"/></span>
		</div>
		
			<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">用户级别</th>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;">别名</th>
				<th style="text-align:center;">文章总数</th>
				<th style="text-align:center;">页面总数</th>
				<th style="text-align:center;">评论总数</th>
				<th style="text-align:center;">附件总数</th>
				<th style="text-align:center;">操作</th>
			</tr>';
	foreach($rows as $k=>$v)
	{		
			$subject .= '<tr>
				<td width="60">'.$v["id"].'</td>
				<td width="150">
				<a href="'.apth_url('system/power.php?user='.$v["id"]).'" class="hoverstyle" target="_blank" title="权限"><img src="'.apth_url('system/admin/images/link.png').'" align="absmiddle"></a>
				'.getUserJb($v["level"]).'
				</td>
				<td width="150">'.$v["userName"].'</td>
				<td width="150">'.$v["alias"].'</td>
				<td width="100">'.$v["newTotal"].'</td>
				<td width="100">'.$v["temTotal"].'</td>
				<td width="100">'.$v["revToal"].'</td>
				<td width="100">'.$v["fjToal"].'</td>
				<td style="text-align:center;">
					<a href="index.php?act=MemberEdt&id='.$v["id"].'" class="hoverstyle" title="修改"><img src="'.site_url('images/user_edit.png').'" align="absmiddle"/></a>
					 &nbsp; &nbsp; 
					<a href="javascript:;" onclick="conf('.$v["id"].','.$page.')" class="hoverstyle" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
				</td>
			</tr>';
	}					
			$subject .= '<tr>
				<td colspan="9">
				<span style="font-size:15px;color:#666666;">总数:'.$totalrows.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$totalpage.'页</span>				
				&nbsp;'; 
		if( $totalrows >= $showpage )
		{		
			$subject .= '<a href="index.php?act=MemberMng&page='.($page-1).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; <a href="index.php?act=MemberMng&page='.($page+1).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="GO" id="renyiCl" class="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub"/>
				</span>';
		}		
			$subject .= '</td>
			</tr>
		</table>
		
	</div>';
	$subject .= '<script>
	$(function(){
		$("#lv").change(function(){
			location.href="index.php?act=MemberMng&level="+$(this).val()+"&search="+$("[name=search]").val();
		});
		$("#sh").click(function(){
			location.href="index.php?act=MemberMng&level="+$("#lv").val()+"&search="+$("[name=search]").val();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	$(function(){
	$("[value=GO]").click(function(){
		if($("#renyiCl").val()!="")
		{
			location.href="index.php?act=MemberMng&page="+$("#renyiCl").val();
		}
	});
});
	function conf(id,page)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=MemberEeeor&id="+id+"&page="+page;
		}
	}
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#用户管理
function MemberMng_phone()
{
	#网站设置
	$setreview = db()->select('rowstotal,searchmaxtotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	#分页
	$sql = 'select * from '.PRE.'login ';
	if($_GET['level']!=''){
		$num = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];
		$sql .= ' where level= "'.$_GET['level'].'" ';
	}
	$search = $_GET['search'];
	if( $search != '' ){
		$num = $setreview['searchmaxtotal']==''?10:$setreview['searchmaxtotal'];
		$sql .= ' and (userName="'.$search.'" or alias="'.$search.'") ';
	}	
	$sql .= ' order by id desc ';
	
	$result = db()->query($sql);	
	$totalrows = $result->array_nums();
	
	$showpage = $num;
	$totalpage = ceil($totalrows/$showpage);
	$page = $_GET['page']==''?'1':$_GET['page'];
	if($page>=$totalpage){$page=$totalpage;}
	if($page<=1||!is_numeric($page)){$page=1;}
	$offset = ($page-1)*$showpage;
	
	$sql = 'select id,level,userName,alias,(select count(author) from '.PRE.'article where author=userName) as newTotal,(select count(userid) from '.PRE.'template where userid=a.id) as temTotal,(select count(id) from '.PRE.'review where name=a.userName) as revToal,(select count(id) from '.PRE.'fileupload where username=a.userName) as fjToal from '.PRE.'login as a ';
	if($_GET['level']!=''){
		$sql .= ' where level= "'.$_GET['level'].'" ';
	}
	$search = $_GET['search'];
	if( $search != '' ){
		$sql .= ' and (userName="'.$search.'" or alias="'.$search.'") ';
	}	
	$sql .= ' limit '.$offset.','.$showpage.' ';
	$result = db()->query($sql);
	$rows = $result->array_rows();

	$subject = '<div class="useredit_phone">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">person_fill</i> 用户管理</div>
		<ul class="menuWeb clear"><li class="menuWebAction"><a href="index.php?act=MemberNew_phone">新建用户</a></li></ul>
		<div class="newsTsBox_phone">
		<p>
		<span>用户级别</span>
		</p>
		<p>
		<span>
			<select id="lv" class="renyiCl">
				<option value="0" '.($_GET['level']=='0'?'selected="selected"':'').'>管理员</option>
				<option value="1" '.($_GET['level']=='1'?'selected="selected"':'').'>网站编辑</option>
				<option value="2" '.($_GET['level']=='2'?'selected="selected"':'').'>作者</option>
				<option value="3" '.($_GET['level']=='3'?'selected="selected"':'').'>协作者</option>
				<option value="4" '.($_GET['level']=='4'?'selected="selected"':'').'>评论员</option>
				<option value="5" '.($_GET['level']=='5'?'selected="selected"':'').'>游客</option>
			<select>
		</span>
		</p>
		<p>		
		<span><input type="text" name="search" value="" class="rinput"/></span>
		</p>
		<p>	
		<span><input type="submit" id="sh" value="搜索" class="subClass"/></span>
		</p>
		</div>
		
			<table class="tableBox">
			<tr>
				<th style="text-align:center;">用户级别</th>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;">操作</th>
			</tr>';
	foreach($rows as $k=>$v)
	{		
			$subject .= '<tr>
				<td>
				<a href="'.apth_url('system/power.php?user='.$v["id"]).'" class="hoverstyle" target="_blank" title="权限"><img src="'.apth_url('system/admin/images/link.png').'" align="absmiddle"></a>
				'.getUserJb($v["level"]).'</td>
				<td>'.$v["userName"].'</td>
				<td style="text-align:center;">
					<p class="art_floats">
					<a href="index.php?act=MemberEdt_phone&id='.$v["id"].'" class="hoverstyle" title="修改"><img src="'.site_url('images/user_edit.png').'" align="absmiddle"/></a>
					</p>
					<p class="art_floats">
					<a href="javascript:;" onclick="conf('.$v["id"].','.$page.')" class="hoverstyle" title="清除"><img src="'.site_url('images/delete.png').'" align="absmiddle"/></a>
					</p>
				</td>
			</tr>';
	}					
			$subject .= '<tr>
				<td colspan="3">
				<p>
				<span style="font-size:15px;color:#666666;">总数:'.$totalrows.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$totalpage.'页</span>				
				</p>'; 
		if( $totalrows >= $showpage )
		{		
			$subject .= '<p><a class="a_href" href="index.php?act=MemberMng_phone&page='.($page-1).'">上一页</a> 
						<a class="a_href" href="index.php?act=MemberMng_phone&page='.($page+1).'">下一页</a>
				&nbsp; 
				<span>
				<input type="text" name="GO" id="renyiCl" class="renyiCl" style="width:50px;height:25px;"/>页 &nbsp; 
				<input type="submit" value="GO" id="GO"/>
				</span></p>';
		}		
			$subject .= '</td>
			</tr>
		</table>
		
	</div>';
	$subject .= '<script>
	$(function(){
		$("#lv").change(function(){
			location.href="index.php?act=MemberMng_phone&level="+$(this).val()+"&search="+$("[name=search]").val();
		});
		$("#sh").click(function(){
			location.href="index.php?act=MemberMng_phone&level="+$("#lv").val()+"&search="+$("[name=search]").val();
		});
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	$(function(){
	$("[value=GO]").click(function(){
		if($("#renyiCl").val()!="")
		{
			location.href="index.php?act=MemberMng_phone&page="+$("#renyiCl").val();
		}
	});
});
	function conf(id,page)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=MemberEeeor&id="+id+"&page="+page;
		}
	}
	</script>';
	$_SESSION['flagEorre'] = null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#附件管理
function UploadMng()
{
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	#查询数据
	$rowsTotal = db()->select("id")->from(PRE.'fileupload')->get()->array_nums();
	$pageTotal = ceil($rowsTotal/$num);
	$page = $_GET['page']==''?1:$_GET['page'];
	if($page>=$pageTotal){$page=$pageTotal;}
	if($page<=1||!is_numeric($page)){$page=1;}
	$offset = ($page-1)*$num;
	$limit = $offset.','.$num;
	$rows = db()->select('id,types,filepath,filename,filesize,FROM_UNIXTIME(uptime) as uptime,username')->from(PRE.'fileupload')->order_by('uptime desc')->limit($limit)->get()->array_rows();
	
	$subject = '<div class="useredit">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}	
	$subject .= '<div class="userheader" style="border:none;">附件管理</div>
		<form action="handling_events.php" method="post" enctype="multipart/form-data">
		<div class="newsTsBox" style="line-height:25px;height:95px;">
		<p style="height:40px;line-height:45px;font-weight:normal;">选择需要上传的文件:</p>
		<p style="height:40px;line-height:40px;">
		<input type="file" name="file" size="60" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"/>
		 &nbsp; 
		<span><input type="checkbox" name="name" value="1" checked="checked"/>自动重命名文件名 </span>
		 &nbsp; 
		<span>
		<input type="hidden" name="act" value="UploadMngFile"/>
		<input type="hidden" name="username" value="'.$_SESSION['username'].'"/>
		<input type="submit" value="上传" class="sub"/>
		</span>
		 &nbsp; 
		<span><input type="reset" value="重置" class="sub"/></span>
		</p>		
		</div>
		</form>
		<table class="tableBox">
			<tr>
				<th style="text-align:center;">ID</th>
				<th style="text-align:center;">作者</th>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;">日期</th>
				<th style="text-align:center;">大小</th>
				<th style="text-align:center;">类型</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if( !empty( $rows ) )
{	
	foreach( $rows as $k => $v )
	{		
	$subject .= '<tr>
				<td width="60">'.$v['id'].'</td>
				<td width="100">'.$v['username'].'</td>
				<td width="444">
				<a href="'.apth_url($v['filepath']).'" class="hoverstyle" target="_blank"><img src="'.site_url('images/link.png').'" align="absmiddle"></a>
				'.$v['filename'].'</td>
				<td width="150">'.$v['uptime'].'</td>
				<td width="150">'.$v['filesize'].'</td>
				<td width="150">'.$v['types'].'</td>
				<td style="text-align:center;"> 
					<a href="javascript:;" onclick="conf('.$v['id'].','.$page.')">
					<img src="'.site_url('images/delete.png').'" align="absmiddle"/>
					</a>
				</td>
			</tr>';
	}
}			
	$subject .= '<tr>
				<td colspan="8">
				<span style="font-size:15px;color:#666666;">总数:'.$rowsTotal.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$pageTotal.'页</span>
				&nbsp; ';
if( $rowsTotal >= $num )
{	
	$subject .= '<a href="index.php?act=UploadMng&page='.($page-1).'"><input type="submit" value="上一页" class="sub"/></a> &nbsp; 
				 <a href="index.php?act=UploadMng&page='.($page+1).'"><input type="submit" value="下一页" class="sub"/></a>
				&nbsp; 
				<span>
				<input type="text" name="GO" value="" class="renyiCl" id="renyiCl" style="width:50px"/>页 &nbsp; 
				<input type="submit" value="GO" class="sub"/>
				</span>';
}	
	$subject .= '</td>
			</tr>
		</table>	
		</div>';
	$subject .= '<script>
	function conf(id,page)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=UploadMngFileDelete&id="+id+"&page="+page;
		}
	}
$(function(){
	$("[value=GO]").click(function(){
		if($("#renyiCl").val()!="")
		{
			location.href="index.php?act=UploadMng&page="+$("#renyiCl").val();
		}
	});
});
	$(function(){
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#附件管理
function UploadMng_phone()
{
	session_start();
	#网站设置
	$setreview = db()->select('rowstotal')->from(PRE.'review_up')->get()->array_row();
	$num = $setreview['rowstotal']==''?10:$setreview['rowstotal'];
	#查询数据
	$rowsTotal = db()->select("id")->from(PRE.'fileupload')->get()->array_nums();
	$pageTotal = ceil($rowsTotal/$num);
	$page = $_GET['page']==''?1:$_GET['page'];
	if($page>=$pageTotal){$page=$pageTotal;}
	if($page<=1||!is_numeric($page)){$page=1;}
	$offset = ($page-1)*$num;
	$limit = $offset.','.$num;
	$rows = db()->select('id,types,filepath,filename,filesize,FROM_UNIXTIME(uptime) as uptime,username')->from(PRE.'fileupload')->order_by('uptime desc')->limit($limit)->get()->array_rows();
	
	$subject = '<div class="useredit_phone">';
	if( isset($_SESSION['flagEorre']) && $_SESSION['flagEorre']==1 )
	{
		$subject .= '<div class="showerror">
		<img src="'.site_url('images/ok.png').'" align="absmiddle"/>
		操作成功
		</div>';
	}	
	$subject .= '<div class="userheader3_phone"><i class="f7-icons size-22">drawers_fill</i> 附件管理</div>
		<form action="handling_events.php" method="post" enctype="multipart/form-data">
		<div class="newsTsBox_phone">
		<p style="height:40px;line-height:45px;font-weight:normal;">选择需要上传的文件:</p>
		<p style="height:40px;line-height:40px;">
		<input type="file" name="file" size="60" style="border: 1px solid #CCCCCC;width:95%;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;"/>
		</p>
		<p>
		<span><input type="checkbox" name="name" value="1" checked="checked"/>自动重命名文件名 </span>
		</p>
		<p style="height:50px;">
		<span>
		<input type="hidden" name="act" value="UploadMngFile"/>
		<input type="hidden" name="username" value="'.$_SESSION['username'].'"/>
		<input type="submit" value="上传" class="subClass"/>
		</span>
		</p> 
		<p style="height:50px;">
		<span><input type="reset" value="重置" class="subClass"/></span>
		</p>		
		</div>
		</form>
		<table class="tableBox">
			<tr>
				<th style="text-align:center;">名称</th>
				<th style="text-align:center;">大小</th>
				<th style="text-align:center;">操作</th>
			</tr>';
if( !empty( $rows ) )
{	
	foreach( $rows as $k => $v )
	{		
	$subject .= '<tr>
				<td>
				<a href="'.apth_url($v['filepath']).'" class="hoverstyle" target="_blank"><img src="'.site_url('images/link.png').'" align="absmiddle"></a>
				'.$v['filename'].'</td>
				<td>'.$v['filesize'].'</td>
				<td style="text-align:center;"> 
					<p class="art_floats">
					<a href="javascript:;" onclick="conf('.$v['id'].','.$page.')">
					<img src="'.site_url('images/delete.png').'" align="absmiddle"/>
					</a>
					</p>
				</td>
			</tr>';
	}
}			
	$subject .= '<tr>
				<td colspan="3">
				<p>
				<span style="font-size:15px;color:#666666;">总数:'.$rowsTotal.'</span>
				&nbsp;
				<span style="font-size:15px;color:#666666;">当前:'.$page.'/'.$pageTotal.'页</span>
				</p>';
if( $rowsTotal >= $num )
{	
	$subject .= '<p><a class="a_href" href="index.php?act=UploadMng_phone&page='.($page-1).'">上一页</a>  
				 <a class="a_href" href="index.php?act=UploadMng_phone&page='.($page+1).'">下一页</a>
				&nbsp; 
				<span>
				<input type="text" name="GO" value="" class="renyiCl" id="renyiCl" style="width:50px;height:25px;"/>页 &nbsp; 
				<input type="submit" value="GO" id="GO"/>
				</span></p>';
}	
	$subject .= '</td>
			</tr>
		</table>	
		</div>';
	$subject .= '<script>
	function conf(id,page)
	{
		var bl = window.confirm("是否要删除");
		if(bl)
		{
			location.href="handling_events.php?act=UploadMngFileDelete&id="+id+"&page="+page;
		}
	}
$(function(){
	$("[value=GO]").click(function(){
		if($("#renyiCl").val()!="")
		{
			location.href="index.php?act=UploadMng_phone&page="+$("#renyiCl").val();
		}
	});
});
	$(function(){
		$(".showerror").hide(2000);
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
});
	});
	</script>';
	$_SESSION['flagEorre']=null;
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#新建用户
function MemberNew()
{
	#当前模板
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$subject = '
	<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit">
		<div class="userheader">新建用户</div>
		<div class="userjibie">用户级别:</div>
		<div>
			<select name="level" class="input-x">
				<option value="0">管理员</option>
				<option value="1">网站编辑</option>
				<option value="2">作者</option>
				<option value="3">协作者</option>
				<option value="4">评论员</option>
				<option value="5">游客</option>
			</select>
			&nbsp;
			<span style="font-size:14px;">( <b>状态:</b>
			<input type="radio" name="state" value="0" checked="checked" id="radio1"/> <label for="radio1">正常<label> &nbsp;
			<input type="radio" name="state" value="1" id="radio2"/> <label for="radio2">审核<label> &nbsp;
			<input type="radio" name="state" value="2" id="radio3"/> <label for="radio3">禁止<label> )
			</span>
		</div>
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="userName" vlaue="" class="input-s"/></div>
		<div class="userjibie">密码: </div>
		<div><input type="password" name="pwd" vlaue="" class="input-s"/></div>
		<div class="userjibie">确认密码: </div>
		<div><input type="password" name="pwd2" vlaue="" class="input-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="alias" vlaue="" class="input-s"/></div>
		<div class="userjibie">邮箱: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="email" vlaue="" class="input-s"/></div>
		<div class="userjibie">主页:</div>
		<div><input type="text" name="homepage" vlaue="" class="input-s"/></div>
		<div class="userjibie">摘要:</div>
		<div><textarea name="abst" class="input-w"></textarea></div>
		<div class="userjibie">模板:</div>
		<div>
			<select name="Template" class="input-x">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>
		<div class="userjibie">默认头像:</div>
		<div class="clear">
		<div class="touxiang1"><img src="'.site_url('header/0.png').'" width="40" height="40"/></div>
		<div class="touxiang2">本地更换</div> &nbsp; <span id="WenPic" style="color:#FF0000;"></span>
		<input type="file" id="tou_file" name="pic" style="display:none">
		</div>
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="act" value="MemberNew2" class="sub"/>
		<input type="submit" value="提交" class="sub"/>
		</div>
	</div></form>';
	$subject .= '<script>
		$(function(){
		$("#frm").submit(function(){
			if($("[name=userName]").val()=="")
			{
				alert("名称未命名");
				$("[name=userName]").focus();
				return false;
			}
			if($("[name=pwd]").val()=="")
			{
				alert("请输入密码");
				$("[name=pwd]").focus();
				return false;
			}
			if($("[name=pwd2]").val()=="")
			{
				alert("请输入确认密码");
				$("[name=pwd2]").focus();
				return false;
			}
			if( $("[name=pwd]").val() != $("[name=pwd2]").val() )
			{
				alert("两次密码不一致");
				$("[name=pwd2]").focus();
				return false;
			}
			if($("[name=email]").val()=="")
			{
				alert("邮箱不能留空");
				$("[name=email]").focus();
				return false;
			}
			var email = $("[name=email]").val();
			email = email.replace(/(^\s*)|(\s*$)/g, "");
			var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
			if(reg.test(email)==false)
			{
				alert("邮箱格式正确");
				$("[name=email]").focus();
				return false;
			}
		});
	$(".touxiang2").click(function(){
	document.getElementById("tou_file").click(); 
	});
	$("body").mousemove(function(){
		if( $("[name=pic]").val() != "" )
		{
			$("#WenPic").html($("[name=pic]").val());
		}
		else
		{
			$("#WenPic").html("");
		}
	});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#新建用户
function MemberNew_phone()
{
	#当前模板
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$subject = '
	<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit_phone">
		<div class="userheader2_phone"><i class="f7-icons size-22">person_fill</i> 新建用户</div>
		<div class="userjibie">用户级别:</div>
		<div>
			<select name="level" class="selens">
				<option value="0">管理员</option>
				<option value="1">网站编辑</option>
				<option value="2">作者</option>
				<option value="3">协作者</option>
				<option value="4">评论员</option>
				<option value="5">游客</option>
			</select>
			&nbsp;
			<span style="font-size:14px;">( <b>状态:</b>
			<input type="radio" name="state" value="0" checked="checked" id="radio1"/> <label for="radio1">正常<label> &nbsp;
			<input type="radio" name="state" value="1" id="radio2"/> <label for="radio2">审核<label> &nbsp;
			<input type="radio" name="state" value="2" id="radio3"/> <label for="radio3">禁止<label> )
			</span>
		</div>
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="userName" vlaue="" class="inputs-s"/></div>
		<div class="userjibie">密码: </div>
		<div><input type="password" name="pwd" vlaue="" class="inputs-s"/></div>
		<div class="userjibie">确认密码: </div>
		<div><input type="password" name="pwd2" vlaue="" class="inputs-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="alias" vlaue="" class="inputs-s"/></div>
		<div class="userjibie">邮箱: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="email" vlaue="" class="inputs-s"/></div>
		<div class="userjibie">主页:</div>
		<div><input type="text" name="homepage" vlaue="" class="inputs-s"/></div>
		<div class="userjibie">摘要:</div>
		<div><textarea name="abst" class="input-w"></textarea></div>
		<div class="userjibie">模板:</div>
		<div>
			<select name="Template" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>
		<div class="userjibie">默认头像:</div>
		<div class="clear">
		<div class="touxiang1"><img src="'.site_url('header/0.png').'" width="40" height="40"/></div>
		<div class="touxiang2">本地更换</div> &nbsp; <span id="WenPic" style="color:#FF0000;"></span>
		<input type="file" id="tou_file" name="pic" style="display:none">
		</div>
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="act" value="MemberNew2" class="sub"/>
		<input type="submit" value="提交" class="subClass"/>
		</div>
	</div></form>';
	$subject .= '<script>
		$(function(){
		$("#frm").submit(function(){
			if($("[name=userName]").val()=="")
			{
				alert("名称未命名");
				$("[name=userName]").focus();
				return false;
			}
			if($("[name=pwd]").val()=="")
			{
				alert("请输入密码");
				$("[name=pwd]").focus();
				return false;
			}
			if($("[name=pwd2]").val()=="")
			{
				alert("请输入确认密码");
				$("[name=pwd2]").focus();
				return false;
			}
			if( $("[name=pwd]").val() != $("[name=pwd2]").val() )
			{
				alert("两次密码不一致");
				$("[name=pwd2]").focus();
				return false;
			}
			if($("[name=email]").val()=="")
			{
				alert("邮箱不能留空");
				$("[name=email]").focus();
				return false;
			}
			var email = $("[name=email]").val();
			email = email.replace(/(^\s*)|(\s*$)/g, "");
			var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
			if(reg.test(email)==false)
			{
				alert("邮箱格式正确");
				$("[name=email]").focus();
				return false;
			}
		});
	$(".touxiang2").click(function(){
	document.getElementById("tou_file").click(); 
	});
	$("body").mousemove(function(){
		if( $("[name=pic]").val() != "" )
		{
			$("#WenPic").html($("[name=pic]").val());
		}
		else
		{
			$("#WenPic").html("");
		}
	});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#用户编辑
function MemberEdt()
{
	if(!empty($_GET['id']))
	{
		$row = db()->select('id,level,state,userName,alias,email,homepage,abst,Template,pic')->from(PRE.'login')->where(array('id'=>mysql_escape_string($_GET['id'])))->get()->array_row();		
	}	
		$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
		
		$subject = '
		<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit">
		<div class="userheader">用户编辑</div>
		<div class="userjibie">用户级别:</div>
		<div>
			<select name="level" class="input-x">
				<option value="0" '.($row['level']=='0'?'selected="selected"':'').'>管理员</option>
				<option value="1" '.($row['level']=='1'?'selected="selected"':'').'>网站编辑</option>
				<option value="2" '.($row['level']=='2'?'selected="selected"':'').'>作者</option>
				<option value="3" '.($row['level']=='3'?'selected="selected"':'').'>协作者</option>
				<option value="4" '.($row['level']=='4'?'selected="selected"':'').'>评论员</option>
				<option value="5" '.($row['level']=='5'?'selected="selected"':'').'>游客</option>
			</select>
			&nbsp;
			<span style="font-size:14px;">( <b>状态:</b>
			<input type="radio" name="state" value="0" '.($row['state']==0?'checked="checked"':'').' id="radio1"/> <label for="radio1">正常<label> &nbsp;
			<input type="radio" name="state" value="1" '.($row['state']==1?'checked="checked"':'').' id="radio2"/> <label for="radio2">审核<label> &nbsp;
			<input type="radio" name="state" value="2" '.($row['state']==2?'checked="checked"':'').' id="radio3"/> <label for="radio3">禁止<label> )
			</span>
		</div>
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="userName" value="'.$row['userName'].'" readonly="readonly"  class="input-s" style="background:#EEEEEE;"/></div>
		<div class="userjibie">密码: </div>
		<div><input type="password" name="pwd" value="" class="input-s"/></div>
		<div class="userjibie">确认密码: </div>
		<div><input type="password" name="pwd2" value="" class="input-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="alias" value="'.$row['alias'].'" class="input-s"/></div>
		<div class="userjibie">邮箱: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="email" value="'.$row['email'].'" class="input-s"/></div>
		<div class="userjibie">主页:</div>
		<div><input type="text" name="homepage" value="'.$row['homepage'].'" class="input-s"/></div>
		<div class="userjibie">摘要:</div>
		<div><textarea name="abst" class="input-w">'.$row['abst'].'</textarea></div>
		<div class="userjibie">模板:</div>
		<div>
			<select name="Template" class="input-x">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>
		<div class="userjibie">默认头像:</div>
		<div class="clear">
		<div class="touxiang1">
		<img src="'.($row['pic']==''?site_url('header/0.png'):site_url($row['pic'])).'" width="40" height="40"/>
		</div>
		<div class="touxiang2">本地更换</div> &nbsp; <span id="WenPic" style="color:#FF0000;"></span>
		<input type="file" id="tou_file" name="pic" style="display:none">
		</div>
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="id" value="'.$_GET['id'].'"/>
		<input type="hidden" name="act" value="MemberEdt"/>
		<input type="submit" value="提交" class="sub"/>
		</div>
	</div></form>';
	$subject .= '<script>
$(function(){
	$(".touxiang2").click(function(){
		document.getElementById("tou_file").click();	
	});
	$("body").mousemove(function(){
		if( $("[name=pic]").val() != "" )
		{
			$("#WenPic").html($("[name=pic]").val());
		}
		else
		{
			$("#WenPic").html("");
		}
	});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#用户编辑
function MemberEdt_phone()
{
	if(!empty($_GET['id']))
	{
		$row = db()->select('id,level,state,userName,alias,email,homepage,abst,Template,pic')->from(PRE.'login')->where(array('id'=>mysql_escape_string($_GET['id'])))->get()->array_row();		
	}	
		$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
		
		$subject = '
		<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit_phone">
		<div class="userheader2_phone"><i class="f7-icons size-22">person_fill</i> 修改用户</div>
		<div class="userjibie">用户级别:</div>
		<div>
			<select name="level" class="selens">
				<option value="0" '.($row['level']=='0'?'selected="selected"':'').'>管理员</option>
				<option value="1" '.($row['level']=='1'?'selected="selected"':'').'>网站编辑</option>
				<option value="2" '.($row['level']=='2'?'selected="selected"':'').'>作者</option>
				<option value="3" '.($row['level']=='3'?'selected="selected"':'').'>协作者</option>
				<option value="4" '.($row['level']=='4'?'selected="selected"':'').'>评论员</option>
				<option value="5" '.($row['level']=='5'?'selected="selected"':'').'>游客</option>
			</select>
			&nbsp;
			<span style="font-size:14px;">( <b>状态:</b>
			<input type="radio" name="state" value="0" '.($row['state']==0?'checked="checked"':'').' id="radio1"/> <label for="radio1">正常<label> &nbsp;
			<input type="radio" name="state" value="1" '.($row['state']==1?'checked="checked"':'').' id="radio2"/> <label for="radio2">审核<label> &nbsp;
			<input type="radio" name="state" value="2" '.($row['state']==2?'checked="checked"':'').' id="radio3"/> <label for="radio3">禁止<label> )
			</span>
		</div>
		<div class="userjibie">名称: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="userName" value="'.$row['userName'].'" readonly="readonly"  class="inputs-s" style="background:#EEEEEE;"/></div>
		<div class="userjibie">密码: </div>
		<div><input type="password" name="pwd" value="" class="inputs-s"/></div>
		<div class="userjibie">确认密码: </div>
		<div><input type="password" name="pwd2" value="" class="inputs-s"/></div>
		<div class="userjibie">别名: </div>
		<div><input type="text" name="alias" value="'.$row['alias'].'" class="inputs-s"/></div>
		<div class="userjibie">邮箱: <span style="color:#FF2F2F;font-weight:normal;">(*)</span> </div>
		<div><input type="text" name="email" value="'.$row['email'].'" class="inputs-s"/></div>
		<div class="userjibie">主页:</div>
		<div><input type="text" name="homepage" value="'.$row['homepage'].'" class="inputs-s"/></div>
		<div class="userjibie">摘要:</div>
		<div><textarea name="abst" class="input-w">'.$row['abst'].'</textarea></div>
		<div class="userjibie">模板:</div>
		<div>
			<select name="Template" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
		</div>
		<div class="userjibie">默认头像:</div>
		<div class="clear">
		<div class="touxiang1">
		<img src="'.($row['pic']==''?site_url('pic/defualt/0.png'):site_url($row['pic'])).'" width="40" height="40"/>
		</div>
		<div class="touxiang2">本地更换</div> &nbsp; <span id="WenPic" style="color:#FF0000;"></span>
		<input type="file" id="tou_file" name="pic" style="display:none">
		</div>
		<div class="userjibie" style="padding-left:10px;margin-bottom:15px;">
		<input type="hidden" name="id" value="'.$_GET['id'].'"/>
		<input type="hidden" name="act" value="MemberEdt"/>
		<input type="submit" value="提交" class="subClass"/>
		</div>
	</div></form>';
	$subject .= '<script>
$(function(){
	$(".touxiang2").click(function(){
		document.getElementById("tou_file").click();	
	});
	$("body").mousemove(function(){
		if( $("[name=pic]").val() != "" )
		{
			$("#WenPic").html($("[name=pic]").val());
		}
		else
		{
			$("#WenPic").html("");
		}
	});
});
	</script>';
	return str_replace(array("\n","\t"), array("",""), $subject);
}
###################################################################################
#文章编辑
function ArticleEdt()
{
	session_start();
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	#所有栏目	
	//$column = db()->select('id,name')->from(PRE.'template')->where(array('templateid'=>$theme['id']))->get()->array_rows();
	$column = GetFenLai('0');
	#作者
	$author = db()->select('userName')->from(PRE.'login')->get()->array_rows();
	#分类
	$rows = db()->select('id,classified')->from(PRE.'classified')->where(array('templateid'=>$theme['id']))->order_by('sort desc')->get()->array_rows();
	#标签
	$tags = db()->select('id,keywords')->from(PRE.'tag')->where(array('templateid'=>$theme['id']))->order_by('id desc')->limit('0,50')->get()->array_rows();
	#获取往期
	$stage = db()->select('stage')->from(PRE.'article')->order_by('stage desc')->limit('0,5')->get()->array_rows();
	#静态化插件
	include Pagecall('static');
	$subject = '<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit">
		<div class="userheader">发布编辑</div>
		<div class="userjibie ujt">标题</div>
		<div><input type="text" name="title" placeholder="未命名" class="inputs-s"/></div>
		<div class="userjibie ujt">正文</div>
		<div><script id="container" name="body" type="text/plain" style="width:90%;height:309px;"></script></div>
		<div class="userjibie ujt">别名 <input type="text" name="alias" class="inputs-w"/></div>
		<div class="ujt" style="font-weight:bold;">
		   标签
		 <input type="text" name="keywords" autoComplete="off" class="inputs-w"/>
		  <span style="font-weight:normal;font-size:13px;">(逗号分割) <a href="javascript:;" id="showClBox" style="color:#1D4C7D;" title="点击">显示常用标签</a></span>
		  <dl id="listDls" class="clear">';
if(!empty($tags))
{		  
	foreach($tags as $k=>$v)
	{
		$subject .= '<dd>'.$v["keywords"].'</dd>';		
	}	
}		  	
	$subject .= ' </dl>
		 </div>
		<div class="userjibie ujt">摘要</div>
		<div style="font-size:15px;color:#333333;margin-bottom:2px;">* 在正文首段落开始，则首段落内容将作为摘要。您也可以<font class="D4C7D" title="点击">[点击自动生成摘要]</font></div>
		<div class="showZhaiYao" style="display: block;">
			<div style="margin-bottom:10px;"><script id="at" name="description" type="text/plain" style="width:90%;height:100px;"></script></div>
		</div>
		
		<div class="userjibie ujt">
			期数: # 第 <input type="text" name="stage" placeholder="几" class="inputs-w" style="text-align:center;width:45px;"/> 期 #
			&nbsp; &nbsp; &nbsp; &nbsp; 
			上一期:
			<select>';
if(!empty($stage))
{	
	foreach( $stage as $k=>$v )
	{
		$subject .= ' <option>'.$v['stage'].'</option>';
	}
}		
		$subject .= ' </select>
		</div>
		
		<div class="userjibie ujt">内容封面图片</div>
		<div>
		<input type="file" name="cover" size="60" onchange="previewImage(this);" style="border: 1px solid #CCCCCC;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;">
		<font color="#555555">图片大小要求98*77像素，上传最大2M，类型要求(jpeg，jpg，gif，png)</font>';
	$subject .= '<div style="border:1px solid #CCCCCC;margin-top:2px;width:98px;">
		<span style="color:#666666;">浏览封面图片</span>
		<img src="'.apth_url('system/admin/pic/defualt/a-ettra01.jpg').'" width="98" height="77" id="img_url">
		</div>';
	$subject .= '</div>
		<div style="height:5px;"></div>
		<div class="userjibie ujt">辅助信息</div>
		<div class="userjibie ujt">售价/工资/范围 <input type="text" name="price" class="inputs-w"/> <span style="font-weight:normal;font-size:13px;color:#666666;">&yen;/$</span></div>
		<div class="userjibie ujt">原价/工资/范围 <input type="text" name="orprice" class="inputs-w"/> <span style="font-weight:normal;font-size:13px;color:#666666;">&yen;/$</span></div>
		<div class="userjibie ujt">销量/数量/人数 <input type="text" name="Sales" class="inputs-w"/> </div>
		<div class="userjibie ujt">链接/文字/其它 <input type="text" name="chain" class="inputs-w"/> <span style="font-weight:normal;font-size:13px;color:#666666;">*+</span></div>
		<div class="userjibie ujt">
		类型 &nbsp; &nbsp;  
		<input type="radio" name="sizetype" value="1" id="s2"/>
		<label for="s2" style="color:#666666;">热销</label>  
		&nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="2" id="s3"/>
		<label for="s3" style="color:#666666;">新品</label>  
		&nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="3" id="s4"/>
		<label for="s4" style="color:#666666;">流行</label>  
		&nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="4" id="s5"/>
		<label for="s5" style="color:#666666;">时尚</label>   
		&nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="5" id="s6"/>
		<label for="s6" style="color:#666666;">推荐</label>  
		&nbsp; &nbsp; 
		<input type="radio" name="sizetype" value="6" id="s7"/>
		<label for="s7" style="color:#666666;">其它</label></div>
		<div style="height:50px;"></div>
		';
if($data['filter'] == 'ON')
{	
	$subject .= '<input type="hidden" name="posflag" value="'.$data['art'].'"/>'; 
}	
else 
{
	$subject .= '<input type="hidden" name="posflag" value="0"/>'; 
}	
	$subject .= '</div>
		<ul class="buttom-girg">
			<li class="subClass1"></li><li>
			<input type="hidden" name="act" value="ArticleEdt" class="subClass"/>
			<input type="submit" value="提交" class="subClass"/>
			</li>
			<li class="clfl">栏目&nbsp;</li>
			<li>
			<select name="columnid" class="selens">
			<option value="0">所有栏目</option>';
if(!empty($column))
{    
	foreach($column as $v)
	{
    $subject .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
	}
}    
    $subject .= '</select></li>
			<li class="clfl">分类&nbsp;</li>
			<li>
			<select name="cipid" class="selens">';
	$subject .= '<option value="-1" selected="selected">--请选择分类--</option>';
if(!empty($rows))
{
	foreach($rows as $k=>$v)
	{			
		$subject .= '<option value="'.$v["id"].'">'.$v["classified"].'</option>';
	}
}
else 
{
	$subject .= '<option value="1">未分类</option>';	
}
    $subject .= '</select>
			</li>
			<li class="clfl">状态&nbsp;</li>
			<li>
			<select name="state" class="selens">
				<option value="0">公开</option>
				<option value="1">草稿</option>
				<option value="2">审核</option>
			</select>
			</li>
			<li class="clfl">主题&nbsp;</li>
			<li>
			<select name="templateid" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
			</li>
			<li class="clfl">作者&nbsp;</li>
			<li>
			<select name="author" class="selens">';
if(!empty($author))
{	
	foreach($author as $k=>$v)
	{		
		$subject .= '<option value="'.$v["userName"].'" '.($v["userName"]==$_SESSION["username"]?'selected="selected"':'').'>'.$v["userName"].'</option>';
	}
}
else 
{
	$subject .= '<option value="'.$_SESSION["username"].'">'.$_SESSION["username"].'</option>';
}				
	$subject .= '</select>
			</li>
			<li class="clfl">定时&nbsp;</li>
			<li><input type="text" id="edtDateTime" name="timerel" value="" class="selens1"/></li>
			<li class="clfl"><input type="hidden" id="offon1" name="offon1" value="ON"/></li>
			<li class="clens">
			<span class="clzhid" style="width:40px;font-weight:bold;font-size:15px;text-align:center;">置顶</span>
			<span class="clzhid offno1" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
			<span class="clzhid showcl" style="width:60px;text-align:center;display:none;">
			<select name="top">
			<option value="101">首页</option>
			<option value="102">全部</option>
			<option value="103">分页</option>
			</select>
			</span>
			</li>
			<li class="clfl"><input type="hidden" id="offon2" name="nocomment" value="ON"/></li>
			<li class="clens"><span class="clzhid" style="width:75px;font-weight:bold;font-size:15px;text-align:center;">禁止评论</span><span class="clzhid offno2" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span></li>			
		</ul>
		</form>';
	$subject .= '
    <script type="text/javascript" src="plugin/UEditor/ueditor.config.js"></script>
    <script type="text/javascript" src="plugin/UEditor/ueditor.all.js"></script>
    <script type="text/javascript" src="plugin/UEditor/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript">
        var ue1 = UE.getEditor("container");
         var ue2 = UE.getEditor("at",{
            //这里可以选择自己需要的工具按钮名称,此处仅选择如下五个
            toolbars:[["Undo","Redo","bold","italic","underline","forecolor", "backcolor","indent","justifyleft", "justifycenter", "justifyright","lineheight","fontfamily", "fontsize","emotion","link","horizontal","inserttable","spechars"]],
            //focus时自动清空初始化时的内容
            autoClearinitialContent:false,
            //关闭字数统计
            wordCount:true,
            //关闭elementPath
            elementPathEnabled:false,
            //默认的编辑区域高度
            initialFrameHeight:120
            //更多其他参数，请参考ueditor.config.js中的配置项
           // serverUrl: "/server/ueditor/controller.php"
        });
        $("#frm").submit(function(){
			if( $("[name=title]").val() == "" )
			{
				alert("标题未命名");
				$("[name=title]").focus();
				return false;
			}
			var ue1Str = ue1.getContentTxt();
			if( ue1Str == "" )
			{
				alert("正文为空");
				return false;
			}
			if( $("[name=stage]").val() == "" )
			{
				alert("请编辑期数");
				$("[name=stage]").focus();
				return false;
			}
			if( $("[name=cover]").val() == "" )
			{
				alert("至少提供一张封面");
				$("[name=cover]").focus();
				return false;
			}			
			var cfClass = $("[name=cipid]").val()
			if(cfClass == -1)
			{
				alert("请选择分类");
				$("[name=cipid]").focus();
				return false;
			}
		});   
        $(function(){
        $("#showClBox").click(function(){
		if($("#listDls").is(":hidden"))
		{
			$("#listDls").show();
		}
		else
		{
			$("#listDls").hide();
		}
});
        var listDlsstr = "";
        $("#listDls dd").click(function(){   
        listDlsstr += (listDlsstr==""?"":",")+$(this).html();
		$("[name=keywords]").val(listDlsstr);
});
        $("#showClBox").hover(function(){
$(this).css({"color":"#FF0000"});
},function(){
$(this).css({"color":"#1D4C7D"});
});
	$(".D4C7D").click(function(){
		//$(".showZhaiYao").show();
		var ue1Str = ue1.getContentTxt();
		ue1Str = ue1Str.substring(0,500);
		ue2.setContent(ue1Str);
		var scrollBottom = $(window).scrollTop() + $(window).height();
		$("body,html").animate({scrollTop:scrollBottom},400);
});
		var i=0;
		$(".offno1").click(function(){
		if(i%2==0)
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$(".showcl").show();
			$("#offon1").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$(".showcl").hide();
			$("#offon1").val("ON");//关闭
		}
		i++;
});
var y=0;
		$(".offno2").click(function(){
		if(y%2==0)
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$("#offon2").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$("#offon2").val("ON");//关闭
		}
		y++;
});
});
$(function(){
//日期时间控件
var dt = new Date();
$("#edtDateTime").val(dt.getFullYear()+"-"+getInts(dt.getMonth()+1)+"-"+getInts(dt.getDate())+" "+getInts(dt.getHours())+":"+getInts(dt.getMinutes())+":"+getInts(dt.getSeconds()));
function getInts(int){if(int<10){var int = "0"+int;} return int;};
$.datepicker.regional["zh-CN"] = {
  closeText: "完成",
  prevText: "上个月",
  nextText: "下个月",
  currentText: "现在",
  monthNames: ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
  monthNamesShort: ["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"],
  dayNames: ["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
  dayNamesShort: ["周日","周一","周二","周三","周四","周五","周六"],
  dayNamesMin: ["日","一","二","三","四","五","六"],
  weekHeader: "周",
  dateFormat: "yy-mm-dd",
  firstDay: 1,
  isRTL: false,
  showMonthAfterYear: true,
  yearSuffix: " 年  "
};
$.datepicker.setDefaults($.datepicker.regional["zh-CN"]);
$.timepicker.regional["zh-CN"] = {
  timeOnlyTitle: "时间",
  timeText: "时间",
  hourText: "小时",
  minuteText: "分钟",
  secondText: "秒钟",
  millisecText: "毫秒",
  currentText: "现在",
  closeText: "完成",
  timeFormat: "HH:mm:ss",
  ampm: false
};
$.timepicker.setDefaults($.timepicker.regional["zh-CN"]);
$("#edtDateTime").datetimepicker({
  showSecond: true
  //changeMonth: true,
  //changeYear: true
});
});

	function previewImage(file){	
		if(file.files && file.files[0]){
			var img = $("#img_url")[0];
			var reader = new FileReader(),rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;;  				
			
			var size = file.size;
			var Max_Size = 2000*1024;
			var width,height;
			var image = new Image();
					
			reader.onload = function(evt){
				img.src=evt.target.result;
				
				var data = evt.target.result;
				/*
				 image.onload=function(){
					width = image.width;
					height = image.height;
					if(width>img.width && height>img.height){ 
						alert("图片宽高不符合要求，请上传宽高"+img.width+"*"+img.height+"像素图片");
					}					
				};*/
				image.src= data;			 
			} 		
				
		    if(!rFilter.test(file.files[0].type)) { alert("文件类型不正确 "); return; }	
		    if(size>Max_Size){ alert("文件大小不能超出2M"); return; }
		    
		    reader.readAsDataURL(file.files[0]);	
	    }	    
	}

    </script>';	
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#文章编辑
function ArticleEdt_phone()
{
	session_start();
	#主题
	$theme = db()->select('id,themeas')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	#所有栏目	
	//$column = db()->select('id,name')->from(PRE.'template')->where(array('templateid'=>$theme['id'],'forbidden'=>'ON'))->get()->array_rows();
	$column = GetFenLai('0');
	#作者
	$author = db()->select('userName')->from(PRE.'login')->get()->array_rows();
	#分类
	$rows = db()->select('id,classified')->from(PRE.'classified')->where(array('templateid'=>$theme['id']))->order_by('sort desc')->get()->array_rows();
	#标签
	$tags = db()->select('id,keywords')->from(PRE.'tag')->where(array('templateid'=>$theme['id']))->order_by('id desc')->limit('0,50')->get()->array_rows();
	#静态化插件
	include Pagecall('static');
	$subject = '<form action="handling_events.php" id="frm" method="post" enctype="multipart/form-data">
		<div class="useredit_phone">
		<div class="userheader2_phone"><i class="f7-icons size-22">compose_fill</i> 发布编辑</div>
		<div class="userjibie ujt">标题</div>
		<div><input type="text" name="title" placeholder="未命名" class="inputs-s"/></div>
		<div class="userjibie ujt">正文</div>
		<div>
			<textarea name="body" id="container_phone"></textarea>
		</div>
		<div class="userjibie ujt">
		<p>别名 </p>
		<input type="text" name="alias" class="inputs-s"/></div>
		<div class="ujt" style="font-weight:bold;">
		 <p>标签  <a href="javascript:;" id="showClBox" style="color:#1D4C7D;">[点击显示常用标签]</a></p>
		 <input type="text" name="keywords" autoComplete="off" class="inputs-s"/>
		  <dl id="listDls" class="clear">';
if(!empty($tags))
{		  
	foreach($tags as $k=>$v)
	{
		$subject .= '<dd>'.$v["keywords"].'</dd>';		
	}	
}		  	
	$subject .= ' </dl>
		 </div>
		<div class="userjibie ujt">摘要 </div>
		<div class="showZhaiYao" style="display: block;">
			<div style="margin-bottom:10px;">
				<textarea name="description" id="description" class="input-w"></textarea>
			</div>
		</div>
		<div class="userjibie ujt">内容封面图片</div>
		<div>
		<input type="file" name="cover" size="60" style="border: 1px solid #CCCCCC;width:100%;padding: 0.25em 0.25em 0.25em 0.25em;background-position: bottom;background: #FFFFFF;font-size: 1em;border-radius:5px 5px 5px 5px;-moz-border-radius:5px 5px 5px 5px;-webkit-border-radius:5px 5px 5px 5px;">
		<p><font color="#555555">上传最大2M，类型要求(jpeg，jpg，gif，png)</font></p>';
	$subject .= '<div style="border:1px solid #999999;width:150px;margin-top:2px;border-radius:5px 5px 5px 5px;-moz-border-radius:5px 5px 5px 5px;-webkit-border-radius:5px 5px 5px 5px;">
		<span>默认封面图片</span>
		<p><img src="'.apth_url('system/admin/pic/defualt/a-ettra01.jpg').'" width="150"></p>
		</div>';
	$subject .= '</div>
		<div style="height:5px;"></div>
		<div class="userjibie ujt">
		<p>售价/工资/范围 </p>
		<input type="text" name="price" class="inputs-s"/></div>
		<div class="userjibie ujt">
		<p>原价/工资/范围  </p>
		<input type="text" name="orprice" class="inputs-s"/></div>
		<div class="userjibie ujt">
		<p>销量/数量/人数  </p>
		<input type="text" name="Sales" class="inputs-s"/></div>
		<div class="userjibie ujt">
		<p>链接/文字/其它  </p>
		<input type="text" name="chain" class="inputs-s"/></div>
		<div class="userjibie ujt">
		<p>类型 </p> 
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="1" id="s2"/>
		<label for="s2" style="color:#666666;">热销</label>  
		</p>
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="2" id="s3"/>
		<label for="s3" style="color:#666666;">新品</label>  
		</p>
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="3" id="s4"/>
		<label for="s4" style="color:#666666;">流行</label>  
		</p>
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="4" id="s5"/>
		<label for="s5" style="color:#666666;">时尚</label>   
		</p> 
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="5" id="s6"/>
		<label for="s6" style="color:#666666;">推荐</label>  
		</p> 
		<p class="list_select_p">
		<input type="radio" name="sizetype" value="6" id="s7"/>
		<label for="s7" style="color:#666666;">其它</label>
		</p>
		</div>
		<div style="height:50px;"></div>
		';
if($data['filter'] == 'ON')
{	
	$subject .= '<input type="hidden" name="posflag" value="'.$data['art'].'"/>'; 
}	
else 
{
	$subject .= '<input type="hidden" name="posflag" value="0"/>';
}	
	$subject .= '</div>
		<ul class="buttom-girg_phone">
			<li class="subClass1"></li>
			<li class="clfl">栏目&nbsp;</li>
			<li>
			<select name="columnid" class="selens">
			<option value="0">所有栏目</option>';
if(!empty($column))
{    
	foreach($column as $v)
	{
    $subject .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
	}
}    
    $subject .= '</select></li>
			<li class="clfl">分类&nbsp;</li>
			<li>
			<select name="cipid" class="selens">';
		$subject .= '<option value="-1" selected="selected">--请选择分类--</option>';
if(!empty($rows))
{
	foreach($rows as $k=>$v)
	{			
		$subject .= '<option value="'.$v["id"].'">'.$v["classified"].'</option>';
	}
}
else 
{
	$subject .= '<option value="1">未分类</option>';	
}
    $subject .= '</select>
			</li>
			<li class="clfl">状态&nbsp;</li>
			<li>
			<select name="state" class="selens">
				<option value="0">公开</option>
				<option value="1">草稿</option>
				<option value="2">审核</option>
			</select>
			</li>
			<li class="clfl">主题&nbsp;</li>
			<li>
			<select name="templateid" class="selens">
				<option value="'.$theme['id'].'">'.$theme['themeas'].'</option>
			</select>
			</li>
			<li class="clfl">作者&nbsp;</li>
			<li>
			<select name="author" class="selens">';
if(!empty($author))
{	
	foreach($author as $k=>$v)
	{		
		$subject .= '<option value="'.$v["userName"].'" '.($v["userName"]==$_SESSION["username"]?'selected="selected"':'').'>'.$v["userName"].'</option>';
	}
}
else 
{
	$subject .= '<option value="'.$_SESSION["username"].'">'.$_SESSION["username"].'</option>';
}				
	$subject .= '</select>
			</li>
			<li class="clfl">定时&nbsp;</li>
			<li><input type="text" id="edtDateTime" name="timerel" value="'.date('Y-m-d H:i:s').'" class="selens1"/></li>
			<li class="clfl" style="height:10px;"><input type="hidden" id="offon1" name="offon1" value="ON"/></li>
			<li class="clens">
			<span class="clzhid" style="width:40px;font-weight:bold;font-size:15px;text-align:center;">置顶</span>
			<span class="clzhid offno1" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span>
			<span class="clzhid showcl" style="width:60px;text-align:center;display:none;">
			<select name="top">
			<option value="101">首页</option>
			<option value="102">全部</option>
			<option value="103">分页</option>
			<option value="104">系统更新</option>
			<option value="105">最新动态</option>
			</select>
			</span>
			</li>
			<li class="clfl"><input type="hidden" id="offon2" name="nocomment" value="ON"/></li>
			<li class="clens"><span class="clzhid" style="width:75px;font-weight:bold;font-size:15px;text-align:center;">禁止评论</span><span class="clzhid offno2" style="height:17px;margin-top:4px;background:url('.site_url('images/checkbox.png').') no-repeat 0 -17px;"></span></li>			
			<li style="height:30px;"></li>
			<li>
			<input type="hidden" name="act" value="ArticleEdt" class="subClass"/>
			<input type="submit" value="提交" class="subClass"/>
			</li>
		</ul>
		</form>';
	$subject .= '
    <script src="'.site_url('plugin/froala_editor/js/libs/jquery-1.11.1.min.js').'"></script>
 	<script src="'.site_url('plugin/froala_editor/js/froala_editor.min.js').'"></script>
  <!--[if lt IE 9]>
    <script src="'.site_url('plugin/froala_editor/js/froala_editor_ie8.min.js').'"></script>
  <![endif]-->
  <script src="'.site_url('plugin/froala_editor/js/plugins/tables.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/lists.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/colors.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/media_manager.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/font_family.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/font_size.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/block_styles.min.js').'"></script>
  <script src="'.site_url('plugin/froala_editor/js/plugins/video.min.js').'"></script>
    <script type="text/javascript">
   	 $(function () {
            $("#container_phone").editable({
                inlineMode: false,
                alwaysBlank: true,
                language: "zh_cn",
                direction: "ltr",
                allowedImageTypes: ["jpeg", "jpg", "png", "gif"],
                autosave: true,
                autosaveInterval: 2500,
                saveURL: "imgupload.php",
                saveParams: { postId: "123" },
                spellcheck: true,
                plainPaste: true,
                imageButtons: ["floatImageLeft", "floatImageNone", "floatImageRight", "linkImage", "replaceImage", "removeImage"],
                imageUploadURL: "imgupload.php",
                imageParams: { postId: "123" },
                enableScript: false
            })
     });
     $(function(){
		$(".froala-box [data-name=color]").hide();
		$(".froala-box [data-name=table]").hide();
		$(".froala-box [data-cmd=html]").hide();
		$(".froala-box [data-cmd=strikeThrough]").hide();
		$(".froala-box [data-cmd=underline]").hide();
		$(".froala-box [data-cmd=insertVideo]").hide();
		$(".froala-box [data-name=formatBlock]").hide();
	 });
        $("#frm").submit(function(){
			if($("[name=title]").val() == "")
			{
				alert("标题未命名");
				$("[name=title]").focus();
				return false;
			}
			var ue1Str = ue1.getContentTxt();
			if(ue1Str == "")
			{
				alert("正文为空");
				return false;
			}
			var cfClass = $("[name=cipid]").val()
			if(cfClass == -1)
			{
				alert("请选择分类");
				$("[name=cipid]").focus();
				return false;
			}
		});   
        $(function(){
        $("#showClBox").click(function(){
		if($("#listDls").is(":hidden"))
		{
			$("#listDls").show();
		}
		else
		{
			$("#listDls").hide();
		}
});
        var listDlsstr = "";
        $("#listDls dd").click(function(){   
        listDlsstr += (listDlsstr==""?"":",")+$(this).html();
		$("[name=keywords]").val(listDlsstr);
});
        $("#showClBox").hover(function(){
$(this).css({"color":"#FF0000"});
},function(){
$(this).css({"color":"#1D4C7D"});
});
	$(".D4C7D").click(function(){
		//$(".showZhaiYao").show();
		var ue1Str = ue1.getContentTxt();
		ue1Str = ue1Str.substring(0,500);
		ue2.setContent(ue1Str);
		var scrollBottom = $(window).scrollTop() + $(window).height();
		$("body,html").animate({scrollTop:scrollBottom},400);
});
		var i=0;
		$(".offno1").click(function(){
		if(i%2==0)
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$(".showcl").show();
			$("#offon1").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$(".showcl").hide();
			$("#offon1").val("ON");//关闭
		}
		i++;
});
var y=0;
		$(".offno2").click(function(){
		if(y%2==0)
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 0"});
			$("#offon2").val("OFF");//打开
		}
		else
		{
			$(this).css({"background":"url('.site_url('images/checkbox.png').') no-repeat 0 -17px"});
			$("#offon2").val("ON");//关闭
		}
		y++;
});
});
    </script>';	
	return str_replace(array("\n","\t"), array("",""), $subject);
}
#获取屏幕宽度
function pingmwh()
{
	$data = file_get_contents('../pingmupixels.xml');
	$pixels = simplexml_load_string($data);
	$w = (int)$pixels->pixels;
	if( $w > 1250){
		return true;#PC屏
	}else{
		return false;#移动屏
	}
}
#底部栏
function BottomColumn()
{
	echo '</body></html>';
}
###################################################################################