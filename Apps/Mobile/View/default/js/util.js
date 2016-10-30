util = { 
	/**
	 * 格式化字符串 
	 * @param {String} str
	 */
	format: function(str) {
		for (var i = 1, len = arguments.length; i < len; i++) {       
			var reg = new RegExp("\\{" + i + "\\}", "gm");             
		    str = str.replace(reg, arguments[i]);
		}
  		return str;
	},
	
	/*
	 * 将分钟转换成时间格式
	 */
	formatMuites: function(muites) {
		var _hours = Math.floor(muites / 60);
		var _muites = Math.round(muites % 60);
		var result = [];
		if(_hours < 10) {
			result.push("0");
		}
		result.push(_hours);
		result.push(":");
		if(_muites < 10) {
			result.push("0");
		}
		result.push(_muites);
		return result.join("");
	},
	
	/**
	 * 格式化动态时间，今天就写
	 * @param {Number} time 毫秒
	 */
	formatDynamicTime: function(time) {
		
	},
	
	/**
	 * body滚动到底部时触发 
	 * @param {Function} callback 回调函数
	 * @param {Number} threshold 阀值，默认为200
	 * @return {Object} 句柄
	 */
	onScrollEnd: function(callback, selector, threshold) {
		var threshold = threshold || 200;
		var handler = 0;
		var parent = $(".mui-content").length == 0 ? $(document.body) : $(".mui-content");
		if(selector) {
			parent = $(selector);
		}
		
		var onscrollend = function() {
			if(!parent.data("isScrollInBuzy")) {
				if ($(window).scrollTop() + $(window).height() + threshold > $(document).height()) {
					parent.data("isScrollInBuzy", true);
					console.info("滚动到了底部");
					
					if($(".cky-up-refresh", selector).length == 0) {
						$("<div class=\"cky-up-refresh\" stytle=\"display:none\"></div>").appendTo(parent);
					}
					
					$(".cky-up-refresh", selector).show();
					callback();
					
					util.__scrollHandler = window.setTimeout(function() {
						if(parent.data("isScrollInBuzy")) {
							util.endScroll(selector);
						}
					}, 3000);
				}
			}
		}
//		$(document).bind("scroll", function() {
//			window.clearTimeout(handler);
//			handler = window.setTimeout(onscrollend, 300);
//		});
		
		var x1 = 0, y1 = 0;
		var moveHandler = 0;
		parent.bind("touchstart",function(evt) {
			window.clearTimeout(moveHandler);
			if (parent.scrollTop() + parent.height() + threshold > parent.height()) {
				x2 = x1 = evt.originalEvent.touches[0].pageX;
				y2 = y1 = evt.originalEvent.touches[0].pageY;
//				console.info("x1: " + x1 + ", y1: " + y1);
				parent.bind("touchmove", ontouchmove);
				parent.bind("touchend", ontouchend);
//				moveHandler = window.setTimeout(function() {
//					if(y2 - y1 > 80 && Math.abs(x1 - x2) < 60) {
//						onscrollend();
//					}
//				}, 300);
			}
		});
		
		function ontouchmove(evt) {
			x2 = evt.originalEvent.touches[0].pageX;
			y2 = evt.originalEvent.touches[0].pageY;
//			console.info("x2: " + (x2 - x1) + ", y2: " + (y2 - y1));
			if(y2 - y1 < -80 && Math.abs(x1 - x2) < 60) {
//				evt.stopPropagation();
//				evt.preventDefault();
//				window.clearTimeout(moveHandler);
				onscrollend();
				ontouchend();
			}
		}
		
		function ontouchend() {
			parent.unbind("touchmove", ontouchmove);
			parent.unbind("touchend", ontouchend);
		}
		
		
		
		
//		//Keep track of how many swipes
//		var count=0;
//		//Enable swiping...
//		parent.swipe( {
//			//Single swipe handler for left swipes
//			swipeUp:function(event, direction, distance, duration, fingerCount) {
//				 onscrollend();
//			},
//			//Default is 75px, set to 0 for demo so any distance triggers swipe
//			threshold:0
//		});
		
		return this;
	},
	
	endScroll: function(selector) {
		var parent = $(".mui-content").length == 0 ? $(document.body) : $(".mui-content");
		if(selector) {
			parent = $(selector);
		}
		
		if(util.__scrollHandler > 0) {
			window.clearTimeout(util.__scrollHandler);
			util.__scrollHandler = 0;
		}
		parent.data("isScrollInBuzy", false);
		$(".cky-up-refresh", selector).hide();
	},
	
	
	/**
	 * 获取url参数
	 * @param {String} 参数名字
	 * @param {String} defaultValue 默认值
	 */
	i: function(name, defaultValue) {
		var url = window.location.href;
		var match = new RegExp("[\?&]" + name + "\=([^&]+)").exec(url);
		if(match && match.length > 1) {
			return decodeURIComponent(match[1]);
		}
		return defaultValue;
	},
	
	/**
	 * 掩码手机号码 138****8888
	 * @param {Object} phone
	 */
	maskPhone: function(phone) {
		if(phone && phone.replace) {
			return phone.replace(/^(\d{3})\d{4}(\d+)$/, "$1****$2");
		}
		return phone;
	},
	/**
	 * 消息提醒
	 * @param {Object} 提醒消息
	 */
	msg: function(msg) {
		layer.open({
		    content: msg,
		    style: 'background-color:#09C1FF; color:#fff; border:none;',
		    time: 5
		});
	}
}