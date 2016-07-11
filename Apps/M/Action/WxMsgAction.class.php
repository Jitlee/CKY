<?php
namespace M\Action;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
use Com\Jssdk;

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
		$WebRoot="http://cukayun.cn";
		$WebDomain="cukayun.cn";
		 
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
                        if(!M('wx_user')->where(array('openid'=>$form['openid']))->Find()){
                            M('wx_user')->add($form);
                        }
                        //如果是砍价1活动
                         if($data['EventKey']=='kj1'){
                            //判断是否已经注册
                            $openid=$data['FromUserName'];
                            $user_info=M('member')->where(array('OpenID'=>$openid))->find();
                            if(empty($user_info)){
                                $back=$Auth->sendText($openid,"您还未注册/未绑定微信 \d 请点击下面链接进行注册/登陆绑定,openid=$openid");
                                if($back){
                                    $mkjp=D('M/Kanjia');
									$newspara=$mkjp->GetNewsPara('reg', $WebDomain ,$kj_id,$appid);
                                    $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
                                }
                               
                            }
                            else{
                                //判断砍主是否已经曾经参与
                                if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>1))->getField('kj_id')){
                                    $mkjp=D('M/Kanjia');
									$newspara=$mkjp->GetNewsPara('1', $WebRoot ,$kj_id);
                                    $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
                                    exit();
                                }
                                //微信信息保存                                       
								$mdb=D('M/WxUser');
	                            $wx_id=$mdb->GetWxID($openid, $Auth);
								if($wx_id==-1)
								{
									$this->replyText("当前参与活动人数太多，请稍后重试");
	                                exit();
								}

                                $uid=$user_info['uid'];
                                $qr_url=$this->create_qr($openid,1);                                        
								//保存砍价主信息
								$mkj=D('M/Kanjia');
								$kj_id=$mkj->Insert($uid, $wx_id, $qr_url ,1);
                                if($kj_id){
                                    $mkjp=D('M/Kanjia');
									$newspara=$mkjp->GetNewsPara('1', $WebRoot ,$kj_id);
                                    $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
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
                                            $mkjp=D('M/Kanjia');
											$newspara=$mkjp->GetNewsPara('reg', $WebDomain ,$kj_id,$appid);
	                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
                                        }
                                       
                                    }
                                    else{
                                        //判断砍主是否已经曾经参与
                                        if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>2))->getField('kj_id')){
                                            $mkjp=D('M/Kanjia');
											$newspara=$mkjp->GetNewsPara('2', $WebRoot ,$kj_id);
	                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
                                            exit();
                                        }
                                        //保存微信信息
										$mdb=D('M/WxUser');
			                            $wx_id=$mdb->GetWxID($openid, $Auth);
										if($wx_id==-1)
										{
											$this->replyText("当前参与活动人数太多，请稍后重试");
			                                exit();
										}

                                        $uid=$user_info['uid'];
                                        $qr_url=$this->create_qr($openid,2);                                        
                                       //保存砍价主信息
										$mkj=D('M/Kanjia');
										$kj_id=$mkj->Insert($uid, $wx_id, $qr_url ,2);
                                        if($kj_id){
                                            $mkjp=D('M/Kanjia');
											$newspara=$mkjp->GetNewsPara('2', $WebRoot ,$kj_id);
	                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
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
                            $D=M('kanjia');
                            $kanjia_info=$D->where(array('uid'=>$uid,'type'=>$type))->find();
                            if(empty($kanjia_info)){
                                $wechat->replyText("系统错误，找不到此砍价信息！$uid | $type |");
                                    exit();
                            }
                            //找出此人的wx_id
                            $wx_id=M('wx_user')->where(array('openid'=>$form_openid))->getField('wx_id');
                            //判断是否已经帮砍过了
                            $is_bangkan=M('bangkan')->where(array('wx_id'=>$wx_id,'kj_id'=>$kanjia_info['kj_id']))->find();
                            if($is_bangkan){
                                //判断是否注册用户
                                //如果是注册用户可以再砍一次
                                $is_register=M('member')->where(array('openid'=>$form_openid))->find();
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
                            //计算砍价金额
                            $shengyumoney=$kanjia_info['shengyumoney'];                            
                            $type=$kanjia_info['type'];
							$money=$kanjia_info['money'];
                            $mkj=D('M/Kanjia');
							$add_money=$mkj->GetAddMoney($type, $money, $shengyumoney);
							
                            if($shengyumoney<=50){
                                $wechat->replyText("当前活动已经结束，请留意最新中奖公告");
                                exit();
                            }
                            //保存砍价记录
                            $wx_id=M('wx_user')->where(array('openid'=>$form_openid))->getField('wx_id');
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
欢迎来到【粗卡】王国游戏王国待会就更新啦，更多消息，请留意我们的微信公众号
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
                                $user_info=M('member')->where(array('openid'=>$openid))->find();
                                if(empty($user_info)){
                                    $back=$Auth->sendText($openid,"您还未注册/未绑定微信 \d 请点击下面链接进行注册/登陆绑定");
                                    if($back){
                                        $mkjp=D('M/Kanjia');
										$newspara=$mkjp->GetNewsPara('reg', $WebDomain ,$kj_id,$appid);
                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动 
                                    }
                                }
                                else{
                                    //判断砍主是否已经曾经参与
                                    if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>1))->getField('kj_id')){
                                        $mkjp=D('M/Kanjia');
										$newspara=$mkjp->GetNewsPara('1', $WebRoot ,$kj_id);
                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
                                        exit();
                                    }
                                    //保存微信信息
									$mdb=D('M/WxUser');
		                            $wx_id=$mdb->GetWxID($openid, $Auth);
									if($wx_id==-1)
									{
										$this->replyText("当前参与活动人数太多，请稍后重试");
		                                exit();
									}

                                    $uid=$user_info['uid'];
                                    $qr_url=$this->create_qr($openid,1);
                                    //保存砍价主信息
									$mkj=D('M/Kanjia');
									$kj_id=$mkj->Insert($uid, $wx_id, $qr_url ,1);
                                    if($kj_id){
                                        $mkjp=D('M/Kanjia');
										$newspara=$mkjp->GetNewsPara('1', $WebRoot ,$kj_id);
                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动 
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
                                        $mkjp=D('M/Kanjia');
										$newspara=$mkjp->GetNewsPara('reg', $WebDomain ,$kj_id,$appid);
                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
                                    }
                                }
                                else{
                                    //判断砍主是否已经曾经参与
                                    if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>2))->getField('kj_id')){
                                        $mkjp=D('M/Kanjia');
										$newspara=$mkjp->GetNewsPara('2', $WebRoot ,$kj_id);
                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
                                        exit();
                                    }
									                                    
									//保存微信信息
									$mdb=D('M/WxUser');
		                            $wx_id=$mdb->GetWxID($openid, $Auth);
									if($wx_id==-1)
									{
										$this->replyText("当前参与活动人数太多，请稍后重试");
		                                exit();
									}

                                    $uid=$user_info['uid'];
                                    $qr_url=$this->create_qr($openid,2);
                                    //保存砍价主信息
									$mkj=D('M/Kanjia');
									$kj_id=$mkj->Insert($uid, $wx_id, $qr_url ,2);
                                    if($kj_id){
                                        $mkjp=D('M/Kanjia');
										$newspara=$mkjp->GetNewsPara('2', $WebRoot ,$kj_id);
                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
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
                            $D=M('kanjia');
                            $kanjia_info=$D->where(array('uid'=>$uid,'type'=>$type))->find();
                            if(empty($kanjia_info)){

                                $wechat->replyText("系统错误，找不到此砍价信息！$uid | $type |");
                                    exit();
                            }
                            //找出此人的wx_id
                            $wx_id=M('wx_user')->where(array('openid'=>$form_openid))->getField('wx_id');
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
                           
                            //区间砍价金额
                            $type=$kanjia_info['type'];
							$money=$kanjia_info['money'];
							$shengyumoney=$kanjia_info['shengyumoney'];
							 							 
                            $mkj=D('M/Kanjia');
							$add_money=$mkj->GetAddMoney($type, $money, $shengyumoney);
                            if($shengyumoney<=50){
                                $wechat->replyText("当前活动已经结束，请留意最新中奖公告");
                                exit();
                            }
                            
                            //保存微信用户信息                                   
                            $mdb=D('M/WxUser');
                            $wx_id=$mdb->GetWxID($form_openid, $Auth);
							if($wx_id==-1)
							{
								$this->replyText("当前参与活动人数太多，请稍后重试");
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
                        break;
                    case "CLICK":
                            if($data['EventKey']=="图文"){
                            	try
								{
	                                //判断是否已经注册
	                                $openid=$data['FromUserName'];
	                                $user_info=M('member')->where(array('OpenID'=>$openid))->find();
	                                // $Auth->sendText($openid,$openid);
	                                if(empty($user_info)){
	                                    $back=$Auth->sendText($openid,"您还未注册/未绑定微信 \d 请点击下面链接进行注册/登陆绑定");
	                                    if($back){
	                                        $mkjp=D('M/Kanjia');
											$newspara=$mkjp->GetNewsPara('reg', $WebDomain ,$kj_id,$appid);
	                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
	                                    }	                                   
	                                }
	                                else{
	                                    //判断砍主是否已经曾经参与
	                                    $kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>1))->getField('kj_id');
										
	                                    if($kj_id>0){
	                                    	$mkjp=D('M/Kanjia');
											$newspara=$mkjp->GetNewsPara('1', $WebRoot ,$kj_id);
	                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);
	                                        exit();
	                                    }
	                                   
	                                    //*保存砍主信息
	                                    //找到砍主id
	                                    $mdb=D('M/WxUser');
	                                    $wx_id=$mdb->GetWxID($openid, $Auth);
										if($wx_id==-1)
										{
											$this->replyText("当前参与活动人数太多，请稍后重试");
                                            exit();
										}										
	                                    // 如果没有获取到该砍主的微信信息
	                                     
	                                    $uid=$user_info['uid'];
	                                    $qr_url=$this->create_qr($openid,1);
										//保存砍价信息
										$mkj=D('M/Kanjia');
										$kj_id=$mkj->Insert($uid, $wx_id, $qr_url ,1);
										 
	                                    if($kj_id){
                                          	$mkjp=D('M/Kanjia');
											$newspara=$mkjp->GetNewsPara('1', $WebRoot ,$kj_id);
	                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
	                                    }
	                                    else{
	                                        $Auth->sendText($openid,"当前参与活动人数太多，请稍后重试");
	                                    }	  
										                                 
	                                }
								}
								catch (Exception $e) {
									$Auth->sendText($openid,$e->getMessage());
								} 
                            } 
							else if($data['EventKey']=="图文2"){
                                //判断是否已经注册
                                $openid=$data['FromUserName'];
                                $user_info=M('member')->where(array('OpenID'=>$openid))->find();
                                // $Auth->sendText($openid,$openid);
                                if(empty($user_info)){
                                    $back=$Auth->sendText($openid,"您还未注册/未绑定微信 \d 请点击下面链接进行注册/登陆绑定");
                                    if($back){
                                        $mkjp=D('M/Kanjia');
										$newspara=$mkjp->GetNewsPara('reg', $WebDomain ,$kj_id,$appid);
                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
                                    }
                                   
                                }
                                else{
                                    //判断砍主是否已经曾经参与
                                    if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>2))->getField('kj_id')){
                                        $mkjp=D('M/Kanjia');
										$newspara=$mkjp->GetNewsPara('2', $WebRoot ,$kj_id);
                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
                                        exit();
                                    }
                                    //保存微信用户信息                                   
                                    $mdb=D('M/WxUser');
                                    $wx_id=$mdb->GetWxID($openid, $Auth);
									if($wx_id==-1)
									{
										$this->replyText("当前参与活动人数太多，请稍后重试");
                                        exit();
									}

                                    $uid=$user_info['uid'];
                                    $qr_url=$this->create_qr($openid,2);
                                    //保存砍价信息
									$mkj=D('M/Kanjia');
									$kj_id=$mkj->Insert($uid, $wx_id, $qr_url ,2);
                                    if($kj_id){
                                        $mkjp=D('M/Kanjia');
										$newspara=$mkjp->GetNewsPara('2', $WebRoot ,$kj_id);
                                        $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
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
那我将有什么事情还没解决的呢？/可爱 你可以在这里给我们发信息，我们会在工作时间回复您的。/亲亲/玫瑰/玫瑰/玫瑰');
                        break;
                     

                    case '图文'://回复单条图文消息

                        break;

                   

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
		 
        //数据结构
        $WebRoot="http://cukayun.cn";
        $array['button'][0]=array(
            'name'=>'粗卡',
            'sub_button'=>array(
                array(
                        'type' => 'view',
                        'name' => '首页',
                        'url' => "$WebRoot/index.php/M/Miaosha/index",
                    ),
                array(
                        'type' => 'view',
                        'name' => '商城',
                        'url' => "$WebRoot/index.php/M/Mall/index.html",
                    ),
                ),
            );
        $array['button'][1]=array(
            'name'=>'惊喜无限',
            'sub_button'=>array(                
                array(
                        'type' => 'click',
                        'name' => '0元砍肾6',
                        'key' => '图文',
                    ),
                array(
                        'type' => 'view',
                        'name' => '一元中大奖',
                        'url' => '$WebRoot/index.php/M/Miaosha/index.html',
                    ),
                array(
                        'type' => 'view',
                        'name' => '卡券大派送',
                        'url' => '$WebRoot/index.php/M/Activity/coupon.html',
                    ),
                ),
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
		 
        // print_r($array);die;
        //创建菜单
        //echo '$array========='. dump( $data);
		//echo '$array========='.$data;
		
        $rs=post('https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token,$data);
        print_r($rs);
    }

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
        
		$wxmsg=new WxUserInfo();
		$access_token=$wxmsg->accessToken();
		
        $rs=post('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token,$param);
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
		 vendor('Weixinpay.WxPayJsApiPay');
				$appid =  \WxPayConfig::APPID;
				$crypt = \WxPayConfig::APPSECRET;
				
	      $jssdk=new Jssdk($appid,$crypt);
	      $signPackage=$jssdk->getSignPackage();
	      
	      echo dump($signPackage);
		$this->display("Wx/getcodeurl");
	}
		

}
 