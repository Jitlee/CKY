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
            

<link rel="stylesheet" type="text/css" href="/Public/plugins/webuploader/style.css" />
<link rel="stylesheet" type="text/css" href="/Public/plugins/webuploader/webuploader.css" />
<script type="text/javascript" src="/Public/plugins/webuploader/webuploader.js"></script>
<script type="text/javascript" src="/Apps/Home/View/default/js/shopsbatchupload.js"></script>
<style>


</style>
<script>
$(function () {
	   $.formValidator.initConfig({
		   theme:'Default',mode:'AutoTip',formID:"myform",debug:true,submitOnce:true,onSuccess:function(){
			   setShop();
			   return false;
			},onError:function(msg){
		}});
	   
	    $("#shopKeywords").formValidator({onShow:"",onFocus:"请输入店铺SEO关键字",onCorrect:"输入正确"}).inputValidator({min:1,max:50,onError:"店铺SEO关键字不能为空,请确认"});
	    
});

function imglimouseover(obj){
	if(!$(obj).find('.file-panel').html()){
		$(obj).find('.setdel').addClass('trconb');
		$(obj).find('.setdel').css({"display":""});
	}
}

function imglimouseout(obj){
	
	$(obj).find('.setdel').removeClass('trconb');
	$(obj).find('.setdel').css({"display":"none"});
}

function imglidel(obj){
	if (confirm('是否删除图片?')) {
		$(obj).parent().remove("li");
		return;
	}
}
var filetypes = ["gif","jpg","png","jpeg"];

</script>
   <div class="rtc-body" style="position:relative;"> 
   		<iframe name="upload" style="display:none"></iframe>
		<form id="uploadform_Filedata" autocomplete="off" style="position:absolute;top:140px;left:129px;z-index:10;" enctype="multipart/form-data" method="POST" target="upload" action="<?php echo U('Home/Shops/uploadPic');?>" >
			<div style="position:relative;">
			<input id="shopBanner" name="shopBanner" type="text" value="<?php echo ($object["shopBanner"]); ?>" readonly style="margin-right:4px;float:left;margin-left:8px;width:250px;"/>
			<div class="div1">
				<div class="div2">浏览</div>
				<input type="file" class="inputstyle" id="Filedata" name="Filedata" onchange="updfile('Filedata');" >
			</div>
			<div style="clear:both;"></div>
			<div >&nbsp;图片大小:1400 x 120 (px)(格式为 gif, jpg, jpeg, png)</div>
			<input type="hidden" name="dir" value="shops">
			<input type="hidden" name="width" value="150">
			<input type="hidden" name="folder" value="Filedata">
			<input type="hidden" name="sfilename" value="Filedata">
			<input type="hidden" name="fname" value="shopBanner">
			<input type="hidden" id="s_Filedata" name="s_Filedata" value="">
			
			</div>
		</form>
       <div class='rtc-page-header'>卖家中心 > 店铺设置</div>
       <div class='rtc-page-content'>
       
       <form name="myform" method="post" id="myform" autocomplete="off">
       	<input type='hidden' id='id' value='0'/>
        <input type='hidden' id='shopBanner' value='<?php echo ($object["shopBanner"]); ?>'/>
        <table class="table table-hover table-striped table-bordered rtc-form">
            <!-- tr >
             <th width='120' align='right'>店铺SEO标题<font color='red'>*</font>：</th>
             <td><input type='text' id='shopTitle' class="form-control rtc-ipt" value='<?php echo ($object["shopTitle"]); ?>' style='width:250px;' maxLength='25'/></td>
           </tr -->
           <tr>
             <th width='120' align='right'>店铺SEO关键字<font color='red'>*</font>：</th>
             <td><input type='text' id='shopKeywords' class="form-control rtc-ipt" value='<?php echo ($object["shopKeywords"]); ?>' style='width:350px;' maxLength='25'/></td>
           </tr>
           <tr>
	         <th width='120'>店铺SEO描述：</th>
	         <td colspan='3'>
	             <textarea rows="2" style='width:350px;' id='shopDesc' name='shopDesc' ><?php echo ($object["shopDesc"]); ?></textarea>
	         </td>
	      </tr>
           <tr style="height:80px">
             <th width='120' align='right' valign='top'>顶部广告：</th>
             <td>
             	<div id="preview_Filedata" style="margin-top: 70px;">
             		<img height="60" <?php if($object["shopBanner"] != ''): ?>src="/<?php echo ($object["shopBanner"]); ?>"<?php else: ?>src="/Apps/Home/View/default/images/s.gif"<?php endif; ?>/>
             	</div>
             </td>
           </tr>
           <tr>
             <th width='120' align='right'>滚动广告<font color='red'>*</font>：</th>
             <td>
             	
		       <div id='galleryImgs' class='rtc-gallery-imgs'>
		        
		       	 <?php if(count($object['shopAds']) == 0): ?><div id="wrapper">
                           <div id="container">
                           	<div style="" class="statusBar">
                            	图片大小:1400 x 350 (px)(格式为 gif, jpg, jpeg, png)
                    		</div>
                       
            <!--头部，相册选择和格式选择-->
                              <div id="uploader">
                               <div class="queueList">
                                   <div id="dndArea" class="placeholder">
                                      <div id="filePicker"></div>
                                      </div>
                                   <ul class="filelist"></ul>
                               </div>
                             <div class="statusBar" style="display:none">
                               <div class="progress">
                                    <span class="text">0%</span>
                                    <span class="percentage"></span>
                               </div>
                                    <div class="info"></div>
                               <div class="btns">
                                 <div id="filePicker2" class="webuploader-containe webuploader-container"></div><div class="uploadBtn state-finish">开始上传</div>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
               <?php else: ?>
               	<div id="wrapper">
                       <div id="container">
                       		<div style="border-bottom:1px solid #dadada;padding-left:10px; ">
                          		图片大小:1400 x 350 (px)(格式为 gif, jpg, jpeg, png)
                   			</div>
                          	<div id="uploader">
                             <div class="queueList">
                                 <div id="dndArea" class="placeholder element-invisible">
                                    <div id="filePicker" class="webuploader-container"></div>
                                    </div>
                                 <ul class="filelist">
                                 	<?php if(is_array($object['shopAds'])): $i = 0; $__LIST__ = $object['shopAds'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="border: 1px solid rgb(59, 114, 165)" order="100" onmouseover="imglimouseover(this)" onmouseout="imglimouseout(this)">
								       		<input type="hidden" class="gallery-img" iv="<?php echo ($vo["adImg_thumb"]); ?>" v="<?php echo ($vo["adImg"]); ?>" />
									       <img width="152" height="152" src="/<?php echo ($vo["adImg_thumb"]); ?>"><span class="setdef" style="display:none">默认</span><span class="setdel" onclick="imglidel(this)" style="display:none">删除</span>
								       	   <input class="gallery-img-url"  placeholder="广告路径" type="text" style="width:118px;" value="<?php echo ($vo["adUrl"]); ?>"/>
								       </li><?php endforeach; endif; else: echo "" ;endif; ?>
								       
                                 	
                                 </ul>
                            </div>
                            <div class="statusBar" style="">
                               <div class="progress">
                                    <span class="text"></span>
                                    <span class="percentage"></span>
                               </div>
                               <div class="info"></div>
                               <div class="btns">
                                  <div id="filePicker2" class="webuploader-containe webuploader-container"></div>
                                  <div class="uploadBtn state-finish">开始上传</div>
                               </div>
                            </div>
                        </div>
                    </div>
                 </div><?php endif; ?>
		       
		       </div>
		       <div style='clear:both;'></div>
             </td>
           </tr>
           <tr>
             <td colspan='2' style='text-align:center;padding:20px;'>
                 <button type="submit" class='rtc-btn-query'>保&nbsp;存</button>&nbsp;&nbsp;
                 <button type="button" class='rtc-btn-query' onclick='javascript:location.reload();'>重&nbsp;置</button>
             </td>
           </tr>
        </table>
       </form>
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