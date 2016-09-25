<?php
namespace M\Action;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
use Com\Jssdk;
class KanjiaAction extends BaseAction{
  
   
		
    public function index(){
        // S('access_token',null);
        /*找到页面信息*/
        $kj_id=I('kj_id',0);
        if(empty($kj_id)){
            $this->error("错误页面",U('/M/'));
        }
        else{
					$mdb = D('M/Kanjia');		  
					$rs=$mdb->GetByid();
        }
				
        if(!$rs){
            $this->error("获取信息失败，请稍后重试",U('/Home/'));
        }
        //剩余台数
        $map['fieldCode']='shengyu'.$rs['type'];
        $shengyutaishu=$rs["shengyuprizenum"];//M('sys_configs')->where($map)->getField('fieldValue');				
				
        unset($map);
        $this->shengyutaishu=$shengyutaishu;
        $this->user_info=$rs;
        //帮砍信息
        $D2=D('M/Kanjia');
				$bangkan_info=$D2->GetBangkanList($kj_id);
        //$bangkan_info=$D2->where(array('kj_id'=>$kj_id))->order('bk_time desc')->select();
        $bangkan_count=count($bangkan_info);
        empty($bangkan_count)?$bangkan_count=0:$bangkan_count;
        $this->bangkan_count=$bangkan_count;
        $this->bangkan_info=$bangkan_info;
        //已砍
        $yikan=round($rs['money']-$rs['shengyumoney'],2);
        $this->yikan=$yikan;
        $bl=$yikan/$rs['money'];
        if(empty($bl)){
            $bl='0';
        }
        // echo "比例：".$bl;
        $this->bl=$bl*100;
        //中奖信息
        $zhongjiang=M('kanzhong')->where(array('type'=>$rs['type']))->select();
        //$this->shengyutaishu=$shengyutaishu-(count($zhongjiang));
        $this->zhongjiang=$zhongjiang;
        // print_r($bangkan_info);
        //$code=I('get.code');
        $domainurl=$_SERVER['SERVER_NAME']; 
				$this->assign('domainurl', $domainurl);
				/****分享与定位***/
				$wxm= new WxUserInfo();
				$signPackage=$wxm->getSignPackage();			 
				$this->assign('signPackage', $signPackage);
		
        $this->display();
    }
    /*获取code*/
    public function getcode($kj_id){
        $domainurl=$_SERVER['SERVER_NAME']; 
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?";
        vendor('Weixinpay.WxPayJsApiPay');
				$appid =  \WxPayConfig::APPID;
				
        $redirect_uri=urlencode("http://".$domainurl."/".U('index').'?kj_id='.$kj_id);
        //构造url
        $url=$url."appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=1&connect_redirect=1#wechat_redirect";
        //重定向url
        header("location: ".$url."");
        exit;
    }
    /*获得用户openid*/
    public function get_user_openid($code){
        //url:https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code 
        $url='https://api.weixin.qq.com/sns/oauth2/access_token';
        vendor('Weixinpay.WxPayJsApiPay');
				$appid =  \WxPayConfig::APPID;
				$crypt = \WxPayConfig::APPSECRET;
				
        $param=array(
                'appid'=>$appid,
                'secret'=>$crypt,
                'code'=>$code,
                'grant_type'=>'authorization_code',
            );
        $result=get($url,$param);
        $result=json_decode($result);
        //处理object
        $result=object_array($result);
        //获取长久一点的access_token
        //url:https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=APPID&grant_type=refresh_token&refresh_token=REFRESH_TOKEN 
        $result=get("https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$appid."&grant_type=refresh_token&refresh_token=".$result['refresh_token']."");
        $result=json_decode($result);
        //处理object
        $result=object_array($result);
        //如果返回错误信息
        if(!empty($result['errcode'])){
            $this->error('网站错误，请联系管理员'.$result['errcode'].'');
        }
        else{
            return $result;
        }
    }
    /*拉取用户信息*/
    //这个access_token和基础配置的access_token的不一样
    public function get_user_info($openid,$access_token){
        $user_info=get('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN');
        $user_info=json_decode($user_info);
        //处理object
        $user_info=object_array($user_info);
        if(!empty($user_info['errcode'])){
            $this->error('网站错误，请联系管理员'.$result['errmsg'].'');
        }else{
           return $user_info; 
        }
    }
    /*判断这个用户有没有关注*/
    public function is_follow($openid){
        $wxmsg=new WxUserInfo();
				$access_token=$wxmsg->accessToken();
		
        $rs=get('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN');
        $rs=json_decode($rs);
        //处理object
       
        $rs=object_array($rs);
        // print_r($rs);
        if($rs['subscribe']==0){
            return false;
        }
        else{
            return true;
        }
    }
    
    /*创建临时二维码*/
//  public function create_qr($openid='oKxDRvyD0gFYNwby4lh7kGrOUcM8'){
//      	
//				$wxmsg=new WxUserInfo();
//				$access_token=$wxmsg->accessToken();
//      //找到此用户的uid
//      $uid=M('member')->where(array('OpenID'=>$openid))->limit(1)->getField('uid');
//      
//      $param=array(
//          'expire_seconds'=>2592000,
//          'action_name'=>'QR_SCENE',
//          'action_info'=>array(
//              'scene'=>array(
//                  'scene_id'=>$uid,
//                  )
//              ),
//          );
//      $param=json_encode($param);
//    
//      $rs=post('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token,$param);
//      $rs=json_decode($rs);
//      //处理object
//      $rs=object_array($rs);
//      header('location:https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$rs['ticket']);
//      // print_r($rs);
//  }

  /*创建永久二维码*/
	public function create_fqr($type=''){
        //找到此用户的uid
        $uid='00';
//				$type='91'
				$dotype='7777';
        $uid=$dotype.$type.$uid;
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
				header('location:https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$rs['ticket']);
    }

    public function test(){
	    	$uid='1';
			$wx_id=1;
			$qr_url='';
			$kjcode="91";
				$mkj=D('M/Kanjia');
					$kj_id=$mkj->Insert($uid, $wx_id, $qr_url ,$kjcode);
//					$this->display();
//      vendor('Weixinpay.WxPayJsApiPay');
//				$appid =  \WxPayConfig::APPID;
//				$crypt = \WxPayConfig::APPSECRET;
//      $jssdk=new Jssdk($appid,$crypt);
//      $signPackage=$jssdk->getSignPackage();
//      print_r($rs);
//      $this->signPackage=$signPackage;
//      $this->display();

			
    }
}
