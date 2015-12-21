<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
	<head>
  		<meta charset="utf-8">
      	<meta http-equiv="X-UA-Compatible" content="IE=edge">
      	<meta name="viewport" content="width=device-width, initial-scale=1">
      	<title>用户注册 - <?php echo ($CONF['mallTitle']); ?></title>
      	<meta name="keywords" content="<?php echo ($CONF['mallKeywords']); ?>" />
      	<meta name="description" content="<?php echo ($CONF['mallDesc']); ?>,用户注册" />
      	<link rel="stylesheet" href="/Apps/Home/View/default/css/common.css">
      	<link rel="stylesheet" href="/Apps/Home/View/default/css/regist.css">
     	<link rel="stylesheet" href="/Apps/Home/View/default/css/passport.css">
     	<link rel="stylesheet" href="/Apps/Home/View/default/css/base.css" />
		<link rel="stylesheet" href="/Apps/Home/View/default/css/head.css" />
     	<link rel="stylesheet" href="/Apps/Home/View/default/css/footer.css">
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

<div style="height:132px;">
<div id="mainsearchbox" style="text-align:center;">
	<div id="rtc-search-pbox">
		<div style="float:left;width:240px;" class="rtc-header-car">
		  <a href='<?php echo RTCDomain();?>'>
			<img id="rtc-logo" width='240' height='132' src="<?php echo RTCDomain();?>/<?php echo ($CONF['mallLogo']); ?>"/>
		  </a>	
		</div>
		<div id="rtc-search-container">
			<div id="rtc-search-type-box">
				<input id="rtc-search-type" type="hidden" value="<?php echo ($searchType); ?>"/>
				<div id="rtc-panel-goods" class="<?php if($searchType == 1): ?>rtc-panel-curr<?php else: ?>rtc-panel-notcurr<?php endif; ?>">商品</div>
				<div id="rtc-panel-shop" class="<?php if($searchType == 2): ?>rtc-panel-curr<?php else: ?>rtc-panel-notcurr<?php endif; ?>">店铺</div>
				<div class="rtc-clear"></div>
			</div>
			<div id="rtc-searchbox">
				<input id="keyword" class="rtc-search-keyword" data="RTC_key_search" onkeyup="getSearchInfo(this,event);" placeholder="<?php if($searchType == 2): ?>搜索 店铺<?php else: ?>搜索 商品<?php endif; ?>" autocomplete="off"  value="<?php echo ($keyWords); ?>">
				<div id="btnsch" class="rtc-search-button">搜&nbsp;索</div>
				<div id="RTC_key_search_list" style="position:absolute;top:38px;left:-1px;border:1px solid #b8b8b8;min-width:567px;display:none;background-color:#ffffff;z-index:1000;">dfdf</div>
			</div>
			<div id="rtc-hotsearch-keys">
				<?php if(is_array($CONF['hotSearchs'])): $k = 0; $__LIST__ = $CONF['hotSearchs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><a href="<?php echo U('Home/goods/getGoodsList',array('keyWords'=>$vo));?>"><?php echo ($vo); ?></a><?php if($k < count($CONF['hotSearchs'])): ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php endif; endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</div>
		<div id="rtc-search-des-container">
			<div class="des-box">
				<div class='rtc-reach'>
					<img src="/Apps/Home/View/default/images/sadn.jpg"  height="38" width="40"/>
					<div style="float:left;position:absolute;top:0px;left:38px;"><span style="font-weight:bolder;">
						闪电配送</span><br/><span style="color:#e23c3d;">1小时送达</span></div>
				</div>
				<div class='rtc-since'>
					<img src="/Apps/Home/View/default/images/sqzt.jpg"  height="38" width="40"/>
					<div style="float:left;position:absolute;top:0px;left:38px;"><span style="font-weight:bolder;">社区自提</span><br/>
						<span style="color:#e23c3d;">超多家自提点</span></div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="headNav">
		  <div class="navCon w1020">
		 
		    <div class="navCon-menu fl">
		      <ul class="fl">
		        <?php $_result=RTCNavigation(0);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li ><a href="<?php echo ($vo['navUrl']); ?>" <?php if($vo['isOpen'] == 1): ?>target="_blank"<?php endif; ?> <?php if($vo['active'] == 1): ?>class="curMenu"<?php endif; ?>>&nbsp;&nbsp;<?php echo ($vo['navTitle']); ?>&nbsp;&nbsp;</a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		      </ul>
		    </div>
		    <li id="rtc-nvg-cart">
		    	<div class='rtc-nvg-cart-cost'>
		       		&nbsp;<span class="rtc-nvg-cart-cnt">0</span>件&nbsp;|&nbsp;<span class="rtc-nvg-cart-price">0.00</span>元
		       	</div>
			</li>
			<div class="rtc-cart-box"><div class="rtc-nvg-cart-goods">购物车中暂无商品</div></div>
		  </div>
		</div>
		<script>
		$(function(){
			checkCart();
		});
		</script>
	<div class="rtc-regist" id="regist">	    
	    <div class="mc">
	        <div style="font-size:30px;color:black;padding-left:355px;border-bottom:dotted 1px #ddd; ">用户注册</div>
	    	<?php if(!$RTC_USER['userId']): ?><form id="register" method="post" autocomplete="off">
	            <input name="regType" id="regType" value="person" type="hidden"/>
	            <input name="nameType" id="nameType" value="2"  type="hidden" />
	            <div class="form">
	                <div class="item" id="select-regName">
	                    <span class="label"><b class="ftx04">*</b>账户名：</span>	
	                    <div class="fl item-ifo">
	                        <div class="o-intelligent-regName" style="position:relative;">	                       
	                            <input id="loginName" name="loginName" class="text" tabindex="1" maxlength="30" autocomplete="off" onpaste="return false;" style="ime-mode:disabled;" onblur="onblurName(this);" onkeypress="return RTC.isUserName(event)" onfocus="onfocusName(this);" value="邮箱/用户名/手机号"  type="text"/>
	                           <div id="loginNameTip" style="display: inline-block; "></div>
								<div id="namelist" style="display:none;position:absolute;width:268px;top:36px;left:0px;border:1px solid #CCCCCC;"></div>
	                        </div>	                        
	                    </div>
	                </div>	                
	                <div id="o-password">
	                    <div class="item">
	                        <span class="label"><b class="ftx04">*</b>请设置密码：</span>	
	                        <div class="fl item-ifo">
	                            <input id="loginPwd" name="loginPwd" class="text" tabindex="2" style="ime-mode:disabled;" autocomplete="off" type="password"/>
	                            <label id="pwd_succeed" class="pwdblank"></label>	                           
	                        </div>
	                    </div>	
	                    <div class="item">
	                        <span class="label"><b class="ftx04">*</b>请确认密码：</span>	
	                        <div class="fl item-ifo">
	                            <input id="reUserPwd" name="reUserPwd" class="text" tabindex="3" autocomplete="off" type="password"/>	                            
	                            <label id="pwdRepeat_succeed" class="pwdblank"></label>	                           
	                        </div>
	                    </div>
						<div class="item" style="display:none;">
	                        <span class="label">电子邮箱：</span>	
	                        <div class="fl item-ifo">
	                            <input id="userEmail" name="userEmail" class="text" tabindex="3" autocomplete="off" style="ime-mode:disabled;" type="text"/>	                           
	                            <label id="pwdRepeat_succeed" ></label>	                           
	                        </div>
	                    </div>     
	                    <div class="item" style="display:none;">
	                        <span class="label"><b class="ftx04">*</b>手机号码：</span>	
	                        <div class="fl item-ifo">
	                            <input id="userPhone" name="userPhone" class="text" tabindex="3" autocomplete="off" type="text" maxlength="11"/>	                           
	                            <label id="pwdRepeat_succeed" ></label>	                           
	                        </div>
	                    </div> 
	                               
	                    <div class="item" id="mobileCodeDiv" style="display:none;">
	                        <span class="label"><b class="ftx04">*</b>短信验证码：</span>	
	                        <div class="fl item-ifo">
	                            <input maxlength="6" autocomplete="off" tabindex="6" class="text text-1" name="mobileCode" style="ime-mode:disabled" id="mobileCode" type="text"/>
	                            <label class="blank invisible"></label>
	                            <a class="btn" href="javascript:void(0);" onclick="getVerifyCode();" id="sendMobileCode">
	                                <span id="timeTips">获取短信验证码</span>
	                           	</a>
	                        </div>
	                        <span class="clr"></span>
	                    </div>
	                     
	                  	<div class="item" id="authcodeDiv">
	                            <span class="label"><b class="ftx04">*</b>验证码：</span>	
	                            <div class="fl item-ifo">
	                                <input id="authcode" style="ime-mode:disabled" name="authcode" class="text text-1" tabindex="6" autocomplete="off" maxlength="6" type="text"/>
	                                <label class="img">
	                                    <img style='vertical-align:middle;cursor:pointer;height:39px;' class='verifyImg' src='/Apps/Home/View/default/images/clickForVerify.png' title='刷新验证码' onclick='javascript:getVerify()'/> 
									 </label>      	
	                                <label class="ftx23">&nbsp;看不清？<a href="javascript:getVerify()" class="flk13">换一张</a></label>
	                            </div>
	                 	</div>	
	                 	 
	                </div>
	                <input name="qVoCgWxtvu" value="EqaRK" type="hidden"/>	
	                <div class="item item-new">
	                    <span class="label">&nbsp;</span>	
	                    <div class="fl item-ifo">
	                        <label>
	                        <input class="checkbox" id="protocol" name="protocol" type="checkbox"/>
	                                                                        我已阅读并同意</label><a href="javascript:;" class="blue" id="protocolInfo" onclick="showXiey();">《用户注册协议》</a>                       
	                        <label id="protocol_error" class="error hide">请接受服务条款</label>
	                    </div>
	                </div>
	                <div class="item">
	                    <span class="label">&nbsp;</span>
	                    <input class="btn-img btn-regist" id="registsubmit" value="立即注册" tabindex="8"  type="submit"/>
	                </div>
	            </div>
	            <div class="phone">
	                
	            </div>
	      		<span class="clr"></span>
	        </form>
	        <?php else: ?>
	        <div style="line-height:150px;text-align: center;font-size:18px;">请先退出当前帐号再进行注册 !</div><?php endif; ?>
	    </div>
	</div>
	<!--footer start--> 
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

	<script src="/Public/plugins/layer/layer.min.js"></script>
   	<script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
    <script src="/Public/js/common.js"></script>
    <script src="/Apps/Home/View/default/js/common.js"></script>
    <script src="/Apps/Home/View/default/js/regist.js"></script>
    <style>
   	.layui-layer-btn a{background:#e23c3d;}
   	</style>
</body>
</html>