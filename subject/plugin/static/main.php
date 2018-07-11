<?php 
require('../../../public_include.php');
$row = db()->select('xmlrpc,themeimg,themeas')->from(PRE.'theme')->where(array('id'=>$_GET['id']))->get()->array_row();
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
	<ul class="menuWeb clear"><li class="menuWebAction">文章静态化管理插件</li></ul>
	<div style="height:10px;"></div>
	<form action="<?php echo apth_url('system/xml-rpc/index.php');?>" method="post" enctype="multipart/form-data">
	<table class="tableBox">
			<tr>
				<td width="320"><b>启用静态化功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid filter" style="height:17px;margin-top:4px;background:url(http://127.0.0.1/ThisCMSSystem/system/admin/images/checkbox.png) no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="filter" value="<?php echo $data['filter']?>" />
				</td>
			</tr>
			<tr>
				<td width="320"><b>文章发布</b></td>
				<td>
				<p style="position:relative;">				
				<input type="radio" name="art" value="1" id="rt2" <?php echo $data['art']==1?'checked="checked"':''?>/><label for="rt2"> 静态</label> &nbsp; &nbsp;
				<input type="radio" name="art" value="0" id="rt1" <?php echo $data['art']==0?'checked="checked"':''?>/><label for="rt1"> 动态</label>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>首页</b></td>
				<td>
				<p style="position:relative;">
				<input type="radio" name="index" value="1" id="index2" <?php echo $data['index']==1?'checked="checked"':''?>/><label for="index2"> 静态</label> &nbsp; &nbsp;	
				<input type="radio" name="index" value="0" id="index1" <?php echo $data['index']==0?'checked="checked"':''?>/><label for="index1"> 动态</label>			
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>栏目列表</b></td>
				<td>
				<p style="position:relative;">
				<input type="radio" name="lanmu" value="1" id="lanmu2" <?php echo $data['lanmu']==1?'checked="checked"':''?>/><label for="lanmu2"> 静态</label> &nbsp; &nbsp;		
				<input type="radio" name="lanmu" value="0" id="lanmu1" <?php echo $data['lanmu']==0?'checked="checked"':''?>/><label for="lanmu1"> 动态</label>	
				</p>
				</td>
			</tr>
			<!-- 	
			<tr>
				<td width="320"><b>列表分页</b></td>
				<td>
				<p style="position:relative;">
				<input type="radio" name="fy" value="1" id="fy2" <?php echo $data['fy']==1?'checked="checked"':''?>/><label for="fy2"> 静态</label> &nbsp; &nbsp;
				<input type="radio" name="fy" value="0" id="fy1" <?php echo $data['fy']==0?'checked="checked"':''?>/><label for="fy1"> 动态</label>			
				</p>
				</td>
			</tr>
			 -->
			<tr height="40">
				<td colspan="2"><font color="red">* 修改参数后，需要点击“保存”</font></td>
			</tr>
			<tr>
				<td colspan="2">
				<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/> 
				<input type="submit" value="保存" class="sub"/>
<script>
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
	</div>
	<hr/>
	<ul class="menuWeb clear" style="margin-top:10px;">
	<li class="menuWebAction" style="margin-right:5px;" title="整站生成静态文件">
	<a href="<?php echo apth_url('subject/plugin/static/keyupdate.php');?>" target="update">一键更新</a>
	</li>
	<li class="menuWebAction" style="margin-right:5px;" title="首页静态化">
	<a href="<?php echo apth_url('subject/plugin/static/compile_index.php?act=0');?>" target="update">生成首页</a>
	</li>
	<li class="menuWebAction" style="margin-right:5px;" title="栏目列表静态化">
	<a href="<?php echo apth_url('subject/plugin/static/column.php?act=0');?>" target="update">更新栏目</a>
	</li>
	<li class="menuWebAction" style="margin-right:5px;">
	<a href="<?php echo apth_url('subject/plugin/static/content.php?act=0');?>" target="update">更新文档</a>
	</li>
	<li class="menuWebAction" style="margin-right:5px;">
	<a href="<?php echo apth_url('subject/plugin/static/clear.php');?>" target="update">清空缓存</a>
	</li>
	</ul>
	<div style="font-size:14px;margin-top:10px;padding:5px;position:relative;">
	<div id="loding_img" style="position:absolute;top:50px;left:45%;display:none;"><img src="<?php echo apth_url('subject/plugin/static/images/loading-0.gif');?>" title="更新中...请耐心等带!"/></div>
	<iframe src="<?php echo apth_url('subject/plugin/static/del.php');?>" name="update" width="100%" height="150"></iframe>
	</div>
</div><!-- 结束  -->
<script>
$(function(){
	//默认对像，请不要删除
	$(".showerror").hide(2000);
	//这里写JS代码
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
function loding_img_show()
{
	$("#loding_img").show();
}
function loding_img_hide()
{
	$("#loding_img").hide();
}
</script>