<?php if (!defined('THINK_PATH')) exit();?>﻿<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
         <title>买家中心 - <?php echo ($CONF['mallTitle']); ?></title>
         <meta name="keywords" content="<?php echo ($CONF['mallKeywords']); ?>" />
      	 <meta name="description" content="<?php echo ($CONF['mallDesc']); ?>,买家中心" />
         <meta http-equiv="Expires" content="0">
		 <meta http-equiv="Pragma" content="no-cache">
		 <meta http-equiv="Cache-control" content="no-cache">
		 <meta http-equiv="Cache" content="no-cache">
         <link rel="stylesheet" href="/Apps/Home/View/default/css/common.css" />
         <link rel="stylesheet" href="/Apps/Home/View/default/css/user.css">
    </head>
	<?php echo RTCLoginTarget(0);?>
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
				<img src="/Apps/Home/View/default/images/icon_top_03.png"/>	
				&nbsp;&nbsp;<a href="javascript:;" onclick="toChangeCity();">切换城市</a><i class="triangle"></i>
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
			<li class="fore2 ld"><s></s>
			<?php if(session('RTC_USER.userId')>0){ ?>
				<?php if(session('RTC_USER.userType')==0){ ?>
				    <a href="<?php echo U('Home/Shops/toOpenShopByUser');?>" rel="nofollow">我要开店</a>
				<?php }else{ ?>
				    <?php if(session('RTC_USER.loginTarget')==0){ ?>
				        <a href="<?php echo U('Home/Shops/index');?>" rel="nofollow">卖家中心</a>
				    <?php }else{ ?>
				        <a href="<?php echo U('Home/Users/index');?>" rel="nofollow">买家中心</a>
				    <?php } ?>
				<?php } ?>
			<?php }else{ ?>
			    <a href="<?php echo U('Home/Shops/toOpenShop');?>" rel="nofollow">我要开店</a>
			<?php } ?>
			</li>
		</ul>
		<span class="clr"></span>
	</div>
</div>

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
			<div class="rtc-shop-nav">
				<div class="rtc-nav-box">
					<li class="liselect"><a href="<?php echo U('Home/Orders/queryByPage');?>">买家首页</a></li>
					<div class="rtc-clear"></div>
				</div>
			</div>
          </div>
          <div class='rtc-nav'></div>
          <div class='rtc-main'>
            <div class='rtc-menu'>
            <span class='rtc-menu-title' style='border-top:0px;'><span></span>交易信息</span>
            
               <a href="<?php echo U('Home/Orders/queryPayByPage/');?>"><li id="li_queryPayByPage" <?php if($umark == "queryPayByPage"): ?>class='selected'<?php endif; ?>>待付款订单<span style="display:none;" class="rtc-msg-tips-box"></span></li></a>
               <a href="<?php echo U('Home/Orders/queryDeliveryByPage/');?>"><li id="li_queryDeliveryByPage" <?php if($umark == "queryDeliveryByPage"): ?>class='selected'<?php endif; ?>>待发货订单<span style="display:none;" class="rtc-msg-tips-box"></span></li></a>
               <a href="<?php echo U('Home/Orders/queryReceiveByPage/');?>"><li id="li_queryReceiveByPage" <?php if($umark == "queryReceiveByPage"): ?>class='selected'<?php endif; ?>>待确认收货<span style="display:none;" class="rtc-msg-tips-box"></span></li></a>
               <a href="<?php echo U('Home/Orders/queryAppraiseByPage/');?>"><li id="li_queryAppraiseByPage" <?php if($umark == "queryAppraiseByPage"): ?>class='selected'<?php endif; ?>>待评价交易<span style="display:none;" class="rtc-msg-tips-box"></span></li></a>
               <a href="<?php echo U('Home/Orders/queryCancelOrders/');?>"><li id="li_queryCancelOrders" <?php if($umark == "queryCancelOrders"): ?>class='selected'<?php endif; ?>>已取消订单</li></a>
               <span class='rtc-menu-title'><span></span>交易操作</span>
           
               <a href="<?php echo U('Home/Orders/queryRefundByPage/');?>"><li <?php if($umark == "queryRefundByPage"): ?>class='selected'<?php endif; ?>>拒收/退款</li></a>
               <a href="<?php echo U('Home/UserAddress/queryByPage/');?>"><li <?php if($umark == "addressQueryByPage"): ?>class='selected'<?php endif; ?>>收货地址</li></a>
               <a  href="<?php echo U('Home/GoodsAppraises/getAppraisesList/');?>"><li <?php if($umark == "getAppraisesList"): ?>class='selected'<?php endif; ?>>评价管理</li></a>
               <a  href="<?php echo U('Home/Favorites/queryByPage/');?>"><li <?php if($umark == "queryFavoritesByPage"): ?>class='selected'<?php endif; ?>>我的关注</li></a>
               <a href="<?php echo U('Home/Messages/queryByPage/');?>"><li id='li_queryMessageByPage' <?php if($umark == "queryMessageByPage"): ?>class='liselect'<?php endif; ?>>商城消息<span style="display:none;" class="rtc-msg-tips-box"></span></li></a>
               <a href="<?php echo U('Home/Users/toEdit/');?>"><li <?php if($umark == "toEditUser"): ?>class='selected'<?php endif; ?>>个人资料</li></a>
               <a href="<?php echo U('Home/Users/toEditPass/');?>"><li <?php if($umark == "toEditPass"): ?>class='selected'<?php endif; ?>>修改密码</li></a>
             <?php if($RTC_USER["userType"] == 0): ?><div class="rtc-appsaler">
				<div>您目前不是卖家，您可以</div>
				<div><a href="<?php echo U('Home/Shops/toOpenShopByUser/');?>"><img src="/Apps/Home/View/default/images/btn_setshop.png" height="38" width="134" /></a></div>
			 </div><?php endif; ?>
            </div>
            <div class='rtc-content'>
            

    <div class="rtc-body"> 
       <div class='rtc-page-header'>买家中心 > 待评价交易</div>
       <div class='rtc-page-content'>
       	<div style="padding-bottom:4px;">
       		订单编号:<input id="orderNo" value="<?php echo ($params['orderNo']); ?>" style="width:80px;" autocomplete="off"/>
       		店铺名称:<input id="shopName" value="<?php echo ($params['shopName']); ?>" style="width:80px;" autocomplete="off"/>
       		收货人:<input id="userName" value="<?php echo ($params['userName']); ?>" style="width:80px;" autocomplete="off"/>
       		<button class="rtc-btn-query" onclick="javascript:getOrdersList(4)">查询</button>
       	</div>
        <table class='rtc-list' style="font-size:13px;">
           <thead>
             <tr>
               <th width='80'>订单编号</th>
               <th width='*'>商品信息</th>
               <th width='100'>店铺名称</th>
               <th width='100'>收货人</th>
               <th width='70'>订单金额</th>
               <th width='140'>下单时间</th>
               <th width='60'>状态</th>
               <th width='130'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($appraiseOrders['root'])): $key1 = 0; $__LIST__ = $appraiseOrders['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$order): $mod = ($key1 % 2 );++$key1;?><tr height="60">
             	<td width='80' style="padding-top:10px;vertical-align:top;"><?php echo ($order["orderNo"]); ?></td>
                <td width='*'>
					<?php if(is_array($order['goodslist'])): $key2 = 0; $__LIST__ = $order['goodslist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods): $mod = ($key2 % 2 );++$key2;?><a target='_blank' href="<?php echo U('Home/Goods/getGoodsDetails/',array('goodsId'=>$goods['goodsId']));?>">
							<img src="/<?php echo ($goods['goodsThums']); ?>" height="50" width="50" class='rtc-goods-tb'/>
						</a><?php endforeach; endif; else: echo "" ;endif; ?>
				</td>
				<td width='100' style="padding-top:10px;vertical-align:top;"><a href="<?php echo U('Home/Shops/toShopHome/',array('shopId'=>$goodsDetails['shopId']));?>" target="_blank"><?php echo ($order["shopName"]); ?></a></td>
               	<td width='100' style="padding-top:10px;vertical-align:top;"><?php echo ($order["userName"]); ?></td>
               	<td width='70' style="padding-top:10px;vertical-align:top;"><?php echo ($order["totalMoney"]); ?></td>
               	<td width='140' style="padding-top:10px;vertical-align:top;"><?php echo ($order["createTime"]); ?></td>
               	<td width='60' style="padding-top:10px;vertical-align:top;">
               		<?php if($order["orderStatus"] == -3): ?>拒收
               		<?php elseif($order["orderStatus"] == -2): ?>未付款
               		<?php elseif($order["orderStatus"] == -1): ?>已取消
               		<?php elseif($order["orderStatus"] == 0): ?>未受理
               		<?php elseif($order["orderStatus"] == 1): ?>已受理
               		<?php elseif($order["orderStatus"] == 2): ?>打包中
               		<?php elseif($order["orderStatus"] == 3): ?>配送中
               		<?php elseif($order["orderStatus"] == 4): ?>已到货
               		<?php elseif($order["orderStatus"] == 5): ?>确认收货<?php endif; ?>
               	</td>
               	<td width='130' style="padding-top:10px;vertical-align:top;">
					<a href="javascript:;" onclick="showOrder(<?php echo ($order['orderId']); ?>)">查看</a> | 
					<?php if($order['isAppraises'] == 1): ?>已评价
					<?php else: ?>
					<a  href="javascript:;" onclick="appraiseOrder(<?php echo ($order['orderId']); ?>)">评价</a><?php endif; ?>
				</td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <?php if($appraiseOrders['totalPage'] > 1): ?><tfoot>
             <tr>
                <td colspan='8' align='center' style="height:30px;border-bottom: 0px;">
					<div class="rtc-page" style="float:right;padding-bottom:10px;">
						<div id="rtc-page-items">
						</div>
					</div>
				</td>
             </tr>
             </tfoot><?php endif; ?>
           </tbody>
        </table>
        </div>
    </div>
    <script>
    <?php if($appraiseOrders['totalPage'] > 1): ?>$(document).ready(function(){
		laypage({
		    cont: 'rtc-page-items',
		    pages: <?php echo ($appraiseOrders['totalPage']); ?>, //总页数
		    skip: true, //是否开启跳页
		    skin: '#e23e3d',
		    groups: 3, //连续显示分页数
		    curr: function(){ //通过url获取当前页，也可以同上（pages）方式获取
		        var page = location.search.match(/pcurr=(\d+)/);
		        return page ? page[1] : 1;
		    }(), 
		    jump: function(e, first){ //触发分页后的回调
		        if(!first){ //一定要加此判断，否则初始时会无限刷新
		        	var nuewurl = RTC.splitURL("pcurr");
		        	var ulist = nuewurl.split("?");
		        	if(ulist.length>1){
		        		location.href = nuewurl+'&pcurr='+e.curr;
		        	}else{
		        		location.href = '?pcurr='+e.curr;
		        	}
		            
		        }
		    }
		});
    });<?php endif; ?>
	</script>

            </div>
          </div>
          <div style='clear:both;'></div>
          <br/>
           <div class='rtc-footer'>
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

          </div>
        </div>
    </body>
    <script src="/Public/plugins/formValidator/formValidator-4.1.3.js"></script>
   	<script src="/Public/js/common.js"></script>
   	<script src="/Apps/Home/View/default/js/usercom.js"></script>
   	<script src="/Apps/Home/View/default/js/head.js"></script>
   	<script src="/Public/plugins/layer/layer.min.js"></script>
  	<script src="/Apps/Home/View/default/js/common.js"></script>
     <script type="text/javascript">
		var publicurl = '/Public/';
		var shopId = '<?php echo ($orderInfo["order"]["shopId"]); ?>';
		checkLogin();
	</script>
</html>