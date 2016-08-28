<?php
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 跳去商家后台
 */ 

phpinfo();


//signature=&echostr=8606863142644776687&timestamp=&nonce=457808951

//signature=dc018b4353eeae73adf93671b1b26b3dc80a6b70&echostr=7121319455003471327&timestamp=1468425427&nonce=1147517608
//458fe0053e8ac779aa3f8d60a803cefe503ff8a5

/*
		$signature = '05f5fd86c118c8b96cd70e24d99060d2488f0fc9';
        $timestamp = '1468425427';
        $nonce = '1147517608';
		 
        $token = "123";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1($tmpStr );
		
		echo $tmpStr;
 * 
 */
// echo $_SERVER['SERVER_NAME'];  
// if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
//      sae_set_display_errors(false);
//      sae_debug($log_content);
//      sae_set_display_errors(true);
//  }else{ //LOCAL
//      $max_size = 500000;
//		
//		 $log_filename = './log/'.date('Y-m-d').'log.xml';
//      if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
//      file_put_contents($log_filename, date('Y-m-d H:i:s').$log_content." ", FILE_APPEND);
//  }
 
 echo "wx";

?>