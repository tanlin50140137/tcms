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
			<tr>
				<th colspan="2">开始制作插件，只需稍稍修改，一切已经准备就绪。注：样式要求，统一使用，内联样式。</th>
			</tr>
			<tr>
				<td width="320"><b>开关</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid filter" style="height:17px;margin-top:4px;background:url(http://127.0.0.1/ThisCMSSystem/system/admin/images/checkbox.png) no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="filter" value="<?php echo $data['filter']?>" />
				</td>
			</tr>
			<tr>
				<td width="320"><b>文本框</b></td>
				<td>		
					<input type="text" name="text" value="<?php echo $data['text']?>"/>
				</td>
			</tr>
			<tr>
				<td width="320"><b>密码框</b></td>
				<td>		
					<input type="password" name="password" value="<?php echo $data['password']?>"/>
				</td>
			</tr>
			<tr>
				<td width="320"><b>大文本框</b></td>
				<td>		
					<textarea name="textarea"><?php echo $data['textarea']?></textarea>
				</td>
			</tr>
			<tr>
				<td width="320"><b>下拉框</b></td>
				<td>		
					<select name="select">
						<option value="1" <?php echo $data['select']==1?'selected="selected"':''?>>下拉1</option>
						<option value="2" <?php echo $data['select']==2?'selected="selected"':''?>>下拉2</option>
						<option value="3" <?php echo $data['select']==3?'selected="selected"':''?>>下拉3</option>
						<option value="4" <?php echo $data['select']==4?'selected="selected"':''?>>下拉4</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="320"><b>单选框</b></td>
				<td>		
				<input type="radio" name="art" value="1" id="rt2" <?php echo $data['art']==1?'checked="checked"':''?>/><label for="rt2"> 选我1</label> &nbsp; &nbsp;
				<input type="radio" name="art" value="0" id="rt1" <?php echo $data['art']==0?'checked="checked"':''?>/><label for="rt1"> 选我2</label>
				</td>
			</tr>
			<tr>
				<td width="320"><b>复选框</b></td>
				<td>
				<input type="checkbox" name="index1" value="1" id="index2" <?php echo $data['index1']==1?'checked="checked"':''?>/><label for="index2"> 选我1</label> &nbsp; &nbsp;	
				<input type="checkbox" name="index2" value="2" id="index1" <?php echo $data['index2']==2?'checked="checked"':''?>/><label for="index1"> 选我2</label>			
				</td>
			</tr>
			<tr>
				<td width="320"><b>文件框</b></td>
				<td>
					图片上传1 
					<input type="file" name="file1"/><img src="<?php echo apth_url($data['file1']);?>" width="32" height="32"/>
					<input type="hidden" name="file1" value="<?php echo $data['file1'];?>"/>
				</td>
			</tr>	
			<tr>
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