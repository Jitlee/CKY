$(function() {
	// 浏览器定位
	var options={
		enableHighAccuracy:true, 
		maximumAge:1000
	};
	if(navigator.geolocation){
		//浏览器支持geolocation
		navigator.geolocation.getCurrentPosition(onsuccess, onerror,options);
	} else{
		//浏览器不支持geolocation
	}
	
	function onsuccess(evt) {
    		var lon =evt.coords.longitude;//经度
    		var lat = evt.coords.latitude; //纬度
    		geocoder(lon, lat);
	}
	
	geocoder(114.21, 22.532);
	
	function onerrror(evt) {
		console.log(evt);
	}
	
	function geocoder(lon, lat) {
		var ak = "87AHNGUkZCHGFPsr9Aq213vx";
		var api = "http://api.map.baidu.com/geocoder/v2/?ak={1}&callback=renderReverse&location={2},{3}&output=json&pois=0";
		api = util.format(api, ak, lat, lon);
		$.getJSON(api, null, function(evt) {
			alert(JSON.stringify(evt));
		}, "jsonp");
	}
});