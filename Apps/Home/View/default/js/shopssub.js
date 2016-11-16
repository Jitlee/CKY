function editShopsSub(){
	   var params = {};
	   params.id = $('#id').val();
	   params.shopName = $('#shopName').val();
	   params.userName = $('#userName').val();
	   params.shopImg = $('#shopImg').val();
	   params.shopTel = $('#shopTel').val();
	   params.shopAddress = $('#shopAddress').val();
	   var loading = layer.load('正在提交商品分类信息，请稍后...', 3);
	   $.post(Think.U('Home/ShopsSub/edit'),params,function(data,textStatus){
		   layer.close(loading);
			var json = RTC.toJson(data);
			if(json.status=='1'){
				RTC.msg('操作成功!', {icon: 1}, function(){
				   location.href= Think.U('Home/ShopsSub/index');
				});
			}else{
				layer.msg('操作失败!', {icon: 5});
			}
	   });
}
function delShopsSub(id){
	layer.confirm("您确定要删除该商品分类吗？",{icon: 3, title:'系统提示'},function(tips){
		layer.load('正在处理，请稍后...', 3);
		layer.close(tips);
		$.post(Think.U('Home/ShopsSub/del'),{id:id},function(data,textStatus){
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

function queryShopsSub(){
	var shopName = $('#shopName').val();
	location.href= Think.U('Home/ShopsSub/index','shopName='+goodsName);
}