
/**
 * 添加到购物车管理
 * @param {Object} pickerId
 * @param {Object} shopId
 * @param {Object} goods
 */
function ShopCart(pickerId, shopId, goods) {
	var shopCart = this;
	// 获取购物车列表
	var CACHE_KEY = "cky-shop-cart";
	var root = $("#" + pickerId).click(onclose);
	var body = $(".cky-goods-picker-body", root);
	var goodsCount = 1;
	var cardType = null; // [add|direct]  add是添加到购物车，direct是直接购买
	var num = $(".num", root).text(goodsCount);
	var cart = cky.storage.getItem(CACHE_KEY) || {
		shops: {}
	};
	
	var hasCart = cart.shops[shopId] && cart.shops[shopId][goods.goodsId];
	if(hasCart) {
		goodsCount = cart.shops[shopId][goods.goodsId].count;
		num.text(goodsCount);
	}
	
	$(".jian", root).click(function(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		var $this = $(this);
		var cnt = Number(num.text()) - 1;
		if(cnt > 0) {
			num.text(cnt);
		}
	});
	
	$(".jia", root).click(function(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		var $this = $(this);		
		var cnt = Number(num.text()) + 1;
		if(cnt <= goods.goodsStock) {
			num.text(cnt);
		}
	});
	
	/**
	 * 
	 * @param {String} type = [add|direct]  add是添加到购物车，direct是直接购买
	 */
	function onopen(type) {
		cartType = type;
		root.fadeIn(300, null);
		window.setTimeout(function() {
			body.addClass("cky-active");
		}, 0);
	}
	
	function onclose(evt) {
		var ele = $(evt.target);
		if(ele.hasClass("cky-goods-picker")) {
			close();
		}
	}
	
	function close() {
		body.removeClass("cky-active");
		root.fadeOut(300, null);
	}
	
	function onok() {
		if(cartType == "add") {
			// 添加到购物车
			if(!hasCart) {
				if(cart.shops[shopId]) {
					cart.shops[shopId] = {};
				}
				cart.shops[shopId] = { };
				cart.shops[shopId][goods.goodsId] = goods;
			}
			cart.shops[shopId][goods.goodsId].count = Number(num.text());
			
			// 保存购物车
			cky.storage.setItem(CACHE_KEY, cart);
		} else if(cartType == "direct") {
			// 直接购买，跳转到下单页面
		}
		close();
	}
	
	shopCart.open = onopen;
	shopCart.ok = onok;
}
