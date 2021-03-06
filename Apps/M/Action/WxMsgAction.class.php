<?php
namespace M\Action;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
use Com\Jssdk;

class WxMsgAction extends Controller{//define("TOKEN", "weixin");
	 
    public function index()
    {	 
	        //define('APP_DEBUG', false);
	        //define('ENGINE_NAME','sae');
			traceHttp(); 
			
			$token = 'token'; //微信后台填写的TOKEN
	        //调试
         
            if (isset($_GET['echostr'])) {
			    $this->valid();
			}else{
	            //$this->responseMsg();
	            vendor('Weixinpay.WxPayJsApiPay');
				$appid =  \WxPayConfig2::APPID;
				$crypt = \WxPayConfig2::APPSECRET;
	            
	            /* 加载微信SDK */
	            $wechat = new Wechat($token, $appid, $crypt);
	            
	            /* 获取请求信息 */
	            $data = $wechat->request();
				
	            if($data && is_array($data))
	            {
	                /**
	                 * 你可以在这里分析数据，决定要返回给用户什么样的信息
	                 * 接受到的信息类型有10种，分别使用下面10个常量标识
	                 * Wechat::MSG_TYPE_TEXT       //文本消息
	                 * Wechat::MSG_TYPE_IMAGE      //图片消息
	                 * Wechat::MSG_TYPE_VOICE      //音频消息
	                 * Wechat::MSG_TYPE_VIDEO      //视频消息
	                 * Wechat::MSG_TYPE_SHORTVIDEO //视频消息
	                 * Wechat::MSG_TYPE_MUSIC      //音乐消息
	                 * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
	                 * Wechat::MSG_TYPE_LOCATION   //位置消息
	                 * Wechat::MSG_TYPE_LINK       //连接消息
	                 * Wechat::MSG_TYPE_EVENT      //事件消息
	                 *
	                 * 事件消息又分为下面五种
	                 * Wechat::MSG_EVENT_SUBSCRIBE    //订阅
	                 * Wechat::MSG_EVENT_UNSUBSCRIBE  //取消订阅
	                 * Wechat::MSG_EVENT_SCAN         //二维码扫描
	                 * Wechat::MSG_EVENT_LOCATION     //报告位置
	                 * Wechat::MSG_EVENT_CLICK        //菜单点击
	                 */
	
	                //记录微信推送过来的数据
	                file_put_contents('./data.json', json_encode($data));
	
	                /* 响应当前请求(自动回复) */
	                //$wechat->response($content, $type);
	
	                /**
	                 * 响应当前请求还有以下方法可以使用
	                 * 具体参数格式说明请参考文档
	                 * 
	                 * $wechat->replyText($text); //回复文本消息
	                 * $wechat->replyImage($media_id); //回复图片消息
	                 * $wechat->replyVoice($media_id); //回复音频消息
	                 * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
	                 * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
	                 * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
	                 * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
	                 * 
	                 */
	                
	                //执行Demo
	                $this->demo($wechat, $data);
                }
            }
         
    }
				
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
        	ob_clean();
            echo $echoStr;
            exit;
        }
    } 
	 
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
		 
        $token = "token";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1($tmpStr );

		logger("/**************$tmpStr******************/");
		logger("/**************$signature******************/");
		
        if( $tmpStr == $signature )
        {
            return true;
        }else{
            	echo "$tmpStr == $signature"; 
            return false;			
        }
    }
	
	/**
     * DEMO
     * @param  Object $wechat Wechat对象
     * @param  array  $data   接受到微信推送的消息
     */
    private function demo($wechat, $data){
        vendor('Weixinpay.WxPayJsApiPay');
		$appid =  \WxPayConfig2::APPID;
		$appsecret = \WxPayConfig2::APPSECRET;
		$wxmsg=new WxUserInfo();
		$access_token=$wxmsg->accessToken();
		/*		*/
		//$WebDomain=$_SERVER['SERVER_NAME'];
		$WebDomain="www.cukayun.cn";
//		$WebDomain="www.yiyigw.cn";
		$WebRoot="http://$WebDomain";
		 
        $Auth=new WechatAuth($appid,$appsecret,$access_token);
		
		 $len=strlen($data['EventKey']);
		 $eventkey="";
        if(!empty($data['EventKey']) && $len > 4){
        		$eventkey=substr($data['EventKey'],0,4);
			logger("/**************eventkey=$eventkey******************/");
		}
        
		$bk1=new WxMsgKanjia();
		
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
	    				if($eventkey == '1001'){
							$bk1->Kanjia($data, $Auth, $wechat, $WebDomain, $WebRoot);
						}
						else if($eventkey == '7777'){
							$bk1->KanjiaCommin($data, $Auth, $wechat, $WebDomain, $WebRoot);							
						}
                        break;

                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;
                    	
                    case 'SCAN'://通过分享出去的扫码事件
                    	if($eventkey == '1001'){
							$bk1->Kanjia($data, $Auth, $wechat, $WebDomain, $WebRoot);
						}
						else if($eventkey == '7777'){
							$bk1->KanjiaCommin($data, $Auth, $wechat, $WebDomain, $WebRoot);
						}
                        break;
                    case "CLICK":
							$posindex=stripos($data['EventKey'],"kj");
	    					if($posindex >= 0){
	                            $bk3=new WxMsgKanjia();
								$bk3->KanjiaClick($data, $Auth, $wechat, $WebDomain, $WebRoot);
							}
                            break;
                    default:
                        $wechat->replyText("亲，想参与最新0元砍价活动。请点击下方菜单");
                        break;
                }
                break;
            case Wechat::MSG_TYPE_TEXT:
                switch ($data['Content']) {
                    case '联系我们':
                        $wechat->replyText('在这个平台里，你的事就是我的事啦、/得意
那我将有什么事情还没解决的呢？/可爱 你可以在这里给我们发信息，我们会在工作时间回复您的。/亲亲/玫瑰/玫瑰/玫瑰');
                        break;
                    case '图文'://回复单条图文消息
                        break;
                        $wechat->replyNews($news, $news, $news, $news, $news);
                        break;                    
                    default:
                        $wechat->replyText("(●˘◡˘●) 想参与最新活动吗？\n\n /玫瑰 100M流量\n /玫瑰 领取50000积分\n\n 点击下方菜单\n->[惊喜无限]参与活动吧！");
                        break;
                }
                break;
            default:
                $wechat->replyText("亲，我还不知道你说的啥呢？");
                break;
        }
		
    }

    /**
     * 资源文件上传方法
     * @param  string $type 上传的资源类型
     * @return string       媒体资源ID
     */
    private function upload($type){ 
		vendor('Weixinpay.WxPayJsApiPay');
		$appid =  \WxPayConfig2::APPID;
		$appsecret = \WxPayConfig2::APPSECRET;
		
        $token = session("token");

        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();

            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }

        switch ($type) {
            case 'image':
                $filename = './Public/image.jpg';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;

            case 'voice':
                $filename = './Public/voice.mp3';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;

            case 'video':
                $filename    = './Public/video.mp4';
                $discription = array('title' => '视频标题', 'introduction' => '视频描述');
                $media       = $auth->materialAddMaterial($filename, $type, $discription);
                break;

            case 'thumb':
                $filename = './Public/music.jpg';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;
            
            default:
                return '';
        }

        if($media["errcode"] == 42001){ //access_token expired
            session("token", null);
            $this->upload($type);
        }

        return $media['media_id'];
    }

    /*构造菜单
     * http://www.cukayun.cn/index.php/M/WxMsg/create_menu
     */
    public function create_menu(){
       
		$wxmsg=new WxUserInfo();
		$access_token=$wxmsg->accessToken();
		 
        //数据结构
//      $WebRoot="http://".$_SERVER['SERVER_NAME'];
		$WebRoot="http://www.cukayun.cn";
//		$WebRoot="http://www.yiyigw.cn";
        $array['button'][0]=array(
            'name'=>'粗卡',
            'sub_button'=>array(
                array(
                        'type' => 'view',
                        'name' => '  首页  ',
                        'url' => "$WebRoot/index.php/M",
                    ),
                array(
                        'type' => 'view',
                        'name' => '  商城  ',
                        'url' => "$WebRoot/index.php/M/Mall/index.html",
                    ),
                array(
                        'type' => 'view',
                        'name' => '速达农村配送',
                        'url' => "$WebRoot/index.php/M/Deliver/index.html",
                    ),
                ),
            );
//      $array['button'][1]=array(
//          'type' => 'view',
//          'name' => '速达农村配送',
//          'url' => "$WebRoot/index.php/M/Deliver/index.html",
//      );
		$array['button'][1]=array(
            'type' => 'click',
            'name' => '免费礼盒',
            'key' => 'kj93',
        );
			 
         $array['button'][2]=array(
            'name'=>'我的',
            'sub_button'=>array(
                array(
                        'type' => 'view',
                        'name' => '个人中心',
                        'url' => "$WebRoot/index.php/M/Person/index",
                    ),
                array(
                        'type' => 'view',
                        'name' => '消费记录',
                        'url' => "$WebRoot/index.php/M/Person/consumelist",
                    ),
                ),
            ); 
		 
        $data=json_encode($array,JSON_UNESCAPED_UNICODE); 
		
        $rs=post('https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token,$data);
        print_r($rs);
    }

//		$array['button'][1]=array(
//      'name'=>'免费电影',
//      'sub_button'=>array(                
//          	array(
//                  'type' => 'click',
//                  'name' => '0元拿10元话费',
//                  'key' => 'kj91',
//              ),
//          ),
//      );

    //获取菜单
    public function menu(){
        vendor('Weixinpay.WxPayJsApiPay');
		$appid =  \WxPayConfig2::APPID;
		$appsecret = \WxPayConfig2::APPSECRET;
		$wxmsg=new WxUserInfo();
		$access_token=$wxmsg->accessToken();
		 
        $Auth=new WechatAuth($appid,$appsecret,$access_token);
        $rs=$Auth->menuGet();
        print_r($rs); 
    }
    //删除菜单
    public function menudel(){
        vendor('Weixinpay.WxPayJsApiPay');
		$appid =  \WxPayConfig2::APPID;
		$appsecret = \WxPayConfig2::APPSECRET;
		$wxmsg=new WxUserInfo();
		$access_token=$wxmsg->accessToken();
		
        
        $Auth=new WechatAuth($appid,$appsecret,$access_token);
        $rs=$Auth->menuDelete();
        print_r($rs);
    }


    public function test($type){
		$bk1=new WxMsgKanjia();
        header("Content-type:text/html;charset=utf-8");
        set_time_limit (0);//   //设置程序最大执行时间为无限  0为无限制
        ini_set('max_execution_time', '0');//设置最大执行时间  0为不限时
        $map = array("type"=> $type);
        $area=M('kanjiarule')->where($map)->order('kjr_yikan ASC')->select();
		$dbjkp = M('kanjia_para');
		$kjpara= $dbjkp->where("kjcode='".$type."'")->find();
		
        //计算已砍比例
        $money=$kjpara["money"];
		$m=$money;
		 echo "总砍价金额".$kjpara["money"]."<br>";
        $a=0;
        while($m>=0.1){
            $yikan=$money-$m;
            $yikan_bl=round(($yikan/$money*100),2);
            //找到它所在的区间
            $mkj=D('M/Kanjia');
			$add_money=$mkj->GetAddMoney($type, $money, $m);
			if($add_money>$m)
			{
				$add_money=$m;
			}
            $a++;
            echo "第 $a 刀<br>";
            echo "当前金额：$m <br>";
            echo "砍掉金额".$add_money."<br>";
            $m=round($m-$add_money,2);
            ob_flush();
            flush();
            echo "剩余金额".$m."<br>";
            echo "=============<br>";
        }
        echo "@@@@@@@@@@@@@<br>";
        echo "一共砍了 $a 刀<br>";
        echo "@@@@@@@@@@@@@<br>";
    }


     /*创建临时二维码*/
    public function create_qr111($openid='oyKgswI_fyh9dM5rdw6SAEy0dEUg',$type=1){
        //找到此用户的uid
        $uid=M('member')->where(array('OpenID'=>$openid))->limit(1)->getField('uid');
        $uid=$type.$uid;
        
        $param=array(
            'expire_seconds'=>2592000,
            'action_name'=>'QR_SCENE',
            'action_info'=>array(
                'scene'=>array(
                    'scene_id'=>$uid,
                    )
                ),
            );
        $param=json_encode($param);
		
        $wxmsg=new WxUserInfo();
		$access_token=$wxmsg->accessToken();
        $rs=post('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token,$param);
        $rs=json_decode($rs);
        //处理object
        $rs=object_array($rs);
        echo  ('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$rs['ticket']);
        // print_r($rs);
    }

	
	public function testc()
	{
//			$type=92;
//			$form_openid='oKxDRvyD0gFYNwby4lh7kGrOUcM8';
//			 
//			$shengyuprizenum=50;
//			 $mkjp=D('M/Kanjia');
//			$ZhongPara= $mkjp->GetZhongJ($type);
//			 
//			 
//			
//			echo $ZhongPara;
				$openid="olAupxKj09dfzPwt91YeQTDwIjCY";
				$wxm= new WxNotify();
				$result=$wxm->KJNotify($form['nickname'],$openid,'','10元话费');
				echo $result;
//          //开启事务
//          M()->startTrans();
//          //保存帮砍信息
//         
//			$add_ZhongParaStatus=TRUE;
//			$save_kanjiaparastatus=TRUE;
//			if($add_money==$shengyumoney){				
//				$add_ZhongParaStatus=M('kanzhong')->add($ZhongPara);
//				$save_kanjiaparastatus=M('kanjia_para')->where(array('kjcode'=>$type))->setField('shengyuprizenum',$shengyuprizenum-1);
//			}
//			
//			
//          
//          if( $add_ZhongParaStatus && $save_kanjiaparastatus){
//              M()->commit();
//				echo "aaaaaaaaaaaaaa";
//          }
//          else{
//              M()->rollback();
//             echo "EEEEEEEEEEEE";
//          }
	}
		

}
 