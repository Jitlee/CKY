<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  		<title>卖家中心 - <?php echo ($CONF['mallTitle']); ?></title>
  		<meta name="keywords" content="<?php echo ($CONF['mallKeywords']); ?>" />
      	<meta name="description" content="<?php echo ($CONF['mallDesc']); ?>,卖家中心" />
  		<meta http-equiv="Expires" content="0">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-control" content="no-cache">
		<meta http-equiv="Cache" content="no-cache">
  		<link rel="stylesheet" href="/Apps/Home/View/default/css/common.css" />
    	<link rel="stylesheet" href="/Apps/Home/View/default/css/shop.css">
    	<link rel="stylesheet" type="text/css" href="/Public/plugins/webuploader/webuploader.css" />
      	<script>
		var publicurl = '/Public/';
		var shopId = '<?php echo ($orderInfo["order"]["shopId"]); ?>';
		var pageCount='<?php echo ($receiveOrders["totalPage"]); ?>';
		var current='<?php echo ($receiveOrders["currPage"]); ?>';
		</script>
		<?php echo RTCLoginTarget(1);?>
    </head>
    <body>
        <div class="rtc-wrap">
          <div class='rtc-header'>
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

				<!--
			<div class='rtc-user-top'>
			<div class="rtc-user-top-main">
					<div class='rtc-user-top-logo'>
						<a href="<?php echo RTCDomain();?>"  title='商城首页'>
						<img src="<?php echo RTCDomain();?>/<?php echo ($CONF['mallLogo']); ?>" height="132" width='240'/>	
						</a>
					</div>
					<div class='rtc-user-top-search'>
						<div class="search-box">
							<input id="rtc-search-type" type="hidden" value="1"/>
							<input id="keyword" class="rtc-search-ipt" me="q" tabindex="9" placeholder="搜索 商品" autocomplete="off" >
							<div id="btnsch" class="rtc-search-btn">搜&nbsp;索</div>
						</div>
					</div>
					
				</div>
			</div>
			-->
			<div class="rtc-shop-nav">
				<div class="rtc-nav-box">
					<li class="liselect"><a href="<?php echo U('Home/Shops/index');?>" style='color:#FFFFFF;'>我是商家</a></li>
					<div class="rtc-clear"></div>
				</div>
			</div>
			<div class="rtc-clear;"></div>
		</div>
          <div class='rtc-nav'></div>
          <div class='rtc-main'>
            <div class='rtc-menu'>
            	<span class='rtc-menu-title'><span></span>交易管理</span>
            
              	<a href='<?php echo U("Home/Orders/toShopOrdersList");?>'><li id='li_toShopOrdersList' <?php if($umark == "toShopOrdersList"): ?>class='liselect'<?php endif; ?>>订单管理<span style="display:none;" class="rtc-msg-tips-box"></span></li></a>
            	<span class='rtc-menu-title' style='border-top:0px;'><span></span>商品管理</span>
            
               	<a href="<?php echo U('Home/ShopsCats/index/');?>"><li <?php if($umark == "index"): ?>class='liselect'<?php endif; ?>>商品分类</li></a>
              	<a href="<?php echo U('Home/Goods/queryOnSaleByPage/');?>"><li <?php if($umark == "queryOnSaleByPage"): ?>class='liselect'<?php endif; ?>>出售中的商品</li></a>
               	<a href="<?php echo U('Home/Goods/queryPenddingByPage/');?>"><li <?php if($umark == "queryPenddingByPage"): ?>class='liselect'<?php endif; ?>>待审核商品</li></a>
               	<a href="<?php echo U('Home/Goods/queryUnSaleByPage/');?>"><li <?php if($umark == "queryUnSaleByPage"): ?>class='liselect'<?php endif; ?>>仓库中的商品</li></a>
               	<a href="<?php echo U('Home/Goods/toEdit/',array('umark'=>'toEditGoods'));?>"><li <?php if($umark == "toEditGoods"): ?>class='liselect'<?php endif; ?>>新增商品</li></a>
               	<a href="<?php echo U('Home/GoodsAppraises/index/');?>"><li <?php if($umark == "GoodsAppraises"): ?>class='liselect'<?php endif; ?>>评价管理</li></a>

               	<a href="<?php echo U('Home/AttributeCats/index');?>"><li <?php if($umark == "AttributeCats"): ?>class='liselect'<?php endif; ?>>商品类型</li></a>
               	<!--<a href="<?php echo U('Home/Imports/index');?>"><li <?php if($umark == "Imports"): ?>class='liselect'<?php endif; ?>>数据导入</li></a>-->
			
              	<span class='rtc-menu-title'><span></span>商家设置</span>
            	<a href="<?php echo U('Home/Shops/toEdit/');?>"><li <?php if($umark == "toEdit"): ?>class='liselect'<?php endif; ?>>商家资料</li></a>
              	<a href="<?php echo U('Home/Shops/toShopCfg/');?>"><li <?php if($umark == "setShop"): ?>class='liselect'<?php endif; ?>>商家设置</li></a>
              	<a href="<?php echo U('Home/Messages/queryByPage/');?>"><li id='li_queryMessageByPage' <?php if($umark == "queryMessageByPage"): ?>class='liselect'<?php endif; ?>>商城消息<span style="display:none;" class="rtc-msg-tips-box"></span></li></a>
              	<a href="<?php echo U('Home/Shops/toEditPass');?>"><li <?php if($umark == "toEditPass"): ?>class='liselect'<?php endif; ?>>修改密码</li></a>
            </div>
            <div class='rtc-content'>
            
<div>
	<div class='rtc-page-header'>
		卖家中心&nbsp;>&nbsp;帐户概览
	</div>
	<div style="height:158px;">
		<table style="width:800px;margin-top:25px;">
			<tbody>
				<tr>
					<td width="140">
						<div class='rtc-shophome-img'>
							<a target="_blank" href="<?php echo U('Home/Shops/toShopHome/',array('shopId'=>$shopInfo['shop']['shopId']));?>">
								<img src="/<?php echo ($shopInfo['shop']['shopImg']); ?>" height="120" width="120" />
							</a>
						</div>
					</td>
					<td style="vertical-align:top;line-height:25px;">
						<div style="font-weight:bolder;"><?php echo ($shopInfo['shop']['shopName']); ?></div>
						<div style="">店铺名称：<a target="_blank" href="<?php echo U('Home/Shops/toShopHome/',array('shopId'=>$shopInfo['shop']['shopId']));?>"><span style="color:#55AAFF"><?php echo ($shopInfo['shop']['shopName']); ?></a></span></div>
						<div style="">店铺状态：
						<?php if($shopInfo['shop']['shopStatus'] == 1): if(($shopInfo['shop']['shopAtive'] == 1) and ($shopInfo['shop']['shopStatus'] == 1)): ?>营业中，
							<?php else: ?>
								休息中，<?php endif; endif; ?>
						<?php if($shopInfo['shop']['shopStatus'] == 1): ?>已审核
						<?php elseif($shopInfo['shop']['shopStatus'] == -2): ?>
							已停止
						<?php elseif($shopInfo['shop']['shopStatus'] == -1): ?>
							已拒绝
						<?php elseif($shopInfo['shop']['shopStatus'] == 0): ?>
							待审核<?php endif; ?>
						</div>
					</td>
					<td width="280" style="vertical-align:top;line-height:25px;">
						<div style="font-weight:bolder;">店铺动态评分</div>
						<div style="">商品描述:
							<?php $__FOR_START_11542__=0;$__FOR_END_11542__=$shopInfo['details']['goodsScore'];for($i=$__FOR_START_11542__;$i < $__FOR_END_11542__;$i+=1){ ?><img src="/Apps/Home/View/default/images/icon_score_yes.png"/><?php } ?>
							<?php echo ($shopInfo['details']['goodsScore']); ?>分
						</div>
						<div style="">时效评分:
							<?php $__FOR_START_5783__=0;$__FOR_END_5783__=$shopInfo['details']['timeScore'];for($i=$__FOR_START_5783__;$i < $__FOR_END_5783__;$i+=1){ ?><img src="/Apps/Home/View/default/images/icon_score_yes.png"/><?php } ?>
							<?php echo ($shopInfo['details']['timeScore']); ?>分
						</div>
						<div style="">服务评分:
							<?php $__FOR_START_20612__=0;$__FOR_END_20612__=$shopInfo['details']['serviceScore'];for($i=$__FOR_START_20612__;$i < $__FOR_END_20612__;$i+=1){ ?><img src="/Apps/Home/View/default/images/icon_score_yes.png"/><?php } ?>
							<?php echo ($shopInfo['details']['serviceScore']); ?>分
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class='rtc-shophome-area'>
		<div class='rtc-shophome-nav'>
			<div class='header'>
				店铺提示
			</div>
			<div class='main'>
				<div style="font-weight:bolder;">您需要关注的店铺情况：</div>
				<div style="color:#55AAFF;">
					商品提示：
					<span>待审核商品（<span style="color:red;"><?php echo (intval($shopInfo['details']['waitGoodsCnt'])); ?></span>）</span>&nbsp;&nbsp;&nbsp;&nbsp;
					<span>仓库中商品（<span style="color:red;"><?php echo (intval($shopInfo['details']['waitSaleGoodsCnt'])); ?></span>）</span>&nbsp;&nbsp;&nbsp;&nbsp;
					<span>买家评价（<span style="color:red;"><?php echo (intval($shopInfo['details']['appraisesCnt'])); ?></span>）</span>
				</div>
			</div>
			<div class='current' >出售中的商品（<?php echo (intval($shopInfo['details']['onSaleGoodsCnt'])); ?>）&nbsp;&nbsp;&nbsp;&nbsp;<!-- 淘宝数据导入 --></div>
			<div class='header'>
				交易提示
			</div>
			<div class='main'>
				<div style="font-weight:bolder;">您需要立即处理的交易订单：</div>
				<div style="color:#55AAFF;">
					订单提示：
					<span>待受理订单（<span style="color:red;"><?php echo (intval($shopInfo['details']['waitHandleOrderCnt'])); ?></span>）</span>&nbsp;&nbsp;&nbsp;&nbsp;
					<span>待发货订单（<span style="color:red;"><?php echo (intval($shopInfo['details']['waitSendOrderCnt'])); ?></span>）</span>&nbsp;&nbsp;&nbsp;&nbsp;
					<span>待结束订单（<span style="color:red;"><?php echo (intval($shopInfo['details']['appraisesOrderCnt'])); ?></span>）</span>
				</div>
			</div>
			<div class='current'>周订单量（<?php echo (intval($shopInfo['details']['weekOrderCnt'])); ?>）&nbsp;&nbsp;&nbsp;&nbsp;周交易金额（<?php echo (intval($shopInfo['details']['weekOrderMoney'])); ?>）&nbsp;&nbsp;&nbsp;&nbsp;一个月订单量（<?php echo (intval($shopInfo['details']['monthOrderCnt'])); ?>）&nbsp;&nbsp;&nbsp;&nbsp;一个月交易金额（<?php echo (intval($shopInfo['details']['monthOrderMoney'])); ?>）&nbsp;&nbsp;&nbsp;&nbsp;</div>
			
			<div class='header' style='display:none'>
				支付帐号
			</div>
			<div class='main' style='display:none'>
				<div style="float:left;width:100px;height:100px;">
					<img src="" width="100" height="100"/>
				</div>
				<div style="float:left;width:400px;height:100px;padding-left:10px;">
					您的帐户余额：43349元<br/>
					<button style="width:80px;height:30px;background-color:#e23e3d;color:#ffffff;border:1px solid #ffffff;">帐户充值</button><br/>
					<div style="color:#55AAFF;">
						<span>支付帐号</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>提现</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>出入明细</span>&nbsp;&nbsp;&nbsp;&nbsp;
					</div>
				</div>
				<div class="rtc-clear"></div>
			</div>
			
		</div>
		
		<div style="width:192px;float:left;min-height:400px;border-left:1px solid #cccccc;">
			<div style="height:36px;line-height:36px;font-weight:bolder;padding-left:10px;">
				平台联系方式
			</div>
			<div style="padding-left:10px;line-height:26px;">
				<div>电话：<?php echo ($shopInfo['shop']['userPhone']); ?></div>
				<div>邮箱：<?php echo ($shopInfo['shop']['userEmail']); ?></div>
				<div>服务时间：<?php echo ($shopInfo['shop']['serviceStartTime']); ?>-<?php echo ($shopInfo['shop']['serviceEndTime']); ?></div>
			</div>
		</div>
		<div class="rtc-clear"></div>
	</div>
</div>

            </div>
          </div>
          <div style='clear:both;'></div>
          <br/>
          <div class='rtc-footer'>
          	 

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

          </div>
        </div>
    </body>
      	<script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
     	<script src="/Public/js/common.js"></script>
      	<script src="/Apps/Home/View/default/js/shopcom.js"></script>
      	<script src="/Apps/Home/View/default/js/head.js"></script>
      	<script src="/Apps/Home/View/default/js/common.js"></script><!---->
      	<script src="/Public/plugins/layer/layer.min.js"></script>
</html>