<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE >
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>首页 - <?php echo ($CONF['mallTitle']); ?></title>
<meta name="keywords" content="<?php echo ($CONF['mallKeywords']); ?>" />
<meta name="description" content="<?php echo ($CONF['mallDesc']); ?>" />
<link rel="stylesheet" href="/Apps/Home/View/default/css/common.css" />
<link rel="stylesheet" href="/Apps/Home/View/default/css/index.css" />
<link rel="stylesheet" href="/Apps/Home/View/default/css/base.css" />
<link rel="stylesheet" href="/Apps/Home/View/default/css/head.css" />
</head>
<body>
	<script src="/Public/js/jquery.min.js"></script>
<script src="/Public/plugins/lazyload/jquery.lazyload.min.js?v=1.9.1"></script>
<script type="text/javascript">
var RTC = ThinkPHP = window.Think = {
        "ROOT"   : "",
        "APP"    : "/index.php",
        "PUBLIC" : "/Public",
        "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>",
        "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
        "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
        "DOMAIN" : "<?php echo RTCDomain();?>",
        "CITYID" : "<?php echo ($currArea['areaId']); ?>",
        "DEFAULT_IMG": "<?php echo RTCDomain();?>/<?php echo ($CONF['goodsImg']); ?>",
        "MALL_TITLE" : "<?php echo ($CONF['mallName']); ?>",
        "SMS_VERFY"  : "<?php echo ($CONF['smsVerfy']); ?>"
}
    var domainURL = "<?php echo RTCDomain();?>";
    var publicurl = "/Public";
    var currCityId = "<?php echo ($currArea['areaId']); ?>";
    var currCityName = "<?php echo ($currArea['areaName']); ?>";
    var currDefaultImg = "<?php echo RTCDomain();?>/<?php echo ($CONF['goodsImg']); ?>";
    var wstMallName = "<?php echo ($CONF['mallName']); ?>";
    $(function() {
    	$('.lazyImg').lazyload({ effect: "fadeIn",failurelimit : 10,threshold: 200,placeholder:RTC.DEFAULT_IMG});
    });
</script>
<script src="/Public/js/think.js"></script>
<div id="rtc-shortcut">
	<div class="w">
		<ul class="fl lh">
			<li class="fore1 ld"><b></b><a href="javascript:addToFavorite()"
				rel="nofollow">收藏<?php echo ($CONF['mallName']); ?></a></li><s></s>
			<!--<li class="fore3 ld menu" id="app-jd" data-widget="dropdown">
				<span class="outline"></span> <span class="blank"></span> 
				<a href="#" target="_blank"><img src="/Apps/Home/View/default/images/icon_top_02.png"/>&nbsp;<?php echo ($CONF['mallName']); ?> 手机版</a>
			</li>-->
			<li class="fore4" id="biz-service" data-widget="dropdown" style='padding:0;'>&nbsp;<s></s>&nbsp;&nbsp;&nbsp;
				所在城市
				【<span class='rtc-city'>&nbsp;<?php echo ($currArea["areaName"]); ?>&nbsp;</span>】
	
				
			</li>
		</ul>
	
		<ul class="fr lh" style='float:right;'>
			<li class="fore1" id="loginbar"><a href="<?php echo U('Home/Orders/queryByPage');?>"><span style='color:blue'><?php echo ($RTC_USER['loginName']); ?></span></a> 欢迎您来到 <a href='<?php echo RTCDomain();?>'><?php echo ($CONF['mallName']); ?></a>！<s></s>&nbsp;
			<span>
				<?php if(!$RTC_USER['userId']): ?><a href="<?php echo U('Home/Users/login');?>">[登录]</a>
				<a href="<?php echo U('Home/Users/regist');?>"	class="link-regist">[免费注册]</a><?php endif; ?>
				<?php if($RTC_USER['userId'] > 0): ?><a href="javascript:logout();">[退出]</a><?php endif; ?>
			</span>
			</li>
		</ul>
		<span class="clr"></span>
	</div>
</div>

 
 
		
	<!----加载广告start----->
	<div class="rtc-ad">
		<div class="rtc-slide" id="rtc-slide">
			<ul class="rtc-slide-items">
				<?php if(is_array($indexAds)): $k = 0; $__LIST__ = $indexAds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li style="z-index: 1;"><a target="_blank" onclick="addAccess(<?php echo ($vo['adId']); ?>)" href="<?php echo ($vo['adURL']); ?>">
						<img src="/<?php echo ($vo['adFile']); ?>" height="360" width="100%" title="<?php echo ($vo['adName']); ?>" />
				</a></li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</div>
	</div>
	<div class='rtc-clear'></div>
	<!----加载商品楼层start----->
	<div class="rtc-container">
		<!-------------F1层---------------->
		<?php if(is_array($catList)): $k1 = 0; $__LIST__ = $catList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($k1 % 2 );++$k1;?><div class="rtc-floor">
			<div class="rtc-fl-left rtc-fl<?php echo ($k1); ?>" style="position: relative;">
				<div
					style="position: absolute; top: 40px; width: 210px; height: 275px;">
					<?php if(is_array($catAds[$vo1['catId']])): $kv = 0; $__LIST__ = $catAds[$vo1['catId']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($kv % 2 );++$kv;?><a onclick="addAccess(<?php echo ($vo['adId']); ?>)" href="<?php echo ($vo['adURL']); ?>" target="_blank"> 
						<img class='lazyImg' data-original="/<?php echo ($vo['adFile']); ?>" height="275" width="210" title="<?php echo ($vo['adName']); ?>" />
					</a><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<div
					style="position: absolute; top: 0px; left: 0px; width: 99%; height: 40px; padding-left: 10px; color: #ffffff; line-height: 38px; font-weight: bolder; background: url(/Apps/Home/View/default/images/<?php echo ($k1); ?>fhd.png) no-repeat 100% 100%;">
					<a
						href="<?php echo U('Home/Goods/getGoodsList/',array('c1Id'=>$vo1['catId']));?>">
						<span style="font-size: 18px;">F<?php echo ($k1); ?></span><?php echo ($vo1["catName"]); ?>
					</a>
				</div>
				<div class='rtc-cat' style="height: 220px; overflow: hidden;">
					<?php if(is_array($vo1['catChildren'])): $k2 = 0; $__LIST__ = $vo1['catChildren'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($k2 % 2 );++$k2; if(is_array($vo2['catChildren'])): $k3 = 0; $__LIST__ = $vo2['catChildren'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($k3 % 2 );++$k3;?><a href="<?php echo U('Home/Goods/getGoodsList/',array('c1Id'=>$vo1['catId'],'c2Id'=>$vo2['catId'],'c3Id'=>$vo3['catId']));?>">
							<li class="rtc-cat-left"><?php echo ($vo3["catName"]); ?></li>
							</a><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>

				</div>
			</div>
			<div class="rtc-fl-right">
				<div class="rtc-fl-nvg<?php echo ($k1); ?>">
					<ul>
						<li id="fl_<?php echo ($k1); ?>_0" style="line-height: 32px; width: 100px;"
							onmouseover="gpanelOver(this);">精品推荐</li>
						<?php if(is_array($vo1['catChildren'])): $k2 = 0; $__LIST__ = $vo1['catChildren'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($k2 % 2 );++$k2;?><li id="fl_<?php echo ($k1); ?>_<?php echo ($k2); ?>" style="line-height: 32px; width: 100px;"
							onmouseover="gpanelOver(this);"><?php echo ($vo2["catName"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
				<div>

					<div id="fl_<?php echo ($k1); ?>_0_pl" class="rtc-goods-container">
						<?php if(is_array($vo1['jpgoods'])): $k2 = 0; $__LIST__ = $vo1['jpgoods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($k2 % 2 );++$k2;?><li class="rtc-goodsbox">
							<div class="rtc-goods-thumb">
								<a
									href="<?php echo U('Home/Goods/getGoodsDetails/',array('goodsId'=>$vo2['goodsId']));?>">
									<img class='lazyImg' data-original="/<?php echo ($vo2['goodsThums']); ?>" width="180" />
								</a>
							</div>
							<div class="rtc-goods-thumb-des">
								<div class="goodsname">
								<a class="rtc-goods-name"
									href="<?php echo U('Home/Goods/getGoodsDetails/',array('goodsId'=>$vo2['goodsId']));?>"><?php echo ($vo2['goodsName']); ?></a>
								</div>
								<div>
									<em class="rtc-left rtc-goods-price" id='shopGoodsPrice_<?php echo ($vo2["goodsId"]); ?>' dataId='<?php echo ($vo2["goodsAttrId"]); ?>'>￥<?php echo (number_format($vo2['shopPrice'],2)); ?></em>
									<a href="javascript:addCart(<?php echo ($vo2['goodsId']); ?>,0,'<?php echo ($vo2['goodsThums']); ?>')" class="rtc-right btnCart"> 
									<img src="/Apps/Home/View/default/images/btn_addcart.png" width="85" />
									</a>
									<div class='rtc-clear'></div>
								</div>
							</div>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
						<div class='rtc-clear'></div>
					</div>


					<?php if(is_array($vo1['catChildren'])): $k2 = 0; $__LIST__ = $vo1['catChildren'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($k2 % 2 );++$k2;?><div id="fl_<?php echo ($k1); ?>_<?php echo ($k2); ?>_pl" class="rtc-goods-container"
						style="display: none;">
						<?php if(is_array($vo2['goods'])): $k3 = 0; $__LIST__ = $vo2['goods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($k3 % 2 );++$k3;?><li class="rtc-goodsbox">
							<div class="rtc-goods-thumb">
								<a
									href="<?php echo U('Home/Goods/getGoodsDetails/',array('goodsId'=>$vo3[goodsId]));?>">
									<img class='lazyImg' data-original="/<?php echo ($vo3['goodsThums']); ?>" width="180" />
								</a>
							</div>
							<div class="rtc-goods-thumb-des">
								<div class="goodsname">
									<a class="rtc-goods-name"
										href="<?php echo U('Home/Goods/getGoodsDetails/',array('goodsId'=>$vo3[goodsId]));?>"><?php echo ($vo3['goodsName']); ?></a>
								</div>
								<div>
									<em class="rtc-left rtc-goods-price" id='shopGoodsPrice_<?php echo ($vo3["goodsId"]); ?>' dataId='<?php echo ($vo3["goodsAttrId"]); ?>'>￥<?php echo (number_format($vo3['shopPrice'],2)); ?></em>
									<a href="javascript:addCart(<?php echo ($vo3['goodsId']); ?>,0,'<?php echo ($vo3['goodsThums']); ?>')" class="rtc-right btnCart" > <img
										src="/Apps/Home/View/default/images/btn_addcart.png"
										width="85" />
									</a>
									<div class='rtc-clear'></div>
								</div>
							</div>
							
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
						<div class='rtc-clear'></div>
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
					<div style="float: right; width: 180px; height: 180px;">
						<?php if(is_array($vo1['recommendShops'])): $k2 = 0; $__LIST__ = $vo1['recommendShops'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($k2 % 2 );++$k2;?><div style="width: 180px; margin-top: 10px;">
							<div style="width: 180px;">
								<a
									href="<?php echo U('Home/Shops/toShopHome/',array('shopId'=>$vo2['shopId']));?>">
									<img class='lazyImg' data-original="/<?php echo ($vo2['shopImg']); ?>" height="120" width="180" />
								</a>
							</div>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
				</div>
			</div>
			<div style="clear: both; font-size: 0px;"></div>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
	<!--<div class="rtc-footer-fl-box">
	<div class="rtc-footer" >
		<div class="rtc-footer-cld-box">
			<div class="rtc-footer-fl">友情链接：</div>
			<div style="padding-left:30px;">
				<?php if(is_array($friendLikds)): $k = 0; $__LIST__ = $friendLikds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div style="float:left;"><a href="<?php echo ($vo["friendlinkUrl"]); ?>" target="_blank"><?php echo ($vo["friendlinkName"]); ?></a>&nbsp;&nbsp;</div><?php endforeach; endif; else: echo "" ;endif; ?>
				<div class="rtc-clear"></div>
			</div>
		</div>
	</div>
</div>-->

<div class="rtc-footer-hp-box">
	<div class="rtc-footer">
		<div class="rtc-footer-hp-ck1">
			<?php if(is_array($helps)): $k1 = 0; $__LIST__ = $helps;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($k1 % 2 );++$k1;?><div class="rtc-footer-wz-ca">
				<div class="rtc-footer-wz-pt">
				    <img src="/Apps/Home/View/default/images/a<?php echo ($k1); ?>.jpg" height="18" width="18"/>
					<span class="rtc-footer-wz-pn"><?php echo ($vo1["catName"]); ?></span>
					<div style='margin-left:30px;'>
						<?php if(is_array($vo1['articlecats'])): $k2 = 0; $__LIST__ = $vo1['articlecats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($k2 % 2 );++$k2;?><a href="<?php echo U('Home/Articles/index/',array('articleId'=>$vo2['articleId']));?>"><?php echo ($vo2['articleTitle']); ?></a><br/><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
				</div>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
			
			<div class="rtc-footer-wz-clt">
				<div style="padding-top:5px;line-height:25px;">
				    <img src="/Apps/Home/View/default/images/a6.jpg" height="18" width="18"/>
					<span class="rtc-footer-wz-kf">联系客服</span>
					<div style='margin-left:30px;'>
						<a href="#">联系电话</a><br/>
						<?php if($CONF['phoneNo'] != ''): ?><span class="rtc-footer-pno"><?php echo ($CONF['phoneNo']); ?></span><br/><?php endif; ?>
						<?php if($CONF['qqNo'] != ''): ?><a href="tencent://message/?uin=<?php echo ($CONF['qqNo']); ?>&Site=QQ交谈&Menu=yes">
						<img border="0" src="http://wpa.qq.com/pa?p=1:<?php echo ($CONF['qqNo']); ?>:7" alt="QQ交谈" width="71" height="24" />
						</a><br/><?php endif; ?>
						
					</div>
				</div>
			</div>
			<div class="rtc-clear"></div>
		</div>
	    
		<div class="rtc-footer-hp-ck2">
			<img src="/Apps/Home/View/default/images/alipay.jpg" height="40" width="120"/>支付宝签约商家&nbsp;|&nbsp;
			<span class="rtc-footer-frd">正品保障</span><span >100%原产地</span>&nbsp;|&nbsp;
			<span class="rtc-footer-frd">7天退货保障</span>购物无忧&nbsp;|&nbsp;
			<span class="rtc-footer-frd">免费配送</span>满98包邮&nbsp;|&nbsp;
			<span class="rtc-footer-frd">货到付款</span>400城市送货上门
		</div>
	    <div class="rtc-footer-hp-ck3">
	       
	        
	        <div class="copyright">
	         <?php if($CONF['mallFooter']!=''){ echo htmlspecialchars_decode($CONF['mallFooter']); } ?>
	      	<?php if($CONF['visitStatistics']!=''){ echo htmlspecialchars_decode($CONF['visitStatistics'])."<br/>"; } ?>	        
	        </div>
	    	
	        	 
	     
	    </div>
	</div>
</div>
<!--
-->

	<link rel="stylesheet" type="text/css" href="/Apps/Home/View/default/css/cart.css" />
<script src="/Apps/Home/View/default/js/userlogin.js"></script>
<script type="text/javascript" src="/Apps/Home/View/default/js/cart/common.js?v=725"></script>
<script type="text/javascript" src="/Apps/Home/View/default/js/cart/quick_links.js"></script>
<!--[if lte IE 8]>
<script src="/Apps/Home/View/default/js/cart/ieBetter.js"></script>
<![endif]-->
<script src="/Apps/Home/View/default/js/cart/parabola.js"></script>
<!--右侧贴边导航quick_links.js控制-->
	<div id="flyItem" class="fly_item" style="display:none;">
		<p class="fly_imgbox">
		<img src="/Apps/Home/View/default/images/item-pic.jpg"
			width="30" height="30">
		</p>
	</div>
	<div class="mui-mbar-tabs">
		<div class="quick_link_mian">
			<div class="quick_links_panel">
				<div id="quick_links" class="quick_links">
					<li><a href="#" class="my_qlinks" style="margin-top: 5px;"><i class="setting"></i></a>
						<div class="ibar_login_box status_login">
							<?php if($RTC_USER['userId'] > 0): ?><div class="avatar_box">
								<p class="avatar_imgbox">
									<img
										src="/Apps/Home/View/default/images/no-img_mid_.jpg" />
								</p>
								<?php if($RTC_USER['userId'] > 0): ?><ul class="user_info">
									<li>用户名：<?php echo ($RTC_USER['loginName']); ?></li>
									<li>级&nbsp;别：普通会员</li>
								</ul><?php endif; ?>
							</div>
							
							<div class="ibar_recharge-btn">
								<input type="button" value="我的订单" onclick="getMyOrders();"/>
							</div>
							<i class="icon_arrow_white"></i>
						</div> <?php else: ?>
							<div style="margin: 0 auto;padding: 15px 0; width: 220px;">
							<div class="ibar_recharge-field">
								<label>帐号</label>
								<div class="ibar_recharge-fl">
									<div class="ibar_recharge-iwrapper">
										<input id="loginName" name="loginName" value="<?php echo ($loginName); ?>" type="text" name="19" placeholder="用户名/手机号/邮箱" />
									</div>
									<i class="ibar_username-contact"></i>
								</div>
							</div>
							<div class="ibar_recharge-field">
								<label>密码</label>
								<div class="ibar_recharge-fl">
									<div class="ibar_recharge-iwrapper">
										<input id="loginPwd" name="loginPwd" type="password" name="19" placeholder="密码" />
									</div>
									<i class="ibar_password-contact"></i>
								</div>
							</div>
							<div class="ibar_recharge-field">
								<label>验证码</label>
								<div class="ibar_recharge-fl" style="width:80px;">
									<div class="ibar_recharge-iwrapper">
										<input id="verify" style="ime-mode:disabled;width:80px;" name="verify" class="text text-1" tabindex="6" autocomplete="off" maxlength="6" type="text" placeholder="验证码"/>
									</div>
								</div>
								<label class="img" onclick="javascript:getVerify()">
				                	<img style='vertical-align:middle;cursor:pointer;height:30px;width:93px;' class='verifyImg' src='/Apps/Home/View/default/images/clickForVerify.png' title='刷新验证码' onclick='javascript:getVerify()'/> 
								</label>
							</div>
							<div class="ibar_recharge-btn">
								<input type="button" value="登录" onclick="checkLoginInfo();"/>
							</div>
							</div><?php endif; ?></li>
					<li id="shopCart"><a href="#" class="message_list"><i class="message"></i>
					<div class="span">购物车</div> <span class="cart_num">0</span></a></li>
					<?php if($CONF['qqNo'] != ''): ?><li>
						<a href="tencent://message/?uin=<?php echo ($CONF['qqNo']); ?>&Site=QQ交谈&Menu=yes" style="padding-top:5px;padding-bottom:5px;margin-bottom: 5px;">
						<img src="/Apps/Home/View/default/images/qq.jpg"  height="38" width="40" />
						</a>
					</li><?php endif; ?>
					
				</div>
				<div class="quick_toggle">
					<li><a href="#none"><i class="mpbtn_qrcode"></i></a>
						<div class="mp_qrcode" style="display: none;">
							<img
								src="/Apps/Home/View/default/images/wstmall_weixin.png"
								width="148"  /><i class="icon_arrow_white"></i>
						</div>
					</li>
					
					<li><a href="#top" class="return_top"><i class="top"></i></a></li>
				</div>
			</div>
			<div id="quick_links_pop" class="quick_links_pop hide"></div>
		</div>
	</div>
	<script type="text/javascript">
	var numberItem = <?php echo RTCCartNum();?>;
	$('.cart_num').html(numberItem);
	$(".quick_links_panel li").mouseenter(function() {
		getVerify();
		$(this).children(".mp_tooltip").animate({
			left : -92,
			queue : true
		});
		$(this).children(".mp_tooltip").css("visibility", "visible");
		$(this).children(".ibar_login_box").css("display", "block");
	});
	$(".quick_links_panel li").mouseleave(function() {
		$(this).children(".mp_tooltip").css("visibility", "hidden");
		$(this).children(".mp_tooltip").animate({
			left : -121,
			queue : true
		});
		$(this).children(".ibar_login_box").css("display", "none");
	});
	$(".quick_toggle li").mouseover(function() {
		$(this).children(".mp_qrcode").show();
	});
	$(".quick_toggle li").mouseleave(function() {
		$(this).children(".mp_qrcode").hide();
	});

	// 元素以及其他一些变量
	var eleFlyElement = document.querySelector("#flyItem"), eleShopCart = document
			.querySelector("#shopCart");
	eleFlyElement.style.visibility = "hidden";
	
	var numberItem = 0;
	// 抛物线运动
	var myParabola = funParabola(eleFlyElement, eleShopCart, {
		speed : 100, //抛物线速度
		curvature : 0.0012, //控制抛物线弧度
		complete : function() {
			eleFlyElement.style.visibility = "hidden";
			jQuery.post(domainURL +"/index.php/Home/Cart/getCartGoodCnt/" ,{"axm":1},function(data) {
				var cart = RTC.toJson(data);
				eleShopCart.querySelector("span").innerHTML = cart.goodscnt;
			});
			
		}
	});
	// 绑定点击事件
	if (eleFlyElement && eleShopCart) {
		[].slice
				.call(document.getElementsByClassName("btnCart"))
				.forEach(
						function(button) {
							button
									.addEventListener(
											"click",
											function(event) {
												// 滚动大小
												var scrollLeft = document.documentElement.scrollLeft
														|| document.body.scrollLeft
														|| 0, scrollTop = document.documentElement.scrollTop
														|| document.body.scrollTop
														|| 0;
												eleFlyElement.style.left = event.clientX
														+ scrollLeft + "px";
												eleFlyElement.style.top = event.clientY
														+ scrollTop + "px";
												eleFlyElement.style.visibility = "visible";
												$(eleFlyElement).show();
												// 需要重定位
												myParabola.position().move();
											});
						});
	}

	function getMyOrders(){
		document.location.href = "<?php echo U('Home/Orders/queryByPage/');?>";
	}
</script>
	<script src="/Public/js/common.js"></script>
    <script src="/Apps/Home/View/default/js/index.js"></script>
    <script src="/Apps/Home/View/default/js/common.js"></script>
    <script src="/Apps/Home/View/default/js/global.js" type="text/javascript"></script>
    <script src="/Apps/Home/View/default/js/head.js" type="text/javascript"></script>
    <script src="/Apps/Home/View/default/js/goods.js" type="text/javascript"></script>
</body>

</html>