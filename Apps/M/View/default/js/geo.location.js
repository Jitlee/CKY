$(function() {
	/**
	 * 米每经纬度 
	 */
	var METERS_PER_DEGREE = 111319.55;
	var geolocation = {
		getCurrentPosition: getCurrentPosition,
		distanceBetween:  distanceBetween,
		distanceToReadability: distanceToReadability
	};
	
	function getCurrentPosition(callback) {
		// 从缓存中取
		var geo = $.localStorage.getItem("geo");
		if(geo) {
			geo = JSON.parse(geo);
			var now = new Date().getTime();
			if(geo && now - geo.__time < 5 * 60 * 1000) { // 有效期5分钟
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
					$.localStorage.setItem("geo", JSON.stringify(evt));
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
	
	/**
	 * 求两点之间的距离 
	 */
	function distanceBetween(p1, p2) {
		return Math.sqrt(Math.pow(p1.x - p2.x, 2) + Math.pow(p1.x - p2.x, 2)) * METERS_PER_DEGREE;
	}
	
	function distanceToReadability(meters) {
		meters = Math.round(meters);
		if(meters > 1000 && meters < 10000) {
			return (meters / 1000).toFixed(1) + "公里";
		} else if(meters >= 10000 && meters < 1000 * 1000) {
			return Math.round(meters / 1000) + "公里";
		} else {
			return "";
		}
		return meters + "米";
	}
	
	window.geolocation = geolocation;
});