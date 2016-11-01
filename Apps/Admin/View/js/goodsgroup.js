
function checkAll(obj){
	$('.chk').each(function(){
		$(this)[0].checked = obj.checked;
	})
}
function getChks(){
	var ids = [];
	$('.chk').each(function(){
		if($(this)[0].checked)ids.push($(this).val());
	});
	return ids.join(',');
}

/****************************商品操作**************************/
function queryUnSaleByPage(){
	var shopCatId1 = $('#shopCatId1').val();
	var shopCatId2 = $('#shopCatId2').val();
	var goodsName = $('#goodsName').val();
	location.href= Think.U('Home/Goods/queryUnSaleByPage','goodsName='+goodsName+"&shopCatId1="+shopCatId1+"&shopCatId2="+shopCatId2); 
}
function queryOnSale(){
	var shopCatId1 = $('#shopCatId1').val();
	var shopCatId2 = $('#shopCatId2').val();
	var goodsName = $('#goodsName').val();
	location.href= Think.U('Home/Goods/queryOnSaleByPage','goodsName='+goodsName+"&shopCatId1="+shopCatId1+"&shopCatId2="+shopCatId2); 
}
function queryPendding(){
	var shopCatId1 = $('#shopCatId1').val();
	var shopCatId2 = $('#shopCatId2').val();
	var goodsName = $('#goodsName').val();
	location.href= Think.U('Home/Goods/queryPenddingByPage','goodsName='+goodsName+"&shopCatId1="+shopCatId1+"&shopCatId2="+shopCatId2); 
}
function toEditGoods(id,menuId){
	location.href= Think.U('Home/Goods/toEdit','umark='+menuId+"&id="+id); 
}
function toViewGoods(id){
	$.post(Think.U('Home/Goods/getGoodsVerify'),{id:id},function(data,textStatus){
		var json = RTC.toJson(data);
		if(json.status=='1'){
			var verifyCode = json.verifyCode;
			window.open(Think.U('Home/Goods/getGoodsDetails','goodsId='+id+"&kcode="+verifyCode));
		}
	});
	
}
function delGoods(id){
	layer.confirm("您确定要删除该商品？",{icon: 3, title:'系统提示'},function(tips){
		$.post(Think.U('Home/Goods/del'),{id:id},function(data,textStatus){
			layer.close(tips);
    		var json = RTC.toJson(data);
    		if(json.status=='1'){
    			RTC.msg('操作成功！', {icon: 1},function(){
    				location.reload();
    			});
    		}else{
    			RTC.msg('操作失败', {icon: 5});
    		}
		});
	});
}
function batchDel(){
	layer.confirm("您确定要删除这些商品？",{icon: 3, title:'系统提示'},function(){
	      var ids = getChks();
	      var loading = layer.load('正在处理，请稍后...', 3);
	      var params = {};
	      params.ids = ids;
	      $.post(Think.U('Home/Goods/batchDel'),params,function(data,textStatus){
	    		var json = RTC.toJson(data);
	    		if(json.status=='1'){
	    			RTC.msg('操作成功！', {icon: 1},function(){
	    				location.reload();
	    			});
	    		}else{
	    			layer.close(loading);
	    			RTC.msg('操作失败', {icon: 5});
	    		}
	     });
	});
}
function sale(v){
	var ids = getChks();
	if(ids==''){
		RTC.msg('请先选择商品!', {icon: 5});
		return;
	}
	layer.confirm((v==1)?"您确定要上架这些商品？":"您确定要下架这些商品？",{icon: 3, title:'系统提示'},function(tips){
	    var loading = layer.load('正在处理，请稍后...', 3);
	    layer.close(tips);
	    var params = {};
	    params.ids = ids;
	    params.isSale= v;
	    $.post(Think.U('Home/Goods/sale'),params,function(data,textStatus){
	    	layer.close(loading);
	    	var json = RTC.toJson(data);
	    	if(json.status=='1'){
	    		RTC.msg('操作成功！', {icon: 1},function(){
	    			location.reload();
	    		});
	    	}else if(json.status=='-2'){
	    		RTC.msg('上架失败，请核对商品信息是否完整!', {icon: 5});
	    	}else if(json.status=='2'){
	    		RTC.msg('已成功上架商品'+json.num+" 件，请核对未能上架的商品信息是否完整。", {icon: 5},function(){
	    			location.reload();
	    		});
	    	}else if(json.status=='-3'){
	    		RTC.msg('上架商品失败!您的商家权限不能出售商品，如有疑问请与平台管理员联系。', {icon: 5,time:3000});
	    	}else{
	    		RTC.msg('操作失败!', {icon: 5});
	    	}
	    });
	});
}
function goodsSet(type,umark){
	var ids = getChks();
	if(ids==''){
		RTC.msg('请先选择商品!', {icon: 5});
		return;
	}

	layer.load('正在处理，请稍后...', 3);
	var params = {};
	params.ids = ids;
	params.code= type;
	$.post(Think.U('Home/Goods/goodsSet'),params,function(data,textStatus){
	    var json = RTC.toJson(data);
	    if(json.status=='1'){
	    	RTC.msg('操作成功！', {icon: 1},function(){
	    		location.reload();
	    	});
	    }else{
	    	RTC.msg('操作失败!', {icon: 5});
	    }
	});
}

function getShopCatListForGoods(v,id){
	   var params = {};
	   params.id = v;
	   $('#shopCatId2').empty();
	   var html = [];
	   $.post(Think.U('Home/ShopsCats/queryByList'),params,function(data,textStatus){
		    html.push('<option value="">请选择</option>');
			var json = RTC.toJson(data);
			if(json.status=='1' && json.list){
				var opts = null;
				for(var i=0;i<json.list.length;i++){
					opts = json.list[i];
					html.push('<option value="'+opts.catId+'" '+((id==opts.catId)?'selected':'')+'>'+opts.catName+'</option>');
				}
			}
			$('#shopCatId2').html(html.join(''));
	   });
}
$.fn.imagePreview = function(options){
	var defaults = {}; 
	var opts = $.extend(defaults, options);
	var t = this;
	xOffset = 5;
	yOffset = 20;
	if(!$('#preview')[0])$("body").append("<div id='preview'><img width='200' src=''/></div>");
	$(this).hover(function(e){
		   $('#preview img').attr('src',domainURL+ "/" +$(this).attr('img'));      
		   $("#preview").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px").show();      
	  },
	  function(){
		$("#preview").hide();
	}); 
	$(this).mousemove(function(e){
		   $("#preview").css("top",(e.pageY - xOffset) + "px").css("left",(e.pageX + yOffset) + "px");
	});
}
function getShopCatListForEdit(v,id){
	   var params = {};
	   params.id = v;
	   $('#shopCatId2').empty();
	   if(v==0){
		   $('#shopCatId2').html('<option value="">请选择</option>');
		   return;
	   }
	   var html = [];
	   $.post(Think.U('Home/ShopsCats/queryByList'),params,function(data,textStatus){
		    html.push('<option value="">请选择</option>');
			var json = RTC.toJson(data);
			if(json.status=='1' && json.list){
				var opts = null;
				for(var i=0;i<json.list.length;i++){
					opts = json.list[i];
					html.push('<option value="'+opts.catId+'" '+((id==opts.catId)?'selected':'')+'>'+opts.catName+'</option>');
				}
			}
			$('#shopCatId2').html(html.join(''));
	   });
}
function getBrands(catId){
	var v = $('#brandId').attr('dataVal');
	var params = {};
	params.catId = catId;
	$('#brandId').empty();
	var html = [];
	$('#brandId').append('<option value="0">请选择</option>');
	$.post(Think.U('Home/Brands/queryBrandsByCat'),params,function(data,textStatus){
		var json = RTC.toJson(data);
		if(json.status=='1' && json.list){
			for(var i=0;i<json.list.length;i++){
				opts = json.list[i];
				$('#brandId').append('<option value="'+opts.brandId+'" '+((v==opts.brandId)?'selected':'')+'>'+opts.brandName+'</option>');
			}
		}
	})
}
function getCatListForEdit(objId,parentId,t,id){
	   var params = {};
	   params.id = parentId;
	   $('#'+objId).empty();
	   if(t<1){
//		   $('#goodsCatId3').empty();
//		   $('#goodsCatId3').html('<option value="">请选择</option>');
		   getBrands(parentId);
	   }
	   var html = [];
	   $.post(Think.U('Home/GoodsCats/queryByList'),params,function(data,textStatus){
		    html.push('<option value="">请选择</option>');
			var json = RTC.toJson(data);
			if(json.status=='1' && json.list){
				var opts = null;
				for(var i=0;i<json.list.length;i++){
					opts = json.list[i];
					html.push('<option value="'+opts.catId+'" '+((id==opts.catId)?'selected':'')+'>'+opts.catName+'</option>');
				}
			}
			$('#'+objId).html(html.join(''));
	   });
}
function editGoods(menuId){		
	   var params = {};
	   params.id = $('#id').val();
	   
	   
	   params.goodsSn = $('#goodsSn').val();
	   params.goodsName = $('#goodsName').val();
	   params.goodsImg = $('#goodsImg').val();
	   params.goodsThumbs = $('#goodsThumbs').val();
	   params.marketPrice = $('#marketPrice').val();
	   params.shopPrice =1;// $('#shopPrice').val();
	   params.goodsStock =0;// $('#goodsStock').val();
	   params.brandId = $('#brandId').val();
	   params.goodsUnit ="次";// $('#goodsUnit').val();
	   params.goodsSpec = $('#goodsSpec').val();
	   params.goodsCatId1 = $('#goodsCatId1').val();
	   params.goodsCatId2 = $('#goodsCatId2').val();
	   params.goodsCatId3 =0;// $('#goodsCatId3').val();
	   params.shopCatId1 =0;// $('#shopCatId1').val();
	   params.shopCatId2 = 0;//$('#shopCatId2').val();
//	   params.isSale = $('input[name="isSale"]:checked').val();
	   params.isNew = $('input[name="isNew"]:checked').val();;
	   params.isBest = $('input[name="isBest"]:checked').val();;
	   params.isHot = $('input[name="isHot"]:checked').val();;
	   params.isRecomm = $('input[name="isRecomm"]:checked').val();;
	   params.goodsDesc = $('#goodsDesc').val();
	   params.goodsKeywords ="";// $('#goodsKeywords').val();
	   
	   //扩展字段	
	  var date = $('#groupTimeRange').val().split(' 至 ');
	   params.groupGoodsId= $('#groupGoodsId').val();
	   params.goodsId= $('#goodsId').val();
	   params.groupPrice= $('#groupPrice').val();
	   params.groupPreNumbers= $('#groupPreNumbers').val();
	   params.groupMaxNumbers= $('#groupMaxNumbers').val();
	   params.groupLimitHours= $('#groupLimitHours').val();
	   params.groupStartTime= date[0];
	   params.groupEndTime= date[1];
	   
  
	   
	   var gallery = [];
	   $('.gallery-img').each(function(){
		   gallery.push($(this).attr('v')+'@'+$(this).attr('iv'));
	   });
	   if(params.goodsDesc==''){
		   RTC.msg('请输入商品描述!', {icon: 5});
		   return;
	   }
	   if(params.goodsImg==''){
		   RTC.msg('请上传商品图片!', {icon: 5});
		   return;
	   }
	   params.gallery = gallery.join(',');
	   var loading = layer.load('正在提交商品信息，请稍后...', 3);	   
	   $.post(Think.U('Admin/GoodsGroup/edit'),params,function(data,textStatus){
	   		layer.close(loading);
	   		var json = RTC.toJson(data);
			if(json.status=='1'){
				RTC.msg('操作成功!', {icon: 1}, function(){					
						location.href=Think.U('Admin/GoodsGroup/index');
				});
			}else{
				RTC.msg('操作失败!'+ data.key, {icon: 5});
			}			
	   });
}
//function getAttrList(catId){
//	$('#priceContainer').hide();
//	$('#attrContainer').hide();
//	$('#priceConent').empty();
//	$('#attrConent').empty();
//	if(catId==0){
//		$('.hiddenPriceAttr').attr('dataId',0);
//		$('.hiddenPriceAttr').attr('dataNo',0);
//		$('.hiddenPriceAttr').val('');
//		$('#goodsStock').attr('disabled',false);
//	}
//	$.post(Think.U('Home/Attributes/getAttributes'),{catId:catId},function(data,textStatus){
//		 var json = RTC.toJson(data);
//		 var priceAttr = null;
//		 if(json.status=='1' && json.list){
//			var opts = null;
//			for(var i=0;i<json.list.length;i++){
//				opts = json.list[i];
//				if(opts.isPriceAttr==1){
//					priceAttr = opts;
//				}else{
//					addAttr(opts);
//					$('#attrContainer').show();
//				}
//			}
//		 }
//		 if(priceAttr){
//			 $('.hiddenPriceAttr').attr('dataId',priceAttr.attrId);
//			 $('.hiddenPriceAttr').attr('dataNo',0);
//			 $('.hiddenPriceAttr').val(priceAttr.attrName);
//			 addPriceAttr();
//			 $('#priceContainer').show();
//			 $('#goodsStock').attr('disabled',true);
//		 }
//	});
//}
//function addPriceAttr(){
//	var goodsPriceNo = $('.hiddenPriceAttr').attr('dataNo');
//	goodsPriceNo++;
//	var obj = {attrId:$('.hiddenPriceAttr').attr('dataId'),attrName:$('.hiddenPriceAttr').val()};
//	var html = [];
//	html.push('<tr id="attr_'+goodsPriceNo+'"><td style="text-align:right">'+obj.attrName+'：</td>');
//	html.push('<td><input type="text" id="price_name_'+obj.attrId+'_'+goodsPriceNo+'" /></td>');
//	html.push('<td><input type="text" id="price_price_'+obj.attrId+'_'+goodsPriceNo+'" value="0" onblur="checkAttPrice('+obj.attrId+','+goodsPriceNo+');" onkeypress="return RTC.isNumberdoteKey(event)" onkeyup="javascript:RTC.isChinese(this,1)" maxLength="10"/></td>');
//	html.push('<td><input type="radio" onclick="checkAttPrice('+obj.attrId+','+goodsPriceNo+');" id="price_isRecomm_'+obj.attrId+'_'+goodsPriceNo+'" name="price_isRecomm"/></td>');
//	html.push('<td><input type="text" id="price_stock_'+obj.attrId+'_'+goodsPriceNo+'" onblur="getTstock();" value="100" onkeypress="return RTC.isNumberKey(event)" onblur="javascript:statGoodsStaock()" onkeyup="javascript:RTC.isChinese(this,1)" maxLength="25"/></td>');
//	if(goodsPriceNo==1){
//		html.push('<td><a title="新增" class="add btn" href="javascript:addPriceAttr()"></a></td>');
//	}else{
//	    html.push('<td><a title="删除" class="del btn" href="javascript:delPriceAttr('+goodsPriceNo+')"></a></td></tr>');
//	}
//	$('.hiddenPriceAttr').attr('dataNo',goodsPriceNo);
//	$('#priceConent').append(html.join(''));
//	statGoodsStaock();
//	getTstock();
//}
//
//function checkAttPrice(attrId,goodsPriceNo){
//	if($("#price_isRecomm_"+attrId+"_"+goodsPriceNo).is(":checked")){
//		$("#shopPrice").val($.trim($("#price_price_"+attrId+"_"+goodsPriceNo).val()));
//	}
//}

//function delPriceAttr(v){
//	$('#attr_'+v).remove();
//	statGoodsStaock();
//	getTstock();
//}

function getTstock(){
	var tstock = 0;
	$("input[id^=price_stock_]").each(function(){
		tstock = tstock+parseInt($(this).val());
	});
	$("#goodsStock").val(tstock);
}

function statGoodsStaock(){
	var goodsPriceNo = $('.hiddenPriceAttr').attr('dataNo');
	var attrId = $('.hiddenPriceAttr').attr('dataId');
	var totalStock = 0;
	for(var i=0;i<=goodsPriceNo;i++){
		if(document.getElementById('price_stock_'+attrId+"_"+i)){
			totalStock = totalStock +parseInt($.trim($('#price_stock_'+attrId+'_'+i).val()),10);
		}
	}
	$('#goodsStock').val(totalStock);
}
//function addAttr(obj){
//	var html = [];
//	html.push("<tr id='attr_"+obj.attrId+"'>");
//	html.push("<th style='width:80px;text-align:right' nowrap>"+obj.attrName+"：</th><td>");
//	if(obj.attrType==0){
//		html.push("<input type='text' style='width:70%;' dataId='"+obj.attrId+"' class='attrList'/>");
//	}else if(obj.attrType==1){
//		if(obj.opts && obj.opts.txt){
//			html.push("<input type='hidden' dataType='"+obj.attrType+"' dataId='"+obj.attrId+"' class='attrList'>");
//	        for(var i=0;i<obj.opts.txt.length;i++){
//	        	html.push("<label><input type='checkbox' name='attrTxtChk_"+obj.attrId+"' value='"+obj.opts.txt[i]+"'/>"+obj.opts.txt[i]+"</label>&nbsp;&nbsp;");
//	        }
//		}
//      html.push("</select>");
//	}else if(obj.attrType==2){
//		html.push("<select class='attrList' id='attr_name_"+obj.attrId+"' dataId='"+obj.attrId+"'>");
//      if(obj.opts && obj.opts.txt){
//			for(var i=0;i<obj.opts.txt.length;i++){
//	        	html.push("<option value='"+obj.opts.txt[i]+"'>"+obj.opts.txt[i]+"</option>");
//	        }
//      }
//      html.push("</select>");
//	}
//	html.push("</td></tr>");
//	$('#attrConent').append(html.join(''));
//}

/*****************************商品分类***********************************/
function editGoodsCat(){
	   var params = {};
	   params.id = $('#id').val();
	   params.parentId = $('#parentId').val();
	   params.catName = $('#catName').val();
	   params.isShow = $('input[name="isShow"]:checked').val();;
	   params.catSort = $('#catSort').val();
	   var loading = layer.load('正在提交商品分类信息，请稍后...', 3);
	   $.post(Think.U('Home/ShopsCats/edit'),params,function(data,textStatus){
		   layer.close(loading);
			var json = RTC.toJson(data);
			if(json.status=='1'){
				RTC.msg('操作成功!', {icon: 1}, function(){
				   location.href= Think.U('Home/ShopsCats/index');
				});
			}else{
				layer.msg('操作失败!', {icon: 5});
			}
	   });
}
function delGoodsCat(id){
	layer.confirm("您确定要删除该商品分类吗？",{icon: 3, title:'系统提示'},function(tips){
		layer.load('正在处理，请稍后...', 3);
		layer.close(tips);
		$.post(Think.U('Home/ShopsCats/del'),{id:id},function(data,textStatus){
			var json = RTC.toJson(data);
			if(json.status=='1'){
				RTC.msg('操作成功!', {icon: 1}, function(){
					location.reload();
				});
			}else{
				RTC.msg('操作失败!', {icon: 5});
			}
		});
	})
}
function editGoodsCatName(obj){
	var name = $('#');
	$.post(Think.U('Home/ShopsCats/editName'),{id:$(obj).attr('dataId'),catName:obj.value},function(data,textStatus){
		var json = RTC.toJson(data);
		if(json.status=='1'){
			RTC.msg('操作成功!',{icon: 1,time:500});
		}else{
			RTC.msg('操作失败!', {icon: 5});
		}
	});
}
function editGoodsCatSort(obj){
	var name = $('#');
	$.post(Think.U('Home/ShopsCats/editSort'),{id:$(obj).attr('dataId'),catSort:obj.value},function(data,textStatus){
		var json = RTC.toJson(data);
		if(json.status=='1'){
			RTC.msg('操作成功!',{icon: 1,time:500});
		}else{
			RTC.msg('操作失败!', {icon: 5});
		}
	});
}
function loadGoodsCatChildTree(obj,pid,objId){
	    var showhtml = "<span class='rtc-state_yes'></span>";
	    var hidehtml = "<span class='rtc-state_no'></span>";
		var str = objId.split("_");
		level = (str.length-2);
		if($(obj).hasClass('rtc-tree-open')){
			$(obj).removeClass('rtc-tree-open').addClass('rtc-tree-close');
			$('tr[class^="'+objId+'"]').hide();
		}else{
			$(obj).removeClass('rtc-tree-close').addClass('rtc-tree-open');
			$('tr[class^="'+objId+'"]').show();
		}
}
/*****************商品评价**************************/
function queryAppraises(){
	var shopCatId1 = $('#shopCatId1').val();
	var shopCatId2 = $('#shopCatId2').val();
	var goodsName = $('#goodsName').val();
	location.href= Think.U('Home/GoodsAppraises/index','umark=GoodsAppraises&goodsName='+goodsName+"&shopCatId1="+shopCatId1+"&shopCatId2="+shopCatId2);
}
function getShopCatListForAppraises(v,id){
	   var params = {};
	   params.id = v;
	   $('#shopCatId2').empty();
	   var html = [];
	   $.post(Think.U('Home/ShopsCats/queryByList'),params,function(data,textStatus){
		    html.push('<option value="">请选择</option>');
			var json = RTC.toJson(data);
			if(json.status=='1' && json.list){
				var opts = null;
				for(var i=0;i<json.list.length;i++){
					opts = json.list[i];
					html.push('<option value="'+opts.catId+'" '+((id==opts.catId)?'selected':'')+'>'+opts.catName+'</option>');
				}
			}
			$('#shopCatId2').html(html.join(''));
	   });
}
 
   
function initTime(objId,val){
	for(var i=0;i<24;i++){
		$('<option value="'+i+'" '+((val==i)?"selected":'')+'>'+i+':00</option>').appendTo($('#'+objId));
		$('<option value="'+(i+".5")+'" '+((val==(i+".5"))?"selected":'')+'>'+i+':30</option>').appendTo($('#'+objId));
	}
}
function isInvoce(v){
	if(v){
		$('#invoiceRemarkstr').show();
	}else{
		$('#invoiceRemarkstr').hide();
	}
} 
 
/***************商品类型****************/
function editAttrCats(type,src){
	var catName = $.trim($('#catName').val());
	if(catName==''){
		RTC.msg('请输入商品类型名称!', {icon: 5});
		return;
	}
	var loading = layer.load('正在处理，请稍后...', 3);
	$.post(Think.U('Home/AttributeCats/edit'),{umark:src,catName:catName,id:$('#id').val()},function(data,textStatus){
		layer.close(loading);
		var json = RTC.toJson(data);
		if(json.status=='1'){
			RTC.msg('操作成功!', {icon: 1}, function(){
				if(type==1){
					$('#myform')[0].reset();
				}else{
				    location.href=Think.U('Home/AttributeCats/index');
				}
			});
		}else{
			RTC.msg('操作失败!', {icon: 5});
		}
	});
}
function delAttrCat(id){
	layer.confirm("您确定要删除该商品类型及其下的属性吗？",{icon: 3, title:'系统提示'},function(tips){
	    var loading = layer.load('正在处理，请稍后...', 3);
	    layer.close(tips);
	    var params = {};
	    $.post(Think.U('Home/AttributeCats/del'),{id:id},function(data,textStatus){
	    	layer.close(loading);
	    	var json = RTC.toJson(data);
	    	if(json.status=='1'){
	    		RTC.msg('操作成功！', {icon: 1},function(){
	    			location.reload();
	    		});
	    	}else{
	    		RTC.msg('操作失败!', {icon: 5});
	    	}
	    });
	});
}
/***********商品属性************/
function getAttrsForAttr(){
	location.href=Think.U("Home/Attributes/index",'catId='+$('#catId').val());
}
function toAddAttr(){
	var attrNoForAttr = $('#catId').attr('dataNo');
	attrNoForAttr++
	var html = [];
	html.push("<tr id='tr_"+attrNoForAttr+"' dataId='0'><td>&nbsp;</td>");
	html.push("<td><input type='text' id='attrName_"+attrNoForAttr+"'/></td>");
	html.push("<td><input type='radio' name='isPriceAttr' id='isPriceAttr_"+attrNoForAttr+"' onclick='javascript:chkPriceAttrForAttr()' id='isPriceAttr_"+attrNoForAttr+"'></td>");
	html.push("<td><select id='attrType_"+attrNoForAttr+"' onchange='javascript:changeAttrTypeForAttr("+attrNoForAttr+")'><option value='0'>输入框</option/><option value='1'>多选项</option/><option value='2'>下拉项</option/></select>");
    html.push("</td>");
    html.push("<td><input type='text' id='attrContent_"+attrNoForAttr+"' style='width:300px;display:none'/></td>");
    html.push("<td><input type='text' id='attrSort_"+attrNoForAttr+"'/></td>");
    html.push("<td>");
    html.push("<a href='javascript:delAttrs("+attrNoForAttr+",0)' class='btn del' title='删除'></a>");
    html.push("</td>");
    html.push("</tr>");
    $('#tbody').append(html.join(''));
    $('#catId').attr('dataNo',attrNoForAttr);
    $('.rtc-btn-query').show();
}
function changeAttrTypeForAttr(v){
	if($('#attrType_'+v).val()==0){
		$('#attrContent_'+v).hide();
	}else{
		$('#attrContent_'+v).show();
	}
}
function chkPriceAttrForAttr(){
	var attrNoForAttr = $('#catId').attr('dataNo');
	for(var i=0;i<attrNoForAttr;i++){
		if(!document.getElementById('tr_'+i))continue;
		if($('#isPriceAttr_'+i)[0].checked){
			$('#attrType_'+i).hide();
			$('#attrContent_'+i).hide();
		}else{
			$('#attrType_'+i).show();
			if($('#attrType_'+i).val()==1 || $('#attrType_'+i).val()==2){
				$('#attrContent_'+i).show();
			}
		}
	}
}
function editAttrs(){
	var attrNoForAttr = $('#catId').attr('dataNo');
	var params = {}
	params.catId = $('#catId').val();
	params.no = attrNoForAttr;
	for(var i=0;i<=attrNoForAttr;i++){
		if(!document.getElementById('tr_'+i))continue;
		params['id_'+i] = $('#tr_'+i).attr('dataId');
		var isPriceAttr = $('#isPriceAttr_'+i)[0].checked?1:0;
		params['isPriceAttr_'+i] = isPriceAttr;
		params['attrName_'+i] = $.trim($('#attrName_'+i).val());
		if(params['attrName_'+i]==''){
			RTC.msg('请输入属性名称!', {icon: 5});
			$('#attrName_'+i).focus();
			return;
		}
		params['attrType_'+i] = $('#attrType_'+i).val();
		params['attrContent_'+i] = $.trim($('#attrContent_'+i).val());
		if((params['attrType_'+i]==1 || params['attrType_'+i]==2) && isPriceAttr==0  && params['attrContent_'+i]==''){
			RTC.msg('请输入属性选项值!', {icon: 5});
			$('#attrContent_'+i).focus();
			return;
		}
		params['attrSort_'+i] = $.trim($('#attrSort_'+i).val());
	}
	var loading = layer.load('正在处理，请稍后...', 3);
	$.post(Think.U('Home/attributes/edit'),params,function(data,textStatus){
		layer.close(loading);
		var json = RTC.toJson(data);
		if(json.status=='1'){
			RTC.msg('操作成功!', {icon: 1}, function(){
				location.href=Think.U('Home/Attributes/index','catId='+$('#catId').val());
			});
		}else{
			RTC.msg('操作失败!', {icon: 5});
		}
	});
}
function delAttrs(no,id){
	if(id>0){
		layer.confirm("您确定要删除该商品属性吗？",{icon: 3, title:'系统提示'},function(tips){
		    var loading = layer.load('正在处理，请稍后...', 3);
		    layer.close(tips);
		    var params = {};
		    $.post(Think.U('Home/Attributes/del'),{id:id},function(data,textStatus){
		    	layer.close(loading);
		    	var json = RTC.toJson(data);
		    	if(json.status=='1'){
		    		RTC.msg('操作成功！', {icon: 1},function(){
		    			location.reload();
		    		});
		    	}else{
		    		RTC.msg('操作失败!', {icon: 5});
		    	}
		    });
		});
	}else{
		$('#tr_'+no).remove();
	}
} 

function toEditGoodsBase(fv,goodsId,flag){	
	if((fv==2 || fv==3) && flag==1){
		RTC.msg('该商品存在商品属性，不能直接修改，请进入编辑页修改', {icon: 5});
		return;
	}else{
		$("#ipt_"+fv+"_"+goodsId).show();
		$("#span_"+fv+"_"+goodsId).hide();
		$("#ipt_"+fv+"_"+goodsId).focus();
		$("#ipt_"+fv+"_"+goodsId).val($("#span_"+fv+"_"+goodsId).html());
	}
	
}
   
