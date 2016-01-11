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
	
	/**
	 * body滚动到底部时触发 
	 * @param {Function} callback 回调函数
	 * @param {Number} threshold 阀值，默认为200
	 * @return {Object} 句柄
	 */
	onScrollEnd: function(callback, threshold) {
		var threshold = threshold || 200;
		var timeHandler = null;
		var onscrollend = function() {
			if ($(window).scrollTop() + $(window).height() + threshold > $(document).height()) {
				console.info("滚动到了底部");
				window.clearTimeout(timeHandler);
       			timeHandler = window.setTimeout(callback, 300);
			}
		}
		$(document).bind("scroll", onscrollend);
		return {
			destory: function() {
				$(document).unbind("scroll", onscrollend);
			}
		};
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
	}
}