<?php 
require('../../../public_include.php');
$row = db()->select('xmlrpc,themeimg,themeas,addmenu')->from(PRE.'theme')->where(array('id'=>$_GET['id']))->get()->array_row();
if(!empty($row))
{
	$data = (array)simplexml_load_string(file_get_contents(DIR_URL.'/'.$row['xmlrpc']));
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
			<tr style="height:450px;">
				<th colspan="2">
					<div id="allmap" style="height:450px;margin:auto;"></div>
				</th>
			</tr>
			<tr style="height:50px;">
				<td colspan="2" align="center">
					<input type="hidden" name="lnglat" id="lnglat" value="<?php echo $data['lnglat']?>" style="width:150px;padding:3px;"/>
					<span style="font-size:14px;color:#333333;"> 鼠标苗准位置，在地图上点击定位，然后点击“保存”即可完成</span>
				</td>
			</tr>
			<tr style="height:50px;">
				<td colspan="2" align="center">
					城市名: <input id="cityName" name="cityName" value="<?php echo $data['cityName'];?>" type="text" style="width:100px; margin-right:10px;padding:3px;" />
					<input type="button" value="查询" onclick="theLocation()" />
					 &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;
					街道地址: <input id="cityNamed" type="text" style="width:350px; margin-right:10px;padding:3px;" />
					<input type="button" value="查询" onclick="thelocation_search()" />
				</td>
			</tr>
			<tr style="height:50px;">
				<td width="200"><b>标注</b></td>
				<td>		
					<input type="radio" name="biao" value="1" id="biao2" <?php echo $data['biao']==1?'checked="checked"':''?>/><label for="biao2"> 固定</label> &nbsp; &nbsp;
				<input type="radio" name="biao" value="2" id="biao1" <?php echo $data['biao']==2?'checked="checked"':''?>/><label for="biao1"> 跳点</label>
				</td>
			</tr>
			<tr style="height:50px;">
				<td width="200"><b>前台展示高度</b></td>
				<td>
					<input type="text" name="height" value="<?php echo $data['height']?>" style="width:50px;padding:3px;"/> px
				</td>
			</tr>
			<tr style="height:50px;">
				<td width="200"><b>信息窗口标题</b></td>
				<td>		
					<input type="text" name="title" value="<?php echo $data['title']?>" style="width:420px;padding:3px;"/>
					<span style="font-size:12px;color:#666666;"> * 点击标注点，查看信息窗口　</span>
				</td>
			</tr>
			<tr>
				<td width="200"><b>信息窗口地址</b></td>
				<td>		
					<textarea name="textarea1" rows="5" cols="30" style="padding:3px;"><?php echo $data['textarea1']?></textarea>
					 &nbsp; &nbsp;
					<textarea name="textarea2" rows="5" cols="30" style="padding:3px;"><?php echo $data['textarea2']?></textarea>
				</td>
			</tr>	
			<tr>
				<td colspan="2">
				<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/> 
				<input type="submit" value="保存" class="sub"/>			
				</td>
			</tr>
		</table>
		</form>
	</div>
	
</div><!-- 结束  -->
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4ea0c424ca942a78b1b19f39c7a928fb"></script>
<script type="text/javascript">
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
//百度地图API功能

var lenlat = $("#lnglat").val();
var lrr = lenlat.split(',');

var biao = '<?php echo $data['biao'];?>';

var map = new BMap.Map("allmap");    // 创建Map实例
var point = new BMap.Point(parseFloat(lrr[0]),parseFloat(lrr[1]));
map.centerAndZoom(point, 18);  // 初始化地图,设置中心点坐标和地图级别
map.addControl(new BMap.MapTypeControl());   //添加地图类型控件
map.setCurrentCity("<?php echo $data['cityName'];?>");          // 设置地图显示的城市 此项是必须设置的
map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放

map.addControl(new BMap.NavigationControl());    
map.addControl(new BMap.ScaleControl());    
map.addControl(new BMap.OverviewMapControl());    
map.addControl(new BMap.MapTypeControl());  

var marker = new BMap.Marker(point);  // 创建标注
map.addOverlay(marker);// 将标注添加到地图中
if( biao == 2 )
{
	marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
}

//信息窗口
var opts = {
		  title : "<?php echo $data['title'];?>"  // 信息窗口标题
		}
var infoWindow = new BMap.InfoWindow("<?php echo $data['textarea1'];?> <br/> <?php echo $data['textarea2'];?>", opts);  // 创建信息窗口对象 
marker.addEventListener("mouseover", function(){     
	map.openInfoWindow(infoWindow,point); //开启信息窗口
});

//单击获取点击的经纬度
map.addEventListener("click",function(e){

	map.clearOverlays(); 
	
	$("#lnglat").val(e.point.lng + "," + e.point.lat);
	lenlat = e.point.lng + "," + e.point.lat;
	lrr = lenlat.split(',');
	point = new BMap.Point(parseFloat(lrr[0]),parseFloat(lrr[1]));

	marker = new BMap.Marker(point);  // 创建标注
	map.addOverlay(marker);// 将标注添加到地图中
	if( biao == 2 )
	{
		marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
	}
	
});

//添加右键
var menu = new BMap.ContextMenu();
var txtMenuItem = [
	{
		text:'放大',
		callback:function(){map.zoomIn()}
	},
	{
		text:'缩小',
		callback:function(){map.zoomOut()}
	}
];
for(var i=0; i < txtMenuItem.length; i++){
	menu.addItem(new BMap.MenuItem(txtMenuItem[i].text,txtMenuItem[i].callback,100));
}
map.addContextMenu(menu);

function theLocation(){
	var city = document.getElementById("cityName").value;
	if(city != ""){
		map.centerAndZoom(city,11);      // 用城市名设置地图中心点
	}
}

function thelocation_search(){
	var cityd = document.getElementById("cityNamed").value;
	
	var local = new BMap.LocalSearch(map, {
		renderOptions:{map: map}
	});
	local.search(cityd);
}
</script>