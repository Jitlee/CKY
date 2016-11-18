
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
	   params.shopPrice = $('#shopPrice').val();
	   params.goodsStock = $('#goodsStock').val();
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
	   
	   params.pointsid= $('#pointsid').val();
	   params.goodsId= $('#goodsId').val();
	   params.points= $('#points').val();
	   params.xiangou= $('#xiangou').val();
	   params.buyinfo= $('#buyinfo').val();
	   params.subtitle= $('#subtitle').val();
  
	   
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
	   $.post(Think.U('Admin/GoodsPoints/edit'),params,function(data,textStatus){
	   		layer.close(loading);
	   		var json = RTC.toJson(data);
			if(json.status=='1'){
				RTC.msg('操作成功!', {icon: 1}, function(){					
						location.href=Think.U('Admin/GoodsPoints/index');
				});
			}else{
				RTC.msg('操作失败!'+ data.key, {icon: 5});
			}			
	   });
} 
 
  
   
