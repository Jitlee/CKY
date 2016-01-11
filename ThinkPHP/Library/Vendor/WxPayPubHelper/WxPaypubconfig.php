<?php

/**
 * 	配置账号信息
 */

class WxPayConf_pub {
	
	
	static public $APPID;
	static public $APPSECRET;
	static public $MCHID;
	static public $KEY;
	static public $JS_API_CALL_URL;
	static public $CURL_TIMEOUT;
	static public $SSLCERT_PATH;
	static public $SSLKEY_PATH;
	static public $NOTIFY_URL;
	static public $RETURN_URL;
	
	public function __construct($wxpayconfig = array()) {
		
//		self::$APPID = $wxpayconfig['APPID'];
//		self::$APPSECRET = $wxpayconfig['APPSECRET'];
//		self::$MCHID = $wxpayconfig['MCHID'];
//		self::$KEY = $wxpayconfig['KEY'];
//		self::$JS_API_CALL_URL = $wxpayconfig['JS_API_CALL_URL'];
//		self::$CURL_TIMEOUT = $wxpayconfig['CURL_TIMEOUT'];
//		self::$SSLCERT_PATH = $wxpayconfig['SSLCERT_PATH'];
//		self::$SSLKEY_PATH = $wxpayconfig['SSLKEY_PATH'];
//		self::$NOTIFY_URL = $wxpayconfig['NOTIFY_URL'];
//		self::$RETURN_URL = $wxpayconfig['returnurl'];
		self::$APPID = "wx426b3015555a46be";
		self::$APPSECRET = "01c6d59a3f9024db6336662ac95c8e74";
		self::$MCHID ="1292995201";
		self::$KEY = "e10adc3949ba59abbe56e057f20f883e";
		self::$JS_API_CALL_URL = "http://cky.ritacc.net/index.php/M/Pay/wxpay";
		self::$CURL_TIMEOUT = 30;
		self::$SSLCERT_PATH = "/ThinkPHP/Library/Vendor/WxPayPubHelper/cacert/apiclient_cert.pem";
		self::$SSLKEY_PATH = "/ThinkPHP/Library/Vendor/WxPayPubHelper/cacert/apiclient_key.pem";
		self::$NOTIFY_URL = "http://cky.ritacc.net/index.php/M/Pay/notify";
		self::$RETURN_URL = "http://cky.ritacc.net/index.php/M/Pay/wxpay";
		
	}
	
	// =======【基本信息设置】=====================================
	// 微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	/*
	const APPID = 'wx426b3015555a46be';
	// 受理商ID，身份标识
	const MCHID = '1292995201'; // 测试
	// 商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = 'e10adc3949ba59abbe56e057f20f883e';
	// JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = '01c6d59a3f9024db6336662ac95c8e74';
	
	// =======【JSAPI路径设置】===================================
	// 获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	const JS_API_CALL_URL = '/index.php/M/Pay/wxpay';
	
	// =======【证书路径设置】=====================================
	// 证书路径,注意应该填写绝对路径
	const SSLCERT_PATH = '';
	const SSLKEY_PATH = '';
	const SSLCA_PATH = '';
	
	// =======【异步通知url设置】===================================
	// 异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = 'http://cky.ritacc.net/index.php/M/Pay/notify';
	
	// =======【curl超时设置】===================================
	// 本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
	 * */
}

?>