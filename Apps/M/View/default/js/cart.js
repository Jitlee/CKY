$(function(){
	var DEFAULT_CACHE_KEY = "cky-shop-cart";
	// 加载购物车数量
	$.refreshCartCount = function() {
		$(".cky-cart-sum").each(function(i, e) {
			var $this = $(this);
			var cacheKey = $this.data("cachekey") || DEFAULT_CACHE_KEY;
			var cart = cky.storage.getItem(cacheKey);
			if(cart) {
				var sum = 0;
				for(var shopId in cart.shops) {
					var shop = cart.shops[shopId];
					if(shop && shop.goods) {
						for(var goodsId in shop.goods) {
							var goods = shop.goods[goodsId];
							sum += goods.count * Number(goods.shopPrice);
						}
					}
				}
				if(sum > 0) {
					if(sum > 10000) {
						$this.removeClass("cky-hidden").text("¥"+Math.round(sum/1000)/10 + "万");
					} else {
						$this.removeClass("cky-hidden").text("¥"+Math.round(sum * 100) / 100);
					}
					$this.removeClass("cky-hidden");
				} else {
					$this.addClass("cky-hidden");
				}
			}
		});
	}
	$.refreshCartCount();

	// 添加按钮
	$("body").on("click", ".cky-cart-fly-btn", function() {
		var $this = $(this);
		var shop = {
			shopId: $this.attr("shopId"),
			shopName: $this.attr("shopName"),
			shopThums: $this.attr("shopthums"),
			provideBox: Number($this.attr("providebox")),
			boxMoney: Number($this.attr("boxmoney")),
			deliveryMoney: Number($this.attr("deliverymoney")),
			deliveryFreeMoney: Number($this.attr("deliveryfreemoney")),
			serviceStartTime: $this.attr("serviceStartTime"),
			serviceEndTime: $this.attr("serviceEndTime")
		};
		var goods = {
			goodsId: $this.attr("goodsid"),
			goodsName: $this.attr("goodsname"),
			goodsThums: $this.attr("goodsthums"),
			shopPrice: $this.attr("shopprice"),
			activeId: $this.attr("activeId"),
			activeType: $this.attr("activeType"),
			activeAmount: $this.attr("activeAmount")
		};
		
		var cacheKey = $this.data("cachekey") || DEFAULT_CACHE_KEY;
		var cart = cky.storage.getItem(cacheKey) || { shops: {} };
		if(cart.shops[shop.shopId]) {
			$.extend(cart.shops[shop.shopId], shop);
			shop.goods = cart.shops[shop.shopId].goods;
		} else {
			cart.shops[shop.shopId] = shop;
			shop.goods = {};
		}
		if(shop.goods[goods.goodsId]) {
			$.extend(shop.goods[goods.goodsId], goods);
			shop.goods[goods.goodsId].count++;
		} else {
			shop.goods[goods.goodsId] = goods;
			shop.goods[goods.goodsId].count = 1;
		}
		calcFreeMoney(shop.goods[goods.goodsId])
		cky.storage.setItem(cacheKey, cart);
		$.refreshCartCount();
	});
	
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
});
