<?php if( $data['filter'] == 'ON' ){?>
	<script type='text/javascript'>
		var url = '<?php echo apth_url('subject/plugin/static/function.php')?>';
		$.post(url,{id:'<?php echo $ArticleBoby['id']?>'},function(data){});
	</script>
<?php } ?>