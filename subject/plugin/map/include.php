<?php 
/**
 * 主题调用外部插件 <?php include Pagecall('map');?>
 * */
$row = db()->select('xmlrpc,themeimg,themeas,addmenu')->from(PRE.'theme')->where(array('themename'=>'map'))->get()->array_row();
if($row['addmenu'] == 'OFF')
{
	if(!empty($row))
	{
		$aamap = (array)simplexml_load_string(file_get_contents(DIR_URL.'/'.$row['xmlrpc']));
	}
}
?>
<?php if($row['addmenu'] == 'OFF'){?> 
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4ea0c424ca942a78b1b19f39c7a928fb"></script> 
<div id="allmap_container" style="height:<?php echo $aamap['height'];?>px;"></div>
<script type="text/javascript">

var lenlat = '<?php echo $aamap['lnglat'];?>';
var lrr = lenlat.split(',');

var biao = '<?php echo $aamap['biao'];?>';

var wws = '<?php echo $aamap['w'];?>';
var hhs = '<?php echo $aamap['h'];?>';

var bbmap = new BMap.Map("allmap_container");    // 创建Map实例
var point = new BMap.Point(parseFloat(lrr[0]),parseFloat(lrr[1]));
bbmap.centerAndZoom(point, 18);  // 初始化地图,设置中心点坐标和地图级别
bbmap.setCurrentCity("<?php echo $aamap['cityName'];?>"); // 设置地图显示的城市 此项是必须设置的

var marker = new BMap.Marker(point);  // 创建标注
bbmap.addOverlay(marker);// 将标注添加到地图中
if( biao == 2 )
{
	marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
}

//信息窗口
var opts = {
		  title : "<?php echo $aamap['title'];?>"  // 信息窗口标题
		}
var infoWindow = new BMap.InfoWindow("<?php echo $aamap['textarea1'];?> <br/> <?php echo $aamap['textarea2'];?>", opts);  // 创建信息窗口对象 
marker.addEventListener("mouseover", function(){     
	bbmap.openInfoWindow(infoWindow,point); //开启信息窗口
});
</script>	
<?php }?> 