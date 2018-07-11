<?php
header('content-type:text/html;charset=utf-8');
/**
 * @author Tanlin
 * 组件模块功能
 * */
require '../../public_include.php';
header('content-type:text/html;charset=utf-8');
############################################################
#注消功能块
function Annotation()
{
	session_start();
	#销毁cookie
	setcookie(username,null,time()-1,"/");
	setcookie(password,null,time()-1,"/");
	#销毁session
	$_SESSION['username'] = null;
	session_unset($_SESSION['username']);
	#执行跳出
	header('location:../login.php');
}
#############################################################
/**
 * 订单提交接口功能块,同步提交
 * 接口字段说明：
 * commodity,商品名称
 * ordernumber,订单号
 * money,金额
 * phone,手机
 * email,邮箱
 * publitime,时间
 * address,地址
 * allinfor,所有订单信息，json格式
 * status,订单状态
 * back,同步返回URL
 * 添加验证码,getViryfy()
 * */
function orders()
{
	session_start();
	$virify = trim(strtolower($_SESSION['virify']));
	$morvirify = trim(strtolower($_REQUEST['mor-virify']));
	
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$data['templateid'] = $theme['id'];
	$data['userip'] = getIP();
	#同步返回链接
	$data['back'] = $_REQUEST['back'];
	if( $data['back'] == '' )
	{
		echo "<script>alert('请提供一个返回链接，否则无法返回参数!');location.href='".apth_url('index.php')."';</script>";exit;
	}
	
	if( $virify != $morvirify )
	{
		echo "<script>alert('验证码不正确!');location.href='".$data['back']."';</script>";exit;
	}

	$data['commodity'] = $_REQUEST['commodity'];
	if( $data['commodity'] == '' )
	{
		echo "<script>alert('商品名称不能留空');location.href='".$data['back']."';</script>";exit;
	}
	$data['ordernumber'] = $_REQUEST['ordernumber'];
	if( $data['ordernumber'] == '' )
	{
		echo "<script>alert('订单号不能留空');location.href='".$data['back']."';</script>";exit;
	}
	$data['money'] = $_REQUEST['money'];
	if( $data['money'] == '' )
	{
		echo "<script>alert('金额不能留空');location.href='".$data['back']."';</script>";exit;
	}
	$data['phone'] = $_REQUEST['phone'];
	if( $data['phone'] == '' )
	{
		echo "<script>alert('手机不能留空');location.href='".$data['back']."';</script>";exit;
	}
	else 
	{
		if(strlen( $_REQUEST['phone']) == 11 )
		{
			if(!preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_REQUEST['phone']) )
			{
				echo "<script>alert('请输入正确的手机号码');location.href='".$data['back']."';</script>";exit;
			}
		}
		else 
		{
			echo "<script>alert('手机长度应为11位');location.href='".$data['back']."';</script>";exit;
		}
	}
	$data['email'] = $_REQUEST['email'];
	if( $data['email'] != '' )
	{
		if( !preg_match("/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/",$data['email']) )
		{
			echo "<script>alert('请输入正确的邮箱');location.href='".$data['back']."';</script>";exit;
		}
	}
	$data['publitime'] = $_REQUEST['publitime']==''?time():$_REQUEST['publitime'];
	$data['address'] = $_REQUEST['address']==''?'':$_REQUEST['address'];
	if( $data['address'] == '' )
	{
		echo "<script>alert('地址不能留空');location.href='".$data['back']."';</script>";exit;
	}
	#所有订单信息，json格式
	$data['allinfor'] = json_encode($data);
	
	$int = db()->insert(PRE.'orders',$data);
	$id = db()->getlast_id();
	if($int)
	{
		header('location:'.get_back_all($data['back'],'userid='.$id));
	}
	else
	{
		header('location:'.$data['back']);
	}
}
#异步提交
function orders_asyn()
{
	session_start();
	$virify = trim(strtolower($_SESSION['virify']));
	$morvirify = trim(strtolower($_REQUEST['mor-virify']));
	
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$data['templateid'] = $theme['id'];
	$data['userip'] = getIP();
	#同步返回链接
	$data['back'] = $_REQUEST['back'];
	
	if( $virify != $morvirify )
	{
		echo '验证码不正确!';exit;
	}
	$_SESSION['virify']=null;
	unset($_SESSION['virify']);
	
	$data['commodity'] = $_REQUEST['commodity'];
	if( $data['commodity'] == '' )
	{
		echo '商品名称不能留空';exit;
	}
	$data['ordernumber'] = $_REQUEST['ordernumber'];
	if( $data['ordernumber'] == '' )
	{
		echo '订单号不能留空';exit;
	}
	$data['money'] = $_REQUEST['money'];
	if( $data['money'] == '' )
	{
		echo '金额不能留空';exit;
	}
	$data['phone'] = $_REQUEST['phone'];
	if( $data['phone'] == '' )
	{
		echo '手机不能留空';exit;
	}
	else 
	{
		if(strlen( $_REQUEST['phone']) == 11 )
		{
			if(!preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_REQUEST['phone']) )
			{
				echo '请输入正确的手机号码';exit;
			}
		}
		else 
		{
			echo '手机长度应为11位';exit;
		}
	}
	$data['email'] = $_REQUEST['email'];
	if( $data['email'] != '' )
	{
		if( !preg_match("/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/",$data['email']) )
		{
			echo '请输入正确的邮箱';exit;
		}
	}
	$data['publitime'] = $_REQUEST['publitime']==''?time():$_REQUEST['publitime'];
	$data['address'] = $_REQUEST['address']==''?'':$_REQUEST['address'];
	if( $data['address'] == '' )
	{
		echo '地址不能留空';exit;
	}
	#所有订单信息，json格式
	$data['allinfor'] = json_encode($data);
	
	$int = db()->insert(PRE.'orders',$data);
	$id = db()->getlast_id();
	if($int)
	{
		echo 'ok';
	}
	else
	{
		echo 'on';
	}
}
#############################################################
/**
 * 留言提交接口功能块,同步提交
 * 接口字段说明：
 * pid,回复
 * body,正文
 * phone,手机
 * email,邮箱
 * publitime,时间
 * status,状态
 * back,同步返回URL
 * 添加验证码,getViryfy()
 * */
function message()
{
	session_start();
	$virify = trim(strtolower($_SESSION['virify']));
	$morvirify = trim(strtolower($_REQUEST['mor-virify']));
	
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$data['templateid'] = $theme['id'];
	$data['userip'] = getIP();
	#同步返回链接
	$data['back'] = $_REQUEST['back'];
	if( $data['back'] == '' )
	{
		echo "<script>alert('请提供一个返回链接，否则无法返回参数!');location.href='".apth_url('index.php')."';</script>";exit;
	}
	
	if( $virify != $morvirify )
	{
		echo "<script>alert('验证码不正确!');location.href='".$data['back']."';</script>";exit;
	}
	
	$data['name'] = $_REQUEST['name'];
	if( $data['name'] == '' )
	{
		echo "<script>alert('姓名不能留空');location.href='".$data['back']."';</script>";exit;
	}
	$data['age'] = $_REQUEST['age'];
	if( $data['age'] == '' )
	{
		echo "<script>alert('年龄不能留空');location.href='".$data['back']."';</script>";exit;
	}
	$data['pid'] = $_REQUEST['pid']==''?0:$_REQUEST['pid'];
	$data['body'] = $_REQUEST['body'];
	if( $data['body'] == '' )
	{
		echo "<script>alert('正文不能留空');location.href='".$data['back']."';</script>";exit;
	}
	$data['phone'] = $_REQUEST['phone'];
	if( $data['phone'] == '' )
	{
		echo "<script>alert('手机不能留空');location.href='".$data['back']."';</script>";exit;
	}
	else 
	{
		if(strlen($_REQUEST['phone']) == 11)
		{
			if(!preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_REQUEST['phone']) )
			{
				echo "<script>alert('请输入正确的手机号码');location.href='".$data['back']."';</script>";exit;
			}
		}
		else 
		{
			echo "<script>alert('手机长度应为11位');location.href='".$data['back']."';</script>";exit;
		}
	}
	$data['email'] = $_REQUEST['email'];
	if( $data['email'] != '' )
	{
		if( !preg_match("/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/",$data['email']) )
		{
			echo "<script>alert('请输入正确的邮箱');location.href='".$data['back']."';</script>";exit;
		}
	}
	$data['publitime'] = $_REQUEST['publitime']==''?time():$_REQUEST['publitime'];
	
	$int = db()->insert(PRE.'message',$data);
	$id = db()->getlast_id();
	if($int)
	{
		header('location:'.get_back_all($data['back'],'userid='.$id));
	}
	else
	{
		header('location:'.$data['back']);
	}
}
#异步提交
function message_asyn()
{
	session_start();
	$virify = trim(strtolower($_SESSION['virify']));
	$morvirify = trim(strtolower($_REQUEST['mor-virify']));
	
	#查询主题ID
	$theme = db()->select('id')->from(PRE.'theme')->where(array('addmenu'=>'OFF','flag'=>0))->get()->array_row();
	$data['templateid'] = $theme['id'];
	$data['userip'] = getIP();
	#同步返回链接
	$data['back'] = $_REQUEST['back'];
	
	if( $virify != $morvirify )
	{
		echo '验证码不正确!';exit;
	}
	$_SESSION['virify']=null;
	unset($_SESSION['virify']);
	
	$data['name'] = $_REQUEST['name'];
	if( $data['name'] == '' )
	{
		echo '姓名不能留空';exit;
	}
	$data['age'] = $_REQUEST['age'];
	if( $data['age'] == '' )
	{
		echo '年龄不能留空';exit;
	}
	$data['pid'] = $_REQUEST['pid']==''?0:$_REQUEST['pid'];
	$data['body'] = $_REQUEST['body'];
	if( $data['body'] == '' )
	{
		echo '正文不能留空';exit;
	}
	$data['phone'] = $_REQUEST['phone'];
	if( $data['phone'] == '' )
	{
		echo '手机不能留空';exit;
	}
	else 
	{
		if(strlen($_REQUEST['phone']) == 11)
		{
			if(!preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_REQUEST['phone']) )
			{
				echo '请输入正确的手机号码';exit;
			}
		}
		else 
		{
			echo '手机长度应为11位';exit;
		}
	}
	$data['email'] = $_REQUEST['email'];
	if( $data['email'] != '' )
	{
		if( !preg_match("/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/",$data['email']) )
		{
			echo '请输入正确的邮箱';exit;
		}
	}
	$data['publitime'] = $_REQUEST['publitime']==''?time():$_REQUEST['publitime'];
	
	$int = db()->insert(PRE.'message',$data);
	$id = db()->getlast_id();
	if($int)
	{
		echo 'ok';
	}
	else
	{
		echo 'on';
	}
}
#############################################################
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
#############################################################