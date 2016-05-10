
 


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
	        'getLocation',        
	        'openProductSpecificView',
	        'addCard',
	        'chooseCard',
	        'openCard'
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
//				$.getJSON('../Areas/getCityCode.html', {
//					areaName: evt.result.addressComponent.city
//				}, function(areaId) {
//					evt.result.areaId = areaId;
//					callback(evt.result);	
//				})
			} else {
				console.error("百度定位API失败");
				callback({ location: { lat: lat, lng: lng } });
			}
		}, "jsonp");
}

$(document).ready(function(){

//	$.getJSON("http://cky.ritacc.net/index.php/M/Wx/getsharekey", {v: '1.100100'}, function(data) {
//		signPackage=new Object();
//		signPackage.appId=data.appId;
//		signPackage.timestamp=data.timestamp;
//		signPackage.nonceStr=data.nonceStr;
//		signPackage.signature=data.signature; 
		initconfig();
//	});
	
 
wx.ready(function () {
	 
		//获取位置
		//document.querySelector('#getLocation').onclick = function () {
//			alert(signPackage.ak);
		if(signPackage.Location && signPackage.Location==1)
		{
		     wx.getLocation({
			    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
			    success: function (res) {
//			        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
//			        var longitude = res.longitude ; // 经度，浮点数，范围为180 ~ -180。
//			        var speed = res.speed; // 速度，以米/每秒计
//			        var accuracy = res.accuracy; // 位置精度
			       
//			        var  Location =new Object();
//			        Location.x=res.latitude;
//			        Location.y=res.longitude;
			        //获取详细地址
			        geocoder(signPackage.ak, res.latitude, res.longitude, wxext.getlocation);
//			        if(wxext.getlocation)
//			        {
//			        	// alert('bbbb=latitude1='+latitude+',longitude='+longitude);
//			        	//wxext.getlocation(Location);
//			        }
			    },
			    cancel: function (res) {
			         alert('用户拒绝授权获取地理位置');
			    }
			});
		}
		//}; 
	 
	
	
	
	
  var defultimg='http://cky.ritacc.net/Public/images/cuka.jpg?ee20160502';
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
  alert("wx.error:"+res.errMsg);
});

 

 
});