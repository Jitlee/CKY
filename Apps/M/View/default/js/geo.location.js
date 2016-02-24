$(function() {
	/**
	 * 米每经纬度 
	 */
	var METERS_PER_DEGREE = 111319.55;
	var tasks = []; // 缓存任务列表
	var _geo = null;
	var isBuzying = false;
	var geolocation = {
		getCurrentPosition: getCurrentPosition,
		distanceBetween:  distanceBetween,
		distanceToReadability: distanceToReadability,
		METERS_PER_DEGREE: METERS_PER_DEGREE
	};
	
	function getCurrentPosition(ak, callback) {
		if(_geo) {
			callback(_geo);
			return;
		}
		tasks.push(callback);
		if(isBuzying) {
			return;
		}
		
		isBuzying = true;
		
		// 从缓存中取
		var geo = cky.storage.getItem("geo");
		if(geo) {
			console.info("使用缓存定位数据");
			_geo = geo;
			isBuzying = false;
			run();
			return;
		}
		
		// 浏览器定位
		var options={
			enableHighAccuracy:true, 
			maximumAge:1000
		};
		if(navigator.geolocation){
			//浏览器支持geolocation
			navigator.geolocation.getCurrentPosition(function(evt) {
				console.info("浏览器定位成功");
				var lng =evt.coords.longitude;//经度
				var lat = evt.coords.latitude; //纬度
				geocoder(ak, lat, lng, function(evt) {
					evt.__time = new Date().getTime();
					// 缓存结果
					cky.storage.setItem("geo", evt, 300); // 有效期5分钟
					_geo = evt;
					isBuzying = false;
					run();
				});
			}, function() {
				
			},options);
		} else{
			//浏览器不支持geolocation
			
		}
	}
	
	function geocoder(ak, lat, lng, callback) {
		var api = "http://api.map.baidu.com/geocoder/v2/?ak={1}&location={2},{3}&output=json&pois=0";
		api = util.format(api, ak, lat, lng);
		$.post(api, null, function(evt) {
			if(evt && evt.status == 0) {
				console.info("百度定位成功");
				$.getJSON('../Areas/getCityCode.html', {
					areaName: evt.result.addressComponent.city
				}, function(areaId) {
					evt.result.areaId = areaId;
					callback(evt.result);	
				})
			} else {
				console.error("百度定位API失败");
				callback({ location: { lat: lat, lng: lng } });
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
	
	function run() {
		var task = tasks.shift();
		if(typeof task == "function") {
			task.call(_geo, _geo.location.lng, _geo.location.lat, _geo.addressComponent.city, _geo.areaId);
			run();
		}
	}
	
	window.geolocation = geolocation;
});