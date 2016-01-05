$(function() {
	var geolocation = {
		getCurrentPosition: getCurrentPosition
	};
	
	function getCurrentPosition(callback) {
		// 从缓存中取
		var geo = $.localStorage.getItem("geo");
		
		if(geo && geo.addressComponent) {
			var now = new Date().getTime();
			if(now - geo.__time < 5 * 60 * 1000) { // 有效期5分钟
				console.info("使用缓存定位数据");
				callback(geo);
				return;
			}
		}
		
		// 浏览器定位
		var options={
			enableHighAccuracy:true, 
			maximumAge:1000
		};
		if(navigator.geolocation){
			//浏览器支持geolocation
			navigator.geolocation.getCurrentPosition(function(evt) {
				console.log("浏览器定位成功");
				var lng =evt.coords.longitude;//经度
				var lat = evt.coords.latitude; //纬度
				geocoder(lat, lng, function(evt) {
					evt.__time = new Date().getTime();
					// 缓存结果
					$.localStorage.setItem("geo", evt);
					callback(evt);
				});
			}, function() {
				callback();
			},options);
		} else{
			//浏览器不支持geolocation
			callback();
		}
	}
	
	function geocoder(lat, lng, callback) {
		var ak = "87AHNGUkZCHGFPsr9Aq213vx";
		var api = "http://api.map.baidu.com/geocoder/v2/?ak={1}&location={2},{3}&output=json&pois=0";
		api = util.format(api, ak, lat, lng);
		$.post(api, null, function(evt) {
			if(evt && evt.status == 0) {
				callback(evt.result);
			} else {
				console.error("百度定位API失败");
				callback({
					location: {
						lat: lat,
						lng: lng
					}
				});
			}
		}, "jsonp");
	}
	
	window.geolocation = geolocation;
});