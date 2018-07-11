<?php 
/**
 * 主题调用外部插件 <?php include Pagecall('member');?>
 * */
$row = db()->select('xmlrpc,themeimg,themeas,addmenu')->from(PRE.'theme')->where(array('themename'=>'member'))->get()->array_row();
if($row['addmenu'] == 'OFF')
{
	if(!empty($row))
	{
		$member = (array)simplexml_load_string(file_get_contents(dir_url($row['xmlrpc'])));
		//print_r($member);
	}
}
?>
<div style="position:absolute;top:0;left:0;margin:0;z-index:999999;background:#666666;background-color:rgba(0,0,0,0.5);filter:Alpha(Opacity='50');display:none;" id="t_wei_bu"></div>
<div style="border:1px solid #DDDDDD;position:fixed;top:30%;left:40%;margin:0;z-index:999999;background:#FFFFFF;font-family:'Microsoft YaHei';color:#999999;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;display:none;" id="this_layer">
	<h2 style="border-bottom:1px solid #DDDDDD;margin:0 20px 0 20px;height:60px;line-height:60px;text-align:center;">
		<p style="margin:0;position:absolute;top:20px;left:20px;width:30px;height:30px;border-radius:30px;-moz-border-radius:30px;-webkit-border-radius:30px;background:url(<?php echo APTH_URL."/subject/plugin/member/images/u-icon-1.png";?>) no-repeat -3px;cursor:pointer;"></p>
		<?php echo $member['login_title'];?>
	</h2>
	<p id="x-box" style="margin:0;position:absolute;top:5px;right:5px;width:30px;height:30px;border-radius:30px;-moz-border-radius:30px;-webkit-border-radius:30px;background:url(<?php echo APTH_URL."/subject/plugin/member/images/x-icon-2-1.png";?>) no-repeat;cursor:pointer;" title="关闭"></p>
<form action="<?php echo APTH_URL."/subject/plugin/member/act_login.php";?>" method="post" onsubmit="return act_submit();">
	<table style="width:460px;margin:5px 20px 0 20px;">
		<tr style="height:48px;">
			<td style="width:80px;">用户名</td>
			<td>
			<input type="text" name="username" value="" id="t_username_u" style="outline: none;width:230px;padding:3px;border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;"/>
			<span style="color:red;font-size:12px;" id="t_tels_1">*</span>
			</td>
		</tr>
		<tr style="height:48px;">
			<td style="width:80px;">密 &nbsp;&nbsp;码</td>
			<td>
			<input type="password" name="pwd" value="" id="t_pwd_p" style="outline: none;width:230px;padding:3px;border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;"/>
			<span style="color:red;font-size:12px;" id="p_tels_1">*</span>
			</td>
		</tr>
		<tr style="height:48px;">
			<td style="width:80px;">验证码</td>
			<td>
			<input type="text" name="virify" value=""  id="v_fy_y"  style="outline: none;width:50px;padding:3px;border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;"/> &nbsp; 
			<img src="<?php echo APTH_URL."/system/virify.php";?>" align="absmiddle" id="virify" alt="验证码"/> &nbsp; 
			<a href="javascript:;" onclick="document.getElementById('virify').src='<?php echo APTH_URL."/system/virify.php";?>?rand='+Math.random();document.getElementById('v_fy_y').value='';" style="color:#999999;" id="t_huan-y">换一张</a> &nbsp;  &nbsp;&nbsp;&nbsp;
			<span style="color:red;font-size:12px;" id="v_tels_1"></span>
			</td>
		</tr>
		<tr style="width:35px;">
			<td style="width:80px;"></td>
			<td><input type="checkbox" name="zhou" value="1" id="che_zhou"/><label for="che_zhou"><font style="font-size:13px;color:#666666;">7天内自动登录</font></label></td>
		</tr>
		<tr style="height:48px;">
			<td></td>
			<td>
			<input type="hidden" name="back" value="<?php echo $member['back_url'];?>"/>
			<input type="hidden" name="act" value="Login"/>
			<input type="submit" value="登录" style="border:1px solid #DDDDDD;outline: none;width:230px;height:35px;border-radius:8px 8px;-moz-border-radius:8px 8px;-webkit-border-radius:8px 8px;cursor:pointer;color:#333333;" id="t_this_submit"/>
			</td>
		</tr>
	</table>
</form>
	<table style="margin:5px 20px 0 20px;">
		<tr>
			<td><?php if( $member['show_hide'] == 1 ){?>其他方式登录<?php }?></td>
			<td>
<?php if( $member['show_hide'] == 1 ){?>			
				<div class="t_logins_san" style="border:1px solid #DDDDDD;width:30px;height:30px;border-radius:30px 30px;-moz-border-radius:30px 30px;-webkit-border-radius:30px 30px;cursor:pointer;float:left;margin-left:20px;margin-right:20px;background:url(<?php echo APTH_URL."/subject/plugin/member/images/q-icon-2.png";?>) no-repeat;"></div>
				<div class="t_logins_san" style="border:1px solid #DDDDDD;width:30px;height:30px;border-radius:30px 30px;-moz-border-radius:30px 30px;-webkit-border-radius:30px 30px;cursor:pointer;float:left;margin-right:20px;background:url(<?php echo APTH_URL."/subject/plugin/member/images/w-icon-2.png";?>) no-repeat;"></div>
				<div class="t_logins_san" style="border:1px solid #DDDDDD;width:30px;height:30px;border-radius:30px 30px;-moz-border-radius:30px 30px;-webkit-border-radius:30px 30px;cursor:pointer;float:left;margin-right:20px;background:url(<?php echo APTH_URL."/subject/plugin/member/images/xin-icon-2.png";?>) no-repeat;"></div>
<?php }?>				
				<div class="t_logins_san" style="border:1px solid #DDDDDD;width:50px;height:30px;cursor:pointer;float:left;margin-left:80px;line-height:30px;text-align:center;color:#999999;" onclick="p_reset();">注册</div>
			</td>
		</tr>
	</table>
</div><!-- 浮层-登录  -->
<div style="position:absolute;top:0;left:0;margin:0;z-index:999999;background:#666666;background-color:rgba(0,0,0,0.5);filter:Alpha(Opacity='50');display:none;" id="t_wei_bu_set"></div>
<div style="border:1px solid #DDDDDD;position:fixed;top:30%;left:40%;margin:0;z-index:999999;background:#FFFFFF;font-family:'Microsoft YaHei';color:#999999;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;display:none;" id="this_layer_reset">
<h2 style="border-bottom:1px solid #DDDDDD;margin:0 20px 0 20px;height:60px;line-height:60px;text-align:center;">
	<p style="margin:0;position:absolute;top:20px;left:20px;width:30px;height:30px;border-radius:30px;-moz-border-radius:30px;-webkit-border-radius:30px;background:url(<?php echo APTH_URL."/subject/plugin/member/images/r_icon_set.png";?>) no-repeat 0px;cursor:pointer;"></p>
		<?php echo $member['reset_title'];?>
	</h2>
	<p id="x-box_set" style="margin:0;position:absolute;top:5px;right:5px;width:30px;height:30px;border-radius:30px;-moz-border-radius:30px;-webkit-border-radius:30px;background:url(<?php echo APTH_URL."/subject/plugin/member/images/x-icon-2-1.png";?>) no-repeat;cursor:pointer;" title="关闭"></p>
	<form action="subject/plugin/member/act_login.php" method="post" onsubmit="return act_submit_set();">
	<table style="width:460px;margin:5px 20px 0 20px;">
		<tr style="height:48px;">
			<td style="width:80px;">用户名</td>
			<td>
			<input type="text" name="username" value="" id="t_username_u_set" style="outline: none;width:230px;padding:3px;border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;"/>
			<span style="color:red;font-size:12px;" id="t_u_1">*</span>
			</td>
		</tr>
		<tr style="height:48px;">
			<td style="width:80px;">密 &nbsp;&nbsp;码</td>
			<td>
			<input type="password" name="pwd" value="" id="t_pwd_p_set" style="outline: none;width:230px;padding:3px;border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;"/>
			<span style="color:red;font-size:12px;" id="t_u_2">*</span>
			</td>
		</tr>
		<tr style="height:48px;">
			<td style="width:80px;">确认密码</td>
			<td>
			<input type="password" name="pwd2" value="" id="t_pwd2_p_set" style="outline: none;width:230px;padding:3px;border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;"/>
			<span style="color:red;font-size:12px;" id="t_u_22">*</span>
			</td>
		</tr>
		<tr style="height:48px;">
			<td style="width:80px;">手 &nbsp;&nbsp;机</td>
			<td>
			<input type="text" name="tel" value="" id="t_tel_p_set" style="outline: none;width:230px;padding:3px;border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;"/>
			<span style="color:red;font-size:12px;" id="t_u_3">*</span>
			</td>
		</tr>
		<tr style="height:48px;">
			<td style="width:80px;">邮 &nbsp;&nbsp;箱</td>
			<td>
			<input type="text" name="email" value="" id="t_email_p_set" style="outline: none;width:230px;padding:3px;border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;"/>
			<span style="color:red;font-size:12px;" id="t_u_4">*</span>
			</td>
		</tr>
		<tr style="height:48px;">
			<td style="width:80px;">验证码</td>
			<td>
			<input type="text" name="virify" value=""  id="v_fy_y_set"  style="outline: none;width:50px;padding:3px;border-bottom:1px solid #DDDDDD;border-right:1px solid #DDDDDD;border-radius:5px 5px;-moz-border-radius:5px 5px;-webkit-border-radius:5px 5px;"/> &nbsp; 
			<img src="<?php echo APTH_URL."/system/virify.php";?>" align="absmiddle" id="virify_set" alt="验证码"/> &nbsp; 
			<a href="javascript:;" onclick="document.getElementById('virify_set').src='<?php echo APTH_URL."/system/virify.php";?>?rand='+Math.random();document.getElementById('v_fy_y_set').value='';" style="color:#999999;" id="t_huan-y_set">换一张</a> &nbsp;  &nbsp;&nbsp;&nbsp;
			<span style="color:red;font-size:12px;" id="t_u_5"></span>
			</td>
		</tr>
		<tr style="height:48px;">
			<td></td>
			<td>
			<input type="hidden" name="act" value="reset_user"/>
			<input type="submit" value="注册" style="border:1px solid #DDDDDD;outline: none;width:230px;height:35px;border-radius:8px 8px;-moz-border-radius:8px 8px;-webkit-border-radius:8px 8px;cursor:pointer;color:#333333;" id="t_this_submit_set"/>
			</td>
		</tr>
	</table>
</form>
	<table style="margin:3px 20px 0 20px;">
		<tr>
			<td><?php if( $member['show_hide'] == 1 ){?>其他方式登录<?php }?></td>
			<td>
<?php if( $member['show_hide'] == 1 ){?>			
				<div class="t_logins_san_set" style="border:1px solid #DDDDDD;width:30px;height:30px;border-radius:30px 30px;-moz-border-radius:30px 30px;-webkit-border-radius:30px 30px;cursor:pointer;float:left;margin-left:20px;margin-right:20px;background:url(<?php echo APTH_URL."/subject/plugin/member/images/q-icon-2.png";?>) no-repeat;"></div>
				<div class="t_logins_san_set" style="border:1px solid #DDDDDD;width:30px;height:30px;border-radius:30px 30px;-moz-border-radius:30px 30px;-webkit-border-radius:30px 30px;cursor:pointer;float:left;margin-right:20px;background:url(<?php echo APTH_URL."/subject/plugin/member/images/w-icon-2.png";?>) no-repeat;"></div>
				<div class="t_logins_san_set" style="border:1px solid #DDDDDD;width:30px;height:30px;border-radius:30px 30px;-moz-border-radius:30px 30px;-webkit-border-radius:30px 30px;cursor:pointer;float:left;margin-right:20px;background:url(<?php echo APTH_URL."/subject/plugin/member/images/xin-icon-2.png";?>) no-repeat;"></div>
<?php }?>				
				<div class="t_logins_san" style="border:1px solid #DDDDDD;width:80px;height:30px;cursor:pointer;float:left;margin-left:80px;line-height:30px;text-align:center;color:#999999;" onclick="p_login();">返回登录</div>
			</td>
		</tr>
	</table>
</div><!-- 浮层-注册  -->
<script src="<?php echo APTH_URL."/subject/plugin/member/js/jquery-1.7.1.min.js";?>"></script>
<script>

var t_sw = window.screen.width;  
var t_sh = window.screen.height; 

var t_w = 500;
var t_h = 340;

var t_w2 = 500;
var t_h2 = 480;

var t_wei = document.getElementById("t_wei_bu");
var t_wei_set = document.getElementById("t_wei_bu_set");
var t_l = document.getElementById("this_layer");
var t_reset = document.getElementById("this_layer_reset");

t_wei.style.width = t_sw+"px";
t_wei.style.height = t_sh+"px";

t_wei_set.style.width = t_sw+"px";
t_wei_set.style.height = t_sh+"px";

t_l.style.width = t_w+"px";
t_l.style.height = t_h+"px";

t_reset.style.width = t_w2+"px";
t_reset.style.height = t_h2+"px";

var scenw=0,scenh=0,scenw2=0,scenh2=0;

scenw = (t_sw-t_w)/2;
scenh = (t_sh-t_h)/3;

scenw2 = (t_sw-t_w2)/2;
scenh2 = (t_sh-t_h2)/3;

t_l.style.top = scenh+"px";
t_l.style.left = scenw+"px";

t_reset.style.top = scenh2+"px";
t_reset.style.left = scenw2+"px";

var userid=1,userid2=0,pwd=1,virify=1,virify2=1,tel=0,email=0;
$(function(){
	$("#t_this_submit,#t_this_submit_set").hover(function(){
		$(this).css({"background":"#0099FF","color":"#FFFFFF"});
	},function(){
		$(this).css({"background":"#DDDDDD","color":"#333333"});
	});
	$("#t_huan-y,#t_huan-y_set").hover(function(){
		$(this).css({"color":"#0066FF"});
	},function(){
		$(this).css({"color":"#999999"});
	});
	$(".t_logins_san,.t_logins_san_set").hover(function(){
		$(this).css({"border":"1px solid #9A9A9A"});
	},function(){
		$(this).css({"border":"1px solid #DDDDDD"});
	});
	$("#x-box,#x-box_set").hover(function(){
		$(this).css({"background":"url(<?php echo APTH_URL."/subject/plugin/member/images/x-icon-2.png";?>) no-repeat"});
	},function(){
		$(this).css({"background":"url(<?php echo APTH_URL."/subject/plugin/member/images/x-icon-2-1.png";?>) no-repeat"});
	});

	$("#x-box").click(function(){
		$("#this_layer").hide();
		$("#t_wei_bu").hide();
	});
	$("#x-box_set").click(function(){
		$("#this_layer_reset").hide();
		$("#t_wei_bu_set").hide();
	});
	
	$("#t_username_u").change(function(){
		$.post("<?php echo APTH_URL."/subject/plugin/member/act_login.php";?>",{"act":"VirifyName","username":$(this).val()},function(d){
			userid = d;
			if( d > 0 ){
				$("#t_tels_1").css({"color":"green"});
				$("#t_tels_1").html("√");
			}else{
				$("#t_tels_1").css({"color":"red"});
				$("#t_tels_1").html("×用户名不存在");
			}
		});
	});

	$("#t_username_u_set").change(function(){
		$.post("<?php echo APTH_URL."/subject/plugin/member/act_login.php";?>",{"act":"VirifyName","username":$(this).val()},function(d){
			userid2 = d;
			if( d > 0 ){
				$("#t_u_1").css({"color":"red"});
				$("#t_u_1").html("×用户名已存在");
			}else{
				$("#t_u_1").css({"color":"green"});
				$("#t_u_1").html("√");
			}
		});
	});

	$("#t_pwd_p").change(function(){
		$.post("<?php echo APTH_URL."/subject/plugin/member/act_login.php";?>",{"act":"VirifyPaw","pwd":$(this).val()},function(d){
			pwd=d;
			if( d > 0 ){
				$("#p_tels_1").css({"color":"green"});
				$("#p_tels_1").html("√");
			}else{
				$("#p_tels_1").css({"color":"red"});
				$("#p_tels_1").html("×密码不正确");
			}
		});
	});

	$("#t_tel_p_set").change(function(){
		$.post("<?php echo APTH_URL."/subject/plugin/member/act_login.php";?>",{"act":"VirifyTel","tel":$(this).val()},function(d){
			tel=d;
			if( d == 0 ){
				$("#t_u_3").css({"color":"green"});
				$("#t_u_3").html("√");
			}else{
				$("#t_u_3").css({"color":"red"});
				$("#t_u_3").html("×手机号码已经被使用");
			}
		});
	});

	$("#t_email_p_set").change(function(){
		$.post("<?php echo APTH_URL."/subject/plugin/member/act_login.php";?>",{"act":"VirifyEmail","email":$(this).val()},function(d){
			email=d;
			if( d == 0 ){
				$("#t_u_4").css({"color":"green"});
				$("#t_u_4").html("√");
			}else{
				$("#t_u_4").css({"color":"red"});
				$("#t_u_4").html("×邮箱已经被占用");
			}
		});
	});
	
	$("#v_fy_y").change(function(){
		$.post("<?php echo APTH_URL."/subject/plugin/member/act_login.php";?>",{"act":"VirifyTwo","virify":$(this).val()},function(d){
			virify=d;
			if( d > 0 ){
				$("#v_tels_1").css({"color":"green"});
				$("#v_tels_1").html("√");
			}else{
				$("#v_tels_1").css({"color":"red"});
				$("#v_tels_1").html("×验证码不正确");
			}
		});
	});
	$("#t_pwd_p_set").change(function(){
		if( $(this).val() == '' ){
			$("#t_u_2").css({"color":"red"});
			$("#t_u_2").html("×请输入密码6-16位");
		}else{
			if( $(this).val().length < 6 || $(this).val().length > 16 ){
				$("#t_u_2").css({"color":"red"});
				$("#t_u_2").html("×密码6-16位字符");
			}else{
				$("#t_u_2").css({"color":"green"});
				$("#t_u_2").html("√");
			}
		}
	});
	$("#t_pwd2_p_set").change(function(){
		if( $(this).val() == '' ){
			$("#t_u_22").css({"color":"red"});
			$("#t_u_22").html("×请输入确认密码");
		}else{
			if($(this).val()!=$("#t_pwd_p_set").val()){
				$("#t_u_22").css({"color":"red"});
				$("#t_u_22").html("×两次密码不一致");
			}else{
				$("#t_u_22").css({"color":"green"});
				$("#t_u_22").html("√");
			}
		}
	});
	$("#t_tel_p_set").change(function(){
		if( $(this).val() == '' ){
			$("#t_u_3").css({"color":"red"});
			$("#t_u_3").html("×请输入手机号码");
		}else{
			var re =/^0?(13|14|15|17|18)[0-9]{9}$/;
		    var result = re.test($(this).val());
			if($(this).val().length != 11){
				$("#t_u_3").css({"color":"red"});
				$("#t_u_3").html("×手机号码不正确");
			}else{
				if(!result){
					$("#t_u_3").css({"color":"red"});
					$("#t_u_3").html("×手机号码不正确");
				}else{
					$("#t_u_3").css({"color":"green"});
					$("#t_u_3").html("√");
				}
			}
		}
	});
	$("#t_email_p_set").change(function(){
		if( $(this).val() == '' ){
			$("#t_u_4").css({"color":"red"});
			$("#t_u_4").html("×请输入邮箱");
		}else{
			var rel =/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/;
		    var resultl = rel.test($(this).val());
		    if(!resultl){
		    	$("#t_u_4").css({"color":"red"});
				$("#t_u_4").html("×邮箱号不正确");
			}else{
				$("#t_u_4").css({"color":"green"});
				$("#t_u_4").html("√");
			}
		}
	});
	$("#v_fy_y_set").change(function(){
		if( $(this).val() == '' ){
			$("#t_u_5").css({"color":"red"});
			$("#t_u_5").html("×请输入验证码");
		}else{
			$.post("<?php echo APTH_URL."/subject/plugin/member/act_login.php";?>",{"act":"VirifyTwo","virify":$(this).val()},function(d){
				virify2=d;
				if( d == 0 ){
					$("#t_u_5").css({"color":"red"});
					$("#t_u_5").html("×验证码不正确");
				}else{
					$("#t_u_5").css({"color":"green"});
					$("#t_u_5").html("√");
				}
			});
		}
	});
});
function p_login(){
	$("#this_layer_reset").hide();
	$("#t_wei_bu_set").hide();
	$("#this_layer").show();
	$("#t_wei_bu").show();
}
function p_reset(){
	$("#this_layer").hide();
	$("#t_wei_bu").hide();
	$("#this_layer_reset").show();
	$("#t_wei_bu_set").show();
}
function act_submit(){
	if( $("#t_username_u").val() == "" ){
		$("#t_tels_1").html("×请输入用户名");
		return false;
	}
	if( userid == 0 ){
		$("#t_tels_1").html("×用户名不存在");
		return false;
	}
	if( $("#t_pwd_p").val() == "" ){
		$("#p_tels_1").html("×请输入密码");
		return false;
	}
	if( pwd == 0 ){
		$("#p_tels_1").html("×密码不正确");
		return false;
	}
	if( $("#v_fy_y").val() == "" ){
		$("#v_tels_1").html("请输入验证码");
		return false;
	}
	if( virify == 0 ){
		$("#v_tels_1").html("×验证码");
		return false;
	}
}
function act_submit_set(){
	if( $("#t_username_u_set").val() == '' ){
		$("#t_u_1").html("×请输入用户名");
		return false;
	}
	if( userid2 > 0 ){
		$("#t_u_1").html("×用户名已经被注册");
		return false;
	}
	if( $("#t_pwd_p_set").val() == '' ){
		$("#t_u_2").css({"color":"red"});
		$("#t_u_2").html("×请输入密码6-16位");
		return false;
	}else{
		$("#t_u_2").css({"color":"green"});
		$("#t_u_2").html("√");
	}
	if( $("#t_pwd_p_set").val().length < 6 || $("#t_pwd_p_set").val().length > 16 ){
		$("#t_u_2").css({"color":"red"});
		$("#t_u_2").html("×密码6-16位字符");
	}else{
		$("#t_u_2").css({"color":"green"});
		$("#t_u_2").html("√");
	}
	if( $("#t_pwd2_p_set").val() == '' ){
		$("#t_u_22").css({"color":"red"});
		$("#t_u_22").html("×请输入确认密码");
		return false;
	}else{
		$("#t_u_22").css({"color":"green"});
		$("#t_u_22").html("√");
	}
	if( $("#t_pwd_p_set").val()!=$("#t_pwd2_p_set").val() ){
		$("#t_u_22").css({"color":"red"});
		$("#t_u_22").html("×两次密码不一致");
		return false;
	}else{
		$("#t_u_22").css({"color":"green"});
		$("#t_u_22").html("√");
	}
	if( $("#t_tel_p_set").val() == '' ){
		$("#t_u_3").css({"color":"red"});
		$("#t_u_3").html("×请输入手机号码");
		return false;
	}else{
		$("#t_u_3").css({"color":"green"});
		$("#t_u_3").html("√");
	}
	var re2 =/^0?(13|14|15|17|18)[0-9]{9}$/;
    var result2 = re2.test($("#t_tel_p_set").val());
    if( !result2 ){
		if( $("#t_tel_p_set").val().length != 11 ){
			$("#t_u_3").css({"color":"red"});
			$("#t_u_3").html("×手机号码不正确");
			return false;
		}
    }else{
		if(!result2){
			$("#t_u_3").css({"color":"red"});
			$("#t_u_3").html("×手机号码不正确");
			return false;
		}else{
			$("#t_u_3").css({"color":"green"});
			$("#t_u_3").html("√");
		}
    }
    if( tel > 0 ){
    	$("#t_u_3").css({"color":"red"});
		$("#t_u_3").html("×手机号码已经被使用");
		return false;
    }else{
    	$("#t_u_3").css({"color":"green"});
		$("#t_u_3").html("√");
    }
	if( $("#t_email_p_set").val() == '' ){
		$("#t_u_4").css({"color":"red"});
		$("#t_u_4").html("×请输入邮箱");
		return false;
	}else{
		$("#t_u_4").css({"color":"green"});
		$("#t_u_4").html("√");
	}
	var rel =/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/;
    var resultl = rel.test($("#t_email_p_set").val());
    if(!resultl){
    	$("#t_u_4").css({"color":"red"});
		$("#t_u_4").html("×邮箱号码不正确");
		return false;
    }else{
    	$("#t_u_4").css({"color":"green"});
		$("#t_u_4").html("√");
    }

    if( email > 0 ){
    	$("#t_u_4").css({"color":"red"});
		$("#t_u_4").html("×邮箱号码已经被点用");
		return false;
    }else{
    	$("#t_u_4").css({"color":"green"});
		$("#t_u_4").html("√");
    }
	if( $("#v_fy_y_set").val() == '' ){
		$("#t_u_5").css({"color":"red"});
		$("#t_u_5").html("×请输入验证码");
		return false;
	}else{
		$("#t_u_5").css({"color":"green"});
		$("#t_u_5").html("√");
	}
	if( virify2 == 0 ){
		$("#t_u_5").css({"color":"red"});
		$("#t_u_5").html("×验证码不正确");
		return false;
	}else{
		$("#t_u_5").css({"color":"green"});
		$("#t_u_5").html("√");
	}
}
</script>