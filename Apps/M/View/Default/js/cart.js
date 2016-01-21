
function ShopCart() {
	// 获取购物车列表
	var cart = cky.storage.getItem("shop-cart") || {
		count: 0,
		total: 0,
		shops: {}
	};
	
	
}
