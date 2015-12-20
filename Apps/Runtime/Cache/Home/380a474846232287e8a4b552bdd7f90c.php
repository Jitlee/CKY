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
            
<div class="rtc-body"> 
<div class='rtc-page-header'>卖家中心 > 商品分类</div>
<div class='rtc-page-content'>
   <div class='rtc-tbar-group' style='text-align:right;'>
   	 <span></span>
   	 <a href='javascript:addGoodsCat(1);' style='margin-right:15px;'><span class='add btn'></span>新增</a>
   </div>
   <form autocomplete="off">
   <table id="cat_list_tab" class='rtc-list rtc-form'>
   <thead>
   <tr>
     <th>名称</th>
     <th width='60'>排序号</th>
     <th width='80' style="line-height: normal;">是否显示<br/><span style="font-weight:normal;color:red;">(双击可修改)</span></th>
     <th width="150">操作</th>
   </tr>
   </thead>
   <?php if(is_array($List)): $i = 0; $__LIST__ = $List;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tbody>
   <tr id='tr_<?php echo ($i); ?>' isLoad='1'>
     <td>
     <span class='rtc-tree-open' onclick='javascript:loadGoodsCatChildTree(this,<?php echo ($vo["catId"]); ?>,"tr_<?php echo ($i); ?>")'>&nbsp;</span>
     <input type='text' style='width:400px;height:22px;' value='<?php echo ($vo['catName']); ?>' dataId="<?php echo ($vo["catId"]); ?>" onchange='javascript:editGoodsCatName(this)'/>
     </td>
     <td><input class='catsort' type='text' style='width:35px;' value="<?php echo ($vo['catSort']); ?>" dataId="<?php echo ($vo["catId"]); ?>" onchange='javascript:editGoodsCatSort(this)' onkeyup="javascript:RTC.isChinese(this,1)" onkeypress="return RTC.isNumberKey(event)"/></td>
     <?php if($vo['isShow']==0 ): ?><td style="cursor:pointer;" ondblclick="changeCatStatus(1,<?php echo ($vo['catId']); ?>,0)"><span class='rtc-state_no'></span></td>
     <?php else: ?>
      <td style="cursor:pointer;" ondblclick="changeCatStatus(0,<?php echo ($vo['catId']); ?>,0)"><span class='rtc-state_yes'></span></td><?php endif; ?>
     <td>
     <span onclick='javascript:addGoodsCat(this,<?php echo ($vo["catId"]); ?>,<?php echo ($i); ?>);' class='add btn' title='新增'></span>
     <span onclick="javascript:delGoodsCat(<?php echo ($vo['catId']); ?>,0)" class='del btn' title='删除'></span>&nbsp;
     </td>
   </tr>
   <?php if($vo['childNum'] > 0 ): if(is_array($vo['child'])): $i2 = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i2 % 2 );++$i2;?><tr id='tr_<?php echo ($i); ?>_<?php echo ($i2); ?>' class="tr_<?php echo ($i); ?>" isLoad='1'>
	     <td>
	     <span class="rtc-tree-second">&nbsp;</span>
	     <input type='text' style='width:400px;height:22px;' value='<?php echo ($vo2['catName']); ?>' dataId="<?php echo ($vo2["catId"]); ?>" onchange='javascript:editGoodsCatName(this)'/>
	     </td>
	     <td><input class='catsort' type='text' style='width:35px;' value="<?php echo ($vo2['catSort']); ?>" dataId="<?php echo ($vo2["catId"]); ?>" onchange='javascript:editGoodsCatSort(this)' onkeyup="javascript:RTC.isChinese(this,1)" onkeypress="return RTC.isNumberKey(event)"/></td>
	     <?php if($vo2['isShow']==0 ): ?><td style="cursor:pointer;" onclick="changeCatStatus(1,<?php echo ($vo2['catId']); ?>,<?php echo ($vo['catId']); ?>)"><span class='rtc-state_no'></span></td>
	     <?php else: ?>
	      <td style="cursor:pointer;" onclick="changeCatStatus(0,<?php echo ($vo2['catId']); ?>,<?php echo ($vo['catId']); ?>)"><span class='rtc-state_yes'></span></td><?php endif; ?>
	     <td>
	     <a href="javascript:delGoodsCat(<?php echo ($vo2['catId']); ?>,0)" class='del btn' title='删除'></a>&nbsp;
	     </td>
	  </tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
   </tbody><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
</form>
<div class='rtc-tbar-group'>
     <button class='rtc-btn-query' style='display:none;margin-top:5px;margin-left:400px;' type="button" onclick='javascript:batchSaveShopCats()'>保&nbsp;存</button>
     <button class='rtc-btn-query' style='display:none;margin-top:5px;margin-left:5px;' type="button" onclick='javascript:location.reload()'>取&nbsp;消</button>
     <a style='float:right;margin-right:5px;' href='javascript:addGoodsCat(1);'><span class='add btn'></span>新增</a>
</div>
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