
/**
 * 添加到购物车管理
 * @param {Object} pickerId
 * @param {Object} shop
 * @param {Object} goods
 */
function ShopCart(pickerId, shop, goods) {
	var shopId = shop.shopId;
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
	
	var hasCart = cart.shops[shopId] && cart.shops[shopId].goods[goods.goodsId];
	if(hasCart) {
		goodsCount = cart.shops[shopId].goods[goods.goodsId].count;
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
		var _shop = $.extend({}, shop);
		var _goods = $.extend({}, goods);
		_goods.count =  Number(num.text());
		if(cartType == "add") {
			// 添加到购物车
			if(!hasCart) {
				if(!cart.shops[shopId]) {
					cart.shops[shopId] = _shop;
					cart.shops[shopId].goods = {};
				}
				cart.shops[shopId].goods[goods.goodsId] = _goods;
			}
			
			// 保存购物车
			cky.storage.setItem(CACHE_KEY, cart);
			close();
		} else if(cartType == "direct") {
			// 直接购买，跳转到下单页面
			var directKey = "direct_" + new Date().getTime();
			var selectedCart ={
				shops: {}
			};
			selectedCart.shops[shopId] = _shop;
			_shop.goods = { };
			_shop.goods[goods.goodsId] = _goods;
			// 保存购物车
			cky.storage.setItem(directKey, selectedCart);
			window.location.href = "../Orders/shop.html?from=direct&shopId=" + shopId + "&submit=" + directKey;
			close();
		}
	}
	
	shopCart.open = onopen;
	shopCart.ok = onok;
}
