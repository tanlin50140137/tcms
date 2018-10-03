<?php
#######################################################
/**
 * 唯一可操作文件
 * 系统函数
 * 数据库操作
 * 自定义函数
 * 注：所有数据操作都在此文件中进行！！！
 * */
########################################################

#全局信息
$global = This_setting();

#服务端处理数据,栏目列表
$col_List = get_columnList(0);
#获取指定栏目信息,栏目列表
$columninfo = get_columninfo($_REQUEST['id']);
#获取主题并插件活动总数
$thpRows = getRowsThP();
#栏目列表+封面,$columnmodule=文件名，该栏目下的所有封面（包括子栏目）
#topics=所有专题
//$cover = get_columnCoverON('topics');#显示导航信息
#about=关于我们
//$about = get_columnCoverOFF('about');#不显示导航信息
#get_pixels($dir,$x,$y),页面图片掉用
/**
 * 锚点分类,相关信息
 * $clid=分类ID
 * $limit=获取长度
 * $img=0,纯文与图文混合
 * $img=1,图文
 * $img=2,纯文章,没有图片
 * AnchorClass($id,$clid,[$limit=5,$img=0])
 **/
/**
 * 锚点栏目
 * $columnname=栏目名称,输入“所有栏目”所得所有,文章
 * $limit=获取长度
 * $img=0,纯文与图文混合
 * $img=1,图文
 * $img=2,纯文章,没有图片
 * AnchorColumn($columnmodule,[$limit=5,$img=0])
 **/
#project_case=项目案例,文章
//$acolumn = AnchorColumn('project_case',10,0);

/**
 * 锚点模块 
 * $modulename,文件名-后台模块编辑可以查看
 * AnchorModule($modulename,[$int=0,$columnmodule=''])
 **/
#友情链接模块
//$Link = AnchorModule('Link');
#热门推荐,project_case=项目案例,文章
//$hot = AnchorModule('Hot',1,'project_case');
//$hot = json_decode($hot,true);
#为您推荐,project_case=项目案例,文章
//$topics = AnchorModule('Hot',1,'topics');
//$topics = json_decode($topics,true);
#调用文档列信息
switch (ACT)
{
	case 'index':
		#首页，服务端处理数据，通过栏目名称获取
		//$articleList = This_article(null,'desc',$page,$showTotal,$cipid,$top,$tag,$author,$fileds,$title,'product');
		//$art_List = server_data($articleList);	
	break;
	case 'article_list':
		#列表页，服务端处理数据，通过ID获取
		$articleList = This_article($id,'desc',$page,$showTotal,$cipid,$top,$tag,$author,$fileds,$title);
		$art_List = server_data($articleList);
	break;
}
/**
 * 订单提交接口功能块
 * 接口字段说明：
 * commodity,商品名称
 * ordernumber,订单号
 * money,金额
 * phone,手机
 * email,邮箱
 * publitime,时间
 * address,地址
 * allinfor,所有订单信息，json格式
 * status,订单状态
 * back,同步返回URL
 * 添加验证码,getViryfy()
 * getOrdersUrl_asyn($form_id)//js异步提交,添加在</form>表单下单
 * string getOrdersUrl();//获取POST接口url,同步提交
 * array getOrderInfo($userid=null,$bool=true,$page=1,$searchArr=null);//返回数据信息
 * */
 /**
 * 留言提交接口功能块
 * 接口字段说明：
 * pid,回复
 * name,姓名
 * age,年龄
 * body,正文
 * phone,手机
 * email,邮箱
 * publitime,时间
 * status,状态
 * back,同步返回URL
 * 添加验证码,getViryfy() 
 * getMessageUrl_asyn($form_id)//js异步提交,添加在</form>表单下单
 * string getMessageUrl();//获取POST接口url,同步提交
 * array getMessageInfo($userid=null,$bool=true,$page=1,$searchArr=null);//返回数据信息
 * */
 /**
 * 会员登录提交接口
 * 接口说明:
 * GetUserpost(),获取提交地址，POST方式
 * <input type="hidden" name="act" value="Login"/>，登录接口，Login
 * 字段说明：
 * username,用户名
 * pwd,密码
 * <input type="checkbox" name="zhou" value="1" id="che_zhou"/>，7天内自动登录
 * virify,验证码
 * getVirifyUrl()，获取验证码URL
 * <input type="hidden" name="back" value="<?php echo $member['back_url'];?>"/> ，调回地址
 * 信息：
 * $user_row = GetUserInfo(),获取用户信息，Array()
 */
/**
 * 会员注册提交接口
 * 接口说明:
 * GetUserpost(),获取提交地址，POST方式
 * <input type="hidden" name="act" value="reset_user"/>，登录接口，reset_user
 * 字段说明：
 * username,用户名
 * pwd,密码
 * pwd2,确认密码
 * tel,手机
 * email,邮箱
 * virify,验证码
 * getVirifyUrl()，获取验证码URL
 * back，调回地址
 */