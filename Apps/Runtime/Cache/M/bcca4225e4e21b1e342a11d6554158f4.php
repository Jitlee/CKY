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
	
	.cky-media p, .cky-table-view-cell, .cky-table-view-cell span, .cky-table-view-cell, .cky-grid-cell {
		color: #333;
	}
	
	.swiper-img {
		display: block;
		margin-bottom: 30px;
		border: solid 3px darkgreen;
		border-radius: 10px;
		height:50vw;
		background-repeat: no-repeat;
		background-size: cover;
		background-position: center;
	}
	
	.cky-grid:after,.cky-table-view:after {
		content: " ";
		height:1px;
		display: block;
		clear: both;
	}
	
	.cky-grid .cky-grid-cell{
		float: left;
		text-align: center;
		font-size:17px;
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
	
	.cky-center {
		display:-webkit-box; 
		-webkit-box-orient:horizontal; 
		-webkit-box-pack:center; 
		/*-webkit-box-align:center;  */
		display:-moz-box; 
		-moz-box-orient:horizontal; 
		-moz-box-pack:center; 
		/*-moz-box-align:center; */
		display:-o-box; 
		-o-box-orient:horizontal; 
		-o-box-pack:center; 
		/*-o-box-align:center;  */
		display:-ms-box; 
		-ms-box-orient:horizontal; 
		-ms-box-pack:center; 
		/*-ms-box-align:center;  */
		display:box; 
		box-orient:horizontal; 
		box-pack:center; 
		/*box-align:center; */
	}
	.cky-media-content {
		margin-top:3px;
		margin-bottom: 3px;
		font-size: 17px;
		height:45px;
		vertical-align: middle;
		overflow: hidden;
		text-align:left;
		font-size:16px;
	}
	.cky-ellipsis2{
		overflow : hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}

	.cky-left {
		float: left;
	}

	.cky-right {
		float: right;
	}

	.cky-table-view .cky-media {
		padding-left: 94px;
	}
	
	.cky-table-view-cell {
		display: block;
		position: relative;
		border-top:1px solid #C0C0C0;
		padding-top:10px;
		padding-bottom: 10px;
		padding-left:8px;
		padding-right:8px;
		margin-left:-8px;
		margin-right:-8px;
	}

	.cky-money {
		color: #33CCCC !important;
		font-size: 20px;
	}

	.cky-money-old,h5,.cky-media-content {
		color: #999;
	}

	.cky-money-old {
		text-decoration:line-through;
		font-size:14px;
	}
	
	.cky-table-cell-thumb{
		background-repeat: no-repeat;
		background-position: center;
		background-size: cover;
		height:90px;
		width: 90px;
		position: absolute;
		left:8px;
		top:50%;
		transform: translateY(-50%);
		-webkit-transform: translateY(-50%);
		-o-transform: translateY(-50%);
		-moz-transform: translateY(-50%);
		-ms-transform: translateY(-50%);
		overflow: hidden;
	}
	
	.cky-free-reserve:before {
		content: "免预约";
		position: absolute;
		left:0px;
		top:0px;
		display: block;
		font-size: 15px;
		line-height: 1.5;
		color:#fff;
		width:85px;
		text-align: center;
		background-color: #33CCCC;
		transform: matrix(0.707107,-0.707107,0.707107,0.707107, -20, 12);
		-webkit-transform: matrix(0.707107,-0.707107,0.707107,0.707107, -20, 12);
	}
</style>
<div class="mui-content mui-content-bottom mui-content-padded">
	<!-- Slider main container -->
	<div class="swiper-container">
	    <!-- Additional required wrapper -->
	    <div class="swiper-wrapper">
	        <!-- Slides -->
	        <?php if(is_array($ads)): foreach($ads as $key=>$item): ?><div class="swiper-slide"><a href="<?php echo ($item["adURL"]); ?>" class="swiper-img" style="background-image: url('<?php echo ($item["adFile"]); ?>');"></a></div><?php endforeach; endif; ?>
	    </div>
	    <!-- If we need pagination -->
	    <div class="swiper-pagination"></div>
	</div>

	<div class="cky-grid cky-grid4">
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/seller.png"/>商家</a>
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/restaurant.png"/>推荐餐厅</a>
		<a class="cky-grid-cell cky-animate-scale"><img src="/Apps/M/View/default/images/activities.png"/>活动</a>
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
	<br />
	<span>猜你喜欢</span>
	<div class="cky-table-view">
		<a class="cky-table-view-cell">
				<div class="cky-table-cell-thumb cky-free-reserve" style="background-image: url(/Apps/M/View/default/images/dabbf221.jpg);">
					
				</div>
				<div class="cky-media">
					<span>华莱士</span>
					<h5 class="cky-right">推荐</h5>
					<div class="cky-center cky-media-content cky-ellipsis2">
						测试费阿凡达测试费阿凡达多上测试两行会省略的打发打发的事发生发送
					</div>
					<span class="cky-money">￥19</span><span class="cky-money-old">￥90</span>
					<h5 class="cky-right">快去吧</h5>
			    </div>
		</a>
		<a class="cky-table-view-cell">
				<div class="cky-table-cell-thumb cky-free-reserve" style="background-image: url(/Apps/M/View/default/images/fasdfa.jpg);">
					
				</div>
				<div class="cky-media">
					<span>华莱士</span>
					<h5 class="cky-right">推荐</h5>
					<div class="cky-center cky-media-content cky-ellipsis2">
						测试费阿凡达测试费阿凡
					</div>
					<span class="cky-money">￥19</span><span class="cky-money-old">￥90</span>
					<h5 class="cky-right">快去吧</h5>
			    </div>
		</a>
		<a class="cky-table-view-cell">
				<div class="cky-table-cell-thumb" style="background-image: url(/Apps/M/View/default/images/fafasdfsd.jpg);">
					
				</div>
				<div class="cky-media">
					<span>华莱士</span>
					<h5 class="cky-right">推荐</h5>
					<div class="cky-center cky-media-content cky-ellipsis2">
						测试费阿凡达测试
					</div>
					<span class="cky-money">￥19</span><span class="cky-money-old">￥90</span>
					<h5 class="cky-right">快去吧</h5>
			    </div>
		</a>
	</div>
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