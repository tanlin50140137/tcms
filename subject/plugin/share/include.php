<?php 
/**
 * 主题调用外部插件 <?php include Pagecall('share');?>
 * */
$row = db()->select('xmlrpc,themeimg,themeas,addmenu')->from(PRE.'theme')->where(array('themename'=>'share'))->get()->array_row();
if($row['addmenu'] == 'OFF')
{
	if(!empty($row))
	{
		$share = (array)simplexml_load_string(file_get_contents(DIR_URL.'/'.$row['xmlrpc']));
	}
}
if( $share['page'] == 1 )
{#显示
switch (ACT)
{
	case 'index':
		$indexIn = 1;
	break;
	case 'article_list':
		$indexIn = 2;
	break;
	case 'article_content':
		$indexIn = 3;
	break;
	default:
		$indexIn = 4;
	break;	
}
$che = $share['check1']==''?'':$share['check1'];
$che .= $share['check2']==''?'':','.$share['check2'];
$che .= $share['check3']==''?'':','.$share['check3'];
$che .= $share['check4']==''?'':','.$share['check4'];
$check = explode(',', $che);
#样式1
if($share['boxstyle']==1)
{##00CCFF
	if( $share['art'] == 1 )
	{#左边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
	else 
	{#右边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}			
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';					
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
}
#样式2
if($share['boxstyle']==2)
{##00CCFF
	if( $share['art'] == 1 )
	{#左边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;move=0&amp;btn=l1.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l1.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';		
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;move=0&amp;btn=l1.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l1.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';	
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;btn=l1.gif" charset="utf-8"></script>';	
						echo '<!-- JiaThis Button END -->';	
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l1.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;btn=l1.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l1.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
	else 
	{#右边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?move=0&amp;btn=r1.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r1.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';																			
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?move=0&amp;btn=r1.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r1.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';								
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}			
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r1.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r1.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';	
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r1.gif" charset="utf-8"></script>';	
						echo '<!-- JiaThis Button END -->';	
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r1.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';								
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
}
#样式3
if($share['boxstyle']==3)
{##00CCFF
	if( $share['art'] == 1 )
	{#左边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;move=0&amp;btn=l2.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l2.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';		
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;move=0&amp;btn=l2.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l2.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';	
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;btn=l2.gif" charset="utf-8"></script>';	
						echo '<!-- JiaThis Button END -->';	
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l2.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;btn=l2.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l2.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
	else 
	{#右边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?move=0&amp;btn=r2.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r2.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';																			
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?move=0&amp;btn=r2.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r2.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';								
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}			
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r2.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r2.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';	
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r2.gif" charset="utf-8"></script>';	
						echo '<!-- JiaThis Button END -->';	
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r2.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';								
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
}
#样式4
if($share['boxstyle']==4)
{##00CCFF
	if( $share['art'] == 1 )
	{#左边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;move=0&amp;btn=l3.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l3.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';		
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;move=0&amp;btn=l3.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l3.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';	
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;btn=l3.gif" charset="utf-8"></script>';	
						echo '<!-- JiaThis Button END -->';	
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l3.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;btn=l3.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l3.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
	else 
	{#右边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?move=0&amp;btn=r3.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r3.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';																			
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?move=0&amp;btn=r3.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r3.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';								
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}			
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r3.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r3.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';	
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r3.gif" charset="utf-8"></script>';	
						echo '<!-- JiaThis Button END -->';	
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r3.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';								
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
}
#样式5
if($share['boxstyle']==5)
{##00CCFF
	if( $share['art'] == 1 )
	{#左边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;move=0&amp;btn=l4.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l4.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';		
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;move=0&amp;btn=l4.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l4.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';	
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;btn=l4.gif" charset="utf-8"></script>';	
						echo '<!-- JiaThis Button END -->';	
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l4.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;btn=l4.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l4.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
	else 
	{#右边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?move=0&amp;btn=r4.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r4.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';																			
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?move=0&amp;btn=r4.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r4.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';								
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}			
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r4.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r4.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';	
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r4.gif" charset="utf-8"></script>';	
						echo '<!-- JiaThis Button END -->';	
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r4.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';								
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
}
#样式6
if($share['boxstyle']==6)
{##00CCFF
	if( $share['art'] == 1 )
	{#左边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;move=0&amp;btn=l5.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l5.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';		
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;move=0&amp;btn=l5.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l5.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';	
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&amp;btn=l5.gif" charset="utf-8"></script>';	
						echo '<!-- JiaThis Button END -->';	
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?type=left&btn=l5.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&amp;btn=l5.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?type=left&btn=l5.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
	else 
	{#右边
		if( $share['index'] == 1 )
		{#固定浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?move=0&amp;btn=r5.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r5.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';																			
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?move=0&amp;btn=r5.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r5.gif&move=0" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';								
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}			
		}
		else 
		{#动态浮窗
			if( $share['lanmu'] == 1 )
			{#标准
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r5.gif" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?btn=r5.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';				
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
			else 
			{#迷你
				if( $share['hide'] == 1 )
				{#开启 
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';	
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r5.gif" charset="utf-8"></script>';	
						echo '<!-- JiaThis Button END -->';	
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
				else 
				{#隐藏
					if( in_array($indexIn, $check) )
					{
						echo '<!-- JiaThis Button BEGIN -->';
						echo '<script type="text/javascript" >';
						echo 'var jiathis_config={
							summary:"",
							showClose:true,
							shortUrl:false,
							hideMore:true
						}';
						echo '</script>';
						echo '<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?btn=r5.gif&move=1" charset="utf-8"></script>';
						echo '<!-- JiaThis Button END -->';								
						echo '<script type="text/javascript" >$(function(){$(".ckepopBottom .link_01").html("");$(".ckepopBottom .link_01").css({"src":"javascript:;"});})</script>';
					}
				}
			}
		}
	}
}
}
?>