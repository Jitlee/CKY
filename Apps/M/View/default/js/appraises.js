
function addGoodsAppraises(shopId,goodsId,goodsAttrId,orderId){
	var goodsScore = $('.'+goodsId+'_'+goodsAttrId+'_goodsScore > input[name="score"]').val();
	if(goodsScore==0){
		cky.toast('请选择商品评分 !');
		return;
	}
	
	var timeScore = $('.'+goodsId+'_'+goodsAttrId+'_timeScore > input[name="score"]').val();
	if(timeScore==0){
		cky.toast('请选择时效得分 !');
		return;
	}
	
	var serviceScore = $('.'+goodsId+'_'+goodsAttrId+'_serviceScore > input[name="score"]').val();
	if(serviceScore==0){
		cky.toast('请选择服务得分 !');
		return;
	}
	
	var content = $.trim($('#'+goodsId+'_'+goodsAttrId+'_content').val());
	if(content.length<3 || content.length>50){
		cky.toast('评价内容为3-50个字 !');
		return;
	}
	
	//layer.confirm('您确定要提交该评价吗？',{icon: 3, title:'系统提示'}, function(tips){
	    var ll = layer.open({type: 2});
		$.post(Think.apprurl,{shopId:shopId, goodsId:goodsId, goodsAttrId:goodsAttrId,orderId:orderId, goodsScore:goodsScore, timeScore:timeScore, serviceScore:serviceScore, content:content }
		,function(data,textStatus){
			//layer.close(tips);
			layer.close(ll);
			var json = data;//RTC.toJson(data);
			if(json.status==1){
				$('#'+goodsId+'_'+goodsAttrId+'_appraise').slideUp();
				$('#'+goodsId+'_'+goodsAttrId+'_appraise').empty();
				var itemstatus=$('.'+goodsId+'_'+goodsAttrId+'_status');
				itemstatus.html('评价成功');
				itemstatus.removeClass("c1");
				itemstatus.addClass("c2");
			}else if(json.status==-1){
				cky.toast(json.msg);
			}else{
				cky.toast('评价失败，请刷新后再重试 !');
			}
		});
	//});
}