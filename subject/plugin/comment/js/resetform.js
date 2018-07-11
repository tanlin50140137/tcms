/**
 * 事件
 */
var mqq=0;

$(function(){
	//提交评论
	$("#frmSumbit").submit(function(){
		if($("[name='titleid']").val() == '')
		{
			alert("文章ID不存在");
			return false;
		}
		if($("[name='name']").val() == '')
		{
			alert("昵称不能留空");
			$("[name='name']").focus();
			return false;
		}
		if($("[name='tal']").val() != '')
		{
			var tals = $("[name='tal']").val();
			if(tals.length == 11 )
			{
				var patrn=/^0?(13|14|15|17|18)[0-9]{9}$/;
				if(!patrn.exec(tals))
				{
					alert("手机号码不正确");
					$("[name='tal']").focus();
					return false;
				}
			}
			else
			{
				alert("手机号码必须11位");
				$("[name='tal']").focus();
				return false;
			}	
			
		}
		if($("[name='email']").val() != '')
		{
			var patrn1=/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/;
			if(!patrn1.exec($("[name='email']").val()))
			{
				alert("请输入正确的邮箱");
				$("[name='email']").focus();
				return false;
			}
		}
		if($("[name='qq']").val() != '')
		{
			var patrn2=/^\d+$/;
			if(!patrn2.exec($("[name='qq']").val()))
			{
				alert("QQ号码不正确");
				$("[name='qq']").focus();
				return false;
			}
		}
		if($("[name='body']").val() == '')
		{
			alert("正文不能留空");
			return false;
		}
		if(mqq == 0)
		{	
		$.ajax({			
			url:$(this).attr('action'),
			type:'post',
			data:$(this).serialize(),
			//beforeSend:function(){},
			complete:function(){
				$("#txaArticle").val("");
				$("[name='name']").attr({"readonly":"readonly"});
				$("[name='tal']").attr({"readonly":"readonly"});
				$("[name='email']").attr({"readonly":"readonly"});
				$("[name='qq']").attr({"readonly":"readonly"});
				$("[name='pid']").val('0');	
				$("#cancel-reply").hide();
				mqq = 0;
			},
			success:function(d){
				//alert(d);return false;
				var obj = eval("("+d+")");
				var html = '<div class="Comment-line"><a>热门评论:</a></div>';
				if( obj.error == '0' )
				{
					var objArr = obj.data;					
					var len = objArr.length-1;
					$(".content-CommentPost").empty();
					for(var i=0;i<=len;i++)
					{
						if(objArr[i].pid !=0 )
						{
							html += '<div class="Commentqu">'+
							'<div class="Commentqu-ins Commentqu-ins-left" ><img src="'+objArr[i].pic+'" width="48" height="48"/></div>'+
							'<div class="Commentqu-ins Commentqu-ins-right">'+
							'<div class="CommentotherName">'+
							'<div class="Comment-name-list1">'+objArr[i].name+'</div>'+
							'<div class="Comment-name-list2">'+
								'<dl>'+
									'<dd id="Comment-dianzan"><span class="Comment-dianzan'+objArr[i].id+'" onclick="dianzan('+objArr[i].id+')" style="cursor:pointer;">点赞</span> <span class="Comment-dianzan-num'+objArr[i].id+'">'+objArr[i].likes+'</span></dd>'+
									'<dd>'+
									'<a href="#comment" class="comment-huifu-this">回复</a>'+
									'<input type="hidden" class="comment-hidden-id" value="'+objArr[i].id+'"/>'+
									'</dd>'+
									'<dd><a href="javascript:;" onclick="report('+objArr[i].id+')">举报</a></dd>'+
								'</dl>'+
							'</div>'+
							'<div style="clear:both;"></div>'+
							'</div>'+
							'<div class="CommentotherTime">'+objArr[i].publitime+'</div>'+
							'<div class="Commentotherbody">'+
							'<p><span class="Comment-huifu-ins">回复</span> <span class="Comment-huifu-ins">'+objArr[i].chill.name+'：</span>'+objArr[i].body+'</p>'+
							'<div class="Comment-name-huifu">'+
							'<p><span class="Comment-huifu-ins">'+objArr[i].chill.name+'：</span>'+objArr[i].chill.body+'</p>'+
							'</div>'+
							'</div>'+
							'</div>'+
							'<div style="clear:both;"></div>'+
						'</div>';
						}
						else
						{
							html += '<div class="Commentqu">'+
							'<div class="Commentqu-ins Commentqu-ins-left" ><img src="'+objArr[i].pic+'" width="48" height="48"/></div>'+
							'<div class="Commentqu-ins Commentqu-ins-right">'+
							'<div class="CommentotherName">'+
							'<div class="Comment-name-list1">'+objArr[i].name+'</div>'+
							'<div class="Comment-name-list2">'+
								'<dl>'+
									'<dd id="Comment-dianzan"><span class="Comment-dianzan'+objArr[i].id+'" onclick="dianzan('+objArr[i].id+')" style="cursor:pointer;">点赞</span> <span class="Comment-dianzan-num'+objArr[i].id+'">'+objArr[i].likes+'</span></dd>'+
									'<dd>'+
									'<a href="#comment" class="comment-huifu-this">回复</a>'+
									'<input type="hidden" class="comment-hidden-id" value="'+objArr[i].id+'"/>'+
									'</dd>'+
									'<dd><a href="javascript:;" onclick="report('+objArr[i].id+')">举报</a></dd>'+
								'</dl>'+
							'</div>'+
							'<div style="clear:both;"></div>'+
							'</div>'+
							'<div class="CommentotherTime">'+objArr[i].publitime+'</div>'+
							'<div class="Commentotherbody">'+
							'<p>'+objArr[i].body+'</p>'+
							'</div>'+
							'</div>'+
							'<div style="clear:both;"></div>'+
						'</div>';							
						}	
					}	
					$(".content-CommentPost").append(html);
					$.post($("#frmSumbit").attr('action'),{act:'artstatic',id:$("[name='titleid']").val()},function(d){});
				}
				else
				{
					alert(obj.error);				
				}	
			}

		});
		}
		mqq++;
		return false;
	});
});

$(function(){
	$(".content-CommentPost").mouseenter(function(){	
		$(".comment-huifu-this").each(function(index){
			$(this).click(function(){
				$("#cancel-reply").show();
				$("[name='pid']").val($(".comment-hidden-id:eq("+index+")").val());	
			});
		});
		$("#cancel-reply").click(function(){
			$("[name='pid']").val('0');	
			$(this).hide();
		});
	});	
	var p = 2; 
	$(window).scroll(function(){
		var scrollTop = $(this).scrollTop();
		var scrollHeight = $(document).height();
		var windowHeight = $(this).height();
		if(scrollTop + windowHeight == scrollHeight)
		{
		　　　$.ajax({			
			url:$("#frmSumbit").attr('action'),
			type:'post',
			data:'act=ReviewPuBuLiu&pid='+$("[name=titleid]").val()+'&page='+p,
			beforeSend:function(){
				$("#pulun-loading1").show();
				$("#pulun-loading2").hide();
			},
			complete:function(){
				$("#pulun-loading1").fadeOut(2000,function(){
					$("#pulun-loading2").show();
				});			
			},
			success:function(d){
				//alert(d);return false;
				var obj = eval("("+d+")");
				var html = '';
				if( obj.error == '0' )
				{
					var objArr = obj.data;					
					var len = objArr.length-1;
					for(var i=0;i<=len;i++)
					{
						if(objArr[i].pid !=0 )
						{
							html += '<div class="Commentqu">'+
							'<div class="Commentqu-ins Commentqu-ins-left" ><img src="'+objArr[i].pic+'" width="48" height="48"/></div>'+
							'<div class="Commentqu-ins Commentqu-ins-right">'+
							'<div class="CommentotherName">'+
							'<div class="Comment-name-list1">'+objArr[i].name+'</div>'+
							'<div class="Comment-name-list2">'+
								'<dl>'+
									'<dd id="Comment-dianzan"><span class="Comment-dianzan'+objArr[i].id+'" onclick="dianzan('+objArr[i].id+')" style="cursor:pointer;">点赞</span> <span class="Comment-dianzan-num'+objArr[i].id+'">'+objArr[i].likes+'</span></dd>'+
									'<dd>'+
									'<a href="#comment" class="comment-huifu-this">回复</a>'+
									'<input type="hidden" class="comment-hidden-id" value="'+objArr[i].id+'"/>'+
									'</dd>'+
									'<dd><a href="javascript:;">举报</a></dd>'+
								'</dl>'+
							'</div>'+
							'<div style="clear:both;"></div>'+
							'</div>'+
							'<div class="CommentotherTime">'+objArr[i].publitime+'</div>'+
							'<div class="Commentotherbody">'+
							'<p><span class="Comment-huifu-ins">回复</span> <span class="Comment-huifu-ins">'+objArr[i].chill.name+'：</span>'+objArr[i].body+'</p>'+
							'<div class="Comment-name-huifu">'+
							'<p><span class="Comment-huifu-ins">'+objArr[i].chill.name+'：</span>'+objArr[i].chill.body+'</p>'+
							'</div>'+
							'</div>'+
							'</div>'+
							'<div style="clear:both;"></div>'+
						'</div>';
						}
						else
						{
							html += '<div class="Commentqu">'+
							'<div class="Commentqu-ins Commentqu-ins-left" ><img src="'+objArr[i].pic+'" width="48" height="48"/></div>'+
							'<div class="Commentqu-ins Commentqu-ins-right">'+
							'<div class="CommentotherName">'+
							'<div class="Comment-name-list1">'+objArr[i].name+'</div>'+
							'<div class="Comment-name-list2">'+
								'<dl>'+
									'<dd id="Comment-dianzan"><span class="Comment-dianzan'+objArr[i].id+'" onclick="dianzan('+objArr[i].id+')" style="cursor:pointer;">点赞</span> <span class="Comment-dianzan-num'+objArr[i].id+'">'+objArr[i].likes+'</span></dd>'+
									'<dd>'+
									'<a href="#comment" class="comment-huifu-this">回复</a>'+
									'<input type="hidden" class="comment-hidden-id" value="'+objArr[i].id+'"/>'+
									'</dd>'+
									'<dd><a href="javascript:;">举报</a></dd>'+
								'</dl>'+
							'</div>'+
							'<div style="clear:both;"></div>'+
							'</div>'+
							'<div class="CommentotherTime">'+objArr[i].publitime+'</div>'+
							'<div class="Commentotherbody">'+
							'<p>'+objArr[i].body+'</p>'+
							'</div>'+
							'</div>'+
							'<div style="clear:both;"></div>'+
						'</div>';							
						}	
					}	
					$(".content-CommentPost").append(html);
				}
				else
				{
					//$("#pulun-loading2").show();		
				}	
			}

		});
			p++;
		}
		else
		{
			$("#pulun-loading1").hide();
			$("#pulun-loading2").hide();
		}	
	});
});

function dianzan(id)
{
	$.ajax({			
		url:$("[name=dianzanUrl]").val(),
		type:'post',
		data:'act=dianzan&id='+id,
		success:function(d){
			if( d != 'no' )
			{
				$(".Comment-dianzan-num"+id).html(d);
				$(".Comment-dianzan"+id).css({"color":"#FF9900"});
			}
			else
			{
				alert('您已经点过赞');
			}	
		}	
	});
}

function report(id)
{
	$.ajax({			
		url:$("[name=dianzanUrl]").val(),
		type:'post',
		data:'act=report&id='+id,
		success:function(d){
			if( d == 'no' )
			{
				alert('您已经举报过');
			}
			else
			{
				alert('举报成功');
			}	
		}	
	});
}