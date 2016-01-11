<?php

if (!function_exists('curl_init')) {
  throw new Exception('Pingpp needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Pingpp needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
  throw new Exception('Pingpp needs the Multibyte String PHP extension.');
}

require(dirname(__FILE__) . '/lib/WxPay.Api.php');

require(dirname(__FILE__) . '/lib/WxPay.Config.php');
require(dirname(__FILE__) . '/lib/WxPay.Data.php');
require(dirname(__FILE__) . '/lib/WxPay.Exception.php');
require(dirname(__FILE__) . '/lib/WxPay.Notify.php');