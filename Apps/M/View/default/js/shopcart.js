
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
	
	goods.shopPrice = Number(goods.shopPrice);
	goods.count = Number(goods.count);
	
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
		calcFreeMoney(_goods);
		if(cartType == "add") {
			// 添加到购物车
			if(!cart.shops[shopId]) {
				cart.shops[shopId] = _shop;
				cart.shops[shopId].goods = {};
			}
			cart.shops[shopId].goods[goods.goodsId] = _goods;
			
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

cky.addToShopCart = function(goods) {
	var CACHE_KEY = "cky-shop-cart";
	var cart = cky.storage.getItem(CACHE_KEY) || { shops: {} };
	var shopId = goods.shopId;
	var shopName = goods.shopName;
	var goodsId = goods.goodsId;
	
	goods.shopPrice = Number(goods.shopPrice);
	
	if(!cart.shops[shopId]) {
		cart.shops[shopId] = {
			shopId: shopId,
			shopName: shopName,
			goods: {}
		};
	}
	
	var _goods = cart.shops[shopId].goods[goodsId];
	if(!cart.shops[shopId].goods[goodsId]) {
		cart.shops[shopId].goods[goodsId] = goods;
		cart.shops[shopId].goods[goodsId].count = 0;
	} else {
		_goods.activeId = goods.activeId;
		_goods.activeType = goods.activeType;
		_goods.activeAmount = Number(goods.activeAmount);
	}
	_goods.count++;
	calcFreeMoney(_goods);
	cky.storage.setItem(CACHE_KEY, cart);
}
	
function calcFreeMoney(goods) {
	var freeMoney = 0;
	var freeTitle = "";
	if(goods.activeId) {
		switch(goods.activeType) {
			case "m2f1": // 买二付一
				freeMoney = Math.floor(goods.count / 2.0) * goods.shopPrice;
				freeTitle = "买二付一";
				if(goods.count%2 == 1) {
					freeTitle += "(还差一件)";
				}
				break;
			case "m2mustless": // 第二件立减
				if(goods.activeAmount > 0) {
					freeMoney = goods.count > 1 ? goods.activeAmount : 0;
					freeTitle = "第二件立减" + goods.activeAmount;
					if(goods.count < 2) {
						freeTitle += "(还差一件)";
					}
				}
				break;
		}
	}
	goods.freeMoney = freeMoney;
	goods.freeTitle = freeTitle;
}
