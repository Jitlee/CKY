<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title><?php echo ($title); ?></title>
    <link rel="stylesheet" href="/Apps/M/View/default/css/mui.min.css" />
    <link rel="stylesheet" href="/Apps/M/View/default/css/common.css" />
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