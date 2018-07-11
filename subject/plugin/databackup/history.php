<?php
require('../../../public_include.php');
date_default_timezone_set('PRC');

#获取备份文件
$bacdir = dir_url('subject/plugin/databackup/backups');

	if(is_dir($bacdir))
	{
		$arrlist = opendir($bacdir);
		while ( ($item = readdir($arrlist))!==false )
		{
			if( $item!='.' && $item!='..' )
			{
				$f[] = $item;
			}		
		}
	}
	if(!empty($f)){
		$blist = array_reverse($f);
		$rowsTotal = count($blist);
		$showTotal = 8;
		$pageTotal = ceil($rowsTotal/$showTotal);
		$page = $_GET['page']==''?1:$_GET['page'];
		if($page>=$pageTotal){$page=$pageTotal;}
		if($page<=1||!is_numeric($page)){$page=1;}
		$offset = ($page-1)*$showTotal;
		
		$alist = array_slice($blist, $offset, $showTotal);
		
		$lens=count($blist);
		if( $page > 1 )
		{
			$lens = $lens-(($page-1)*$showTotal);
			if( $lens <= 1 )
			{
				$lens = 1;
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<title>历史备份</title>
<script type="text/javascript" src="<?php echo apth_url('subject/plugin/databackup/js/jquery-1.7.1.min.js');?>"></script>
<style type="text/css">
*{margin:0;padding:0;font-family:"微软雅黑","Times New Roman",Georgia,Serif}
</style>
</head>
<body>

<div style="width:1000px;margin:10px auto auto auto;">

					<table class="tableBox" style="border:1px solid #E1E1E1;width:100%;border-collapse:collapse;font-size:14px;color:#666666;" border="1">
						<tr>
							<th colspan="4" height="40">历史备份</th>
						</tr>
						<tr>
							<th height="40">序号</th>
							<th height="40">时间</th>
							<th height="40">大小</th>
							<th height="40">操作</th>
						</tr>
		<?php if(!empty($alist)){?>
			<?php foreach( $alist as $k => $v ){?>					
						<tr>
							<td height="40" align="center"><?php echo $lens;?></td>
							<td height="40" align="center" style="text-indent:0.5em;">
							<?php echo date('Y-m-d H:i:s',filemtime($bacdir.'/'.$v));?>
							 &nbsp; 
							<?php echo get_day(time()-filemtime($bacdir.'/'.$v));?> 
							</td>
							<td height="40" align="center"><?php echo get_size(filesize($bacdir.'/'.$v));?></td>
							<td height="40" align="center">
							<a href="javascript:;" onclick="conf2('<?php echo $v;?>','<?php echo $page;?>');">一键还原</a>
							 &nbsp;  &nbsp;
							<a href="javascript:;" onclick="conf3('<?php echo $v;?>');">下载</a>
							 &nbsp;  &nbsp;
							<a href="javascript:;" onclick="conf('<?php echo $v;?>','<?php echo $page;?>');">删除</a> 
							</td>
						</tr>
			<?php $lens--;}?>			
		<?php }?>	
						<tr>
							<td colspan="4" height="40" align="center">
								总数:<?php echo count($blist);?> &nbsp; 当前:<?php echo $page;?>/<?php echo $pageTotal;?>页
								&nbsp; &nbsp; 
								<a href="?page=<?php echo ($page-1);?>">上一页</a>
								 &nbsp;|&nbsp;
								<a href="?page=<?php echo ($page+1);?>">下一页</a>
								 &nbsp; &nbsp;
								<input type="text" name="GO" size="2" value=""/>
								<input type="submit" value="GO" id="GO"/>
							</td>
						</tr>
					</table>

	</div>
	<script>
	$(function(){
		$("#GO").click(function(){
			if($("[name=GO]").val()!=''){
				location.href='?page='+$("[name=GO]").val();
			}
		});
		$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		$(".tableBox tr").hover(function(){
		$(this).css({"background":"#FFFFDD"});
		},function(){
			$(".tableBox tr").filter(":even").css("background","#F4F4F4").end().filter(":odd").css("background","#FFFFFF");
		});
	});
	function conf(h,p)
	{
		var bl = window.confirm('是否要删除?');
		if(bl)
		{
			location.href="delete.php?f="+h+"&page="+p;
		}
	}
	function conf2(h,p)
	{
		var bl = window.confirm('是否要还原?');
		if(bl)
		{
			location.href="restores.php?f="+h+"&page="+p;
		}
	}
	function conf3(h)
	{
		var bl = window.confirm('是否下载?');
		if(bl)
		{
			location.href="download.php?f="+h;
		}
	}
	</script>
</body>
</html>
<?php 
function get_size($int)
{
	$i = 0;
	while ( $int >= 1024 )
	{
		$int /= 1024;
		$i++;
	}
	$ext = array('B','KB','MB','GB','TB');
	
	return round($int,2).$ext[$i];
}
function get_day($int)
{
	if( $int < 86400 )
	{#秒->分->时 换算
		$i = 0;
		while ( $int >= 60 )
		{
			$int /= 60;
			$i++;
		}
		$ext = array('秒前','分钟前','小时前');
	}
	
	if( $int >= 86400 && $int < 2592000)
	{#时->天 换算
		$i = 0;
		while ( $int >= 86400 )
		{
			$int /= 86400;
			$i++;
		}
		$ext = array('小时前','天前');
	}
	if( $int >= 2592000 && $int < 31104000)
	{#天->月 换算
		$i = 0;
		while ( $int >= 2592000 )
		{
			$int /= 2592000;
			$i++;
		}
		$ext = array('天前','个月前');
	}
	if( $int >= 31104000 )
	{#月->年 换算
		$i = 0;
		while ( $int >= 31104000 )
		{
			$int /= 31104000;
			$i++;
		}
		$ext = array('个月前','年前');
	}
	return floor($int).$ext[$i];
}
?>