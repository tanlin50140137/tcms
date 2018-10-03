# tcms
最新版本CMS

<p>首页 <font color="red">subject/default/template/index.html</font></p>
<p>栏目页面 <font color="red">subject/default/template/article_list.html</font></p>
<p>内容页面 <font color="red">subject/default/template/article_content.html</font></p>
<p><font color="#0000ff">经过长时间的打磨，TCMS已经支持 pathinfo 模式；很轻松就可以实现伪静态。</font></p>
<p>TCMS传统的路由都是由“?act=value” 号传参数</p>
<p><font color="#0000ff">pathinfo模式“/value”</font></p>
<p><font color="#0000ff">如：现在要访问默认的文章列表“article_list.html”文章列表全部路径为“subject/default/template/article_list.html”,我们不需要访问这么长的路径</font></p>
<p>TCMS传统访问内页 "url?act=article_list" 这种是最基本的访问内页路径</p>
<p><font color="#0000ff">pathinfo模式访问内页 "/article_list" 直接就省去了"?act=" 而变得更简单了</font></p>
<p><font color="#0000ff">如果已经开启伪静态 pathinfo模式访问内页可以直接在后面加上html "/article_list.html" </font></p>
<p><font color="#0000ff">如果还有其他参数要传直接后面加上如 “/article_list/a/b/c/1/2/3....” 或 “/article_list/a/b/c/1/2/3.html”</font></p>

<p><font color="red">页面引用css,js,images目录文件 可以html页面中使用全局函数 "&lt;?php echo apth_url('subject/default/css/css文件.css');?&gt;"</font></p>
<p><font color="red">"&lt;?php echo apth_url('subject/default/css/css文件.css');?&gt;"</font></p>
<p><font color="red">"&lt;?php echo apth_url('subject/default/js/js文件.js');?&gt;"</font></p>
<p><font color="red">"&lt;?php echo apth_url('subject/default/images/images文件.jpg');?&gt;"</font></p>
