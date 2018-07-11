<?php
require '../public_include.php';
session_start();

$user = $_REQUEST['user']==''?'':$_REQUEST['user'];
if( $user != '' )
{
	$userlv = db()->select('userName,level')->from(PRE.'login')->where(array('id'=>$user))->get()->array_row();
}
else if( isset($_SESSION['username']) && $_SESSION['username'] != null )
{
	$userlv = db()->select('userName,level')->from(PRE.'login')->where(array('userName'=>$_SESSION['username']))->get()->array_row();
}
else 
{
	$userlv = null;
}
#根限控制 
function PowarFunc($a,$int)
{
	if( !empty($a) )
	{
		switch ($int)
		{
			case 1:
				if($a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 2:
				if($a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 3:
				if($a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 4:
				if($a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 5:
				if($a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 6:
				if($a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 7:
				if($a['level']==2||$a['level']==3||$a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 8:
				if($a['level']==2||$a['level']==3||$a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 9:
				if($a['level']==2||$a['level']==3||$a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 10:
				if($a['level']==2||$a['level']==3||$a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 11:
				if($a['level']==2||$a['level']==3||$a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
			case 12:
				if($a['level']==2||$a['level']==3||$a['level']==4||$a['level']==5)
				{
					$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
				}
				else 
				{
					$html = '<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>';
				}
			break;
		}
	}
	else 
	{
		$html = '<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>';
	}
	return $html;
}
$subject = '<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1" />
	<meta name="robots" content="none" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0"/>
	<link rel="stylesheet" href="'.site_url('admin/css/common_style.css').'" type="text/css" media="screen" />
	<link rel="stylesheet" href="'.site_url('admin/css/admin_style.css').'" type="text/css" media="screen" />
	<script src="'.site_url('admin/js/jquery-1.8.3.min.js').'" type="text/javascript"></script>
	<title>权限管理</title>
</head>';
$subject .= '<body>';
$subject .= '<div style="border-top:1px solid #3A6EA5;height:85px;background:#3A6EA5;">
			<div style="margin:8px 0 0 15px;width:200px;height:70px;">
			<img src="'.site_url('admin/images/logo.png').'"/>
			</div>
		</div>';
$subject .= '<div style="margin:auto 250px;">
	<table class="tableBox" style="margin-top:8px;">
			<tr>
				<td style="text-align:center;" colspan="4">
				'.($userlv==null?'未登录':'当前用户：'.$userlv['userName']).'
				</td>
			</tr>
			
			<tr>
				<td style="text-align:center;" colspan="4">权限列表</td>
			</tr>
			
			<tr>
				<td style="text-align:center;" width="200">文章编辑</td>
				<td>
				'.PowarFunc($userlv,1).'			
				</td>
				<td style="text-align:center;" width="200">文章管理</td>
				<td>
				'.PowarFunc($userlv,2).'
				</td>
			</tr>';

	$subject .= '<tr>
				<td style="text-align:center;">页面管理</td>
				<td>
				'.PowarFunc($userlv,3).'
				</td>
				<td style="text-align:center;">分类管理</td>
				<td>
				'.PowarFunc($userlv,4).'
				</td>
			</tr>';
	
	$subject .= '<tr>
				<td style="text-align:center;">标签管理</td>
				<td>
				'.PowarFunc($userlv,5).'
				</td>
				<td style="text-align:center;">评论管理</td>
				<td>
				'.PowarFunc($userlv,6).'
				</td>
			</tr>';
	
	$subject .= '<tr>
				<td style="text-align:center;">附件管理</td>
				<td>
				'.PowarFunc($userlv,7).'
				</td>
				<td style="text-align:center;">用户管理</td>
				<td>
				'.PowarFunc($userlv,8).'
				</td>
			</tr>';
	
	$subject .= '<tr>
				<td style="text-align:center;">主题管理</td>
				<td>
				'.PowarFunc($userlv,9).'
				</td>
				<td style="text-align:center;">模块管理</td>
				<td>
				'.PowarFunc($userlv,10).'
				</td>
			</tr>';
	
	$subject .= '<tr>
				<td style="text-align:center;">插件管理</td>
				<td>
				'.PowarFunc($userlv,11).'
				</td>
				<td style="text-align:center;">应用中心</td>
				<td>
				'.PowarFunc($userlv,12).'
				</td>
			</tr>';
	
	$subject .= '<tr>
				<td style="text-align:center;" colspan="4">注：
				<img src="'.site_url('admin/images/ok.png').'" align="absmiddle"/>“权限可用”
				<img src="'.site_url('admin/images/exclamation.png').'" align="absmiddle"/>“没有权限”
				</td>
			</tr>';
	
	$subject .= '</table></div>';
				
	$subject .= '<script>
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
$subject .= '</body>
</html>';
echo $subject;