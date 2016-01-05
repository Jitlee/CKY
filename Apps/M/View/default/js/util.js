util = { 
	// 格式化字符串
	format: function(str) {
		for (var i = 1, len = arguments.length; i < len; i++) {       
			var reg = new RegExp("\\{" + i + "\\}", "gm");             
		    str = str.replace(reg, arguments[i]);
		}
  		return str;
	}
}