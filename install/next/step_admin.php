<?php
/**
 * This_cms_system 程序安装
 */
function virify_step()
{
	$filename = '../system/config/config.php';
	
	if(file_exists($filename))
	{
		echo "<script>alert('已经安装成功');location.href='../index.php';</script>";exit;
	}
}
function step1()
{
	virify_step();
	$html = '<div style="position:relative;width:1000px;margin:30px auto auto auto;">
		<div style="height:468px;float:left;width:340px;">
			<div style="margin-top:120px">
				<p style="width:200px;height:70px;background:#3A6EA5;float:left;"><img src="next/images/logo.png" width="200" height="70"/></p> 
				<p style="float:left;width:130px;height:70px;line-height:70px;margin-left:5px;color:#666666;font-size:28px;text-align:center;"><b>安装程序</b></p>
				<div style="clear:both;"></div>
			</div>
			<div style="margin-top:25px">
				<p style="width:70px;height:15px;float:left;color:#666666;font-size:14px;line-height:15px;">安装进度：</P>
				<p style="border:1px solid #E5E5E5;width:256px;height:15px;background:#F7F7F7;float:left;border-radius:4px;"></P>
				<div style="clear:both;"></div>
			</div>
			<div style="margin-top:20px;color:#666666;font-size:14px;"><b style="color:#3A6EA5;">安装协议</b> &raquo; <span>环境检查</span> &raquo; <span>数据库建立与设置</span> &raquo; <span>安装完成</span></div>
		</div>
		<div style="height:468px;float:left;">
			<div style="width:654px;height:468px;">
				<h3 style="border-bottom:1px solid #3A6EA5;height:50px;color:#3A6EA5;line-height:63px;">This_cms_system 7.4.1 User PHP 安装协议</h3>
				<div style="margin-top:5px">
					<textarea style="border:none;width:654px;height:370px;" readonly="readonly">
This_cms_system 7.4.1 User PHP 最终用户授权协议 

感谢您选择This_cms_system。 This_cms_system 基于 PHP 的技术开发，采用 MySQL作为数据库，全部源码开放。希望我们的努力能为您提供一个高效快速、强大的站点解决方案。

This_cms_system官方网址：http://www.thiscms.com/

为了使您正确并合法的使用本软件，请您在使用前务必阅读清楚下面的协议条款：

一、本授权协议适用且仅适用于 This_cms_system 7.4.1 User PHP ，This_cms_system 官方对本授权协议拥有最终解释权。

二、协议许可的权利

1. 本程序基于 MIT 协议开源，您可以在 MIT 协议允许的范围内对源代码进行使用，包括源代码或界面风格以适应您的网站要求。
2. 您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。
3. 您可以从 This_cms_system 提供的应用中心服务中下载适合您网站的应用程序，但应向应用程序开发者/所有者支付相应的费用。
4. 本程序在云主机（新浪SAE、百度BAE、阿里云等）使用的相关授权费用由 This_cms_system 另行规定。

三、协议规定的约束和限制

1. 无论如何，即无论用途如何、是否经过修改或美化、修改程度如何，只要使用 This_cms_system 程序本身，未经书面许可，必须保留页面底部的版权（Powered by This_cms_system 7.4.1 User），不得删除；但可以以任何访客可见的形式对其进行修改和美化。
2. 您从应用中心下载的应用程序，未经应用程序开发者的书面许可，不得对其进行反向工程、反向汇编、反向编译等，不得擅自复制、修改、链接、转载、汇编、发表、出版、发展与之有关的衍生产品、作品等。
3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。

四、有限担保和免责声明

1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。
2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。
3. 电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始确认本协议并安装 This_cms_system 7.4.1 User ，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
4. 如果本软件带有其它软件的整合API示范例子包，这些文件版权不属于本软件官方，并且这些文件是没经过授权发布的，请参考相关软件的使用许可合法的使用。

版权所有 ©2005 - 2017，This_cms_system 保留所有权利。

协议发布时间：2013 年 5 月 1 日
版本最新更新：2017 年 5 月 16 日 By This_cms_system 7.4.1 User					
					</textarea>
				</div>
				<div style="margin-top:20px;text-align:right;">
				<input type="checkbox" id="shous" onclick="cheSels(this);"/><label for="shous" style="font-size:14px;color:#666666;"> 我已阅读并同意此协议</label>  &nbsp; 
				<input type="button" value="下一步" id="button" style="border:1px solid #DDDDDD;width:109px;height:28px;background:#EEEEEE;color:#999999;"/>
				</div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>';
	$html .= '<script>
		var b = document.getElementById("button");
		b.onclick = function(){
			if(document.getElementById("shous").checked){
	   			 location.href="index.php?act=step2";
			}
		}
		if(document.getElementById("shous").checked)
		{
			b.style.border = "1px solid #3A6EA5";	
			b.style.background = "#3A6EA5";
			b.style.color = "#FFFFFF";
		}
		else
		{
			b.style.border = "1px solid #DDDDDD";	
			b.style.background = "#EEEEEE";
			b.style.color = "#999999";
		}
		function cheSels(t){
			if(t.checked){
				b.style.border = "1px solid #3A6EA5";	
				b.style.background = "#3A6EA5";
				b.style.color = "#FFFFFF";
			}else{
				b.style.border = "1px solid #DDDDDD";	
				b.style.background = "#EEEEEE";
				b.style.color = "#999999";
			}
		}
	</script>';
	echo $html;
}
function step2()
{
	virify_step();
	
	#获取工作目当
	$string = $_SERVER['REQUEST_URI'];
	$arr = explode('/', $string);
	
	$dirpath = $_SERVER['DOCUMENT_ROOT'].'/'.($arr[1]==''?'':$arr[1].'/'); 	

	$html = '<div style="position:relative;width:1000px;margin:30px auto auto auto;">
		<div style="height:468px;float:left;width:340px;">
			<div style="margin-top:120px">
				<p style="width:200px;height:70px;background:#3A6EA5;float:left;"><img src="next/images/logo.png" width="200" height="70"/></p> 
				<p style="float:left;width:130px;height:70px;line-height:70px;margin-left:5px;color:#666666;font-size:28px;text-align:center;"><b>安装程序</b></p>
				<div style="clear:both;"></div>
			</div>
			<div style="margin-top:25px">
				<p style="width:70px;height:15px;float:left;color:#666666;font-size:14px;line-height:15px;">安装进度：</P>
				<div style="border:1px solid #E5E5E5;width:256px;height:15px;background:#F7F7F7;float:left;border-radius:4px;">
					<p style="background:#428BCA;width:40%;height:15px;"></p>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div style="margin-top:20px;color:#666666;font-size:14px;"><b style="color:#3A6EA5;">安装协议</b> &raquo; <b style="color:#3A6EA5;">环境检查</b> &raquo; <span>数据库建立与设置</span> &raquo; <span>安装完成</span></div>
		</div>
		<div style="height:468px;float:left;">
			<div style="width:654px;height:468px;">
				<h3 style="border-bottom:1px solid #3A6EA5;height:50px;color:#3A6EA5;line-height:63px;">环境检查</h3>
				<div style="margin-top:5px">
					<table class="tableBox" style="border:1px solid #E1E1E1;width:100%;border-collapse:collapse;font-size:14px;color:#666666;" border="1">
						<tr>
							<th colspan="3" height="25">服务器环境检查</th>
						</tr>
						<tr>
							<td width="195" height="25" style="text-indent:0.5em;">HTTP 服务器</td>
							<td height="25" align="center">'.$_SERVER['SERVER_SOFTWARE'].'</td>
							<td width="44" height="25" align="center">'.($_SERVER['SERVER_SOFTWARE']==''?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">PHP 版本支持</td>
							<td height="25" align="center">'.phpversion().'</td>
							<td height="25" align="center">'.(phpversion()==''?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">system 路径</td>
							<td height="25" align="center">'.$dirpath.'</td>
							<td height="25" align="center">'.($_SERVER['DOCUMENT_ROOT']==''?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<th colspan="3" height="25">组件支持检查</th>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">session</td>
							<td height="25" align="center">'.(extension_loaded('session')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('session')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>';
					/*
					$html .= '<tr>
							<td height="25" style="text-indent:0.5em;">bz2</td>
							<td height="25" align="center">'.(extension_loaded('bz2')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('bz2')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>';*/
					$html .= '<tr>
							<td height="25" style="text-indent:0.5em;">SimpleXML</td>
							<td height="25" align="center">'.(extension_loaded('SimpleXML')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('SimpleXML')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">gd2</td>
							<td height="25" align="center">'.(extension_loaded('gd')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('gd')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">mbstring</td>
							<td height="25" align="center">'.(extension_loaded('mbstring')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('mbstring')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">mysql</td>
							<td height="25" align="center">'.(extension_loaded('mysql')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('mysql')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">mysqli</td>
							<td height="25" align="center">'.(extension_loaded('mysqli')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('mysqli')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>';
					/*
					$html .= '<tr>
							<td height="25" style="text-indent:0.5em;">openssl</td>
							<td height="25" align="center">'.(extension_loaded('openssl')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('openssl')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>';
					*/
					$html .= '<tr>
							<td height="25" style="text-indent:0.5em;">pdo</td>
							<td height="25" align="center">'.(extension_loaded('PDO')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('PDO')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">pdo_mysql</td>
							<td height="25" align="center">'.(extension_loaded('pdo_mysql')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('pdo_mysql')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">zip</td>
							<td height="25" align="center">'.(extension_loaded('zip')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('zip')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">iconv</td>
							<td height="25" align="center">'.(extension_loaded('iconv')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('iconv')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">json</td>
							<td height="25" align="center">'.(extension_loaded('json')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('json')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<th colspan="3" height="25">权限检查</th>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">subject</td>
							<td height="25" align="center">'.perms_all('../subject').'</td>
							<td height="25" align="center">'.(perms_all('../subject',1)!='0777'?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">subject/plugin</td>
							<td height="25" align="center">'.perms_all('../subject/plugin').'</td>
							<td height="25" align="center">'.(perms_all('../subject/plugin',1)!='0777'?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">subject/upload</td>
							<td height="25" align="center">'.perms_all('../subject/upload').'</td>
							<td height="25" align="center">'.(perms_all('../subject/upload',1)!='0777'?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">system</td>
							<td height="25" align="center">'.perms_all('../system').'</td>
							<td height="25" align="center">'.(perms_all('../system',1)!='0777'?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">system/config</td>
							<td height="25" align="center">'.perms_all('../system/config').'</td>
							<td height="25" align="center">'.(perms_all('../system/config',1)!='0777'?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<th colspan="3" height="25">接口检查</th>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">sockets支持</td>
							<td height="25" align="center">'.(extension_loaded('sockets')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('sockets')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">xml支持</td>
							<td height="25" align="center">'.(extension_loaded('xml')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('xml')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
						<tr>
							<td height="25" style="text-indent:0.5em;">curl</td>
							<td height="25" align="center">'.(extension_loaded('curl')==false?'未开启':'开启').'</td>
							<td height="25" align="center">'.(extension_loaded('curl')==false?'<img src="next/images/exclamation.png" align="absmiddle"/>':'<img src="next/images/ok.png" align="absmiddle"/>').'</td>
						</tr>
					</table>
				</div>
				<div style="margin-top:20px;text-align:right;">
				<input type="button" value="下一步" id="button" style="border:1px solid #3A6EA5;width:109px;height:28px;background:#3A6EA5;color:#FFFFFF;"/>
				</div>
				<div style="height:30px;"></div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>';
	$html .= '<script>
	$(function(){
			$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
			$(".tableBox tr").hover(function(){
			$(this).css({"background":"#FFFFDD"});
		},function(){
			$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		});
	});
	var b = document.getElementById("button");
	var http = "'.$_SERVER['SERVER_SOFTWARE'].'";
	var p2p = "'.phpversion().'";
	var thiscms = "'.$_SERVER['DOCUMENT_ROOT'].'";
	var sesion = "'.extension_loaded('session').'";
	//var bz2 = "'.extension_loaded('bz2').'";
	var SimpleXML = "'.extension_loaded('SimpleXML').'";
	var gd = "'.extension_loaded('gd').'";
	var mbstring = "'.extension_loaded('mbstring').'";
	var mysql = "'.extension_loaded('mysql').'";
	var mysqli = "'.extension_loaded('mysqli').'";
	//var openssl = "'.extension_loaded('openssl').'";
	var pdo = "'.extension_loaded('PDO').'";
	var pdo_mysql = "'.extension_loaded('pdo_mysql').'";
	var zip = "'.extension_loaded('zip').'";
	var iconv = "'.extension_loaded('iconv').'";
	var json = "'.extension_loaded('json').'";
	var dir1 = "'.perms_all('../subject',1).'";
	var dir2 = "'.perms_all('../subject/plugin',1).'";
	var dir3 = "'.perms_all('../subject/upload',1).'";
	var dir4 = "'.perms_all('../system',1).'";
	var dir5 = "'.perms_all('../system/config',1).'";
	var sockets = "'.extension_loaded('sockets').'";
	var xml = "'.extension_loaded('xml').'";
	var curl = "'.extension_loaded('curl').'";
	b.onclick = function(){
		if(http == ""){
			alert("服务器环境错误，HTTP服务器，无法找到");
	   		return false;
		}
		if(p2p == ""){
			alert("PHP配置错误");
	   		return false;
		}
		if(thiscms == ""){
			alert("system路径错误");
	   		return false;
		}
		if( sesion == false ){
			alert("session组件未开启");
	   		return false;
		}
		/*
		if( bz2 == false ){
			alert("bz2组件未开启");
	   		return false;
		}*/
		if( SimpleXML == false ){
			alert("SimpleXML组件未开启");
	   		return false;
		}
		if( gd == false ){
			alert("gd2组件未开启");
	   		return false;
		}
		if( mbstring == false ){
			alert("mbstring组件未开启");
	   		return false;
		}
		if( mysql == false ){
			alert("mysql组件未开启");
	   		return false;
		}
		if( mysqli == false ){
			alert("mysqli组件未开启");
	   		return false;
		}
		/*
		if( openssl == false ){
			alert("openssl组件未开启");
	   		return false;
		}
		*/
		if( pdo == false ){
			alert("pdo组件未开启");
	   		return false;
		}
		if( pdo_mysql == false ){
			alert("pdo_mysql组件未开启");
	   		return false;
		}
		if( zip == false ){
			alert("zip组件未开启");
	   		return false;
		}
		if( iconv == false ){
			alert("iconv组件未开启");
	   		return false;
		}
		if( json == false ){
			alert("json组件未开启");
	   		return false;
		}
		if( dir1 != "0777" ){
			alert("subject目录权限不足，请设置为可读写权限");
	   		return false;
		}
		if( dir2 != "0777" ){
			alert("subject/plugin目录权限不足，请设置为可读写权限");
	   		return false;
		}
		if( dir3 != "0777" ){
			alert("subject/upload目录权限不足，请设置为可读写权限");
	   		return false;
		}
		if( dir4 != "0777" ){
			alert("system目录权限不足，请设置为可读写权限");
	   		return false;
		}
		if( dir5 != "0777" ){
			alert("system/config目录权限不足，请设置为可读写权限");
	   		return false;
		}
		if( sockets == false ){
			alert("sockets组件未开启");
	   		return false;
		}
		if( xml == false ){
			alert("xml组件未开启");
	   		return false;
		}
		if( curl == false ){
			alert("curl组件未开启");
	   		return false;
		}
		location.href="index.php?act=step3";
	}
</script>';
	echo $html;
}
#获取当前完整url
function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}
function step3()
{
	virify_step();
	
	$html = '<form id="frm" action="index.php?act=step3_data" method="post">
	<div style="position:relative;width:1000px;margin:30px auto auto auto;">
		<div style="height:468px;float:left;width:340px;">
			<div style="margin-top:120px">
				<p style="width:200px;height:70px;background:#3A6EA5;float:left;"><img src="next/images/logo.png" width="200" height="70"/></p> 
				<p style="float:left;width:130px;height:70px;line-height:70px;margin-left:5px;color:#666666;font-size:28px;text-align:center;"><b>安装程序</b></p>
				<div style="clear:both;"></div>
			</div>
			<div style="margin-top:25px">
				<p style="width:70px;height:15px;float:left;color:#666666;font-size:14px;line-height:15px;">安装进度：</P>
				<div style="border:1px solid #E5E5E5;width:256px;height:15px;background:#F7F7F7;float:left;border-radius:4px;">
					<p style="background:#428BCA;width:70%;height:15px;"></p>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div style="margin-top:20px;color:#666666;font-size:14px;"><b style="color:#3A6EA5;">安装协议</b> &raquo; <b style="color:#3A6EA5;">环境检查</b> &raquo; <b style="color:#3A6EA5;">数据库建立与设置</b> &raquo; <span>安装完成</span></div>
		</div>
		<div style="height:468px;float:left;">
			<div style="width:654px;height:468px;">
				<h3 style="border-bottom:1px solid #3A6EA5;height:50px;color:#3A6EA5;line-height:63px;">数据库建立与设置</h3>
				<div style="margin-top:5px">		
					<table class="tableBox" style="border:1px solid #E1E1E1;width:100%;border-collapse:collapse;font-size:14px;color:#666666;" border="1">
						<tr>
							<td width="195" height="36" style="text-indent:0.5em;"><b>数据库类型：</b></td>
							<td height="36" style="text-indent:0.5em;"><input type="radio" name="mysql" checked="checked" value="mysql" id="mysql"/> <label for="mysql">MySQL</label> </td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>数据库主机：</b></td>
							<td height="36" style="text-indent:0.5em;"><input type="text" name="host" value="localhost" style="width:280px;padding:3px;"/></td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>数据库账号：</b></td>
							<td height="36" style="text-indent:0.5em;"><input type="text" name="username" value="root" style="width:280px;padding:3px;"/></td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>数据库密码：</b></td>
							<td height="36" style="text-indent:0.5em;"><input type="password" name="password"  style="width:280px;padding:3px;"/></td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>数据库名称：</b></td>
							<td height="36" style="text-indent:0.5em;"><input type="text" name="dbname"  style="width:280px;padding:3px;"/></td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>表前缀：</td>
							<td height="36" style="text-indent:0.5em;"><input type="text" name="prefix" value="This_" style="width:280px;padding:3px;"/></td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>表存储引擎：</b></td>
							<td height="36" style="text-indent:0.5em;">
							<select name="engine" style="width:288px;padding:3px;">
								<option value="MyISAM" selected="selected">MyISAM(默认)</option>
								<option value="InnoDB">InnoDB</option>
							</select>
							</td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>编码格式：</b></td>
							<td height="36" style="text-indent:0.5em;">
								<select name="coded" style="width:288px;padding:3px;">
									<option value="utf8" selected="selected">UTF-8(默认)</option>
									<option value="gbk">GBK</option>
								</select>
							</td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>数据库驱动：</b></td>
							<td height="36" style="text-indent:0.5em;">
								<input type="radio" name="sql" value="1" checked="checked" id="ql1"/> <label for="ql1">MySql<label> &nbsp; &nbsp; &nbsp; 
								<input type="radio" name="sql" value="2" id="ql2"/> <label for="ql2">MySqli<label> &nbsp; &nbsp; &nbsp; 
								<input type="radio" name="sql" value="3" id="ql3"/> <label for="ql3">PDO<label> &nbsp; &nbsp; &nbsp; 
							</td>
						</tr>
						<tr>
							<td  height="36" colspan="2" style="text-indent:0.5em;">端口号默认3306，如需要修改请在"数据库主机"里追加":端口号"。</td>
						</tr>
						<tr>
							<td  height="36" colspan="2" style="text-indent:0.5em;color:#3A6EA5;"><h3>网站设置</h3></td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>网站标题：</b></td>
							<td height="36" style="text-indent:0.5em;"><input type="text" name="title"  style="width:280px;padding:3px;"/></td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>管理员名称：</b></td>
							<td height="36" style="text-indent:0.5em;"><input type="text" name="admin" style="padding:3px;"/> <span style="font-size:12px;">英文,数字,汉字或._的组合</span></td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>管理员密码：</b></td>
							<td height="36" style="text-indent:0.5em;"><input type="password" name="pwd1" style="padding:3px;"/> <span style="font-size:12px;">6位或更长的数字或字母组合</span></td>
						</tr>
						<tr>
							<td height="36" style="text-indent:0.5em;"><b>确认密码：</b></td>
							<td height="36" style="text-indent:0.5em;"><input type="password" name="pwd2" style="padding:3px;"/></td>
						</tr>
					</table>
				</div>
				<div style="margin-top:20px;text-align:right;">
				<input type="submit" value="下一步" id="button" style="border:1px solid #3A6EA5;width:109px;height:28px;background:#3A6EA5;color:#FFFFFF;"/>
				</div>
				<div style="height:30px;"></div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div><form>';
	$html .= '<script>
	$(function(){
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
			$(this).css({"background":"#FFFFDD"});
		},function(){
			$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		});
		$("#frm").submit(function(){
			if( $("[name=host]").val() == "" ){
				alert("数据库主机不能留空,请输入正确的数据库主机！");
				$("[name=host]").focus();
				return false;
			}
			if( $("[name=username]").val() == "" ){
				alert("数据库账号不能留空,请输入正确的数据库账号！");
				$("[name=username]").focus();
				return false;
			}
			if( $("[name=password]").val() == "" ){
				alert("数据库密码不能留空,请输入正确的数据库密码！");
				$("[name=password]").focus();
				return false;
			}
			if( $("[name=dbname]").val() == "" ){
				alert("数据库名称不能留空,请输入正确的数据库名称！");
				$("[name=dbname]").focus();
				return false;
			}
			if( $("[name=prefix]").val() == "" ){
				alert("表前缀不能留空,请输入正确的表前缀格式如: This_ 形式出现");
				$("[name=prefix]").focus();
				return false;
			}
			var re = /^[a-zA-Z]+_$/g;
			if( $("[name=prefix]").val() !="" ){
				if(!re.test($("[name=prefix]").val())){
					alert("表前缀格式错误");
					$("[name=prefix]").focus();
					return false;
				}
			}
			if( $("[name=title]").val() == "" ){
				alert("网站标题不能留空");
				$("[name=title]").focus();
				return false;
			}
			if( $("[name=admin]").val() == "" ){
				alert("管理员名称不能留空");
				$("[name=admin]").focus();
				return false;
			}
			var re1 = /^[a-zA-Z0-9_]{1,}[a-zA-Z0-9\._]+/g;
			if(!re1.test($("[name=admin]").val()))
			{
				alert("英文,数字,汉字或._的组合");
				$("[name=admin]").focus();
				return false;
			}
			if( $("[name=pwd1]").val() == "" ){
				alert("管理员密码不能留空");
				$("[name=pwd1]").focus();
				return false;
			}
			var re2 = /[a-zA-Z0-9]{6,}/g;
			if(!re2.test($("[name=pwd1]").val())){
				alert("6位或更长的数字或字母组合");
				$("[name=pwd1]").focus();
				return false;
			}
			if( $("[name=pwd2]").val() == "" ){
				alert("确认密码不能留空");
				$("[name=pwd2]").focus();
				return false;
			}
			var re3 = /[a-zA-Z0-9]{6,}/g;
			if(!re3.test($("[name=pwd2]").val())){
				alert("6位或更长的数字或字母组合");
				$("[name=pwd2]").focus();
				return false;
			}
			if( $("[name=pwd1]").val() != $("[name=pwd2]").val() ){
				alert("两次密码不一至");
				$("[name=pwd2]").focus();
				return false;
			}
		});
	});	
</script>';
	echo $html;
}
function step4()
{
	$houtai = '../system/login.php';
	$urlpath = '../'; 	

	$html = '<div style="position:relative;width:1000px;margin:30px auto auto auto;">
		<div style="height:468px;float:left;width:340px;">
			<div style="margin-top:120px">
				<p style="width:200px;height:70px;background:#3A6EA5;float:left;"><img src="next/images/logo.png" width="200" height="70"/></p> 
				<p style="float:left;width:130px;height:70px;line-height:70px;margin-left:5px;color:#666666;font-size:28px;text-align:center;"><b>安装程序</b></p>
				<div style="clear:both;"></div>
			</div>
			<div style="margin-top:25px">
				<p style="width:70px;height:15px;float:left;color:#666666;font-size:14px;line-height:15px;">安装进度：</P>
				<div style="border:1px solid #E5E5E5;width:256px;height:15px;background:#F7F7F7;float:left;border-radius:4px;">
					<p style="background:#428BCA;width:100%;height:15px;"></p>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div style="margin-top:20px;color:#666666;font-size:14px;"><b style="color:#3A6EA5;">安装协议</b> &raquo; <b style="color:#3A6EA5;">环境检查</b> &raquo; <b style="color:#3A6EA5;">数据库建立与设置</b> &raquo; <b style="color:#3A6EA5;">安装完成</b></div>
		</div>
		<div style="height:468px;float:left;">
			<div style="width:654px;height:468px;">
				<h3 style="border-bottom:1px solid #3A6EA5;height:50px;color:#3A6EA5;line-height:63px;">安装完成</h3>
				<div style="margin-top:5px">		
					<table class="tableBox" style="border:1px solid #E1E1E1;width:100%;border-collapse:collapse;font-size:14px;color:#666666;" border="1">
						<tr>
							<td width="195" height="26" style="text-indent:0.5em;" colspan="">连接数据库并创建数据表！</td>
						</tr>	
						<tr>
							<td width="195" height="26" style="text-indent:0.5em;" colspan="">创建并插入数据成功！</td>
						</tr>	
						<tr>
							<td width="195" height="26" style="text-indent:0.5em;" colspan="">数据库引擎并编码格式配置成功！</td>
						</tr>
						<tr>
							<td width="195" height="26" style="text-indent:0.5em;" colspan="">全局文件配置成功！</td>
						</tr>	
						<tr>
							<td width="195" height="26" style="text-indent:0.5em;" colspan="">管理员帐号并密码注册成功！</td>
						</tr>	
						<tr>
							<td width="195" height="75" style="text-indent:0.5em;" colspan="">
								<a href="'.$houtai.'"><b>登录网站管理后台</b></a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>登录网站首页，请点击“下一步”</b>
							</td>
						</tr>	
					</table>
				</div>
				<div style="margin-top:20px;text-align:right;">
				<input type="submit" value="下一步" id="button" style="border:1px solid #3A6EA5;width:109px;height:28px;background:#3A6EA5;color:#FFFFFF;"/>
				</div>
				<div style="height:30px;"></div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>';
	$html .= '<script>
	$(function(){
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
			$(this).css({"background":"#FFFFDD"});
		},function(){
			$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		});	
	});	
	var b = document.getElementById("button");
	b.onclick = function(){
		location.href="'.$urlpath.'";
	}
</script>';
	echo $html;
}
function step3_data()
{
	$data['mysql'] = $_POST['mysql'];
	$data['engine'] = $_POST['engine'];
    $data['coded'] = $_POST['coded'];
    
    $data['host'] = $_POST['host'];
    if( $data['host'] == '' )
    {
    	echo "<script>alert('数据库主机不能留空,请输入正确的数据库主机！');location.href='index.php?act=step3';</script>";exit;
    }
    $data['username'] = $_POST['username'];
	if( $data['username'] == '' )
    {
    	echo "<script>alert('数据库账号不能留空,请输入正确的数据库账号！');location.href='index.php?act=step3';</script>";exit;
    }
    $data['password'] = $_POST['password']; 
	if( $data['password'] == '' )
    {
    	echo "<script>alert('数据库密码不能留空,请输入正确的数据库密码！');location.href='index.php?act=step3';</script>";exit;
    }
     
    $data['dbname'] = $_POST['dbname'];
	if( $data['dbname'] == '' )
    {
    	echo "<script>alert('数据库名称不能留空,请输入正确的数据库名称！');location.href='index.php?act=step3';</script>";exit;
    }
    
    $data['prefix'] = strtolower($_POST['prefix']);
	if( $data['prefix'] == '' )
    {
    	echo "<script>alert('表前缀不能留空,请输入正确的表前缀格式如: This_ 形式出现');location.href='index.php?act=step3';</script>";exit;
    }  
    if(!preg_match("/^[a-zA-Z]+_$/", $data['prefix']))
    {
    	echo "<script>alert('表前缀格式错误');location.href='index.php?act=step3';</script>";exit;
    }
    $data['title'] = $_POST['title'];
	if( $data['title'] == '' )
    {
    	echo "<script>alert('网站标题不能留空');location.href='index.php?act=step3';</script>";exit;
    }
    $data['admin'] = $_POST['admin'];
	if( $data['admin'] == '' )
    {
    	echo "<script>alert('管理员名称不能留空');location.href='index.php?act=step3';</script>";exit;
    }
 	if(!preg_match("/^[a-zA-Z0-9_]{1,}[a-zA-Z0-9\._]+/", $data['admin']))
    {
    	echo "<script>alert('英文,数字,汉字或._的组合');location.href='index.php?act=step3';</script>";exit;
    }
    $data['pwd1'] = $_POST['pwd1'];
	if( $data['pwd1'] == '' )
    {
    	echo "<script>alert('管理员密码不能留空');location.href='index.php?act=step3';</script>";exit;
    }
	if(!preg_match("/[a-zA-Z0-9]{6,}/", $data['pwd1']))
    {
    	echo "<script>alert('6位或更长的数字或字母组合');location.href='index.php?act=step3';</script>";exit;
    }
    $data['pwd2'] = $_POST['pwd2'];
	if( $data['pwd2'] == '' )
    {
    	echo "<script>alert('确认密码不能留空');location.href='index.php?act=step3';</script>";exit;
    }
	if(!preg_match("/[a-zA-Z0-9]{6,}/", $data['pwd2']))
    {
    	echo "<script>alert('6位或更长的数字或字母组合');location.href='index.php?act=step3';</script>";exit;
    }
    if( $data['pwd1'] != $data['pwd2'] )
    {
    	echo "<script>alert('两次密码不一至');location.href='index.php?act=step3';</script>";exit;
    }	
    if( $_POST['sql'] == 1 )
    {#mysql驱动
	    $link = @mysql_connect($data['host'],$data['username'],$data['password']) or exit('错误 '.mysql_errno().'-'.mysql_error().' 数据库连接错误');
	    if( mysql_select_db($data['dbname']) == false )
	    {
	    	mysql_query(create_db($data));
	    	mysql_select_db($data['dbname']);
	    }    
	    mysql_query('set names utf8');
	    #创建数据表
	    $tableArr =  table_data($data);
	    if(!empty($tableArr))
	    {
		    foreach($tableArr as $k=>$v)
		    {
		    	$int = mysql_query($v) or exit('sql语法错误 '.mysql_errno()."  <br/>\n\n  ".mysql_error()." <br/>\n\n ".$v);
		    }
	    }
	    else 
	    {
	    	echo '创建数据库失败，原因：install/next/　目录下无法找到　mapping.sql 文件。请登录官方网站，重新下载程序包后重试！！！';exit;
	    }	
		mysql_close($link);
	    if($int)
		{
			#创建配置文件
	   		$str_data = system_config($data);
	   		file_put_contents('../system/config/config.php', $str_data);
	    
			echo '<script>location.href="index.php?act=step4";</script>';
		}
	}
	elseif( $_POST['sql'] == 2 )
	{#mysqli驱动
		$mysqli = @new mysqli($data['host'],$data['username'],$data['password']);

		$dbint = $mysqli->select_db($data['dbname']);
		if( $dbint == false )
		{
			$mysqli->query(create_db($data));
			
			$mysqli->select_db($data['dbname']);
		}
		
		$result = $mysqli->query("set names utf8");
		#创建数据表
	    $tableArr =  table_data($data);
		if(!empty($tableArr))
	    {
		    foreach($tableArr as $k=>$v)
		    {
		    	$int = $mysqli->query($v) or exit('sql语法错误 '.mysqli_errno()."  <br/>\n\n  ".mysqli_error()." <br/>\n\n ".$v);
		    }
	    }
	    else 
	    {
	    	echo '创建数据库失败，原因：install/next/　目录下无法找到　mapping.sql 文件。请登录官方网站，重新下载程序包后重试！！！';exit;
	    }	
		$mysqli->close();
		if($int)
		{
			#创建配置文件
	   		$str_data = system_config($data);
	   		file_put_contents('../system/config/config.php', $str_data);
	    
			echo '<script>location.href="index.php?act=step4";</script>';
		}
	}
	elseif( $_POST['sql'] == 3 )
	{#PDO驱动
		$dbms=$data['mysql'];     //数据库类型
		$host=$data['host']; //数据库主机名
		$dbName=$data['dbname'];    //使用的数据库
		$user=$data['username'];      //数据库连接用户名
		$pass=$data['password'];          //对应的密码
		$dsn1="$dbms:host=$host;dbname=$dbName";
		$dsn2="$dbms:host=$host;";
		try 
		{#有库
		    $dbh = new PDO($dsn1, $user, $pass); //初始化一个PDO对象	    
		    $dbh->exec("set names utf8");
		    #创建数据表
	   		$tableArr =  table_data($data);
		    if(!empty($tableArr))
		    {
			    foreach($tableArr as $k=>$v)
			    {
			    	$dbh->exec($v);
			    }
		    }
		    else 
		    {
		    	echo '创建数据库失败，原因：install/next/　目录下无法找到　mapping.sql 文件。请登录官方网站，重新下载程序包后重试！！！';exit;
		    }
			#创建配置文件
		   	$str_data = system_config($data);
		   	file_put_contents('../system/config/config.php', $str_data);
		    
			echo '<script>location.href="index.php?act=step4";</script>';  
		} 
		catch (PDOException $e) 
		{#无库			
			$dbh = new PDO($dsn2, $user, $pass); //初始化一个PDO对象		
			$dbin = $dbh->exec(create_db($data));
	
			$dbh2 = new PDO($dsn1, $user, $pass); //初始化一个PDO对象
			$dbh2->exec("set names utf8");
			#创建数据表
	    	$tableArr =  table_data($data);
			if(!empty($tableArr))
		    {
			    foreach($tableArr as $k=>$v)
			    {
			    	$dbh2->exec($v);
			    }
		    }
		    else 
		    {
		    	echo '创建数据库失败，原因：install/next/　目录下无法找到　mapping.sql 文件。请登录官方网站，重新下载程序包后重试！！！';exit;
		    }

			#创建配置文件
		   	$str_data = system_config($data);
		   	file_put_contents('../system/config/config.php', $str_data);
		    
			echo '<script>location.href="index.php?act=step4";</script>';
		}
	}
}
function create_db($data)
{
	return "create database if not exists ".$data['dbname']." default character set '".$data['coded']."';";
}
function table_data($data)
{
	$http = dirname(dirname(get_url()));
	
	$subject = file_get_contents('next/data/mapping.sql');
	$string = str_replace(array("\n","\t"), array("","",""), $subject);
	
	$password = substr(md5(base64_decode($data['pwd1'])),10,10);
	
	$string = str_replace(array("%102%","%103%","%104%","%user%","%pwd%","%ip%","%t%","%http%","%title%","%static%"), array($data['coded'],$data['prefix'],$data['engine'],$data['admin'],$password,getIP(),time(),$http,$data['title'],date('YmdHis')), $string);
	$qArr = explode('#777#', $string);
	
	return $qArr;
}
function system_config($data)
{
	$string = '<?php'."\n";
	$string .= 'define("SERVER", "'.$data['host'].'");'."\n";
	$string .= 'define("USERNAME", "'.$data['username'].'");'."\n";
	$string .= 'define("PASSWORD", "'.$data['password'].'");'."\n";
	$string .= 'define("BASENAME", "'.$data['dbname'].'");'."\n";
	$string .= 'define("BAS", "'.$data['mysql'].'");'."\n";
	$string .= 'define("PRE", "'.$data['prefix'].'");';
	
	return $string;
}
#获取权限
function perms_all($filename,$int=0)
{	
	if(file_exists($filename))
	{
	$perms = fileperms($filename);
	
	if (($perms & 0xC000) == 0xC000) {
	    $info = 's';
	} elseif (($perms & 0xA000) == 0xA000) {
	    $info = 'l';
	} elseif (($perms & 0x8000) == 0x8000) {
	    $info = '-';
	} elseif (($perms & 0x6000) == 0x6000) {
	    $info = 'b';
	} elseif (($perms & 0x4000) == 0x4000) {
	    $info = 'd';
	} elseif (($perms & 0x2000) == 0x2000) {
	    $info = 'c';
	} elseif (($perms & 0x1000) == 0x1000) {
	    $info = 'p';
	} else {
	    $info = 'u';
	}

	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
	            (($perms & 0x0800) ? 's' : 'x' ) :
	            (($perms & 0x0800) ? 'S' : '-'));
	
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
	            (($perms & 0x0400) ? 's' : 'x' ) :
	            (($perms & 0x0400) ? 'S' : '-'));
	
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
	            (($perms & 0x0200) ? 't' : 'x' ) :
	            (($perms & 0x0200) ? 'T' : '-'));
		if( $int == 0 )
		{
			return $info.' | '.substr(base_convert($perms,10,8),-4);
		}
		else 
		{
			return substr(base_convert($perms,10,8),-4);
		}
	}
	else 
	{
		return '文件或目录不存在';
	}
}
function getIP()
{
	static $realip;
	if (isset($_SERVER)){
	      if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
	          $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	 	  } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
	         $realip = $_SERVER["HTTP_CLIENT_IP"];
	 	  } else {
	         $realip = $_SERVER["REMOTE_ADDR"];
	 	  }
	 } else {
	        if (getenv("HTTP_X_FORWARDED_FOR")){
	            $realip = getenv("HTTP_X_FORWARDED_FOR");
	        } else if (getenv("HTTP_CLIENT_IP")) {
	            $realip = getenv("HTTP_CLIENT_IP");
	        } else {
	            $realip = getenv("REMOTE_ADDR");
	        }
	 }
	 return $realip;
}