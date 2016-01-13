$(function() {
	$(".cky-back").click(function() {
		window.history.back();		
	});
});

var cky = {
	/**
	 * 扩展包装html5storage, 每一项都可以设置失效时间(s)
	 */
	storage: {
		getItem: function(key) {
			var data = $.localStorage.getItem(key);
			if(data) {
				data = JSON.parse(data);
				var expires = data.expires;
				var time = data.time;
				if(expires > 0 && time > 0) {
					var now = new Date().getTime();
					if(now - time < expires * 1000) {
						return data.item;
					}
				}
				return data;
			}
			return null;
		},
		setItem: function(key, item, expires) {
			var data = item;
			if(expires > 0) {
				data = {};
				data.item = item;
				data.expires = expires;
				data.time = new Date().getTime();
			}
			$.localStorage.setItem(key, JSON.stringify(data));
		}
	}	
};
