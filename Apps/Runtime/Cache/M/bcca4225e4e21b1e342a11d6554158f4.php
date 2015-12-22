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
	<button class="mui-btn mui-btn-link mui-pull-left">海通 ▼</button>
	<h1 id="title" class="mui-title"><?php echo ($title); ?></h1>
	<button class="mui-btn mui-btn-link mui-pull-right">我的</button>
</header>

<link rel="stylesheet" href="/Apps/M/View/default/css/swiper.min.css" />
<script src="/Apps/M/View/default/js/swiper.jquery.min.js"></script>
<style>
	.mui-content-bottom {
		padding-bottom: 50px;
	}
	.swiper-img {
		margin-bottom: 30px;
		border: solid 3px darkgreen;
		border-radius: 10px;
		height:50vw;
		background-repeat: no-repeat;
		background-size: cover;
		background-position: center;
	}
	
	.cky-grid:after {
		content: " ";
		height:1px;
		display: block;
		clear: both;
	}
	
	.cky-grid .cky-grid-cell{
		float: left;
		text-align: center;
		font-size:17px;
		color:#333;
		display: block;
	}
	
	.cky-grid4 .cky-grid-cell{
		width: 25%;
	}
	
	.cky-grid2 .cky-grid-cell{
		width: 50%;
	}
	
	.cky-grid4 .cky-grid-cell img{
		width:12vw;
		height:12vw;
		max-width: 90px;
		display: block;
		margin:5px auto;
	}
	
	.cky-special {
		margin-top:10px;
		background-color: #eee;
		border: solid 1px green;
		box-sizing: border-box;
		-webkit-box-sizing: border-box;
	}
	
	.cky-special .cky-grid-cell {
		box-sizing: border-box;
		-webkit-box-sizing: border-box;
		background-repeat: no-repeat;
		border: 1px solid transparent;
		background-position: 90% 50%;
		background-size: 10vw 10vw;
		padding-right: 12vw;
		padding-top: 5vw;
		padding-bottom: 5vw;
	}
	
	.cky-hotpot-ticket {
		background-image: url(/Apps/M/View/default/images/hotpot-ticket.png);	
		border-right-color:#C0C0C0!important;
		border-bottom-color:#C0C0C0!important;
	}
	
	.cky-points-edemption {
		background-image: url(/Apps/M/View/default/images/points-edemption.png);		
		border-bottom-color:#C0C0C0!important;
	}
	
	.cky-scratch-off {
		background-image: url(/Apps/M/View/default/images/scratch-off.png);	
		border-right-color:#C0C0C0!important;
	}
	
	.cky-delivery-free {
		background-image: url(/Apps/M/View/default/images/delivery-free.png);
	}
	
	.cky-grid2 .cky-media p{
		font-size:  16px;
		color:#333;
		line-height: 1.1;
	}
	
	.cky-grid2 .cky-media h5{
		font-size:  13px;
		color:#666;
	}
	
	.cky-animate-scale {
		transition: transform 0.2s;
		-webkit-transition: -webkit-transform 0.2s;
	}
	
	.cky-animate-scale:active {
		transform: scale(1.2);
		-webkit-transform: scale(1.2);
	}
</style>
<div class="mui-content mui-content-bottom mui-content-padded">
	<!-- Slider main container -->
	<div class="swiper-container">
	    <!-- Additional required wrapper -->
	    <div class="swiper-wrapper">
	        <!-- Slides -->
	        <div class="swiper-slide"><div class="swiper-img" style="background-image: url(/Apps/M/View/default/images/0JJ2G77427KR.jpg);"></div></div>
	        <div class="swiper-slide"><div class="swiper-img" style="background-image: url(/Apps/M/View/default/images/74F58PICre2.jpg);"></div></div>
	        <div class="swiper-slide"><div class="swiper-img" style="background-image: url(/Apps/M/View/default/images/d33fas2dfsafasdfa3.jpg);"></div></div>
	        <div class="swiper-slide"><div class="swiper-img" style="background-image: url(/Apps/M/View/default/images/lisD2ULUjfWuM.jpg);"></div></div>
	    </div>
	    <!-- If we need pagination -->
	    <div class="swiper-pagination"></div>
	</div>

	<div class="cky-grid cky-grid4">
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/seller.png"/>商家</a>
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/restaurant.png"/>推荐餐厅</a>
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/restaurant.png"/>活动</a>
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/takeaway.png"/>外卖</a>
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/monkey.png"/>小猴快跑</a>
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/life.png"/>生活服务</a>
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/mall.png"/>商城</a>
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/other.png"/>其他</a>
	</div>
	
	<div class="cky-grid cky-grid2 cky-special">
		<a class="cky-grid-cell cky-hotpot-ticket">
			<div class="cky-media">
				<p>9.9 火锅券</p>
				<h5>新用户专享</h5>
			</div>
		</a>
		<a class="cky-grid-cell cky-points-edemption">
			<div class="cky-media">
				<p>积分抽奖</p>
				<h5>好运气 快来玩</h5>
			</div>
		</a>
		<a class="cky-grid-cell cky-scratch-off">
			<div class="cky-media">
				<p>一分钱 刮大奖</p>
				<h5>机会不容错过</h5>
			</div>
		</a>
		<a class="cky-grid-cell cky-delivery-free">
			<div class="cky-media">
				<p>卡券大派送</p>
				<h5>商家大让利</h5>
			</div>
		</a>
	</div>
	<h5>猜你喜欢</h5>
</div>


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

<script>
	$(function() {
		var mySwiper = new Swiper ('.swiper-container', {
	      // Optional parameters
	      direction: 'horizontal',
	      pagination: '.swiper-pagination',
	      autoplay: 3000,
	      loop: true
	   });
	});
</script>

</body>
</html>