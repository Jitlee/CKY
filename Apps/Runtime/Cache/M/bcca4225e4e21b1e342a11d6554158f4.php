<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title><?php echo ($title); ?></title>
    <link rel="stylesheet" href="/Apps/M/View/default/css/mui.min.css" />
    <link rel="stylesheet" href="//at.alicdn.com/t/font_1450773372_6072462.css" />
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
</head>
<body>
	<header class="mui-bar mui-bar-nav">
	<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	<h1 id="title" class="mui-title"><?php echo ($title); ?></h1>
</header>


<nav class="mui-bar mui-bar-tab">
	<a id="home" class="mui-tab-item mui-active" href="<?php echo U('Index/index', '', '');?>">
		<span class="mui-icon iconfont icon-home"></span>
		<span class="mui-tab-label">首页</span>
	</a>
	<a id="seller" class="mui-tab-item" href="<?php echo U('Index/index', '', '');?>">
		<span class="mui-icon iconfont icon-seller"></span>
		<span class="mui-tab-label">商家</span>
	</a>
	<a id="member" class="mui-tab-item" href="<?php echo U('Index/index', '', '');?>">
		<span class="mui-icon iconfont icon-member"></span>
		<span class="mui-tab-label">会员中心</span>
	</a>
	<a id="more" class="mui-tab-item" href="<?php echo U('Index/index', '', '');?>">
		<span class="mui-icon iconfont icon-more"></span>
		<span class="mui-tab-label">更多</span>
	</a>
</nav>

</body>
</html>