<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
	<head>
  		<meta charset="utf-8">
      	<meta http-equiv="X-UA-Compatible" content="IE=edge">
      	<meta name="viewport" content="width=device-width, initial-scale=1">
      	<title>卖家登录 - <?php echo ($CONF['mallTitle']); ?></title>
      	<meta name="keywords" content="<?php echo ($CONF['mallKeywords']); ?>" />
      	<meta name="description" content="<?php echo ($CONF['mallDesc']); ?>,卖家登录" />
     	<link rel="stylesheet" href="/Apps/Home/View/default/css/common.css">
     	<link rel="stylesheet" href="/Apps/Home/View/default/css/head.css" />
     	<link rel="stylesheet" href="/Apps/Home/View/default/css/login.css">
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
			<li class="fore1" id="loginbar"><a href="<?php echo U('Home/Orders/queryByPage');?>">
				<span style='color:blue'><?php echo ($RTC_USER['loginName']); ?></span></a> 欢迎您来到 <a href='<?php echo RTCDomain();?>'><?php echo ($CONF['mallName']); ?></a>！<s></s>&nbsp;
			<span>
				<?php if(!$RTC_USER['userId']): ?><a href="<?php echo U('Home/Shops/login');?>">[登录]</a><?php endif; ?>
				<?php if($RTC_USER['userId'] > 0): ?><a href="javascript:logout();">[退出]</a><?php endif; ?>
			</span>
			</li>
		</ul>
		<span class="clr"></span>
	</div>
</div>

   		<div class='rtc-login'>
		    <div class=" w1" id="entry">       
		        <div class="mc " id="bgDiv" style="position:relative">
		        	<div style="font-size:65px;color:red;position:absolute;top:160px;left:60px;">商家登录</div>
		            <div class="form">
		                <div class="item fore1" style="position:relative;">
		                    <span>用户名/手机号/邮箱</span><span id="errmsg" style="color:red;position:absolute;top:0px;left:100px;"></span>
		                    <div class="item-ifo">
		                        <input id="loginName" name="loginName" class="text"  tabindex="1" value="<?php echo ($loginName); ?>" autocomplete="off" type="text"/>
		                        <div class="i-name ico"></div>                       
		                    </div>
		                </div>               
		                <div class="item fore2">
		                    <span>密码</span>
		                    <div class="item-ifo">                       
		                        <input id="loginPwd" name="loginPwd" value="<?php echo ($loginPwd); ?>" class="text" tabindex="2" autocomplete="off" type="password"/>                      
		                        <div class="i-pass ico"></div>
		                                            
		                    </div>
		                </div>
		               
		                <div class="item fore3 " id="o-authcode">
		                    <span>验证码</span>
		                    <div class="item-ifo">
		                        <input id="verify" style="ime-mode:disabled" name="verify" class="text text-1" tabindex="6" autocomplete="off" maxlength="6" type="text"/>
			                    <label class="img">
			                		<img style='vertical-align:middle;cursor:pointer;height:39px;' class='verifyImg' src='/Apps/Home/View/default/images/clickForVerify.png' title='刷新验证码' onclick='javascript:getVerify()'/> 
								</label>      	
			              		<label class="ftx23">&nbsp;看不清？<a href="javascript:getVerify()" class="flk13">换一张</a></label>
		                    </div>
		                </div>
		               
		                <div class="item fore4" id="autoentry">
		                    <div class="item-ifo">
		                        <input class="checkbox" id="rememberPwd" name="rememberPwd" checked="checked" type="checkbox"/>
		                        <label class="mar">记住密码</label>                                          
		                        <label class="mar"><a href="<?php echo U('Users/forgetPass');?>">忘记密码?</a></label>
		                        
		                        <div class="clr"></div>
		                    </div>
		            	</div>
		                <div class="item login-btn2013">
		                    <input class="btn-img btn-entry" id="loginsubmit" value="登录" tabindex="8" onclick="javascript:login();"/>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
		 

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

   	</body>
   	<script src="/Public/js/common.js"></script>
    <script src="/Apps/Home/View/default/js/common.js"></script>
   	<script src="/Apps/Home/View/default/js/shoplogin.js"></script>
</html>