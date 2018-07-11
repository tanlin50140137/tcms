<?php
#默认反回UTF-8编码
header("content-type:text/html;charset=utf-8");

require('../../../public_include.php');
/**
 * 应用－登录
 * */

#用户登录
function Login()
{
	session_start();
	$v = strtolower($_SESSION["virify"]);
	$f = strtolower($_POST["virify"]);
	
	$zhou = $_POST['zhou'];
	
	$data['back'] = $_POST['back'];
	if( $data['back'] == '' ){
		echo "<script>alert('请提供登录成功后调回链接(url)');location.href='../../../';</script>";exit;
	}
	$data["username"] = $_POST["username"];
	if( empty($data["username"]) )
	{
		echo "<script>alert('请输入用户名');location.href='../../../';</script>";exit;
	}
	$int = db()->select("username")->from(PRE."member")->where(array("username"=>mysql_escape_string($data["username"])))->get()->array_nums();
	if( $int == 0 )
	{
		echo "<script>alert('用户名不存在');location.href='../../../';</script>";exit;
	}
	$pwd = $_POST["pwd"];
	if( empty($pwd) )
	{
		echo "<script>alert('请输入密码');location.href='../../../';</script>";exit;
	}
	#加密
	$data['pwd'] = substr( base64_encode( md5( md5( $pwd ) ) ) ,0 ,15);
	
	$int = db()->select("pwd")->from(PRE."member")->where(array("pwd"=>mysql_escape_string($data["pwd"])))->get()->array_nums();
	if( $int == 0 )
	{
		echo "<script>alert('密码不存在');location.href='../../../';</script>";exit;
	}
	if( $v != $f )
	{
		echo "<script>alert('验证码不正确');location.href='../../../';</script>";exit;
	}
	$int = db()->update(PRE."member", array("logintime"=>time()), array("username"=>$data["username"]));
	if( $int )
	{
		#7天内自动登录
		if( !empty($zhou) )
		{
			setcookie("user_login_name",$data['username'],time()+604800,"/");
			$_SESSION['user_login_name'] = $data["username"];
		}
		else
		{
			$_SESSION['user_login_name'] = $data["username"];
		}
		//进入会员中心
		header("location:".$data['back']);
	}
	else 
	{
		echo "<script>alert('登录失败');location.href='../../../';</script>";
	}
}
#用户名验证
function VirifyName()
{
	$username = mysql_escape_string($_POST['username']);
	$int = db()->select("username")->from(PRE."member")->where(array("username"=>$username))->get()->array_nums();
	echo $int;
}
#密码验证
function VirifyPaw()
{
	$pwd = mysql_escape_string($_POST['pwd']);
	
	#加密
	$data['pwd'] = substr( base64_encode( md5( md5( $pwd ) ) ) ,0 ,15);
	
	$int = db()->select("pwd")->from(PRE."member")->where(array("pwd"=>$data['pwd']))->get()->array_nums();
	echo $int;
}
#手机验证
function VirifyTel()
{
	$tel = mysql_escape_string($_POST['tel']);
	
	$int = db()->select("tel")->from(PRE."member")->where(array('tel'=>mysql_escape_string($tel)))->get()->array_nums();
	echo $int;
}
#邮箱验证
function VirifyEmail()
{
	$email = mysql_escape_string($_POST['email']);
	
	$int = db()->select("email")->from(PRE."member")->where(array('email'=>mysql_escape_string($email)))->get()->array_nums();
	echo $int;
}
#检验验证码
function VirifyTwo()
{
	session_start();
	$v = strtolower($_SESSION["virify"]);
	$f = strtolower($_POST["virify"]);
	if( $v != $f )
	{
		echo 0;
	}
	else 
	{
		echo 1;
	}
}
#用户注册
function reset_user()
{
	session_start();
	$v = strtolower($_SESSION["virify"]);
	$f = strtolower($_POST["virify"]);
	
	$back = $_POST['back'];
	
	$data['username'] = $_POST['username'];
	if( $data['username'] == '' )
	{
		echo "<script>alert('请输入用户名');location.href='../../../';</script>";exit;
	}
	$int = db()->select('username')->from(PRE."member")->where(array('username'=>mysql_escape_string($data['username'])))->get()->array_nums();
	if( $int == 1 )
	{
		echo "<script>alert('用户名已经存在');location.href='../../../';</script>";exit;
	}
	$pwd = $_POST['pwd']; 
	if( $pwd == '' )
	{
		echo "<script>alert('请输入密码');location.href='../../../';</script>";exit;
	}
	if( mb_strlen($pwd,"utf-8") < 6 || mb_strlen($pwd,"utf-8") > 16 ){
		echo "<script>alert('密码6-16位任意字符');location.href='../../../';</script>";exit;
	}
	$pwd2 = $_POST['pwd2']; 
	if( $pwd2 == '' )
	{
		echo "<script>alert('请输入确认密码');location.href='../../../';</script>";exit;
	}
	if( $pwd != $pwd2 )
	{
		echo "<script>alert('两次密码不一致');location.href='../../../';</script>";exit;
	}
	
	#加密
	$data['pwd'] = substr( base64_encode( md5( md5( $pwd2 ) ) ) ,0 ,15);
	
	$data['tel'] = $_POST['tel']; 
	if( $data['tel'] == '' )
	{
		echo "<script>alert('请输入手机号码');location.href='../../../';</script>";exit;
	}
	if(strlen( $_POST['tel'] ) == 11 )
	{
		if(!preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $_POST['tel']) )
		{
			echo "<script>alert('请输入正确的手机号码');location.href='../../../';</script>";exit;
		}
	}
	else 
	{
		echo "<script>alert('请输入正确的手机号码');location.href='../../../';</script>";exit;
	}
	$int = db()->select("tel")->from(PRE."member")->where(array('tel'=>mysql_escape_string($data['tel'])))->get()->array_nums();
	if( $int > 0 )
	{
		echo "<script>alert('手机号码已经被使用');location.href='../../../';</script>";exit;
	}
	$data['email'] = $_POST['email'];
	if( $data['email'] == '' )
	{
		echo "<script>alert('请输入邮箱');location.href='../../../';</script>";exit;
	} 
	if( !preg_match("/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/",$_POST['email']) )
	{
		echo "<script>alert('请输入正确的邮箱');location.href='../../../';</script>";exit;
	}
	$int = db()->select("email")->from(PRE."member")->where(array('email'=>mysql_escape_string($data['email'])))->get()->array_nums();
	if( $int > 0 )
	{
		echo "<script>alert('邮箱已经被占用');location.href='../../../';</script>";exit;
	}
	if( $v != $f )
	{
		echo "<script>alert('验证码不正确');location.href='../../../';</script>";exit;
	}
	$data['pic'] = 'subject/plugin/member/0.png';#默认头像
	$data['powername'] = '普通会员';
	$data['publitime'] = time();
	$data['abstract'] = '';
	$data['background'] = '';
	$data['identity'] = '';
	//注册
	$int = db()->insert(PRE."member",$data);
	if($int)
	{
		//header("location:".APTH_URL."/subject/plugin/member/login.php");
		$url = $back==''?APTH_URL.'/subject/plugin/member/login.php':$back;
		echo "<script>alert('注册成功！点击“确定登录”');location.href='".$url."';</script>";
	}
	else 
	{
		echo "<script>alert('注册失败');location.href='../../../';</script>";exit;
	}
}
############################################################################################################
$act = $_POST['act']==''?'index':$_POST['act'];

if( $act!=null && function_exists( $act ) )
{
	$act();
}