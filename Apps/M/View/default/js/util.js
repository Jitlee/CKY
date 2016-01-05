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
	 * @param {Number} threshold 阀值，默认为50
	 * @return {Object} 句柄
	 */
	onScrollEnd: function(callback, threshold) {
		var threshold = threshold || 100;
		var timeHandler = null;
		var onscrollendfunction = function() {
			window.clearTimeout(timeHandler);
			if ($(window).scrollTop() + $(window).height() + threshold > $(document).height()) {
       			var timeHandler = window.setTimeout(callback, 300);
			}
		}
		$(document).bind("scroll", onscrollend);
		return {
			destory: function() {
				$(document).unbind("scroll", onscrollend);
			}
		};
	}
}