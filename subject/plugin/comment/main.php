<?php 
require('../../../public_include.php');
$row = db()->select('xmlrpc,themeimg,themeas')->from(PRE.'theme')->where(array('id'=>$_GET['id']))->get()->array_row();
$data = (array)simplexml_load_string(file_get_contents(dir_url($row['xmlrpc'])));

$review = db()->select('*')->from(PRE.'review_up')->get()->array_row();
?>
<div class="useredit"><!-- 开始　-->
	<div class="userheadermain">
	<img src="<?php echo apth_url($row['themeimg']);?>" width="32" height="32" align="absmiddle"/> 
	<?php echo $row['themeas']?>
	</div>
	<!-- 布局页面开始　 -->
	<div>
	<ul class="menuWeb clear"><li class="menuWebAction">文章评论管理插件</li></ul>
	<div style="height:10px;"></div>
	<form action="handling_events.php" method="post">
	<table class="tableBox">
			<tr>
				<td width="320"><b>启用评论过滤器功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid filter" style="height:17px;margin-top:4px;background:url(http://127.0.0.1/ThisCMSSystem/system/admin/images/checkbox.png) no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="filter" value="<?php echo $review['filter']?>" />
				</td>
			</tr>
			<tr>
				<td width="320"><b>黑词列表</b><br>· 使用正则表达式，最后一个字符不能是“|”<br>· 启用评论过滤器功能，立即生效。</td>
				<td>
				<textarea name="blacklist" class="text-web"><?php echo $review['blacklist']?></textarea>
				</td>
			</tr>
			<tr>
				<td width="320"><b>敏感词列表 </b><br>· 使用正则表达式，最后一个字符不能是“|”<br>· 启用评论过滤器功能，立即生效。</td>
				<td>
				<textarea name="sensitivelist" class="text-web"><?php echo $review['sensitivelist']?></textarea>
				</td>
			</tr>
			<tr>
				<td width="320"><b>启用评论拦截IP功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid stopped" style="height:17px;margin-top:4px;background:url(http://127.0.0.1/ThisCMSSystem/system/admin/images/checkbox.png) no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="stopped" value="<?php echo $review['stopped']?>"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>IP过滤列表</b><br>· 用|分隔，支持*屏蔽IP段<br>· 启用评论拦截IP功能，立即生效。</td>
				<td>
				<textarea name="ipfilterlist" class="text-web"><?php echo $review['ipfilterlist']?></textarea>
				</td>
			</tr>
			<tr>
				<td width="320"><b>关闭评论功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid pinglun" style="height:17px;margin-top:4px;background:url(http://127.0.0.1/ThisCMSSystem/system/admin/images/checkbox.png) no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="colsecomment" value="<?php echo $review['colsecomment']?>" class="pagepl"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>审核评论</b><br/>打开后发布的评论都将进入审核状态</td>
				<td>
				<p style="position:relative;">
				<span class="clzhid shenhe" style="height:17px;margin-top:4px;background:url(http://127.0.0.1/ThisCMSSystem/system/admin/images/checkbox.png) no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="moderation" value="<?php echo $review['moderation']?>" class="pageshenhe"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>启用评论倒序输出</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid pldese" style="height:17px;margin-top:4px;background:url(http://127.0.0.1/ThisCMSSystem/system/admin/images/checkbox.png) no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="sort" value="<?php echo $review['sort']?>" class="pagepldese"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>启用评论验证码功能</b></td>
				<td>
				<p style="position:relative;">
				<span class="clzhid virify" style="height:17px;margin-top:4px;background:url(http://127.0.0.1/ThisCMSSystem/system/admin/images/checkbox.png) no-repeat 0 -17px;"></span><span class="clzhid" style="width:100px;margin-left:2px;"></span>
				<input type="hidden" name="vifiy" value="<?php echo $review['vifiy']?>" class="pagevirify"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>每页输出评论数量</b></td>
				<td>
				<p style="margin-top:10px;margin-bottom:10px;">
				<input type="text" name="listtotal" value="<?php echo $review['listtotal']?>" class="input-web"/>
				</p>
				</td>
			</tr>
			<tr>
				<td width="320"><b>非登录用户信息项</b></td>
				<td>
				<p style="">
				<input type="checkbox" name="talbox" value="1" <?php echo $review['talbox']==0?'':'checked="checked"'?> id="bo1"/>
				<label for="bo1">显示手机框(Tal)</label>				
				 &nbsp; &nbsp; &nbsp; 
				 <input type="checkbox" name="emailbox" <?php echo $review['emailbox']==0?'':'checked="checked"'?> value="1" id="bo2"/>
				<label for="bo2">显示邮箱框(email)</label>				
				 &nbsp; &nbsp; &nbsp; 
				 <input type="checkbox" name="qqbox" <?php echo $review['qqbox']==0?'':'checked="checked"'?> value="1" id="bo3"/>
				<label for="bo3">显示QQ框(QQ)</label>				
				</p>
				</td>
			</tr>		
			
			<tr>
				<td colspan="2">
				<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>
				<input type="hidden" name="act" value="ReviewMain"/>
				<input type="submit" value="提交" class="sub"/>
<script>
//默认对像，请不要删除
$(".showerror").hide(2000);

if( $("[name=colsecomment]").val() == 'ON' )
{
	$(".pinglun").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
}
else
{
	$(".pinglun").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
}	
$(".pinglun").click(function(){
if( $("[name=colsecomment]").val() == 'OFF' )
{
	$("[name=colsecomment]").val("ON");
	$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
}
else
{
	$("[name=colsecomment]").val("OFF");
	$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
}
});

if( $(".pageshenhe").val() == 'ON' )
{
	$(".shenhe").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
}
else
{
	$(".shenhe").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
}
$(".shenhe").click(function(){
if( $(".pageshenhe").val() == 'OFF' )
{
	$(".pageshenhe").val("ON");
	$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
}
else
{
	$(".pageshenhe").val("OFF");
	$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
}
});

if( $(".pagepldese").val() == 'ON' )
{
	$(".pldese").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
}
else
{
	$(".pldese").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
}
$(".pldese").click(function(){
if( $(".pagepldese").val() == 'OFF' )
{
	$(".pagepldese").val("ON");
	$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
}
else
{
	$(".pagepldese").val("OFF");
	$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
}
});

if($("[name=stopped]").val()=="ON")
{
	$(".stopped").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
}
else
{
	$(".stopped").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
}
$(".stopped").click(function(){
	if($("[name=stopped]").val()=="OFF")
	{
		$("[name=stopped]").val("ON");
		$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
	}
	else
	{
		$("[name=stopped]").val("OFF");
		$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
	}
});

if($("[name=filter]").val()=="ON")
{
	$(".filter").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
}
else
{
	$(".filter").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
}
$(".filter").click(function(){
	if($("[name=filter]").val()=="OFF")
	{
		$("[name=filter]").val("ON");
		$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
	}
	else
	{
		$("[name=filter]").val("OFF");
		$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
	}
});

if( $(".pagevirify").val() == 'ON' )
{
	$(".virify").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
}
else
{
	$(".virify").css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
}
$(".virify").click(function(){
if( $(".pagevirify").val() == 'OFF' )
{
	$(".pagevirify").val("ON");
	$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 -17px"});	
}
else
{
	$(".pagevirify").val("OFF");
	$(this).css({"background":"url(<?php echo apth_url('system/admin/images/checkbox.png');?>) no-repeat 0 0"});
}
});
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
	
</div><!-- 结束  -->
<script>
$(function(){
	//这里写JS代码
	
});
</script>