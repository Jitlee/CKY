$(function() {
	// 获取购物车列表
	var carts = cartcky.storage.getItem("fast-cart") || { count: 0, total: 0, goods: {} };
	
	// 购物车 加号按钮
	$(".mui-content").on("click", "button.add", function() {
		var $this = $(this);
		var parent = $this.parent();
		var goodsId = $this.attr("goodsId");
		var goodsName = $this.attr("goodsName");
		var shopCatId = $this.attr("shopCatId");
		var goodsThums = $this.attr("goodsThums");
		var shopPrice = Number($this.attr("shopPrice"));
		if(carts.goods[goodsId]) {
			// 有则数量加一
			carts.goods[goodsId].count++;
			carts.count++;
		} else { // 没有就创建一个
			carts.goods[goodsId] = {
				goodsId: goodsId,
				goodsName: goodsName,
				goodsThums: goodsThums,
				shopCatId: shopCatId,
				shopPrice: shopPrice,
				count: 1
			};
			
			// 显示减号和数量
			$(".reduce,.count", $parent).show();
		}
		// 总价++;
		carts.total += shopPrice;
		// 数量对应
		$(".count", $parent).text(carts.count);
	});
	
	$(".mui-content").on("click", "button.reduce", function() {
		
	});
});