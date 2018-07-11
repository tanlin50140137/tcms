<?php if( $chajian['addmenu'] == 'OFF'){?> <!--插件启用时生效-->
<?php if($setcomment['colsecomment'] == 'ON'){?> <!--关闭评论-->
<?php if($ArticleBoby['nocomment'] == 'ON'){?> <!--禁止评论-->

<link rel="stylesheet" rev="stylesheet" href="<?php echo apth_url('subject/plugin/'.$chajian['themename'].'/css/global.css')?>" type="text/css" media="all"/>
<div class="post" id="divCommentPost">
	
	<p class="posttop">发表评论:
	<a name="comment"></a>
	</p>
	<p><?php echo $nickname;?></p>
	<form id="frmSumbit" method="post" action="<?php echo apth_url('system/admin/handling_events.php')?>">
	<input type="hidden" name="titleid" id="inpId" value="<?php echo $ArticleBoby['id']?>" />
<?php if( $nickname == false ){?>	
	<p><input type="text" name="name" id="inpRevID" value="" /> 昵称(*)</p>
	<?php if( $setcomment['talbox'] == '1' ){?>
	<p><input type="text" name="tal" id="inpName" value="" /> Tal</p>
	<?php }else{?>
	<input type="hidden" name="tal" id="inpName" value="" />
	<?php }?>
	<?php if( $setcomment['emailbox'] == '1' ){?>
	<p><input type="text" name="email" id="inpEmail" value="" /> Email</p>
	<?php }else{?>
	<input type="hidden" name="email" id="inpEmail" value="" />
	<?php }?>
	<?php if( $setcomment['qqbox'] == '1' ){?>
	<p><input type="text" name="qq" id="inpHomePage" value="" /> QQ</p>
	<?php }else{?>
	<input type="hidden" name="qq" id="inpEmail" value="" />
	<?php }?>
<?php }else{?>	
	<input type="hidden" name="name" id="inpRevID" value="<?php echo $nickname;?>" />
	<input type="hidden" name="tal" id="inpName" value="" />
	<input type="hidden" name="email" id="inpEmail" value="" />
	<input type="hidden" name="qq" id="inpEmail" value="" />
<?php }?>
	<p><label for="txaArticle">正文(*)</label>
	<a rel="nofollow" id="cancel-reply" href="javascript:;" style="display:none;">
	<small>取消回复</small></a>
	</p>
	<p><textarea name="body" id="txaArticle" class="text" cols="50" rows="4" tabindex="5" ></textarea></p>
	<p>
	<input type="hidden" name="pid" value="0" />
	<input type="hidden" name="flag" value="0" />
	<input type="hidden" name="dianzanUrl" value="<?php echo apth_url('system/external_request.php')?>" />
	<input type="hidden" name="act" value="review" />
	<input type="submit" tabindex="6" value="提交" class="button" />
	<?php if( $setcomment['vifiy'] == 'OFF' ){?>
	　验证码　<input type="text" name="virify" size="3" id="inpHomePage" value="" /> 
	<img src="<?php echo site_url('system/virify.php');?>" alt="验证码" id="imgvirify" style="pointer-events: none;"/>
	<a href="javascript:;" title="点击换下一张" onclick="document.getElementById('imgvirify').src = document.getElementById('imgvirify').src+'?random='+Math.random();">换一张</a>
	<?php }?>
	</p>
	</form>
	<p class="postbottom">◎欢迎参与讨论，请在这里发表您的看法、交流您的观点。</p>
	

	<div class="content-CommentPost">
<?php if(!empty($commentList)){?>		
		<div class="Comment-line"><a>热门评论:</a></div>
<?php foreach($commentList as $k=>$v){?>	
	<?php if( $v['pid'] != 0 ){?>	
		<div class="Commentqu">
			<div class="Commentqu-ins Commentqu-ins-left" ><img src="<?php echo $v['pic']?>" width="48" height="48"/></div>
			<div class="Commentqu-ins Commentqu-ins-right">
			<div class="CommentotherName">
			<div class="Comment-name-list1"><?php echo $v['name']?></div>
			<div class="Comment-name-list2">
				<dl>
					<dd id="Comment-dianzan"><span class="Comment-dianzan<?php echo $v['id']?>" onclick="dianzan('<?php echo $v['id']?>')" style="cursor:pointer;">点赞</span> <span class="Comment-dianzan-num<?php echo $v['id']?>"><?php echo $v['likes']?></span></dd>
					<dd>
					<a href="#comment" class="comment-huifu-this">回复</a>
					<input type="hidden" class="comment-hidden-id" value="<?php echo $v['id']?>"/>
					</dd>
					<dd><a href="javascript:;" onclick="report('<?php echo $v['id']?>')">举报</a></dd>
				</dl>
			</div>
			<div style="clear:both;"></div>
			</div>
			<div class="CommentotherTime"><?php echo $v['publitime']?></div>
			<div class="Commentotherbody">
			<p><span class="Comment-huifu-ins">回复</span> <span class="Comment-huifu-ins"><?php echo $v['chill']['name']?>：</span><?php echo $v['body']?></p>
			<div class="Comment-name-huifu">
			<p><span class="Comment-huifu-ins"><?php echo $v['chill']['name']?>：</span><?php echo $v['chill']['body']?></p>
			</div>
			</div>
			</div>
			<div style="clear:both;"></div>
		</div><!-- 子块 -->
	<?php }else{?>		
		<div class="Commentqu">
			<div class="Commentqu-ins Commentqu-ins-left" ><img src="<?php echo $v['pic']?>" width="48" height="48"/></div>
			<div class="Commentqu-ins Commentqu-ins-right">
			<div class="CommentotherName">
			<div class="Comment-name-list1"><?php echo $v['name']?></div>
			<div class="Comment-name-list2">
				<dl>
					<dd id="Comment-dianzan"><span class="Comment-dianzan<?php echo $v['id']?>" onclick="dianzan('<?php echo $v['id']?>')" style="cursor:pointer;">点赞</span> <span class="Comment-dianzan-num<?php echo $v['id']?>"><?php echo $v['likes']?></span></dd>
					<dd>
					<a href="#comment" class="comment-huifu-this">回复</a>
					<input type="hidden" class="comment-hidden-id" value="<?php echo $v['id']?>"/>
					</dd>
					<dd><a href="javascript:;" onclick="report('<?php echo $v['id']?>')">举报</a></dd>
				</dl>
			</div>
			<div style="clear:both;"></div>
			</div>
			<div class="CommentotherTime"><?php echo $v['publitime']?></div>
			<div class="Commentotherbody">
			<p><?php echo $v['body']?></p>
			</div>
			</div>
			<div style="clear:both;"></div>
		</div><!-- 父块 -->
<?php } } ?>	
<?php } ?>	
	</div>
	<div style="text-align:center;display:none;" id="pulun-loading1"><img src="<?php echo apth_url('subject/plugin/'.$chajian['themename'].'/images/loading-0.gif')?>" alt="加载中..."/></div>
	<div style="text-align:center;display:none;" id="pulun-loading2"></div>
</div>
<script src="<?php echo apth_url('subject/plugin/'.$chajian['themename'].'/js/jquery-1.7.1.min.js')?>" type="text/javascript"></script>
<script src="<?php echo apth_url('subject/plugin/'.$chajian['themename'].'/js/resetform.js')?>" type="text/javascript"></script>

<?php } } }?>