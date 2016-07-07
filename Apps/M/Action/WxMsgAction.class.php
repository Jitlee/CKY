<?php
namespace M\Action;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;

class WxMsgAction extends Controller{//define("TOKEN", "weixin");
	 
    public function index()
    {
    	 
        define('APP_DEBUG', false);
        define('ENGINE_NAME','sae');
		
		$token = 'weixin'; //微信后台填写的TOKEN
        //调试
        try{
            if (isset($_GET['echostr'])) {
			    $this->valid();
			}else{ 
	            
	            //$this->responseMsg();
				 
	            
	            vendor('Weixinpay.WxPayJsApiPay');
				$appid =  \WxPayConfig::APPID;
				$crypt = \WxPayConfig::APPSECRET;
	            
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
        } catch(\Exception $e){
            file_put_contents('./error.json', json_encode($e->getMessage()));
        }
    }
				
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
		
    public function responseMsg()
    {
    		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

	        if (!empty($postStr)){
	        	logger($postStr);
	            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	            $fromUsername = $postObj->FromUserName;
	            $toUsername = $postObj->ToUserName;
	            $keyword = trim($postObj->Content);
				$EventKey= trim($postObj->EventKey);
	            $time = time();
	            $textTpl = "<xml>
	                        <ToUserName><![CDATA[%s]]></ToUserName>
	                        <FromUserName><![CDATA[%s]]></FromUserName>
	                        <CreateTime>%s</CreateTime>
	                        <MsgType><![CDATA[%s]]></MsgType>
	                        <Content><![CDATA[%s]]></Content>
	                        <FuncFlag>0</FuncFlag>
	                        </xml>";
				$contentStr=" ";
				if (!empty($EventKey)){
					switch($EventKey)
					{
						case "introduct": //简介
							$contentStr="关于简介，它。。。。";
							break;
						case "MGOOD":
							$contentStr="感谢您的支持，我们一定会做得更好。";
							break;
						default:
							$contentStr="需然不知道你说的是什么，相信它一定是对的。\n";
					}
				}
				else
			 	{
			 		$contentStr="您说的是：".$keyword;
			 	}
	            //$contentStr=$contentStr.$fromUsername.$toUsername;
	            $strlen=strlen($contentStr);
	            if(!empty($keyword))
				{
	                $msgType = "text";
	                //$contentStr = date("Y-m-d H:i:s",time());
	                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	                echo $resultStr;					
				}		             
	        }else{
	            echo "";
	            exit;
	        }
    }

    private function checkSignature()
    {
       $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
		//define("TOKEN", "weixin");
        $token = "weixin";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
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
		$appid =  \WxPayConfig::APPID;
		$appsecret = \WxPayConfig::APPSECRET;
		$wxmsg=new WxUserInfo();
		$access_token=$wxmsg->accessToken();
		
        $Auth=new WechatAuth($appid,$appsecret,$access_token);
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        //来源openid
                        $form_openid=$data['FromUserName'];
                        //获取来源者信息
                        $form=$Auth->userInfo($form_openid);
                        //保存关注者信息
                        if(!M('wx_user')->where(array('OpenID'=>$form['openid']))->Find()){
                            // M('wx_user')->where(array('openid'=>$form['openid']))->delete();
                            M('wx_user')->add($form);
                        }
                        //如果是砍价1活动
                         if($data['EventKey']=='kj1'){
                                    //判断是否已经注册
                                    $openid=$data['FromUserName'];
                                    $user_info=M('member')->where(array('OpenID'=>$openid))->find();
                                    // $Auth->sendText($openid,$openid);
                                    if(empty($user_info)){
                                        $back=$Auth->sendText($openid,"您还未注册/未绑定微信 \d 请点击下面链接进行注册/登陆绑定");
                                        if($back){
                                            $wechat->replyNewsOnce(
                                                "点击注册送福气！",
                                                "只需几步即可完成注册", 
                                                // "$WebRoot/index.php/Home/Public/reg",
                                                "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http%3A%2F%2Fwww.eyuanduobao.com%2Findex.php%2FHome%2FPerson%2Fme&response_type=code&scope=snsapi_base&state=STATE",
                                                "http://pic.qiantucdn.com/58pic/18/32/60/10c58PICXbP_1024.jpg"
                                            );  
                                        }
                                       
                                    }
                                    else{
                                        //判断砍主是否已经曾经参与
                                        if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>1))->getField('kj_id')){
                                            $wechat->replyNewsOnce(
                                                "[有人@你]您有一台Iphone6S尚未领取",
                                                "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                                "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                                "$WebRoot/Public/images/kj.png"
                                            );
                                            exit();
                                        }
                                        /*保存砍主信息*/
                                        //找到砍主id
                                        $wx_id=M('wx_user')->where(array('OpenID'=>$openid))->getField('wx_id');
                                        // 如果没有获取到该砍主的微信信息
                                        if(empty($wx_id)){
                                            //获取来源者信息
                                            $add_info=$Auth->userInfo($openid);
                                            //保存关注者信息
                                            if(!M('wx_user')->where(array('OpenID'=>$form['openid']))->Find()){
                                                // M('wx_user')->where(array('openid'=>$form['openid']))->delete();
                                                if(M('wx_user')->add($add_info)===false){
                                                    $this->replyText("当前参与活动人数太多，请稍后重试");
                                                    exit();
                                                }
                                                $wx_id=M('wx_user')->where(array('openid'=>$openid))->getField('wx_id');
                                            }
                                            
                                        }

                                        $uid=$user_info['uid'];
                                        $qr_url=$this->create_qr($openid,1);
                                        $map=array(
                                            'uid'=>$uid,
                                            'wx_id'=>$wx_id,
                                            'time'=>time(),
                                            'money'=>'7888.00',
                                            'shengyumoney'=>'7888.00',
                                            'count'=>0,
                                            'status'=>1,
                                            'qr_url'=>$qr_url,
                                            'type'=>1,
                                            );
                                        //保存砍主信息
                                        $kj_id=M('kanjia')->add($map);
                                        if($kj_id){
                                              $wechat->replyNewsOnce(
                                                "[有人@你]您有一台Iphone6S尚未领取",
                                                "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                                "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                                "$WebRoot/Public/images/kj.png"
                                            );   
                                        }
                                        else{
                                            $Auth->sendText($openid,"当前参与活动人数太多，请稍后重试");
                                        }
                                    }  
                            exit();
                         }
                         //如果是砍价2活动
                         if($data['EventKey']=='kj2'){
                                    //判断是否已经注册
                                    $openid=$data['FromUserName'];
                                    $user_info=M('member')->where(array('OpenID'=>$openid))->find();
                                    // $Auth->sendText($openid,$openid);
                                    if(empty($user_info)){
                                        $back=$Auth->sendText($openid,"您还未注册/未绑定微信 \d 请点击下面链接进行注册/登陆绑定");
                                        if($back){
                                            $wechat->replyNewsOnce(
                                                "点击注册送福气！",
                                                "只需几步即可完成注册", 
                                                // "$WebRoot/index.php/Home/Public/reg",
                                                "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http%3A%2F%2Fwww.eyuanduobao.com%2Findex.php%2FHome%2FPerson%2Fme&response_type=code&scope=snsapi_base&state=STATE",
                                                "http://pic.qiantucdn.com/58pic/18/32/60/10c58PICXbP_1024.jpg"
                                            );  
                                        }
                                       
                                    }
                                    else{
                                        //判断砍主是否已经曾经参与
                                        if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>2))->getField('kj_id')){
                                            $wechat->replyNewsOnce(
                                                "[有人@你]5万积分等你来拿！!",
                                                "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                                "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                                "$WebRoot/Public/images/kj2.png"
                                            );
                                            exit();
                                        }
                                        /*保存砍主信息*/
                                        //找到砍主id
                                        $wx_id=M('wx_user')->where(array('OpenID'=>$openid))->getField('wx_id');
                                        // 如果没有获取到该砍主的微信信息
                                        if(empty($wx_id)){
                                            //获取来源者信息
                                            $add_info=$Auth->userInfo($openid);
                                            //保存关注者信息
                                            if(!M('wx_user')->where(array('OpenID'=>$form['openid']))->Find()){
                                                // M('wx_user')->where(array('openid'=>$form['openid']))->delete();
                                                if(M('wx_user')->add($add_info)===false){
                                                    $this->replyText("当前参与活动人数太多，请稍后重试");
                                                    exit();
                                                }
                                                $wx_id=M('wx_user')->where(array('openid'=>$openid))->getField('wx_id');
                                            }
                                            
                                        }

                                        $uid=$user_info['uid'];
                                        $qr_url=$this->create_qr($openid,2);
                                        $map=array(
                                            'uid'=>$uid,
                                            'wx_id'=>$wx_id,
                                            'time'=>time(),
                                            'money'=>'50000.00',
                                            'shengyumoney'=>'50000.00',
                                            'count'=>0,
                                            'status'=>1,
                                            'qr_url'=>$qr_url,
                                            'type'=>2,
                                            );
                                        //保存砍主信息
                                        $kj_id=M('kanjia')->add($map);
                                        if($kj_id){
                                              $wechat->replyNewsOnce(
                                                "[有人@你]5万积分等你来拿！!",
                                                "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                                "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                                "$WebRoot/Public/images/kj2.png"
                                            );
                                        }
                                        else{
                                            $Auth->sendText($openid,"当前参与活动人数太多，请稍后重试");
                                        }
                                    }  
                            exit();
                         }
                        if(!empty($data['EventKey'])){
                            $data['EventKey']=str_replace('qrscene_', '', $data['EventKey']);
                            $uid=$data['EventKey'];
                            $type=$uid[0];
                            $uid=substr($uid,1);
                            $openid=M('member')->where(array('uid'=>$uid))->limit(1)->getField('openid');
                            //找到这个砍价消息
                            $D=D('Common/KanjiaUser','VModel');
                            $kanjia_info=$D->where(array('uid'=>$uid,'type'=>$type))->find();
                            if(empty($kanjia_info)){
                                $wechat->replyText("系统错误，找不到此砍价信息！$uid | $type |");
                                    exit();
                            }
                            //找出此人的wx_id
                            $wx_id=M('wx_user')->where(array('OpenID'=>$form_openid))->getField('wx_id');
                            //判断是否已经帮砍过了
                            $is_bangkan=M('bangkan')->where(array('wx_id'=>$wx_id,'kj_id'=>$kanjia_info['kj_id']))->find();
                            if($is_bangkan){
                                //判断是否注册用户
                                //如果是注册用户可以再砍一次
                                $is_register=M('member')->where(array('OpenID'=>$form_openid))->find();
                                if(empty($is_register)){
                                    $wechat->replyText("/玫瑰 注册成为会员并通过微信登陆即可帮ta再砍一刀哦！");
                                    exit();
                                }
                                if($is_register['is_kan']){
                                   $wechat->replyText("/玫瑰 1.关注微信\n/玫瑰 2.注册成为会员\n以上2种方法都可以帮ta砍一次哦~\nps：注册还可以免费抽iphone哦~");
                                   exit();
                                }
                                else{
                                    M('member')->where(array('uid'=>$is_register['uid']))->setField('is_kan',1);
                                }
                            }
                            //找出剩余的钱
                            $shengyumoney=$kanjia_info['shengyumoney'];
                            //区间砍价金额
                            $rule_map['type']=$kanjia_info['type'];
                            $area=M('kanjiarule')->where($rule_map)->order('kjr_yikan ASC')->select();
                            if(empty($area)){
                                //区间砍价金额
                                if($shengyumoney>=3000){
                                    $money100=mt_rand(0,1)*100;
                                    $money10=mt_rand(0,9)*10;
                                    $money1=mt_rand(0,9)*1;
                                    $moneyf=mt_rand(1,9)*0.1;
                                    $moneyf2=mt_rand(0,0)*0.01;
                                }
                                if($shengyumoney>=1000&&$shengyumoney<3000)
                                {
                                    $money100=mt_rand(0,0)*100;
                                    $money10=mt_rand(0,7)*10;
                                    $money1=mt_rand(0,9)*1;
                                    $moneyf=mt_rand(1,9)*0.1;
                                    $moneyf2=mt_rand(0,0)*0.01;
                                }
                                if($shengyumoney>=500&&$shengyumoney<1000){
                                    $money100=mt_rand(0,0)*100;
                                    $money10=mt_rand(0,0)*10;
                                    $money1=mt_rand(0,9)*1;
                                    $moneyf=mt_rand(1,9)*0.1;
                                    $moneyf2=mt_rand(0,0)*0.01;
                                }
                                if($shengyumoney<500){
                                    $money100=mt_rand(0,0)*100;
                                    $money10=mt_rand(0,0)*10;
                                    $money1=mt_rand(0,0)*1;
                                    $moneyf=mt_rand(0,9)*0.1;
                                    $moneyf2=mt_rand(1,9)*0.01;
                                }
                                $add_money=$money100+$money10+$money1+$moneyf+$moneyf2;
                            }else{
                                //计算已砍比例
                                $yikan=$kanjia_info['money']-$kanjia_info['shengyumoney'];
                                $yikan_bl=round(($yikan/$kanjia_info['money']*100),2);
                                //找到它所在的区间
                                foreach ($area as $key => $value) {
                                    if($yikan_bl<=$value['kjr_yikan']){
                                        $min=$value['kjr_min'];
                                        $max=$value['kjr_max'];
                                        if($min>0&&$max>0){
                                            $min=(int)$min;
                                            $max=(int)$max;
                                            $add_money1=mt_rand($min,$max-1);
                                            $add_money2=mt_rand(1,99)/100;
                                            $add_money=$add_money1+$add_money2;
                                        }
                                        elseif($min<=0&&$max>=0){
                                            $min=(int)($min*100);
                                            $max=(int)$max;
                                            $add_money1=mt_rand(0,$max-1);
                                            $add_money2=mt_rand($min,99)/100;
                                            $add_money=$add_money1+$add_money2;
                                        }
                                        elseif($min<=0&&$max<=0){
                                            $min=(int)($min*100);
                                            $max=(int)($max*100);
                                            $add_money1=mt_rand(0,0);
                                            $add_money2=mt_rand($min,$max)/100;
                                            $add_money=$add_money1+$add_money2;
                                        }
                                        else{
                                            $add_money1=mt_rand(0,99);
                                            $add_money2=mt_rand(1,99)/100;
                                            $add_money=$add_money1+$add_money2;
                                        }
                                        break;
                                    }
                                    else{
                                        $add_money2=mt_rand(1,99)/100;
                                        $add_money=$add_money2;
                                    }
                                }
                            }
                            if($shengyumoney<=50){
                                $wechat->replyText("当前活动已经结束，请留意最新中奖公告");
                                exit();
                            }
                            //保存砍价记录
                            $wx_id=M('wx_user')->where(array('OpenID'=>$form_openid))->getField('wx_id');
                            if(empty($wx_id)){
                                $wechat->replyText("当前参与活动人数太多，请稍后重试");
                                exit();
                            }
                            $bangkan_add=array(
                                'wx_id'=>$wx_id,
                                'kj_id'=>$kanjia_info['kj_id'],
                                'bk_money'=>$add_money,
                                'bk_time'=>time(),
                                );
                            //开启事物
                            M()->startTrans();
                            //保存帮砍信息
                            $add_status=M('bangkan')->add($bangkan_add);
                            //更新砍主信息
                            $save_status=M('kanjia')->where(array('kj_id'=>$kanjia_info['kj_id']))->setField('shengyumoney',round($shengyumoney-$add_money,2));
                            M('kanjia')->where(array('kj_id'=>$kanjia_info['kj_id']))->setINC('count',1);
                            if($add_status&&$save_status){
                                M()->commit();
                            }
                            else{
                                M()->rollback();
                                $wechat->replyText("当前参与活动人数太多，请稍后重试");
                                exit();
                            }
                            //获取来源者信息
                            $form=$Auth->userInfo($form_openid);
                            $rs=$Auth->userInfo($openid);
                            //发送消息给砍主
                            $Auth->sendText($openid,'您的好友“'.$form['nickname'].'”\n帮您砍下了'.$add_money.'元，快去答谢他（她）吧/示爱');
                            $wechat->replyText("您刚刚帮助您的好友[".$rs['nickname']."]砍了".$add_money."元");
                        }
                        $wechat->replyText('哟呵~主子终于等到你，还好我没放屁啊！/示爱/示爱/示爱
欢迎来到【粗卡】王国游戏王国待会就更新啦，更多消息，请留意我们的微信公众号和新浪微博
请直接点击底部菜单，尽情购物吧！/玫瑰/玫瑰/玫瑰');
                        break;

                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;
                    //通过分享出去的扫码事件
                    case 'SCAN':
                    //如果是砍价1活动
                     if($data['EventKey']=='kj1'){
                                //判断是否已经注册
                                $openid=$data['FromUserName'];
                                $user_info=M('member')->where(array('OpenID'=>$openid))->find();
                                // $Auth->sendText($openid,$openid);
                                if(empty($user_info)){
                                    $back=$Auth->sendText($openid,"您还未注册/未绑定微信 \d 请点击下面链接进行注册/登陆绑定");
                                    if($back){
                                        $wechat->replyNewsOnce(
                                            "点击注册送福气！",
                                            "只需几步即可完成注册", 
                                            // "$WebRoot/index.php/Home/Public/reg",
                                            "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http%3A%2F%2Fwww.eyuanduobao.com%2Findex.php%2FHome%2FPerson%2Fme&response_type=code&scope=snsapi_base&state=STATE",
                                            "http://pic.qiantucdn.com/58pic/18/32/60/10c58PICXbP_1024.jpg"
                                        );  
                                    }
                                   
                                }
                                else{
                                    //判断砍主是否已经曾经参与
                                    if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>1))->getField('kj_id')){
                                        $wechat->replyNewsOnce(
                                            "[有人@你]您有一台Iphone6S尚未领取",
                                            "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                            "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                            "$WebRoot/Public/images/kj.png"
                                        );
                                        exit();
                                    }
                                    /*保存砍主信息*/
                                    //找到砍主id
                                    $wx_id=M('wx_user')->where(array('OpenID'=>$openid))->getField('wx_id');
                                    // 如果没有获取到该砍主的微信信息
                                    if(empty($wx_id)){
                                        //获取来源者信息
                                        $add_info=$Auth->userInfo($openid);
                                        //保存关注者信息
                                        if(!M('wx_user')->where(array('OpenID'=>$form['openid']))->Find()){
                                            // M('wx_user')->where(array('openid'=>$form['openid']))->delete();
                                            if(M('wx_user')->add($add_info)===false){
                                                $this->replyText("当前参与活动人数太多，请稍后重试");
                                                exit();
                                            }
                                            $wx_id=M('wx_user')->where(array('openid'=>$openid))->getField('wx_id');
                                        }
                                        
                                    }

                                    $uid=$user_info['uid'];
                                    $qr_url=$this->create_qr($openid,1);
                                    $map=array(
                                        'uid'=>$uid,
                                        'wx_id'=>$wx_id,
                                        'time'=>time(),
                                        'money'=>'7888.00',
                                        'shengyumoney'=>'7888.00',
                                        'count'=>0,
                                        'status'=>1,
                                        'qr_url'=>$qr_url,
                                        'type'=>1,
                                        );
                                    //保存砍主信息
                                    $kj_id=M('kanjia')->add($map);
                                    if($kj_id){
                                          $wechat->replyNewsOnce(
                                            "[有人@你]您有一台Iphone6S尚未领取",
                                            "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                            "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                            "$WebRoot/Public/images/kj.png"
                                        );   
                                    }
                                    else{
                                        $Auth->sendText($openid,"当前参与活动人数太多，请稍后重试");
                                    }
                                }  
                        exit();
                     }
                     //如果是砍价2活动
                     if($data['EventKey']=='kj2'){
                                //判断是否已经注册
                                $openid=$data['FromUserName'];
                                $user_info=M('member')->where(array('OpenID'=>$openid))->find();
                                // $Auth->sendText($openid,$openid);
                                if(empty($user_info)){
                                    $back=$Auth->sendText($openid,"您还未注册/未绑定微信 \d 请点击下面链接进行注册/登陆绑定");
                                    if($back){
                                        $wechat->replyNewsOnce(
                                            "点击注册送福气！",
                                            "只需几步即可完成注册", 
                                            // "$WebRoot/index.php/Home/Public/reg",
                                            "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http%3A%2F%2Fwww.eyuanduobao.com%2Findex.php%2FHome%2FPerson%2Fme&response_type=code&scope=snsapi_base&state=STATE",
                                            "http://pic.qiantucdn.com/58pic/18/32/60/10c58PICXbP_1024.jpg"
                                        );  
                                    }
                                   
                                }
                                else{
                                    //判断砍主是否已经曾经参与
                                    if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>2))->getField('kj_id')){
                                        $wechat->replyNewsOnce(
                                            "[有人@你]5万积分等你来拿！!",
                                            "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                            "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                            "$WebRoot/Public/images/kj2.png"
                                        );
                                        exit();
                                    }
                                    /*保存砍主信息*/
                                    //找到砍主id
                                    $wx_id=M('wx_user')->where(array('OpenID'=>$openid))->getField('wx_id');
                                    // 如果没有获取到该砍主的微信信息
                                    if(empty($wx_id)){
                                        //获取来源者信息
                                        $add_info=$Auth->userInfo($openid);
                                        //保存关注者信息
                                        if(!M('wx_user')->where(array('OpenID'=>$form['openid']))->Find()){
                                            // M('wx_user')->where(array('openid'=>$form['openid']))->delete();
                                            if(M('wx_user')->add($add_info)===false){
                                                $this->replyText("当前参与活动人数太多，请稍后重试");
                                                exit();
                                            }
                                            $wx_id=M('wx_user')->where(array('OpenID'=>$openid))->getField('wx_id');
                                        }
                                        
                                    }

                                    $uid=$user_info['uid'];
                                    $qr_url=$this->create_qr($openid,2);
                                    $map=array(
                                        'uid'=>$uid,
                                        'wx_id'=>$wx_id,
                                        'time'=>time(),
                                        'money'=>'50000.00',
                                        'shengyumoney'=>'50000.00',
                                        'count'=>0,
                                        'status'=>1,
                                        'qr_url'=>$qr_url,
                                        'type'=>2,
                                        );
                                    //保存砍主信息
                                    $kj_id=M('kanjia')->add($map);
                                    if($kj_id){
                                          $wechat->replyNewsOnce(
                                            "[有人@你]5万积分等你来拿！!",
                                            "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                            "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                            "$WebRoot/Public/images/kj2.png"
                                        ); 
                                    }
                                    else{
                                        $Auth->sendText($openid,"当前参与活动人数太多，请稍后重试");
                                    }
                                }  
                        exit();
                     }
                        if(!empty($data['EventKey'])){
                            $uid=$data['EventKey'];
                            $type=$uid[0];
                            $uid=substr($uid,1);
                            //砍主openid
                            $openid=M('member')->where(array('uid'=>$uid))->limit(1)->getField('openid');
                            //来源openid
                            $form_openid=$data['FromUserName'];
                            //找到这个砍价消息
                            $D=D('Common/KanjiaUser','VModel');
                            $kanjia_info=$D->where(array('uid'=>$uid,'type'=>$type))->find();
                            if(empty($kanjia_info)){

                                $wechat->replyText("系统错误，找不到此砍价信息！$uid | $type |");
                                    exit();
                            }
                            //找出此人的wx_id
                            $wx_id=M('wx_user')->where(array('OpenID'=>$form_openid))->getField('wx_id');
                            //判断是否已经帮砍过了
                            $is_bangkan=M('bangkan')->where(array('wx_id'=>$wx_id,'kj_id'=>$kanjia_info['kj_id']))->find();
                            if($is_bangkan){
                                //判断是否注册用户
                                //如果是注册用户可以再砍一次
                                $is_register=M('member')->where(array('OpenID'=>$form_openid))->find();
                                if(empty($is_register)){
                                    $wechat->replyText("/玫瑰 注册成为会员并通过微信登陆即可帮ta再砍一刀哦！");
                                    exit();
                                }
                                if($is_register['is_kan']){
                                    $wechat->replyText("/玫瑰 1.关注微信\n/玫瑰 2.注册成为会员\n以上2种方法都可以帮ta砍一次哦~\nps：注册还可以免费抽iphone哦~");
                                    exit();
                                }
                                else{
                                    M('member')->where(array('uid'=>$is_register['uid']))->setField('is_kan',1);
                                }
                            }
                            //找出剩余的钱
                            $shengyumoney=$kanjia_info['shengyumoney'];
                            //区间砍价金额
                            $rule_map['type']=$kanjia_info['type'];
                            $area=M('kanjiarule')->where($rule_map)->order('kjr_yikan ASC')->select();
                            if(empty($area)){
                                //区间砍价金额
                                if($shengyumoney>=3000){
                                    $money100=mt_rand(0,1)*100;
                                    $money10=mt_rand(0,9)*10;
                                    $money1=mt_rand(0,9)*1;
                                    $moneyf=mt_rand(1,9)*0.1;
                                    $moneyf2=mt_rand(0,0)*0.01;
                                }
                                if($shengyumoney>=1000&&$shengyumoney<3000)
                                {
                                    $money100=mt_rand(0,0)*100;
                                    $money10=mt_rand(0,7)*10;
                                    $money1=mt_rand(0,9)*1;
                                    $moneyf=mt_rand(1,9)*0.1;
                                    $moneyf2=mt_rand(0,0)*0.01;
                                }
                                if($shengyumoney>=500&&$shengyumoney<1000){
                                    $money100=mt_rand(0,0)*100;
                                    $money10=mt_rand(0,0)*10;
                                    $money1=mt_rand(0,9)*1;
                                    $moneyf=mt_rand(1,9)*0.1;
                                    $moneyf2=mt_rand(0,0)*0.01;
                                }
                                if($shengyumoney<500){
                                    $money100=mt_rand(0,0)*100;
                                    $money10=mt_rand(0,0)*10;
                                    $money1=mt_rand(0,0)*1;
                                    $moneyf=mt_rand(0,9)*0.1;
                                    $moneyf2=mt_rand(1,9)*0.01;
                                }
                                $add_money=$money100+$money10+$money1+$moneyf+$moneyf2;
                            }else{
                                //计算已砍比例
                                $yikan=$kanjia_info['money']-$kanjia_info['shengyumoney'];
                                $yikan_bl=round(($yikan/$kanjia_info['money']*100),2);
                                //找到它所在的区间
                                foreach ($area as $key => $value) {
                                    if($yikan_bl<=$value['kjr_yikan']){
                                        $min=$value['kjr_min'];
                                        $max=$value['kjr_max'];
                                        if($min>0&&$max>0){
                                            $min=(int)$min;
                                            $max=(int)$max;
                                            $add_money1=mt_rand($min,$max-1);
                                            $add_money2=mt_rand(1,99)/100;
                                            $add_money=$add_money1+$add_money2;
                                        }
                                        elseif($min<=0&&$max>=0){
                                            $min=(int)($min*100);
                                            $max=(int)$max;
                                            $add_money1=mt_rand(0,$max-1);
                                            $add_money2=mt_rand($min,99)/100;
                                            $add_money=$add_money1+$add_money2;
                                        }
                                        elseif($min<=0&&$max<=0){
                                            $min=(int)($min*100);
                                            $max=(int)($max*100);
                                            $add_money1=mt_rand(0,0);
                                            $add_money2=mt_rand($min,$max)/100;
                                            $add_money=$add_money1+$add_money2;
                                        }
                                        else{
                                            $add_money1=mt_rand(0,99);
                                            $add_money2=mt_rand(1,99)/100;
                                            $add_money=$add_money1+$add_money2;
                                        }
                                        break;
                                    }
                                    else{
                                        $add_money2=mt_rand(1,99)/100;
                                        $add_money=$add_money2;
                                    }
                                }
                            }
                            if($shengyumoney<=50){
                                $wechat->replyText("当前活动已经结束，请留意最新中奖公告");
                                exit();
                            }
                            //保存砍价记录
                            $wx_id=M('wx_user')->where(array('OpenID'=>$form_openid))->getField('wx_id');
                            // 如果没有获取到该砍主的微信信息
                            if(empty($wx_id)){
                                //获取来源者信息
                                $add_info=$Auth->userInfo($form_openid);
                                //保存关注者信息
                                if(!M('wx_user')->where(array('OpenID'=>$form_openid))->Find()){
                                    // M('wx_user')->where(array('openid'=>$form['openid']))->delete();
                                    if(M('wx_user')->add($add_info)===false){
                                        $this->replyText("当前参与活动人数太多，请稍后重试");
                                        exit();
                                    }
                                    $wx_id=M('wx_user')->where(array('OpenID'=>$form_openid))->getField('wx_id');
                                }else{
                                    $wechat->replyText("当前参与活动人数太多，请稍后重试");
                                    exit();
                                }
                                
                            }
                            $bangkan_add=array(
                                'wx_id'=>$wx_id,
                                'kj_id'=>$kanjia_info['kj_id'],
                                'bk_money'=>$add_money,
                                'bk_time'=>time(),
                                );
                            //开启事物
                            M()->startTrans();
                            //保存帮砍信息
                            $add_status=M('bangkan')->add($bangkan_add);
                            //更新砍主信息
                            $save_status=M('kanjia')->where(array('kj_id'=>$kanjia_info['kj_id']))->setField('shengyumoney',round($shengyumoney-$add_money,2));
                            M('kanjia')->where(array('kj_id'=>$kanjia_info['kj_id']))->setINC('count',1);
                            if($add_status&&$save_status){
                                M()->commit();
                            }
                            else{
                                M()->rollback();
                                $wechat->replyText("当前参与活动人数太多，请稍后重试");
                                exit();
                            }
                            //获取来源者信息
                            $form=$Auth->userInfo($form_openid);
                            $rs=$Auth->userInfo($openid);
                            //发送消息给砍主
                            $Auth->sendText($openid,'您的好友“'.$form['nickname'].'”\n帮您砍下了'.$add_money.'元，快去答谢他（她）吧/示爱');
                            $wechat->replyText("您刚刚帮助您的好友[".$rs['nickname']."]砍了".$add_money."元"); 
                        }
                        break;
                    case "CLICK":
                            if($data['EventKey']=="图文"){
                                //判断是否已经注册
                                $openid=$data['FromUserName'];
                                $user_info=M('member')->where(array('OpenID'=>$openid))->find();
                                // $Auth->sendText($openid,$openid);
                                if(empty($user_info)){
                                    $back=$Auth->sendText($openid,"您还未注册/未绑定微信 \d 请点击下面链接进行注册/登陆绑定");
                                    if($back){
                                        $wechat->replyNewsOnce(
                                            "点击注册送福气！",
                                            "只需几步即可完成注册", 
                                            // "$WebRoot/index.php/Home/Public/reg",
                                            "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http%3A%2F%2Fwww.eyuanduobao.com%2Findex.php%2FHome%2FPerson%2Fme&response_type=code&scope=snsapi_base&state=STATE",
                                            "http://pic.qiantucdn.com/58pic/18/32/60/10c58PICXbP_1024.jpg"
                                        );  
                                    }
                                   
                                }
                                else{
                                    //判断砍主是否已经曾经参与
                                    if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>1))->getField('kj_id')){
                                        $wechat->replyNewsOnce(
                                            "[有人@你]您有一台Iphone6S尚未领取",
                                            "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                            "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                            "$WebRoot/Public/images/kj.png"
                                        );
                                        exit();
                                    }
                                    /*保存砍主信息*/
                                    //找到砍主id
                                    $wx_id=M('wx_user')->where(array('OpenID'=>$openid))->getField('wx_id');
                                    // 如果没有获取到该砍主的微信信息
                                    if(empty($wx_id)){
                                        //获取来源者信息
                                        $add_info=$Auth->userInfo($openid);
                                        //保存关注者信息
                                        if(!M('wx_user')->where(array('OpenID'=>$form['openid']))->Find()){
                                            // M('wx_user')->where(array('openid'=>$form['openid']))->delete();
                                            if(M('wx_user')->add($add_info)===false){
                                                $this->replyText("当前参与活动人数太多，请稍后重试");
                                                exit();
                                            }
                                            $wx_id=M('wx_user')->where(array('OpenID'=>$openid))->getField('wx_id');
                                        }
                                        
                                    }

                                    $uid=$user_info['uid'];
                                    $qr_url=$this->create_qr($openid,1);
                                    $map=array(
                                        'uid'=>$uid,
                                        'wx_id'=>$wx_id,
                                        'time'=>time(),
                                        'money'=>'7888.00',
                                        'shengyumoney'=>'7888.00',
                                        'count'=>0,
                                        'status'=>1,
                                        'qr_url'=>$qr_url,
                                        'type'=>1,
                                        );
                                    //保存砍主信息
                                    $kj_id=M('kanjia')->add($map);
                                    if($kj_id){
                                          $wechat->replyNewsOnce(
                                            "[有人@你]您有一台Iphone6S尚未领取",
                                            "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                            "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                            "$WebRoot/Public/images/kj.png"
                                        );   
                                    }
                                    else{
                                        $Auth->sendText($openid,"当前参与活动人数太多，请稍后重试");
                                    }
                                    
                                }  
                            }
                            

                            if($data['EventKey']=="图文2"){
                                //判断是否已经注册
                                $openid=$data['FromUserName'];
                                $user_info=M('member')->where(array('openid'=>$openid))->find();
                                // $Auth->sendText($openid,$openid);
                                if(empty($user_info)){
                                    $back=$Auth->sendText($openid,"您还未注册/未绑定微信 \d 请点击下面链接进行注册/登陆绑定");
                                    if($back){
                                        $wechat->replyNewsOnce(
                                            "点击注册送福气！",
                                            "只需几步即可完成注册", 
                                            // "$WebRoot/index.php/Home/Public/reg",
                                            "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=http%3A%2F%2Fwww.eyuanduobao.com%2Findex.php%2FHome%2FPerson%2Fme&response_type=code&scope=snsapi_base&state=STATE",
                                            "http://pic.qiantucdn.com/58pic/18/32/60/10c58PICXbP_1024.jpg"
                                        );  
                                    }
                                   
                                }
                                else{
                                    //判断砍主是否已经曾经参与
                                    if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>2))->getField('kj_id')){
                                        $wechat->replyNewsOnce(
                                            "[有人@你]5万积分等你来拿！！",
                                            "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                            "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                            "$WebRoot/Public/images/kj2.png"
                                        );
                                        exit();
                                    }
                                    /*保存砍主信息*/
                                    //找到砍主id
                                    $wx_id=M('wx_user')->where(array('OpenID'=>$openid))->getField('wx_id');
                                    // 如果没有获取到该砍主的微信信息
                                    if(empty($wx_id)){
                                        //获取来源者信息
                                        $add_info=$Auth->userInfo($openid);
                                        //保存关注者信息
                                        if(!M('wx_user')->where(array('OpenID'=>$form['openid']))->Find()){
                                            // M('wx_user')->where(array('openid'=>$form['openid']))->delete();
                                            if(M('wx_user')->add($add_info)===false){
                                                $this->replyText("当前参与活动人数太多，请稍后重试");
                                                exit();
                                            }
                                            $wx_id=M('wx_user')->where(array('openid'=>$openid))->getField('wx_id');
                                        }
                                    }

                                    $uid=$user_info['uid'];
                                    $qr_url=$this->create_qr($openid,2);
                                    $map=array(
                                        'uid'=>$uid,
                                        'wx_id'=>$wx_id,
                                        'time'=>time(),
                                        'money'=>'50000.00',
                                        'shengyumoney'=>'50000.00',
                                        'count'=>0,
                                        'status'=>1,
                                        'qr_url'=>$qr_url,
                                        'type'=>2,
                                        );
                                    //保存砍主信息
                                    $kj_id=M('kanjia')->add($map);
                                    if($kj_id){
                                          $wechat->replyNewsOnce(
                                            "[有人@你]5万积分等你来拿！！",
                                            "此链接是您的专属链接，请分享让朋友帮您砍价，由“粗卡”助力夺宝", 
                                            "$WebRoot/index.php/Wechat/Kanjia/index?kj_id=".$kj_id,
                                            "$WebRoot/Public/images/kj2.png"
                                        );
                                    }
                                    else{
                                        $Auth->sendText($openid,"当前参与活动人数太多，请稍后重试");
                                    }
                                    
                                }  
                            }
                            break;
                    default:
                        // $wechat->replyText("欢迎访问[粗卡]公众平台！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
                        $wechat->replyText("亲，想参与最新0元砍价活动。请点击下方菜单");
                        break;
                }
                break;

            case Wechat::MSG_TYPE_TEXT:
                switch ($data['Content']) {
                    case '联系我们':
                        $wechat->replyText('在这个平台里，你的事就是我的事啦、/得意
那我将有什么事情还没解决的呢？/可爱 你可以在这里给我们发信息，我们会在工作时间回复您的。/亲亲也可以拨打电话：400-680-6180 /玫瑰/玫瑰/玫瑰');
                        break;
                     

                    case '图文'://回复单条图文消息

                        break;

                    // case '多图文':
                    //     $news = array(
                    //         "全民创业蒙的就是你，来一盆冷水吧！",
                    //         "全民创业已经如火如荼，然而创业是一个非常自我的过程，它是一种生活方式的选择。从外部的推动有助于提高创业的存活率，但是未必能够提高创新的成功率。第一次创业的人，至少90%以上都会以失败而告终。创业成功者大部分年龄在30岁到38岁之间，而且创业成功最高的概率是第三次创业。", 
                    //         "http://www.topthink.com/topic/11991.html",
                    //         "http://yun.topthink.com/Uploads/Editor/2015-07-30/55b991cad4c48.jpg"
                    //     ); //回复单条图文消息

                        $wechat->replyNews($news, $news, $news, $news, $news);
                        break;
                    
                    default:
                        $wechat->replyText("(●˘◡˘●) 想参与最新活动吗？\n\n /玫瑰 0元得iphone6s\n /玫瑰 领取5万积分\n\n 点击下方菜单\n->[惊喜无限]参与活动吧！");
                        break;
                }
                break;
            
            default:
                # code...
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
		$appid =  \WxPayConfig::APPID;
		$appsecret = \WxPayConfig::APPSECRET;
		
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

    /*构造菜单*/
    public function create_menu(){
       
		$wxmsg=new WxUserInfo();
		$access_token=$wxmsg->accessToken();
		//echo '$access_token='.$access_token;
        //数据结构
        $WebRoot="http://cky.ritacc.net";
        $array['button'][0]=array(
            'name'=>'粗卡cky',
            'sub_button'=>array(
                array(
                        'type' => 'view',
                        'name' => '粗卡',
                        'url' => "$WebRoot/index.php/Home?v1=11",
                    ),
                array(
                        'type' => 'view',
                        'name' => '限时秒杀',
                        'url' => "$WebRoot/index.php/Home/Jiexiao/index",
                    ),
                array(
                        'type' => 'view',
                        'name' => '晒单分享',
                        'url' => "$WebRoot/index.php/Home?v1=11",
                    ),
                array(
                        'type' => 'view',
                        'name' => '最新揭晓',
                        'url' => "$WebRoot/index.php/Home/Jiexiao/history",
                    ),
                ),
            );
        $array['button'][1]=array(
            'name'=>'惊喜无限',
            'sub_button'=>array(
                array(
                        'type' => 'view',
                        'name' => '三级佣金',
                        'url' => 'http://mp.weixin.qq.com/s?__biz=MzIyNDE1MjI2NA==&mid=2247483698&idx=3&sn=9da382829911fa27177e362c37789a2f&scene=0#wechat_redirect',
                    ),
                array(
                        'type' => 'click',
                        'name' => '0元砍肾6',
                        'key' => '图文',
                    ),
                array(
                        'type' => 'click',
                        'name' => '领取5万积分',
                        'key' => '图文2',
                    ),
                ),
            );
         $array['button'][2]=array(
            'name'=>'我的',
            'sub_button'=>array(
                array(
                        'type' => 'view',
                        'name' => '个人中心',
                        'url' => "$WebRoot/index.php/Home/Person/me",
                    ),
                array(
                        'type' => 'view',
                        'name' => '购物记录',
                        'url' => "$WebRoot/index.php/Home/Person/me",
                    ),
                array(
                        'type' => 'view',
                        'name' => '新手指导',
                        'url' => "$WebRoot/index.php/Home/Person/me",
                    ),
                array(
                        'type' => 'click',
                        'name' => '联系我们',
                        'key' => '联系我们',
                    ),
                ),
            );
        $data=json_encode($array,JSON_UNESCAPED_UNICODE);
		 
        // print_r($array);die;
        //创建菜单
        //echo '$array========='. dump( $data);
		//echo '$array========='.$data;
        $rs=post('https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token,$data);
        print_r($rs);
    }
 
/*
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
*/
    /*创建临时二维码*/
    public function create_qr($openid='',$type=1){
        //找到此用户的uid
        $uid=M('member')->where(array('OpenID'=>$openid))->limit(1)->getField('uid');
        $uid=$type.$uid;
        //https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=TOKEN
        //{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
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
        // S('access_token',null);die;
        // echo S('access_token');die;
        $rs=post('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.S('access_token'),$param);
        $rs=json_decode($rs);
        //处理object
        $rs=object_array($rs);
        return ('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$rs['ticket']);
        // print_r($rs);
    }

    //获取菜单
    public function menu(){
        vendor('Weixinpay.WxPayJsApiPay');
		$appid =  \WxPayConfig::APPID;
		$appsecret = \WxPayConfig::APPSECRET;
		$wxmsg=new WxUserInfo();
		$access_token=$wxmsg->accessToken();
		 
        $Auth=new WechatAuth($appid,$appsecret,$access_token);
        $rs=$Auth->menuGet();
        print_r($rs);

    }
    //删除菜单
    public function menudel(){
        vendor('Weixinpay.WxPayJsApiPay');
		$appid =  \WxPayConfig::APPID;
		$appsecret = \WxPayConfig::APPSECRET;
		$wxmsg=new WxUserInfo();
		$access_token=$wxmsg->accessToken();
		
        
        $Auth=new WechatAuth($appid,$appsecret,$access_token);
        $rs=$Auth->menuDelete();
        print_r($rs);

    }


    public function test($m=7888){

        header("Content-type:text/html;charset=utf-8");
        set_time_limit (0);//   //设置程序最大执行时间为无限  0为无限制
        ini_set('max_execution_time', '0');//设置最大执行时间  0为不限时
        $area=M('kanjiarule')->where()->order('kjr_yikan ASC')->select();
        //计算已砍比例
        $kanjia_info['money']=7888;
        $a=0;
        while($m>=10){
            $yikan=$kanjia_info['money']-$m;
            $yikan_bl=round(($yikan/$kanjia_info['money']*100),2);
            //找到它所在的区间
            foreach ($area as $key => $value) {
                if($yikan_bl<=$value['kjr_yikan']){
                    $min=$value['kjr_min'];
                    $max=$value['kjr_max'];
                    if($min>0&&$max>0){
                        $min=(int)$min;
                        $max=(int)$max;
                        $add_money1=mt_rand($min,$max-1);
                        $add_money2=mt_rand(1,99)/100;
                        $add_money=$add_money1+$add_money2;
                    }
                    elseif($min<=0&&$max>=0){
                        $min=(int)($min*100);
                        $max=(int)$max;
                        $add_money1=mt_rand(0,$max-1);
                        $add_money2=mt_rand($min,99)/100;
                        $add_money=$add_money1+$add_money2;
                    }
                    elseif($min<=0&&$max<=0){
                        $min=(int)($min*100);
                        $max=(int)($max*100);
                        $add_money1=mt_rand(0,0);
                        $add_money2=mt_rand($min,$max)/100;
                        $add_money=$add_money1+$add_money2;
                    }
                    else{
                        $add_money1=mt_rand(0,99);
                        $add_money2=mt_rand(1,99)/100;
                        $add_money=$add_money1+$add_money2;
                    }
                    break;
                }
                else{
                    $add_money2=mt_rand(1,99)/100;
                    $add_money=$add_money2;
                }
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
        //https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=TOKEN
        //{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
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
        // S('access_token',null);die;
        // echo S('access_token');die;
        $rs=post('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.S('access_token'),$param);
        $rs=json_decode($rs);
        //处理object
        $rs=object_array($rs);
        echo  ('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$rs['ticket']);
        // print_r($rs);
    }

		

}
 