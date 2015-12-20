<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
	<head>
  		<meta charset="utf-8">
      	<meta http-equiv="X-UA-Compatible" content="IE=edge">
      	<meta name="viewport" content="width=device-width, initial-scale=1">
      	<title>忘记密码 - <?php echo ($CONF['mallTitle']); ?></title>
      	<meta name="keywords" content="<?php echo ($CONF['mallKeywords']); ?>" />
      	<meta name="description" content="<?php echo ($CONF['mallDesc']); ?>,忘记密码" />
      	<link rel="stylesheet" href="/Apps/Home/View/default/css/common.css">
     	<link rel="stylesheet" href="/Apps/Home/View/default/css/footer.css">
   	</head>
   	<body>
	<div id="shortcut-2013">
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

	</div>
	<div class="main">
	    <div class="main-top">
	        <div class="forget-pwd">
	            <h5>找回密码</h5>
	            <div id="stepflex" class="stepflex">
	                <dl class="first doing">
	                    <dt class="s-num">1</dt>
	                    <dd class="s-text">填写账户名<s></s><b></b></dd>
	                    <dd></dd>
	                </dl>
	                <dl class="normal">
	                    <dt class="s-num">2</dt>
	                    <dd class="s-text">验证身份<s></s><b></b></dd>
	                </dl>
	                <dl class="normal">
	                    <dt class="s-num">3</dt>
	                    <dd class="s-text">设置新密码<s></s><b></b></dd>
	                </dl>
	                <dl class="last">
	                    <dt class="s-num">&nbsp;</dt>
	                    <dd class="s-text">完成<s></s><b></b></dd>
	                </dl>
	            </div>
	            <form method="post" id="forgetPwdForm" action="<?php echo U('Users/findPass'); ?>" autocomplete="off">
	            <input type="hidden" name="step" value="1">
	                <table style="margin:0 auto;">
	                    <tbody>
	                        <tr>
	                            <th>用户名：</th>
	                            <td><input class="text" type="text" name="loginName" id="loginName" value=''></td>
	                            <td><div id="loginNameTip" style="width:280px;text-align:left;"></div></td>
	                        </tr>
	                        <tr>
	                            <th>验证码：</th>
	                            <td><input class="text" type="text" name="code" id="verify" maxlength="12" value=''></td>
	                            <td><div id="verifyTip" style="width:280px;text-align:left;"></div></td>
	                        </tr>
	                        <tr>
	                            <th></th>
	                            <td>
	                            <label class="img">
	                                    <img style='vertical-align:middle;cursor:pointer;height:39px;' class='verifyImg' src='/Apps/Home/View/default/images/clickForVerify.png' title='刷新验证码' onclick='javascript:getVerify()'/> 
									 </label>      	
	                                 <label class="ftx23">&nbsp;看不清？<a href="javascript:getVerify()" class="flk13">换一张</a></label>
	                            </td>
	                            <td></td>
	                        </tr>
	                        <tr>
	                            <th>&nbsp;</th>
	                            <td>
	                                <input class='btn-red' type="submit" id="goform" value="下一步">
	                            </td>
	                        </tr>
	                    </tbody></table>
	                </form>
	            </div>
	            <div class="clearfix"></div>
	        </div>
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

	<!--footer end-->
	</body>
	<script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
    <script src="/Public/js/common.js"></script>
    <script src="/Apps/Home/View/default/js/common.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		getVerify();
		$.formValidator.initConfig({formID:"forgetPwdForm",theme:"Default",submitOnce:true,
	        onError:function(msg,obj,errorlist){
	        }
	    });
		
	   $("#loginName").formValidator({onShowText:"请输入用户名",onShow:"",onFocus:"账户名不能为空",onCorrect:"&nbsp;"}).inputValidator({min:5,max:50,onError:"你输入的用户名非法,请确认"})
	        .ajaxValidator({
	        dataType : "json",
	        async : true,
	        url : "<?php echo U('Users/checkLoginKey'); ?>",
	        success : function(data){
	            if( data.status == -1) return true;
	            return "该用户不存在,请检查！";
	        },
	        buttons: $("#goform"),
	        error: function(jqXHR, textStatus, errorThrown){alert("服务器没有返回数据，可能服务器忙，请重试"+errorThrown);},
	        onError : "该用户不存在！",
	        onWait : "正在对用户名进行合法性校验，请稍候..."
	    });
	    $("#verify").formValidator({onShow:"",onFocus:"请输入正确的验证码！",onCorrect:"输入正确！",defaultValue:""})
	        .ajaxValidator({
	        dataType : "json",
	        async : true,
	        url : "<?php echo U('Users/checkCodeVerify'); ?>",
	        success : function(data){
	            if( data.status == 1) return true;
	            return "验证码错误";
	        },
	        buttons: $("#button"),
	        error: function(jqXHR, textStatus, errorThrown){alert("服务器没有返回数据，可能服务器忙，请重试"+errorThrown);},
	        onError : "验证码错误",
	        onWait : "正在对验证码进行合法性校验，请稍候..."
	    });
	});
	</script>
</html>