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
	<hr/>
	<ul class="menuWeb clear" style="margin-top:10px;">
	<li class="menuWebAction" style="margin-right:5px;" title="备份项目">
	<a href="<?php echo apth_url('subject/plugin/databackup/del.php');?>" target="update">备份项目</a>
	</li>
	<li class="menuWebAction" style="margin-left:10px;margin-right:5px;" title="查看历史备份">
	<a href="<?php echo apth_url('subject/plugin/databackup/history.php');?>" target="update">查看历史备份</a>
	</li>
	<li class="menuWebAction" style="margin-left:10px;margin-right:5px;" title="一键备份">
	<a href="javascript:;" id="backup">一键备份</a>
	</li>
	<li class="menuWebAction" style="margin-left:10px;margin-right:5px;" title="帮助">
	<a href="<?php echo apth_url('subject/plugin/databackup/help.php');?>" target="update">帮助</a>
	</li>
	<li class="menuWebAction" style="margin-left:50px;margin-right:5px;" title="一键下载">
	<a href="<?php echo apth_url('subject/plugin/databackup/download.php');?>" target="update">一键下载</a>
	</li>
	<li class="menuWebAction" style="margin-left:20px;margin-right:5px;" title="导入">
	<a href="<?php echo apth_url('subject/plugin/databackup/import.php');?>" target="update">导入</a>
	</li>
	</ul>
	<div style="font-size:14px;margin-top:10px;padding:5px;">
	<iframe src="<?php echo apth_url('subject/plugin/databackup/del.php');?>" name="update" width="100%" height="450"></iframe>
	</div>
	</div>
	
</div><!-- 结束  -->
<script>
$(function(){
	//默认对像，请不要删除
	$(".showerror").hide(2000);
	
	//这里写JS代码 
	$("#backup").click(function(){
		conf("是否要备份？提示：点击“确定”后请不要离开此页面，否则备份失败或造成数据丢失，看到备份成功后再离开此页面！","<?php echo apth_url('subject/plugin/databackup/otb.php');?>");
	});

	function conf(h,u)
	{
		var bl = window.confirm(h);
		if(bl)
		{
			open(u,'update');
		}
	}
});
</script>