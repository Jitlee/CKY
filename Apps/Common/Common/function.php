<?php

/**
 * 判断是否手机访问
 */
function RTCIsMobile() {
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
    $mobile_browser = '0';  
    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
       $mobile_browser++;  
    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
       $mobile_browser++;  
    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
       $mobile_browser++;  
    if(isset($_SERVER['HTTP_PROFILE']))  
       $mobile_browser++;  
       $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
       $mobile_agents = array(  
		    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
		    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
		    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
		    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
		    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
		    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
		    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
		    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
		    'wapr','webc','winw','winw','xda','xda-'
	   );  
    if(in_array($mobile_ua, $mobile_agents))$mobile_browser++;  
    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)$mobile_browser++;  
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)$mobile_browser=0;  
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)$mobile_browser++;  
    if($mobile_browser>0){  
       return true;  
    }else{
       return false;
    }
}

/**
 * 邮件发送函数
 * @param string to      要发送的邮箱地址
 * @param string subject 邮件标题
 * @param string content 邮件内容
 * @return array
 */
function RTCSendMail($to, $subject, $content) {
	require_cache(VENDOR_PATH."PHPMailer/class.smtp.php");
    require_cache(VENDOR_PATH."PHPMailer/class.phpmailer.php");
    $mail = new PHPMailer();
    // 装配邮件服务器
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = $GLOBALS['CONFIG']['mailSmtp'];
    $mail->SMTPAuth = $GLOBALS['CONFIG']['mailAuth'];
    $mail->Username = $GLOBALS['CONFIG']['mailUserName'];
    $mail->Password = $GLOBALS['CONFIG']['mailPassword'];
    $mail->CharSet = 'utf-8';
    // 装配邮件头信息
    $mail->From = $GLOBALS['CONFIG']['mailUserName'];
    $mail->AddAddress($to);
    $mail->FromName = $GLOBALS['CONFIG']['mailSendTitle'];
    $mail->IsHTML(true);
    // 装配邮件正文信息
    $mail->Subject = $subject;
    $mail->Body = $content;
    // 发送邮件
    $rs =array();
    if (!$mail->Send()) {
    	$rs['status'] = 0;
    	$rs['msg'] = $mail->ErrorInfo;
        return $rs;
    } else {
    	$rs['status'] = 1;
        return $rs;
    }
}
/**
 * 发送短信
 * 此接口要根据不同的短信服务商去写，这里只是一个参考
 * @param string $phoneNumer  手机号码
 * @param string $content     短信内容
 */
function RTCSendSMS($phoneNumer,$content){
	$url = 'http://223.4.21.214:8180/service.asmx/SendMessage?Id='.$GLOBALS['CONFIG']['smsOrg']."&Name=".$GLOBALS['CONFIG']['smsKey']."&Psw=".$GLOBALS['CONFIG']['smsPass']."&Timestamp=0&Message=".$content."&Phone=".$phoneNumer;
	$ch=curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置否输出到页面
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30 ); //设置连接等待时间
    curl_setopt($ch, CURLOPT_ENCODING, "gzip" );
    $data=curl_exec($ch);
    curl_close($ch);
    return "$data";
}
/**
 * 字符串替换
 * @param string $str     要替换的字符串
 * @param string $repStr  即将被替换的字符串
 * @param int $start      要替换的起始位置,从0开始
 * @param string $splilt  遇到这个指定的字符串就停止替换
 */
function RTCStrReplace($str,$repStr,$start,$splilt = ''){
	$newStr = substr($str,0,$start);
	$breakNum = -1;
	for ($i=$start;$i<strlen($str);$i++){
		$char = substr($str,$i,1);
		if($char==$splilt){
			$breakNum = $i;
			break;
		}
		$newStr.=$repStr;
	}
	if($splilt!='' && $breakNum>-1){
		for ($i=$breakNum;$i<strlen($str);$i++){
			$char = substr($str,$i,1);
			$newStr.=$char;
		}
	}
	return $newStr;
}
/**
 * 循环删除指定目录下的文件及文件夹
 * @param string $dirpath 文件夹路径
 */
function RTCDelDir($dirpath){
	$dh=opendir($dirpath);
	while (($file=readdir($dh))!==false) {
		if($file!="." && $file!="..") {
		    $fullpath=$dirpath."/".$file;
		    if(!is_dir($fullpath)) {
		        unlink($fullpath);
		    } else {
		        RTCDelDir($fullpath);
		        rmdir($fullpath);
		    }
	    }
	}	 
	closedir($dh);
    $isEmpty = 1;
	$dh=opendir($dirpath);
	while (($file=readdir($dh))!== false) {
		if($file!="." && $file!="..") {
			$isEmpty = 0;
			break;
		}
	}
	return $isEmpty;
}
/**
 * 获取网站域名
 */
function RTCDomain(){
	$server = $_SERVER['HTTP_HOST'];
	$http = is_ssl()?'https://':'http://';
	return $http.$server.__ROOT__;
}
/**
 * 获取系统根目录
 */
function RTCRootPath(){
	return dirname(dirname(dirname(dirname(__File__))));
}
/**
 * 获取网站根域名
 */
function RTCRootDomain(){
	$server = $_SERVER['HTTP_HOST'];
	$http = is_ssl()?'https://':'http://';
	return $http.$server;
}
/**
 * 设置当前页面对象
 * @param int 0-用户  1-商家
 */
function RTCLoginTarget($target = 0){
	$RTC_USER = session('RTC_USER');
	$RTC_USER['loginTarget'] = $target;
	session('RTC_USER',$RTC_USER);
}

/**
 * 生成缓存文件
 */
function RTCDataFile($name, $path = '',$data=array()){
	$key = C('DATA_CACHE_KEY');
	$name = md5($key.$name);
	if(is_array($data) && !empty($data)){		
	    $data   =   serialize($data);
        if( C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
            //数据压缩
            $data   =   gzcompress($data,3);
        }
        if(C('DATA_CACHE_CHECK')) {//开启数据校验
            $check  =  md5($data);
        }else {
            $check  =  '';
        }
        $data    = "<?php\n//".sprintf('%012d',$expire).$check.$data."\n?>";
        $result  =   file_put_contents(DATA_PATH.$path.$name.".php",$data);
	    clearstatcache();
	}else if(is_null($data)){
	    unlink(DATA_PATH.$path.$name.".php");
	}else{
		if(file_exists(DATA_PATH.$path.$name.'.php')){
		    $content    =   file_get_contents(DATA_PATH.$path.$name.'.php');
            if( false !== $content) {
	            $expire  =  (int)substr($content,8, 12);
	            if(C('DATA_CACHE_CHECK')) {//开启数据校验
	                $check  =  substr($content,20, 32);
	                $content   =  substr($content,52, -3);
	                if($check != md5($content)) {//校验错误
	                    return null;
	                }
	            }else {
	            	$content   =  substr($content,20, -3);
	            }
	            if(C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
	                //启用数据压缩
	                $content   =   gzuncompress($content);
	            }
	            $content    =   unserialize($content);
	            return $content;
	        }
		}
		return null;
	}
}



/**
 * 建立文件夹
 * @param string $aimUrl
 * @return viod
 */
function RTCCreateDir($aimUrl) {
	$aimUrl = str_replace('', '/', $aimUrl);
	$aimDir = '';
	$arr = explode('/', $aimUrl);
	$result = true;
	foreach ($arr as $str) {
		$aimDir .= $str . '/';
		if (!file_exists_case($aimDir)) {
			$result = mkdir($aimDir,0777);
		}
	}
	return $result;
}

/**
 * 建立文件
 * @param string $aimUrl
 * @param boolean $overWrite 该参数控制是否覆盖原文件
 * @return boolean
 */
function RTCCreateFile($aimUrl, $overWrite = false) {
	if (file_exists_case($aimUrl) && $overWrite == false) {
		return false;
	} elseif (file_exists_case($aimUrl) && $overWrite == true) {
		RTCUnlinkFile($aimUrl);
	}
	$aimDir = dirname($aimUrl);
	RTCCreateDir($aimDir);
	touch($aimUrl);
	return true;
}

/**
 * 删除文件
 * @param string $aimUrl
 * @return boolean
 */
function RTCUnlinkFile($aimUrl) {
	if (file_exists_case($aimUrl)) {
		unlink($aimUrl);
		return true;
	} else {
		return false;
	}
}

function  RTCLogResult($filepath,$word){
	if(!file_exists_case($filepath)){
		RTCCreateFile($filepath);
	}
	$fp = fopen($filepath,"a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"执行日期：".strftime("%Y-%m-%d %H:%M:%S",time())."\n".$word."\n\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}

function RTCReadExcel($file){
	Vendor("PHPExcel.PHPExcel");
	Vendor("PHPExcel.PHPExcel.IOFactory");
	return PHPExcel_IOFactory::load(RTCRootPath()."/Upload/".$file);
}

/**
 * 去除多余的0
 */
function del0($s) {
    $s = trim(strval($s));
    if (preg_match('#^-?\d+?\.0+$#', $s)) {
        return preg_replace('#^(-?\d+?)\.0+$#','$1',$s);
    } 
    if (preg_match('#^-?\d+?\.[0-9]+?0+$#', $s)) {
        return preg_replace('#^(-?\d+\.[0-9]+?)0+$#','$1',$s);
    }
    return $s;
}

function newid($length = 36 ) {

	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$password = "";
	for ( $i = 0; $i < $length; $i++ )
		$password .= $chars[ mt_rand(0, strlen($chars) - 1) ];

	return $password;
}



 /*
 * HTTP GET Request
*/
function get($url, $param = null) {
    if($param != null) {
        $query = http_build_query($param);
        $url = $url . '?' . $query;
    }   
    $ch = curl_init();
    if(stripos($url, "https://") !== false){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }   

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    $content = curl_exec($ch);
    $status = curl_getinfo($ch);
    curl_close($ch);
    if(intval($status["http_code"]) == 200) {
        return $content;
    }else{
        echo $status["http_code"];
        return false;
    }   
}

/*
 * HTTP POST Request
*/
function post($url, $params) {
    $ch = curl_init();
    if(stripos($url, "https://") !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    $content = curl_exec($ch);
    $status = curl_getinfo($ch);
    curl_close($ch);
    if(intval($status["http_code"]) == 200) {
        return $content;
    } else {
        echo $status["http_code"];
        return false;
    }
}


/*json stdClass转换处理*/
function object_array($array){
      if(is_object($array)){
        $array = (array)$array;
      }
      if(is_array($array)){
        foreach($array as $key=>$value){
          $array[$key] = object_array($value);
        }
      }
      return $array;
}

