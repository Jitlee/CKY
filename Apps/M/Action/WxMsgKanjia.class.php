<?php
 namespace M\Action;
 
class WxMsgKanjia
{
	
	public function Kanjia($data, $Auth, $wechat, $WebDomain, $WebRoot)
	{
		//$wechat->replyText("当前活动已经结束，请留意最新中奖公告");
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
		
	}
	
	public function KanjiaClick($data, $Auth, $wechat, $WebDomain, $WebRoot)
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
                exit;
            }	                                   
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
		
		if($data['EventKey']=="图文"){
	    	try
			{
	                //判断砍主是否已经曾经参与
	                $kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>1))->getField('kj_id');
	                if($kj_id>0){
	                	$mkjp=D('M/Kanjia');
						$newspara=$mkjp->GetNewsPara('1', $WebRoot ,$kj_id);
	                    $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);
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
			catch (Exception $e) {
				$Auth->sendText($openid,$e->getMessage());
			} 
	    } 
		else if($data['EventKey']=="图文2"){
	            //判断砍主是否已经曾经参与
	            if($kj_id=M('kanjia')->where(array('uid'=>$user_info['uid'],'type'=>2))->getField('kj_id')){
	                $mkjp=D('M/Kanjia');
					$newspara=$mkjp->GetNewsPara('2', $WebRoot ,$kj_id);
	                $wechat->replyNewsOnce($newspara["title"],$newspara["discription"],$newspara["url"],$newspara["picurl"]);//发送活动
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
    }




}
