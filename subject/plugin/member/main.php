<?php 
require('../../../public_include.php');
$row = db()->select('xmlrpc,themeimg,themeas,addmenu')->from(PRE.'theme')->where(array('id'=>$_GET['id']))->get()->array_row();
if(!empty($row))
{
	$data = (array)simplexml_load_string(file_get_contents(dir_url($row['xmlrpc'])));
}
?>
<div class="useredit"><!-- 开始　-->

	<div class="userheadermain">
	<img src="<?php echo apth_url($row['themeimg']);?>" width="32" height="32" align="absmiddle"/> 
	<?php echo $row['themeas']?>
	</div>
	
	<!-- 布局页面开始　 -->
	<div>
		<!-- 创建插件模块 -->
		<ul class="menuWeb clear">
		<li class="menuWebAction">
		<a href="<?php echo apth_url('system/admin/index.php?act=ModuleEdt&flag=5&id='.$_GET['id']);?>">创键模块</a></li></ul>
		<div style="height:10px;"></div>
		
		<!-- 插件页面表单测试－〉直接提交数据　 -->
		<form action="<?php echo apth_url('system/xml-rpc/index.php');?>" method="post" enctype="multipart/form-data">
		<table class="tableBox">
			<tr style="height:50px;">
				<th colspan="2">会员管理插件</th>
			</tr>
			<tr>
				<td width="320"><b>登录调回URL</b><br/> * 登录成功后，会员中心链接地址</td>
				<td>		
					设置 URL : <input type="text" name="back_url" value="<?php echo $data['back_url']?>" style="width:450px;padding:3px;"/> <font color="red">*</font>
				</td>
			</tr>
			<tr>
				<td width="320"><b>其他方式登录</b><br/> * 设置第三方密钥</td>
				<td>
				<br/>
				<h4>QQ设置</h4>	
				<br/>
				<p>	
					APP ID : <input type="text" name="qq_appid" value="<?php echo $data['qq_appid']?>" style="width:450px;padding:3px;"/>
				</P>
				<br/>
				<p>	
					APPKEY : <input type="text" name="qq_appkey" value="<?php echo $data['qq_appkey']?>" style="width:450px;padding:3px;"/>
				</p>
				<br/>
				<p>	
					链接设置 : <input type="text" name="qq_url" value="<?php echo $data['qq_url']?>" style="width:450px;padding:3px;"/>
				</p>	
				<br/>
				<hr/>
				<br/>
				<h4>微信设置</h4>	
				<br/>
				<p>	
					APP ID : <input type="text" name="w_appid" value="<?php echo $data['w_appid']?>" style="width:450px;padding:3px;"/>
				</P>
				<br/>
				<p>	
					APPKEY : <input type="text" name="w_appkey" value="<?php echo $data['w_appkey']?>" style="width:450px;padding:3px;"/>
				</p>
				<br/>
				<p>	
					链接设置 : <input type="text" name="w_url" value="<?php echo $data['w_url']?>" style="width:450px;padding:3px;"/>
				</p>		
				<br/>
				<hr/>
				<br/>
				<h4>新浪设置</h4>	
				<br/>
				<p>	
					APP ID : <input type="text" name="x_appid" value="<?php echo $data['x_appid']?>" style="width:450px;padding:3px;"/>
				</P>
				<br/>
				<p>	
					APPKEY : <input type="text" name="x_appkey" value="<?php echo $data['x_appkey']?>" style="width:450px;padding:3px;"/>
				</p>
				<br/>
				<p>	
					链接设置 : <input type="text" name="x_url" value="<?php echo $data['x_url']?>" style="width:450px;padding:3px;"/>
				</p>	
				<br/>
				</td>
			</tr>
			<tr>
				<td width="320"><b>其他登录方式显示状态</b></td>
				<td>		
				<input type="radio" name="show_hide" value="1" id="rt2" <?php echo $data['show_hide']==1?'checked="checked"':''?>/><label for="rt2"> 显示</label> &nbsp; &nbsp;
				<input type="radio" name="show_hide" value="0" id="rt1" <?php echo $data['show_hide']==0?'checked="checked"':''?>/><label for="rt1"> 隐藏</label>
				</td>
			</tr>
			<tr>
				<td width="320"><b>其他登录方式小图标</b></td>
				<td>
					<br/>
					<p>
					图标1 
					<input type="file" name="tBiao11"/><img src="<?php echo apth_url($data['tBiao11']);?>" width="32" height="32" align="absmiddle" />
					<input type="hidden" name="tBiao11" value="<?php echo $data['tBiao11'];?>"/> &nbsp; &nbsp;  &nbsp; &nbsp;alt:
					<input type="text" name="tbalt1" value="<?php echo $data['tbalt1'];?>"/>
					</p>
					<br/>
					<p>
					图标2 
					<input type="file" name="tBiao12"/><img src="<?php echo apth_url($data['tBiao12']);?>" width="32" height="32" align="absmiddle" />
					<input type="hidden" name="tBiao12" value="<?php echo $data['tBiao12'];?>"/> &nbsp; &nbsp;  &nbsp; &nbsp;alt:
					<input type="text" name="tbalt2" value="<?php echo $data['tbalt2'];?>"/>
					</p>
					<br/>
					<p>
					图标3 
					<input type="file" name="tBiao13"/><img src="<?php echo apth_url($data['tBiao13']);?>" width="32" height="32" align="absmiddle" />
					<input type="hidden" name="tBiao13" value="<?php echo $data['tBiao13'];?>"/> &nbsp; &nbsp;  &nbsp; &nbsp;alt:
					<input type="text" name="tbalt3" value="<?php echo $data['tbalt3'];?>"/>
					</p>
					<br/>
				</td>
			</tr>
			<tr>
				<td width="320"><b>登录框标题</b><br/> * 修改登录框标题文字</td>
				<td>		
					设置 标题 : <input type="text" name="login_title" value="<?php echo $data['login_title']==''?'登录':$data['login_title'];?>" style="width:450px;padding:3px;"/> <font color="red">*</font>
				</td>
			</tr>
			<tr>
				<td width="320"><b>注册框标题</b><br/> * 修改注册框标题文字</td>
				<td>		
					设置 标题 : <input type="text" name="reset_title" value="<?php echo $data['reset_title']==''?'注册':$data['reset_title'];?>" style="width:450px;padding:3px;"/> <font color="red">*</font>
				</td>
			</tr>	
			<tr style="height:60px;">
				<td colspan="2">
				<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/> 
				<input type="submit" value="保存" class="sub"/>		
				<input type="reset" value="重置" class="sub"/>		
				</td>
			</tr>
		</table>
		</form>
	</div>
	
</div><!-- 结束  -->
<script>
//这里写JS代码
$(function(){
	//默认对像，请不要删除
	$(".showerror").hide(2000);
	
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
	$(".tableBox tr").hover(function(){
	$(this).css({"background":"#FFFFDD"});
	},function(){
	$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
	});
});
$(function(){
	if($("[name='filter']").val() == "ON")
	{
		$(".filter").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
	}
	else
	{
		$(".filter").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});
	}	
	$(".filter").click(function(){
		if($("[name='filter']").val() == "ON")
		{
			$("[name='filter']").val("OFF");
			$(".filter").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});
		}
		else
		{
			$("[name='filter']").val("ON");
			$(".filter").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
		}	
	});
});
</script>