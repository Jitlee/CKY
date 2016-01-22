/**
 * 
 * @param {Number} shopId 商铺Id
 * @param {String} shopId 商铺名称
 * @param {Number} startMoney 起送费
 * @param {Number} freeMoney 免配送费
 * @param {Number} fastMoney 送餐费
 * @param {Number} costTime 平均送餐时间
 */
function FastCart(shopId, shopName, startMoney, freeMoney, fastMoney, costTime, startTime, endTime) {
	// 获取购物车列表
	var cart = cky.storage.getItem("fast-cart" + shopId) || {
		count: 0,
		total: 0,
		startMoney: startMoney,
		freeMoney: freeMoney,
		fastMoney: fastMoney,
		goods: {},
		cats: {}
	};
	
	cart.shopName = shopName;
	cart.startMoney = startMoney;
	cart.freeMoney = freeMoney;
	cart.fastMoney = fastMoney;
	cart.costTime = costTime;
	cart.startTime = startTime;
	cart.endTime = endTime;
	
	var vm = {
		num: ko.observable(cart.count),
		total: ko.observable(cart.total),
		fastMoney: ko.observable("免配送费"),
		startMoney: ko.observable("选好了"),
		ready: ko.observable(false),
		activeCss: ko.observable(""),
		ok: onok
	}
	ko.applyBindings(vm, document.getElementById("fastCart"));
	
	function refreshGoods() {
		// 初始化
		for(var goodsId in cart.goods) {
			var goods = cart.goods[goodsId];
			var element = $("#goods_" + goodsId);
			$(".reduce,.count", element).removeClass("cky-hidden");
			$(".count", element).text(goods.count);
		}
	}
	
	// 购物车 加号按钮
	$("#goodsList").on("click", "button.add", function(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		var $this = $(this);
		var parent = $this.parent();
		var goodsId = parent.attr("goodsId");
		var goodsName = parent.attr("goodsName");
		var shopCatId = parent.attr("shopCatId");
		var goodsThums = parent.attr("goodsThums");
		var goodsUnit = parent.attr("goodsUnit");
		var shopPrice = Number(parent.attr("shopPrice"));
		
		if(cart.cats[shopCatId]) {
			cart.cats[shopCatId]++;
		} else {
			cart.cats[shopCatId] = 1;
		}
		
		if(cart.goods[goodsId]) {
			// 有则数量加一
			cart.goods[goodsId].count++;
		} else { // 没有就创建一个
			cart.goods[goodsId] = {
				goodsId: goodsId,
				goodsName: goodsName,
				goodsThums: goodsThums,
				shopCatId: shopCatId,
				shopPrice: shopPrice,
				goodsUnit: goodsUnit,
				count: 1
			};
			
			// 显示减号和数量
			$(".reduce,.count", parent).removeClass("cky-hidden");
		}
		// 总价++;
		cart.total += shopPrice;
		cart.count++;
		// 数量对应
		$(".count", parent).text(cart.count);
		
		refreshCart();
	});
	
	$("#goodsList").on("click", "button.reduce", function(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		var $this = $(this);
		var parent = $this.parent();
		var goodsId = parent.attr("goodsId");
		var shopPrice = Number(parent.attr("shopPrice"));
		var shopCatId = parent.attr("shopCatId");
		
		if(cart.cats[shopCatId]) {
			cart.cats[shopCatId]--;
		}
		
		if(cart.goods[goodsId]) {
			cart.goods[goodsId].count--;
			if(cart.goods[goodsId].count == 0) {
				delete cart.goods[goodsId];
				$(".reduce,.count", parent).addClass("cky-hidden");
			}
		}
		cart.total -= shopPrice;
		cart.count--;
		$(".count", parent).text(cart.count);
		refreshCart();
	});
	
	function refreshCart() {
		vm.num(cart.count);
		vm.total(cart.total);
		vm.activeCss(cart.count == 0 ? "" : "cky-active");
		
		if(fastMoney == 0 || (cart.total >= freeMoney && freeMoney > 0)) {
			vm.fastMoney("免配送费");
		} else {
			vm.fastMoney("另需配送费 ¥ " + fastMoney);
		}
		
		if(cart.total >= startMoney) {
			vm.startMoney("选好了");
			vm.ready(true);
		} else {
			vm.startMoney("还差 ¥ " +  (startMoney - cart.total) + "起送");
			vm.ready(false);
		}
		cky.storage.setItem("fast-cart" + shopId, cart, 24 * 60 * 60); // 有效时间1天
		
		refreshCats();
	}
	
	function refreshCats() {
		if(cart.cats) {
			for(var catId in cart.cats) {
				var badge = $("#badge_cat_" + catId).text(cart.cats[catId]);
				cart.cats[catId] > 0 ? badge.show() : badge.hide();
			}
		}
	}
	
	this.refreshCats =  refreshCats;
	this.refreshGoods =  refreshGoods;
	
	refreshCart();
	refreshGoods();
	
	$("#fastCart").removeClass("cky-hidden");
	
	// 提交事件
	function onok() {
		window.location.href = "../Orders/index.html?shopId=" + shopId;
	}
}