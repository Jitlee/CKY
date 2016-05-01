function initconfig(signPackage)
{
	
	wx.config({
	      debug: true,
	      appId: signPackage.appId,
	      timestamp: signPackage.timestamp,
	      nonceStr: signPackage.nonceStr,
	      signature: signPackage.signature,
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
$(document).ready(function(){

	$.getJSON("/index.php/M/Wx/getsharekey", {v:1.100100}, function(data) {
		alert(data.appId);
		  alert(data.timestamp);
		  alert(data.nonceStr);
		  $("#sing").val(data.signature);
		  alert(data.signature);
		initconfig(data);
	});
	
 
wx.ready(function () {	
  // 2. 分享接口
  // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
//document.querySelector('#onMenuShareAppMessage').onclick = function () {
//  wx.onMenuShareAppMessage({
//    title: '移动互联网交流学习',
//    desc: '在长大的过程中，我才慢慢发现，我身边的所有事，别人跟我说的所有事，那些所谓本来如此，注定如此的事，它们其实没有非得如此，事情是可以改变的。更重要的是，有些事既然错了，那就该做出改变。',
//    link: 'http://bbs.weiwangvip.com/',
//    imgUrl: 'http://bbs.weiwangvip.com/data/attachment/forum/201501/09/135516s99ll77cl5p86p0o.jpg',
//    trigger: function (res) {
//      alert('用户点击发送给朋友');
//    },
//    success: function (res) {
//      alert('已分享');
//    },
//    cancel: function (res) {
//      alert('已取消');
//    },
//    fail: function (res) {
//      alert(JSON.stringify(res));
//    }
//  });
//  alert('已注册获取“发送给朋友”状态事件');
//};

  // 2.2 监听“分享到朋友圈”按钮点击、自定义分享内容及分享结果接口
//document.querySelector('#onMenuShareTimeline').onclick = function () {
//  wx.onMenuShareTimeline({
//    title: '移动互联网交流学习',
//    link: 'http://bbs.weiwangvip.com/',
//    imgUrl: 'http://bbs.weiwangvip.com/data/attachment/forum/201501/09/135516s99ll77cl5p86p0o.jpg',
//    trigger: function (res) {
//      alert('用户点击分享到朋友圈');
//    },
//    success: function (res) {
//      alert('已分享');
//    },
//    cancel: function (res) {
//      alert('已取消');
//    },
//    fail: function (res) {
//      alert(JSON.stringify(res));
//    }
//  });
//  alert('已注册获取“分享到朋友圈”状态事件');
//};

  // 2.3 监听“分享到QQ”按钮点击、自定义分享内容及分享结果接口
//document.querySelector('#onMenuShareQQ').onclick = function () {
//  wx.onMenuShareQQ({
//    title: '移动互联网交流学习',
//    desc: '在长大的过程中，我才慢慢发现，我身边的所有事，别人跟我说的所有事，那些所谓本来如此，注定如此的事，它们其实没有非得如此，事情是可以改变的。更重要的是，有些事既然错了，那就该做出改变。',
//    link: 'http://bbs.weiwangvip.com/',
//    imgUrl: 'http://bbs.weiwangvip.com/data/attachment/forum/201501/09/135516s99ll77cl5p86p0o.jpg',
//    trigger: function (res) {
//      alert('用户点击分享到QQ');
//    },
//    complete: function (res) {
//      alert(JSON.stringify(res));
//    },
//    success: function (res) {
//      alert('已分享');
//    },
//    cancel: function (res) {
//      alert('已取消');
//    },
//    fail: function (res) {
//      alert(JSON.stringify(res));
//    }
//  });
//  alert('已注册获取“分享到 QQ”状态事件');
//};
  
  // 2.4 监听“分享到微博”按钮点击、自定义分享内容及分享结果接口
//document.querySelector('#onMenuShareWeibo').onclick = function () {
//  wx.onMenuShareWeibo({
//    title: '移动互联网交流学习',
//    desc: '在长大的过程中，我才慢慢发现，我身边的所有事，别人跟我说的所有事，那些所谓本来如此，注定如此的事，它们其实没有非得如此，事情是可以改变的。更重要的是，有些事既然错了，那就该做出改变。',
//    link: 'http://bbs.weiwangvip.com/',
//    imgUrl: 'http://bbs.weiwangvip.com/data/attachment/forum/201501/09/135516s99ll77cl5p86p0o.jpg',
//    trigger: function (res) {
//      alert('用户点击分享到微博');
//    },
//    complete: function (res) {
//      alert(JSON.stringify(res));
//    },
//    success: function (res) {
//      alert('已分享');
//    },
//    cancel: function (res) {
//      alert('已取消');
//    },
//    fail: function (res) {
//      alert(JSON.stringify(res));
//    }
//  });
//  alert('已注册获取“分享到微博”状态事件');
//};

	//获取位置
//	document.querySelector('#getLocation').onclick = function () {
//	    wx.getLocation({
//		    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
//		    success: function (res) {
//		        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
//		        var longitude = res.longitude ; // 经度，浮点数，范围为180 ~ -180。
//		        var speed = res.speed; // 速度，以米/每秒计
//		        var accuracy = res.accuracy; // 位置精度
//		        alert(latitude+',longitude='+longitude);
//		    }
//		});
//}; 
  
  wx.onMenuShareAppMessage(shareData);
  wx.onMenuShareQQ(shareData);
  	wx.onMenuShareWeibo(shareData);
  wx.onMenuShareTimeline(shareData);
  
});

wx.error(function (res) {
  alert("wx.error:"+res.errMsg);
});

 
});