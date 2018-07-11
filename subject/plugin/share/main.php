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
		
		<form action="<?php echo apth_url('system/xml-rpc/index.php');?>" method="post" enctype="multipart/form-data">
	<table class="tableBox">
			<tr>
				<td width="320"><b>页面</b></td>
				<td>
				<p style="position:relative;">	
				<input type="radio" name="page" value="1" id="rt1" <?php echo $data['page']==1?'checked="checked"':''?>/><label for="rt1"> 显示</label> &nbsp; &nbsp;			
				<input type="radio" name="page" value="2" id="rt2" <?php echo $data['page']==2?'checked="checked"':''?>/><label for="rt2"> 隐藏</label>			
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>框体样式</b></td>
				<td>
				<input type="radio" name="boxstyle" value="1" id="boxstyle1" <?php echo $data['boxstyle']==1?'checked="checked"':''?>/> <label for="boxstyle1" style="background-color:#00CCFF;"> &nbsp; &nbsp; </label> &nbsp; &nbsp;
				<input type="radio" name="boxstyle" value="2" id="boxstyle2" <?php echo $data['boxstyle']==2?'checked="checked"':''?>/> <label for="boxstyle2" style="background-color:#A9D401;"> &nbsp; &nbsp; </label> &nbsp; &nbsp;
				<input type="radio" name="boxstyle" value="3" id="boxstyle3" <?php echo $data['boxstyle']==3?'checked="checked"':''?>/> <label for="boxstyle3" style="background-color:#7B7B7B;"> &nbsp; &nbsp; </label> &nbsp; &nbsp;
				<input type="radio" name="boxstyle" value="4" id="boxstyle4" <?php echo $data['boxstyle']==4?'checked="checked"':''?>/> <label for="boxstyle4" style="background-color:#015EAC;"> &nbsp; &nbsp; </label> &nbsp; &nbsp;
				<input type="radio" name="boxstyle" value="5" id="boxstyle5" <?php echo $data['boxstyle']==5?'checked="checked"':''?>/> <label for="boxstyle5" style="background-color:#ED022A;"> &nbsp; &nbsp; </label> &nbsp; &nbsp;
				<input type="radio" name="boxstyle" value="6" id="boxstyle6" <?php echo $data['boxstyle']==6?'checked="checked"':''?>/> <label for="boxstyle6" style="background-color:#FB8200;"> &nbsp; &nbsp; </label> &nbsp; &nbsp;
				</td>
			</tr>
			<tr>
				<td width="320"><b>浮窗位置</b></td>
				<td>
				<p style="position:relative;">	
				<input type="radio" name="art" value="1" id="rt3" <?php echo $data['art']==1?'checked="checked"':''?>/><label for="rt3"> 左侧浮窗</label> &nbsp; &nbsp;			
				<input type="radio" name="art" value="2" id="rt4" <?php echo $data['art']==2?'checked="checked"':''?>/><label for="rt4"> 右侧浮窗</label>			
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>浮窗类型</b></td>
				<td>
				<p style="position:relative;">
				<input type="radio" name="index" value="1" id="index2" <?php echo $data['index']==1?'checked="checked"':''?>/><label for="index2"> 固定浮窗</label> &nbsp; &nbsp;	
				<input type="radio" name="index" value="2" id="index1" <?php echo $data['index']==2?'checked="checked"':''?>/><label for="index1"> 动态浮窗</label>			
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>风格</b></td>
				<td>
				<p style="position:relative;">
				<input type="radio" name="lanmu" value="1" id="lanmu2" <?php echo $data['lanmu']==1?'checked="checked"':''?>/><label for="lanmu2"> 标准</label> &nbsp; &nbsp;		
				<input type="radio" name="lanmu" value="2" id="lanmu1" <?php echo $data['lanmu']==2?'checked="checked"':''?>/><label for="lanmu1"> 迷你</label>	
				</p>
				</td>
			</tr>	
			<tr>
				<td width="320"><b>隐藏"查看更多"窗口</b></td>
				<td>
				<p style="position:relative;">
				<input type="radio" name="hide" value="1" id="hide2" <?php echo $data['hide']==1?'checked="checked"':''?>/><label for="hide2"> 开启</label> &nbsp; &nbsp;
				<input type="radio" name="hide" value="2" id="hide1" <?php echo $data['hide']==2?'checked="checked"':''?>/><label for="hide1"> 关闭</label>			
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>显示</b></td>
				<td>
				<p style="position:relative;">
				<input type="checkbox" name="check1" value="1" id="check1" <?php echo $data['check1']==1?'checked="checked"':''?>/><label for="check1"> 首页</label> &nbsp; &nbsp;
				<input type="checkbox" name="check2" value="2" id="check2" <?php echo $data['check2']==2?'checked="checked"':''?>/><label for="check2"> 列表页</label> &nbsp; &nbsp;
				<input type="checkbox" name="check3" value="3" id="check3" <?php echo $data['check3']==3?'checked="checked"':''?>/><label for="check3"> 内容页</label>	 &nbsp; &nbsp;		
				<input type="checkbox" name="check4" value="4" id="check4" <?php echo $data['check4']==4?'checked="checked"':''?>/><label for="check4"> 其它页</label>
				</p>
				</td>
			</tr>
			<tr height="50" >
				<td colspan="2"><font color="red">注：修改参数后，静态页面时，需要更新。动态页面时，首页需要等待缓存失效后，生效! &nbsp;首页面缓存设置 ： 网站设置 -> 全局设置 -> 首页缓存</font></td>
			</tr>
			<tr>
				<td colspan="2">
				<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/> 
				<input type="submit" value="修改" class="sub"/>				
				</td>
			</tr>
		</table>
		</form>
	</div>
	
</div><!-- 结束  -->
<script>
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
</script>