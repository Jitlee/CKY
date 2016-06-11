function initconfig()
{	
	wx.config({
	      debug: false,
	      appId: 		signPackage.appId,
	      timestamp: 	signPackage.timestamp,
	      nonceStr: 	signPackage.nonceStr,
	      signature: 	signPackage.signature,
	      jsApiList: [
	        'checkJsApi',
	        'onMenuShareTimeline',
	        'onMenuShareAppMessage',
	        'onMenuShareQQ',
	        'onMenuShareWeibo',
	        'hideMenuItems',
	        'showMenuItems',        
	        'chooseImage',
	        'previewImage',
	        'uploadImage',        
	        'getNetworkType',
	        'openLocation',
	        'getLocation'
	      ]
 	});
}
function geocoder(ak, lat, lng, callback) {
	var api = "http://api.map.baidu.com/geocoder/v2/?ak={1}&location={2},{3}&output=json&pois=0";
	api = util.format(api, ak, lat, lng);
	$.post(api, null, function(evt) {
		//alert(evt.result.addressComponent.city);
		if(evt && evt.status == 0) {
			console.info("百度定位成功");
			callback(lat,lng, evt.result.addressComponent.city );

		} else {
			console.error("百度定位API失败");
			callback({ location: { lat: lat, lng: lng } });
		}
	}, "jsonp");
}

$(document).ready(function(){
	
	initconfig();
		
	wx.ready(function () {	 
		//获取位置
		if(signPackage.Location && signPackage.Location==1)
		{
		     wx.getLocation({
			    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
			    success: function (res) {
			        //获取详细地址
			        geocoder(signPackage.ak, res.latitude, res.longitude, wxext.getlocation);
			    },
			    cancel: function (res) {
			         alert('用户拒绝授权获取地理位置');
			    }
			});
		}
			
		var defultimg='http://www.cukayun.cn/Public/images/cuka.jpg?ee20160502';
		if(shareData.imgUrl=='')
		{
		 	shareData.imgUrl=defultimg;
		}
		wx.onMenuShareAppMessage(shareData);
		wx.onMenuShareQQ(shareData);
		wx.onMenuShareWeibo(shareData);
		wx.onMenuShareTimeline(shareData);
	  
	});
	
	wx.error(function (res) {
	  //alert("wx.error:"+res.errMsg);
	});

 

 
});

/******位置相关函数*******/
$(function() {
	/**
	 * 米每经纬度 
	 */
	var METERS_PER_DEGREE = 111319.55;
	var tasks = []; // 缓存任务列表
	var _geo = null;
	var isBuzying = false;
	var geolocation = {
		distanceBetween:  distanceBetween,
		distanceToReadability: distanceToReadability,
		METERS_PER_DEGREE: METERS_PER_DEGREE
	};
	 
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
			if(_geo) {
				task.call(_geo, _geo.location.lng, _geo.location.lat, _geo.addressComponent.city, _geo.areaId);
			} else {
				task.call();
			}
			run();
		}
	}
	
	window.geolocation = geolocation;
});