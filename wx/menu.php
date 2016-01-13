<?php
header("Content-type: text/html; charset=utf-8");
define("ACCESS_TOKEN", "5-XqoTS8jSxyr672xOCw6dmGz5Rt9VAO7cFbVzITmti4N8Ta47f_Qv3oGGCoFQ6pzcP5CAQ40wSmDil_XaKfyX0PFquBvmHULUopJ3alkywBZEiAJAVTY");

//https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx9c7c9bb54952b54d&secret=d4624c36b6795d1d99dcf0547af5443d

//创建菜单
function createMenu($data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
	  return curl_error($ch);
	}
	
	curl_close($ch);
	return $tmpInfo;

}

//获取菜单
function getMenu(){
return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".ACCESS_TOKEN);
}

//删除菜单
function deleteMenu(){
return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".ACCESS_TOKEN);
}





$data = '{
     "button":[
     {
          "type":"view",
          "name":"首页",
          "url":"http://cky.ritacc.net/index.php/M"
      },
      {
          "type":"view",
          "name":"个人中心",
          "url":"http://cky.ritacc.net/index.php/M/Person/index"
      },
      {
           "name":"关于我们",
           "sub_button":[
            {
            	"type":"click",
	           "name":"简介",
	           "key":"introduct"
               
            },
            {	
               "type":"click",
               "name":"赞一下我们",
               "key":"MGOOD"
            }]
       }]
}';





//echo getMenu();
echo deleteMenu();
echo createMenu($data);
