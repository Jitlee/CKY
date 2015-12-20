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
            
<style type="text/css">
#preview{border:1px solid #cccccc; background:#CCC;color:#fff; padding:5px; display:none; position:absolute;}
</style>
<script>
$(document).ready(function(){
    $('.imgPreview').imagePreview();
    <?php if(!empty($shopCatId1)): ?>getShopCatListForGoods('<?php echo ($shopCatId1); ?>','<?php echo ($shopCatId2); ?>');<?php endif; ?>
});
</script>
    <div class="rtc-body"> 
       <div class='rtc-page-header'>卖家中心 > 出售中的商品</div>
       <div class='rtc-page-content'>
        <div class='rtc-tbar-query'>
        店铺分类：<select id='shopCatId1' autocomplete="off" onchange='javascript:getShopCatListForGoods(this.value,"<?php echo ($object['shopCatId2']); ?>")'>
	         <option value='0'>请选择</option>
	         <?php if(is_array($shopCatsList)): $i = 0; $__LIST__ = $shopCatsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo['catId']); ?>' <?php if($shopCatId1 == $vo['catId'] ): ?>selected<?php endif; ?>><?php echo ($vo['catName']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	     </select>
	     <select id='shopCatId2' autocomplete="off">
	         <option value='0'>请选择</option>
	     </select>
        商品：<input type='text' id='goodsName' value='<?php echo ($goodsName); ?>'/>
      <button class='rtc-btn-query' onclick='javascript:queryOnSale()'>查询</button>
        </div>
        <div class='rtc-tbar-group'>
           <span></span>
           <a href='javascript:batchDel()'><span class='del btn'></span>删除</a>
           <a href='javascript:sale(0)'><span class='unSale btn'></span>下架</a>
           <a href='javascript:goodsSet("isRecomm","queryUnSaleByPage")'><span class='recomm btn'></span>推荐</a>
           <a href='javascript:goodsSet("isBest","queryUnSaleByPage")'><span class='best btn'></span>精品</a>
           <a href='javascript:goodsSet("isNew","queryUnSaleByPage")'><span class='new btn'></span>新品</a>
           <a href='javascript:goodsSet("isHot","queryUnSaleByPage")'><span class='hot btn'></span>热销</a>
        </div>
        <table class='rtc-list'>
           <thead>
             <tr>
               <th width='40'><input type='checkbox' onclick='javascript:checkAll(this)'/></th>
               <th>商品名称</th>
               <th width="100">商品编号</th>
               <th width="80">价格</th>
               <th>推荐</th>
               <th>精品</th>
               <th>新品</th>
               <th>热销</th>
               <th>上架</th>
               <th>销量</th>
               <th width="100" >库存<br/>
               	<span style='color:red;font-weight:normal;'>(双击可修改)</span>
               </th>
               <th width='120'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td><input class='chk' type='checkbox' value='<?php echo ($vo['goodsId']); ?>'/></td>
               <td <?php if($vo['goodsThums'] != ''): ?>img='<?php echo ($vo['goodsThums']); ?>' class='imgPreview'<?php endif; ?>>
               <img class='lazyImg' data-original="/<?php echo ($vo['goodsThums']); ?>" height="50" width="50"/>
               <?php echo ($vo['goodsName']); ?>
               </td>
               <td style="cursor:pointer;" title='双击修改' ondblclick="javascript:toEditGoodsBase(1,<?php echo ($vo['goodsId']); ?>,0)">
               		<input id="ipt_1_<?php echo ($vo['goodsId']); ?>" onblur="javascript:editGoodsBase(1,<?php echo ($vo['goodsId']); ?>)" style="display:none;width:100%;border:1px solid red;width:40px;" maxlength="20"/>
	               	<span id="span_1_<?php echo ($vo['goodsId']); ?>" style="display: inline;"><?php echo ($vo['goodsSn']); ?></span>
					<img id="img_1_<?php echo ($vo['goodsId']); ?>" style="opacity: 0;display:none;" src="/Apps/Home/View/default/images/action_check.gif">
               </td>
               <td style="cursor:pointer;" title='双击修改' ondblclick="javascript:toEditGoodsBase(2,<?php echo ($vo['goodsId']); ?>,'<?php echo ($vo[attIsRecomm]); ?>')">               		
               		<input id="ipt_2_<?php echo ($vo['goodsId']); ?>" onkeyup="javascript:RTC.isChinese(this,1)" onkeypress="return RTC.isNumberdoteKey(event)" onblur="javascript:editGoodsBase(2,<?php echo ($vo['goodsId']); ?>)" style="display:none;width:100%;border:1px solid red;width:40px;" maxlength="10"/>
	               	<span id="span_2_<?php echo ($vo['goodsId']); ?>" style="display: inline;"><?php echo ($vo['shopPrice']); ?></span>
					<img id="img_2_<?php echo ($vo['goodsId']); ?>" style="opacity: 0;display:none;" src="/Apps/Home/View/default/images/action_check.gif">
               </td>
               <td style="cursor:pointer;" title='双击修改' ondblclick="javascript:changSaleStatus(<?php echo ($vo['goodsId']); ?>,1)">
               <input id="isRecomm_<?php echo ($vo['goodsId']); ?>" type="hidden" value="<?php echo ($vo[isRecomm]); ?>"/>
               <div id="isRecomm_div_<?php echo ($vo['goodsId']); ?>">
	               <?php if($vo['isRecomm'] == 1 ): ?><span class='rtc-state_yes'></span>
	               <?php else: ?>
	               <span class='rtc-state_no'></span><?php endif; ?>
               </div>
               </td>
               <td style="cursor:pointer;" title='双击修改' ondblclick="javascript:changSaleStatus(<?php echo ($vo['goodsId']); ?>,2)">
               <input id="isBest_<?php echo ($vo['goodsId']); ?>" type="hidden" value="<?php echo ($vo[isBest]); ?>"/>
               <div id="isBest_div_<?php echo ($vo['goodsId']); ?>">
	               <?php if($vo['isBest'] == 1 ): ?><span class='rtc-state_yes'></span>
	               <?php else: ?>
	               <span class='rtc-state_no'></span><?php endif; ?>
               </div>
               </td>
               <td style="cursor:pointer;" title='双击修改' ondblclick="javascript:changSaleStatus(<?php echo ($vo['goodsId']); ?>,3)">
               <input id="isNew_<?php echo ($vo['goodsId']); ?>" type="hidden" value="<?php echo ($vo[isNew]); ?>"/>
               <div id="isNew_div_<?php echo ($vo['goodsId']); ?>">
	               <?php if($vo['isNew'] == 1 ): ?><span class='rtc-state_yes'></span>
	               <?php else: ?>
	               <span class='rtc-state_no'></span><?php endif; ?>
               </div>
               </td>
               <td style="cursor:pointer;" title='双击修改' ondblclick="javascript:changSaleStatus(<?php echo ($vo['goodsId']); ?>,4)">
               <input id="isHot_<?php echo ($vo['goodsId']); ?>" type="hidden" value="<?php echo ($vo[isHot]); ?>"/>
               <div id="isHot_div_<?php echo ($vo['goodsId']); ?>">
	               <?php if($vo['isHot'] == 1 ): ?><span class='rtc-state_yes'></span>
	               <?php else: ?>
	               <span class='rtc-state_no'></span><?php endif; ?>
               </div>
               </td>
               <td style="cursor:pointer;" title='双击修改' ondblclick="javascript:changSaleStatus(<?php echo ($vo['goodsId']); ?>,5)">
               <input id="isSale_<?php echo ($vo['goodsId']); ?>" type="hidden" value="<?php echo ($vo[isSale]); ?>"/>
               <div id="isSale_div_<?php echo ($vo['goodsId']); ?>">
	               <?php if($vo['isSale'] == 1 ): ?><span class='rtc-state_yes'></span>
	               <?php else: ?>
	               <span class='rtc-state_no'></span><?php endif; ?>
               </div>
               </td>
               <td><?php echo ($vo['saleCount']); ?></td>
               <td style="cursor:pointer;" title='双击修改' ondblclick="javascript:toEditGoodsBase(3,<?php echo ($vo['goodsId']); ?>,'<?php echo ($vo[attIsRecomm]); ?>')" >
	               	<input id="ipt_3_<?php echo ($vo['goodsId']); ?>" onkeyup="javascript:RTC.isChinese(this,1)" onkeypress="return RTC.isNumberKey(event)" onblur="javascript:editGoodsBase(3,<?php echo ($vo['goodsId']); ?>)" style="display:none;width:100%;border:1px solid red;width:40px;" maxlength="6"/>
	               	<span id="span_3_<?php echo ($vo['goodsId']); ?>" style="display: inline;"><?php echo ($vo['goodsStock']); ?></span>
					<img id="img_3_<?php echo ($vo['goodsId']); ?>" style="opacity: 0;display:none;" src="/Apps/Home/View/default/images/action_check.gif">
               </td>
               <td>
               <a href="javascript:toViewGoods(<?php echo ($vo['goodsId']); ?>)" class='btn view'></a>
               <a href="javascript:toEditGoods(<?php echo ($vo['goodsId']); ?>,'queryOnSaleByPage')" class='btn edit'></a>
               <a href="javascript:delGoods(<?php echo ($vo['goodsId']); ?>)" class='btn del'></a>
               &nbsp;
               </td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tfoot>
             <tr>
                <td colspan='12'>
                <div class="rtc-page" style="float:right;padding-bottom:10px;">
						<div id="rtc-page-items"></div>
				</div>
                <script>
			    <?php if($Page['totalPage'] > 1): ?>$(document).ready(function(){
					laypage({
					    cont: 'rtc-page-items',
					    pages: <?php echo ($Page['totalPage']); ?>, //总页数
					    skip: true, //是否开启跳页
					    skin: '#e23e3d',
					    groups: 3, //连续显示分页数
					    curr: function(){ //通过url获取当前页，也可以同上（pages）方式获取
					        var page = location.search.match(/p=(\d+)/);
					        return page ? page[1] : 1;
					    }(), 
					    jump: function(e, first){ //触发分页后的回调
					        if(!first){ //一定要加此判断，否则初始时会无限刷新
					        	var nuewurl = RTC.splitURL("p");
					        	var ulist = nuewurl.split("?");
					        	if(ulist.length>1){
					        		location.href = nuewurl+'&p='+e.curr;
					        	}else{
					        		location.href = '?p='+e.curr;
					        	}
					            
					        }
					    }
					});
			    });<?php endif; ?>
				</script>
                </td>
             </tr>
             </tfoot>
           </tbody>
        </table>
        <div class='rtc-tbar-group'>
           <span></span>
           <a href='javascript:batchDel()'><span class='del btn'></span>删除</a>
           <a href='javascript:sale(0)'><span class='unSale btn'></span>下架</a>
           <a href='javascript:goodsSet("isRecomm","queryUnSaleByPage")'><span class='recomm btn'></span>推荐</a>
           <a href='javascript:goodsSet("isBest","queryUnSaleByPage")'><span class='best btn'></span>精品</a>
           <a href='javascript:goodsSet("isNew","queryUnSaleByPage")'><span class='new btn'></span>新品</a>
           <a href='javascript:goodsSet("isHot","queryUnSaleByPage")'><span class='hot btn'></span>热销</a>
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